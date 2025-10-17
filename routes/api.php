<?php
use Illuminate\Support\Facades\Route;
Route::prefix('api/v1')->group(function(){});

Route::middleware(['auth:sanctum', 'role:reader'])->get('/profile', function () {
    return response()->json(['message' => 'Reader profiline eriştin']);
});
