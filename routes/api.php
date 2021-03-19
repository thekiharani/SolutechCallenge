<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SupplierController;
use Illuminate\Support\Facades\Route;

// Auth
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register'])->name('register');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('profile', [AuthController::class, 'profile'])->name('profile');
    Route::patch('profile/update', [AuthController::class, 'update'])->name('profile.update');
    Route::patch('password/change', [AuthController::class, 'password_change'])->name('password.change');

    // Resourceful routes
    Route::apiResource('products', ProductController::class);
    Route::patch('products/{product}/restore', [ProductController::class, 'restore'])
        ->name('products.restore');

    Route::apiResource('orders', OrderController::class);
    Route::patch('orders/{product}/restore', [OrderController::class, 'restore'])
        ->name('orders.restore');

    Route::apiResource('suppliers', SupplierController::class);
    Route::patch('suppliers/{product}/restore', [SupplierController::class, 'restore'])
        ->name('suppliers.restore');
});

