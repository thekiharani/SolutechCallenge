<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

// Auth
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register'])->name('register');

Route::middleware(['auth:sanctum'])
    ->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('profile', [AuthController::class, 'profile'])->name('profile');
        Route::patch('profile/update', [AuthController::class, 'update'])->name('profile.update');
        Route::patch('password/change', [AuthController::class, 'password_change'])->name('password.change');
    });

