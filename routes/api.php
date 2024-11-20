<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;

Route::prefix('v1')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('/login', 'login')->middleware('throttle:10,1');
        Route::post('/logout', 'logout')->middleware(['auth:sanctum', 'throttle:10,1']);
        Route::post('/register', 'register')->middleware('throttle:10,1');
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('posts', PostController::class);

        Route::prefix('posts/{post}')->group(function () {
            Route::get('comments', [CommentController::class, 'index']);
            Route::post('comments', [CommentController::class, 'store']);
            Route::delete('comments/{comment}', [CommentController::class, 'destroy']);
        });
    });
});
