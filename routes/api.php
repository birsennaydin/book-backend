<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\SocialAuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function(){
    // Normal auth
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:register');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');

    // Social auth
    /**
    Route::post('/social/register', [SocialAuthController::class, 'register'])->middleware('throttle:social');
    Route::post('/social/login', [SocialAuthController::class, 'login'])->middleware('throttle:social');
     **/

    Route::post('/social/authorize', [SocialAuthController::class, 'authorize'])
        ->middleware('throttle:social');

    // Email verification (public)
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->middleware(['signed','throttle:6,1'])
        ->name('verification.verify');

    Route::post('/email/resend', [EmailVerificationController::class, 'resend'])
        ->middleware('throttle:email-resend');

    // Logout system and profile
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->middleware('throttle:60,1');
        Route::get('/profile', [AuthController::class, 'getProfile']);
        Route::post('/revoke-all', [AuthController::class, 'revokeAll'])->middleware('throttle:30,1');
    });
});
