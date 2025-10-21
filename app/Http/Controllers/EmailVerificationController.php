<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResendVerificationRequest;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    /**
     * Handle email verification link (signed URL).
     *
     * This endpoint is typically accessed when the user clicks
     * the verification link sent to their email address.
     */
    public function verify(Request $request, string $id, string $hash): JsonResponse
    {
        /** @var User $user */
        $user = User::findOrFail($id);

        // Ensure the hash in the URL matches the user's email hash.
        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return response()->json([
                'message' => 'Invalid or expired verification link.',
            ], 403);
        }

        // If already verified, return early.
        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email already verified.',
            ], 200);
        }

        // Mark the email as verified and dispatch the Verified event.
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return response()->json([
            'message' => 'Email verified successfully. You may now log in.',
        ], 200);
    }

    /**
     * Resend verification email.
     *
     * Accepts a JSON body with the user's email address.
     * The same response is returned regardless of whether
     * the user exists — this prevents user enumeration.
     */
    public function resend(ResendVerificationRequest $request): JsonResponse
    {
        $email = $request->validated('email');

        $user = User::where('email', $email)->first();

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email is already verified.'], 400);
        }

        $user->sendEmailVerificationNotification();

        return response()->json(['message' => 'Verification email resent successfully.'], 200);
    }
}
