<?php

namespace App\Http\Controllers;

use App\Http\Requests\SocialAuthLoginRequest;
use App\Http\Requests\SocialAuthRegisterRequest;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class SocialAuthController extends Controller
{
    /**
     * Social account register endpoint
     * POST /api/v1/social/register
     */
    public function register(SocialAuthRegisterRequest $request): JsonResponse
    {
        // Create new user (no password)
        $user = User::create([
            'email'    => $request->email,
            'username' => $request->username,
            'password' => null,
        ]);

        // Create SocialAccount
        $user->socialAccounts()->create([
            'provider'         => $request->provider,
            'provider_user_id' => $request->provider_user_id,
            'last_used_at'     => now(),
        ]);

        // Create Sanctum token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Social account registered successfully.',
            'user'    => $user,
            'token'   => $token,
        ], 201);
    }

    /**
     * Social account login endpoint
     * POST /api/v1/social/login
     */
    public function login(SocialAuthLoginRequest $request): JsonResponse
    {
        $account = SocialAccount::where('provider', $request->provider)
            ->where('provider_user_id', $request->provider_user_id)
            ->first();

        if (!$account) {
            return response()->json([
                'message' => 'Social account not found.',
            ], 404);
        }

        $account->update(['last_used_at' => now()]);

        $user = $account->user;

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Social login successful.',
            'user'    => $user,
            'token'   => $token,
        ]);
    }
}
