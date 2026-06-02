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
    Route::middleware('role:customer')->prefix('customer')->name('customer.')->group(function () {
        // Sports endpoints
        Route::get('/sports', [\App\Http\Controllers\Api\Customer\SportsController::class, 'index'])->name('sports.index');
        Route::get('/sports/{sport}', [\App\Http\Controllers\Api\Customer\SportsController::class, 'show'])->name('sports.show');

        // Fields endpoints
        Route::get('/fields', [\App\Http\Controllers\Api\Customer\FieldsController::class, 'index'])->name('fields.index');
        Route::get('/fields/{field}', [\App\Http\Controllers\Api\Customer\FieldsController::class, 'show'])->name('fields.show');
        Route::get('/fields/{field}/time-slots', [\App\Http\Controllers\Api\Customer\FieldsController::class, 'timeSlots'])->name('fields.time-slots');

        // Bookings endpoints
        Route::get('/bookings', [\App\Http\Controllers\Api\Customer\BookingsController::class, 'index'])->name('bookings.index');
        Route::post('/bookings', [\App\Http\Controllers\Api\Customer\BookingsController::class, 'store'])->name('bookings.store');
        Route::get('/bookings/{booking}', [\App\Http\Controllers\Api\Customer\BookingsController::class, 'show'])->name('bookings.show');
        Route::patch('/bookings/{booking}', [\App\Http\Controllers\Api\Customer\BookingsController::class, 'update'])->name('bookings.update');
        Route::post('/bookings/{booking}/cancel', [\App\Http\Controllers\Api\Customer\BookingsController::class, 'cancel'])->name('bookings.cancel');

        // Payments endpoints
        Route::get('/payments', [\App\Http\Controllers\Api\Customer\PaymentsController::class, 'index'])->name('payments.index');
        Route::post('/bookings/{booking}/payment', [\App\Http\Controllers\Api\Customer\PaymentsController::class, 'store'])->name('payments.store');
        Route::get('/payments/{payment}', [\App\Http\Controllers\Api\Customer\PaymentsController::class, 'show'])->name('payments.show');
    });
});
