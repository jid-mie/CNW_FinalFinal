<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Owner\BookingController;
use App\Http\Controllers\Api\Owner\CustomerController;
use App\Http\Controllers\Api\Owner\FieldController;
use App\Http\Controllers\Api\Owner\TimeSlotController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:3,60');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,60');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->middleware('throttle:3,60');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('throttle:3,60');
Route::post('/refresh-token', [AuthController::class, 'refresh']);
Route::post('/webhooks/seepay', [\App\Http\Controllers\Api\SeepayWebhookController::class, 'handle']);

// ✅ TEST WEBHOOK (development only) - Simulate Seepay webhook to confirm payment
Route::post('/webhooks/seepay/test', function (\Illuminate\Http\Request $request) {
    if (!app()->isLocal()) {
        return response()->json(['error' => 'Not available in production'], 403);
    }
    
    $bookingId = $request->input('booking_id') ?? 1;
    $amount = $request->input('amount') ?? 100000;
    
    return app(\App\Http\Controllers\Api\SeepayWebhookController::class)->handle(
        new \Illuminate\Http\Request([
            'transactionContent' => "PLAY{$bookingId}",
            'transferAmount' => $amount,
            'referenceCode' => 'TEST-' . time(),
            'token' => config('services.seepay.webhook_token'),
        ])
    );
});

Route::middleware(['auth:sanctum', 'abilities:access'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::put('/user', [AuthController::class, 'updateProfile']);

    // Admin API Routes
    Route::middleware('role:admin')->prefix('admin')->name('api.admin.')->group(function () {
        Route::post('/users', [\App\Http\Controllers\Api\Admin\UserController::class, 'store'])->name('users.store');
    });

    // Owner API Routes
    Route::middleware('role:owner')->prefix('owner')->name('api.owner.')->group(function () {
        // Dashboard stats
        Route::get('/stats', [BookingController::class, 'stats'])->name('stats');

        // Revenue report
        Route::get('/revenue', [BookingController::class, 'revenue'])->name('revenue');

        // Fields CRUD
        Route::apiResource('fields', FieldController::class)->names([
            'index' => 'fields.index',
            'store' => 'fields.store',
            'show' => 'fields.show',
            'update' => 'fields.update',
            'destroy' => 'fields.destroy',
        ]);

        // Field image
        Route::post('/fields/{field}/image', [FieldController::class, 'uploadImage'])->name('fields.upload-image');
        Route::delete('/fields/{field}/image', [FieldController::class, 'deleteImage'])->name('fields.delete-image');

        // Time slots
        Route::get('/fields/{field}/time-slots', [TimeSlotController::class, 'index'])->name('time-slots.index');
        Route::post('/fields/{field}/time-slots', [TimeSlotController::class, 'store'])->name('time-slots.store');
        Route::put('/fields/{field}/time-slots/{timeSlot}', [TimeSlotController::class, 'update'])->name('time-slots.update');
        Route::delete('/fields/{field}/time-slots/{timeSlot}', [TimeSlotController::class, 'destroy'])->name('time-slots.destroy');
        Route::post('/fields/{field}/time-slots/generate-default', [TimeSlotController::class, 'generateDefault'])->name('time-slots.generate-default');

        // Bookings
        Route::get('/bookings/export', [BookingController::class, 'exportBookings'])->name('bookings.export');
        Route::get('/bookings/calendar', [BookingController::class, 'calendar'])->name('bookings.calendar');
        Route::get('/bookings/pending', [BookingController::class, 'pending'])->name('bookings.pending');
        Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
        Route::put('/bookings/{booking}', [BookingController::class, 'update'])->name('bookings.update');
        Route::put('/bookings/{booking}/checkin', [BookingController::class, 'checkin'])->name('bookings.checkin');

        // Revenue export
        Route::get('/revenue/export', [BookingController::class, 'exportRevenue'])->name('revenue.export');

        // Customers
        Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
        Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
    });

    // Customer API Routes
    Route::middleware('role:customer')->prefix('customer')->name('api.customer.')->group(function () {
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
