<?php

use App\Http\Controllers\Api\ProxyController;
use Illuminate\Support\Facades\Route;

Route::post('proxies/check-all', [ProxyController::class, 'checkAll']);
Route::post('proxies/{proxy}/check', [ProxyController::class, 'check']);

Route::apiResource('proxies', ProxyController::class)->only(['index', 'store', 'update', 'destroy']);
