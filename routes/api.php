<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;

Route::prefix('v1')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('/login', 'login')->middleware('throttle:10,1');
        Route::post('/logout', 'logout')->middleware(['auth:sanctum', 'throttle:10,1']);
        Route::post('/register', 'register')->middleware('throttle:10,1');
    });

    Route::middleware('auth:sanctum')->apiResource('posts', PostController::class);
});
