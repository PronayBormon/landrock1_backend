<?php

use App\Http\Controllers\API\RideRequestApiController;
use Illuminate\Support\Facades\Route;

// Public routes (search/browse ride requests)
Route::get('ride-requests', [RideRequestApiController::class, 'index']);
Route::get('ride-requests/{id}', [RideRequestApiController::class, 'show']);

// Protected routes (user posting, my requests, update, delete)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('ride-requests', [RideRequestApiController::class, 'store']);
    Route::get('my-ride-requests', [RideRequestApiController::class, 'myRequests']);
    Route::put('ride-requests/{id}', [RideRequestApiController::class, 'update']);
    Route::delete('ride-requests/{id}', [RideRequestApiController::class, 'destroy']);
});
