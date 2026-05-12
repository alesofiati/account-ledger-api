<?php

use App\Http\Controllers\BalanceController;
use App\Http\Controllers\ResetController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('reset', [ResetController::class, '__invoke'])->name('reset');
Route::get('balance', [BalanceController::class, '__invoke'])->name('balance');
