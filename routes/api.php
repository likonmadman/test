<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\BookingController;

Route::apiResource('resources', ResourceController::class)->only(['index', 'store']);
Route::apiResource('bookings', BookingController::class)->only(['store', 'destroy']);
Route::get('resources/{resource}/bookings', [BookingController::class, 'resourceBookings']);