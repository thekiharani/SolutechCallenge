<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Route::get('/', HomeController::class)->name('home');

Route::get('test', function () {
    $arr1 = [2, 4, 6, 8];
    $arr2 = [1, 2, 3, 4];
    dd(array_diff($arr1, $arr2), array_diff($arr2, $arr1));
});

Route::get('/{any}', HomeController::class)->where('any', '.*');
