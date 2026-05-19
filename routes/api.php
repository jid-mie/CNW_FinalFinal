<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:3,60');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,60');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->middleware('throttle:3,60');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('throttle:3,60');
Route::post('/refresh-token', [AuthController::class, 'refresh']);

Route::middleware(['auth:sanctum', 'abilities:access'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Admin API Routes
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        // Admin specific API endpoints
    });

    // Owner API Routes
    Route::middleware('role:owner')->prefix('owner')->group(function () {
        // Owner specific API endpoints
    });

    // Customer API Routes
    Route::middleware('role:customer')->prefix('customer')->group(function () {
        // Customer specific API endpoints
    });
});
