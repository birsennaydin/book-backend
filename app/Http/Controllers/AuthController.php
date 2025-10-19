<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Normal registration
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        $email = strtolower(trim($data['email']));
        $username = trim($data['username']);

        $user = User::create([
            'username' => $username,
            'email'    => $email,
            'password' => Hash::make($data['password']),
        ]);

        event(new Registered($user));

        return response()->json([
            'message' => 'Registration successful. Please check your email to verify your account.',
            'user'    => [
                'id'       => $user->id,
                'username' => $user->username,
                'email'    => $user->email,
            ],
            'verification' => ['sent' => true],
        ], 201);
    }

    // Normal login
    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();
        $email = strtolower(trim($data['email']));

        $user = User::where('email', $email)->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid login credentials.',
            ], 401);
        }

        if (! $user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email not verified. Please check your inbox.',
            ], 403);
        }

        $user->forceFill([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ])->save();

        $token = $user->createToken('api')->plainTextToken;

        return response()->json([
            'message' => 'Login successful.',
            'user'    => [
                'id'       => $user->id,
                'username' => $user->username,
                'email'    => $user->email,
            ],
            'token'   => $token,
        ], 200);
    }

    public function logout(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        if (! $user) {
            return response()->json([
                'message' => 'Not authenticated.',
            ], 401);
        }

        // Revoke the current access token only
        $user->currentAccessToken()?->delete();

        return response()->json([
            'message' => 'Successfully logged out.',
        ], 200);
    }

    public function getProfile(Request $request): JsonResponse
    {
        return response()->json(['user' => $request->user()]);
    }

    public function revokeAll(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'All tokens revoked.']);
    }
}
