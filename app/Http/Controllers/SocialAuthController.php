<?php

namespace App\Http\Controllers;

use App\Http\Requests\SocialAuthorizeRequest;
use App\Models\SocialAccount;
use App\Models\User;
use App\Services\SocialAuth\SocialVerifierFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    public function __construct(private readonly SocialVerifierFactory $factory) {}

    /**
     * POST /api/v1/social/authorize
     * Body: { provider, id_token? / access_token?, email?, username? }
     *
     * Flow:
     *  1) Verify the token server-side (per provider) and normalize the profile payload.
     *  2) If a SocialAccount exists, treat as login; otherwise "find-or-create" a User and link the social account.
     *  3) Update last login meta and mint a personal access token (Sanctum).
     */
    public function authorize(SocialAuthorizeRequest $request): JsonResponse
    {
        $provider = $request->string('provider')->toString();

        // 1) Verify token per provider and normalize profile data
        $verifier = $this->factory->make($provider);
        $profile  = $verifier->verify($request->validated());

        $providerUserId = $profile['provider_user_id'] ?? null;
        if (! $providerUserId) {
            return response()->json(['message' => 'Unable to resolve provider_user_id'], 422);
        }

        // 2) If the social account is already linked, behave like login
        $account = SocialAccount::query()
            ->where('provider', $provider)
            ->where('provider_user_id', $providerUserId)
            ->first();

        if ($account) {
            $user = $account->user;
            $account->update(['last_used_at' => now()]);
        } else {
            // First-time: if email is present, link to existing user by email; otherwise create a new user.
            // Some providers do not return email; allow passing it in the body as a fallback.
            $email = $profile['email'] ?? $request->input('email');

            // Use a transaction to avoid race conditions when creating user + social account
            $user = DB::transaction(function () use ($email, $profile, $request, $provider, $providerUserId) {
                $user = $email ? User::where('email', trim((string)$email))->first() : null;

                if (! $user) {
                    // Username is optional; generate a reasonable handle if missing.
                    $username = $request->input('username')
                        ?: ('user' . Str::lower(Str::random(8)));

                    // NOTE: ensure a uniqueness rule/constraint exists for username/email at the DB layer.
                    $user = User::create([
                        'email'             => $email,
                        'username'          => $username,
                        'password'          => null, // social-only account
                        'email_verified_at' => ($profile['email_verified'] ?? false) ? now() : null,
                    ]);
                } else {
                    // Existing user: mark email as verified if the provider guarantees verification
                    if (! $user->hasVerifiedEmail() && ($profile['email_verified'] ?? false)) {
                        $user->email_verified_at = now();
                    }
                    $user->save();
                }

                // Link the social account
                $user->socialAccounts()->create([
                    'provider'         => $provider,
                    'provider_user_id' => (string)$providerUserId,
                    'last_used_at'     => now(),
                ]);

                return $user;
            });
        }

        // 3) Update last login metadata (consider trusted proxies for accurate IPs)
        $user->forceFill([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ])->save();

        // 4) Mint API token (Sanctum)
        $tokenName = substr((string) $request->userAgent(), 0, 120) ?: 'api';
        $token     = $user->createToken($tokenName)->plainTextToken;

        return response()->json([
            'message' => $account ? 'Social login successful.' : 'Social account linked and authorized.',
            'user'    => [
                'id'       => $user->id,
                'username' => $user->username,
                'email'    => $user->email,
            ],
            'token'   => $token,
        ], $account ? 200 : 201);
    }
}
