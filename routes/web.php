<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:web', 'role:admin|writer'])->group(function () {
    Route::get('/dashboard', fn() => 'Panel sayfası');
});
