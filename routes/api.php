<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SocialAuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function(){
    // Normal auth
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // Social auth
    Route::post('/social/register', [SocialAuthController::class, 'register']);
    Route::post('/social/login', [SocialAuthController::class, 'login']);

    // Logout system and profile
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/revoke-all', [AuthController::class, 'revokeAll']);
    });
});
