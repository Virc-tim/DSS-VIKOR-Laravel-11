<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VikorController;
use App\Http\Controllers\CalculationsController;

Route::get('/', function () {
    return view('vikor');
});

Route::post('/vikor', [VikorController::class, 'store'])->name('vikor.store');

Route::get('/calculation', [VikorController::class, 'sessionStore'])->name('sessionStore');

Route::post('/calculation', [CalculationsController::class, 'store'])->name('calculation.store');
