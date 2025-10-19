<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SocialAuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function(){
    // Normal auth
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:register');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');

    // Social auth
    Route::post('/social/register', [SocialAuthController::class, 'register'])->middleware('throttle:social');
    Route::post('/social/login', [SocialAuthController::class, 'login'])->middleware('throttle:social');

    // Logout system and profile
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/profile', [AuthController::class, 'getProfile']);
        Route::post('/revoke-all', [AuthController::class, 'revokeAll']);
    });
});
