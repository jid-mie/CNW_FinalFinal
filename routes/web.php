<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Web\Owner\BookingController as OwnerBookingController;
use App\Http\Controllers\Web\Owner\CustomerController as OwnerCustomerController;
use App\Http\Controllers\Web\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Web\Owner\FieldController as OwnerFieldController;
use App\Http\Controllers\Web\Owner\ProfileController as OwnerProfileController;
use App\Http\Controllers\Web\Owner\RevenueController as OwnerRevenueController;
use App\Http\Controllers\Web\Owner\TimeSlotController as OwnerTimeSlotController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->hasRole('owner')) {
        return redirect()->route('owner.dashboard');
    } else {
        return redirect()->route('customer.dashboard');
    }
})->middleware(['auth'])->name('dashboard');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    Route::resource('users', UserController::class);
});

// Owner Routes
Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');

    // Fields
    Route::get('/fields', [OwnerFieldController::class, 'index'])->name('fields.index');
    Route::get('/fields/create', [OwnerFieldController::class, 'create'])->name('fields.create');
    Route::post('/fields', [OwnerFieldController::class, 'store'])->name('fields.store');
    Route::get('/fields/{field}/edit', [OwnerFieldController::class, 'edit'])->name('fields.edit');
    Route::put('/fields/{field}', [OwnerFieldController::class, 'update'])->name('fields.update');
    Route::delete('/fields/{field}', [OwnerFieldController::class, 'destroy'])->name('fields.destroy');
    Route::post('/fields/{field}/toggle-status', [OwnerFieldController::class, 'toggleStatus'])->name('fields.toggle-status');

    // Time Slots
    Route::get('/fields/{field}/time-slots', [OwnerTimeSlotController::class, 'index'])->name('time-slots.index');
    Route::post('/fields/{field}/time-slots', [OwnerTimeSlotController::class, 'store'])->name('time-slots.store');
    Route::put('/time-slots/{timeSlot}', [OwnerTimeSlotController::class, 'update'])->name('time-slots.update');
    Route::delete('/time-slots/{timeSlot}', [OwnerTimeSlotController::class, 'destroy'])->name('time-slots.destroy');
    Route::post('/fields/{field}/time-slots/generate-default', [OwnerTimeSlotController::class, 'generateDefault'])->name('time-slots.generate-default');

    // Bookings
    Route::get('/bookings', [OwnerBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/pending', [OwnerBookingController::class, 'pending'])->name('bookings.pending');
    Route::get('/bookings/calendar', [OwnerBookingController::class, 'calendar'])->name('bookings.calendar');
    Route::post('/bookings/{booking}/confirm', [OwnerBookingController::class, 'confirm'])->name('bookings.confirm');
    Route::post('/bookings/{booking}/cancel', [OwnerBookingController::class, 'cancel'])->name('bookings.cancel');
    Route::post('/bookings/{booking}/checkin', [OwnerBookingController::class, 'checkin'])->name('bookings.checkin');

    // Customers
    Route::get('/customers', [OwnerCustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{customer}', [OwnerCustomerController::class, 'show'])->name('customers.show');

    // Revenue
    Route::get('/revenue', [OwnerRevenueController::class, 'index'])->name('revenue.index');

    // Profile
    Route::get('/profile', [OwnerProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [OwnerProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [OwnerProfileController::class, 'avatar'])->name('profile.avatar');
});

// Customer Routes
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', function () {
        return view('customer.dashboard');
    })->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
