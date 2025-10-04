<?php
use Illuminate\Support\Facades\Route;
Route::middleware(['web'])->prefix('admin')->group(function(){});