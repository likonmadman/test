<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\ActivityController;

Route::middleware(['apikey'])->group(function () {

    // Организации
    Route::middleware('apikey')->prefix('organizations')->group(function () {
        Route::get('building/{buildingId}', [OrganizationController::class, 'byBuilding']);
        Route::get('activity/{activityId}', [OrganizationController::class, 'byActivity']);
        Route::get('radius', [OrganizationController::class, 'byRadius']);
        Route::get('search', [OrganizationController::class, 'search']);
        Route::get('{id}', [OrganizationController::class, 'show']);
    });

    // Здания
    Route::prefix('buildings')->group(function () {
        Route::get('/', [BuildingController::class, 'index']);
        Route::get('/{id}', [BuildingController::class, 'show']);
    });

    // Виды деятельности
    Route::prefix('activities')->group(function () {
        Route::get('/', [ActivityController::class, 'index']);
        Route::get('/{name}', [ActivityController::class, 'search']);
    });

});