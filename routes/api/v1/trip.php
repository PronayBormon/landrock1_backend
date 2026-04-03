<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\TripController;

// Public routes
Route::get('trips', [TripController::class, 'index']);
Route::get('trips/{trip}', [TripController::class, 'show']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {

    Route::post('trips', [TripController::class, 'store']);
    Route::post('trips/{trip}', [TripController::class, 'update']);
    Route::delete('trips/{trip}', [TripController::class, 'destroy']);

});