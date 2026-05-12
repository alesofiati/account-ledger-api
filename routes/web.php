<?php

use App\Http\Controllers\ResetController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('reset', [ResetController::class, '__invoke'])->name('reset');
