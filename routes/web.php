<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\SportController; 
use App\Http\Controllers\Admin\FieldController;
use Illuminate\Support\Facades\Route;

// 1. Điều hướng trang chủ mặc định khi vừa vào web
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// 2. Bộ điều hướng Dashboard tổng sau khi đăng nhập theo từng vai trò (Role)
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


// 3. 🛡️ PHÂN HỆ ADMIN DASHBOARD (Đã bảo mật và gom cụm mượt mà)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Trang tổng quan đồ thị Dashboard Admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Quản lý Users gốc của nhóm bạn
    Route::resource('users', UserController::class);

    // ⚽ Phân hệ quản lý Môn thể thao (Sports) của bạn
    Route::get('/sports', [SportController::class, 'index'])->name('sports.index');
    Route::get('/sports/{id}/edit', [SportController::class, 'edit'])->name('sports.edit');
    Route::post('/sports', [SportController::class, 'store'])->name('sports.store');
    Route::post('/sports/{id}/toggle-status', [SportController::class, 'toggleStatus'])->name('sports.toggle-status');
    Route::post('/sports/{id}/update', [SportController::class, 'update'])->name('sports.update');
    Route::post('/sports/{id}/delete', [SportController::class, 'destroy'])->name('sports.destroy');

    // 🏢 Phân hệ quản lý Sân chi tiết (Fields) của bạn
    Route::get('/fields', [FieldController::class, 'index'])->name('fields.index');
    Route::get('/fields/{id}/edit', [FieldController::class, 'edit'])->name('fields.edit');
    Route::post('/fields', [FieldController::class, 'store'])->name('fields.store');
    Route::post('/fields/{id}/update', [FieldController::class, 'update'])->name('fields.update');
    Route::post('/fields/{id}/delete', [FieldController::class, 'destroy'])->name('fields.destroy');
    
});


// 4. Phân hệ của CHỦ SÂN (Owner Routes)
Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', function () {
        return view('owner.dashboard');
    })->name('dashboard');
});


// 5. Phân hệ của KHÁCH HÀNG (Customer Routes)
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', function () {
        return view('customer.dashboard');
    })->name('dashboard');
});


// 6. Quản lý thông tin tài khoản cá nhân chung (Profile)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// 7. 🌟 CỤM ROUTE GIẢ LẬP USERS: Phục vụ Sidebar chung không bao giờ crash lỗi 500
Route::prefix('users')->name('users.')->group(function () {
    Route::get('/', function () { return 'Trang danh sách thành viên'; })->name('index');
    Route::get('/create', function () { return 'Trang thêm thành viên'; })->name('create');
    Route::post('/', function () { return 'Xử lý thêm'; })->name('store');
    Route::get('/{id}/edit', function () { return 'Trang sửa thành viên'; })->name('edit');
    Route::post('/{id}/update', function () { return 'Xử lý cập nhật'; })->name('update');
    Route::post('/{id}/delete', function () { return 'Xử lý xóa'; })->name('destroy');
});


// Nạp hệ thống Đăng ký / Đăng nhập mặc định của Laravel Breeze
require __DIR__.'/auth.php';