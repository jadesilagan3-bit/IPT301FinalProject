<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\ParkingSlotController;
use App\Http\Controllers\ParkingSessionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RegisterParkingController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/register-parking', [RegisterParkingController::class, 'create'])
    ->name('register-parking.create');

Route::post('/register-parking', [RegisterParkingController::class, 'store'])
    ->name('register-parking.store');

Route::resource('owners', OwnerController::class);
Route::resource('vehicles', VehicleController::class);
Route::resource('parking-slots', ParkingSlotController::class);
Route::resource('parking-sessions', ParkingSessionController::class);
Route::resource('payments', PaymentController::class);
