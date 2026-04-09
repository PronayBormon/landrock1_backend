<?php

use App\Http\Controllers\API\ProfileApiController;
use Illuminate\Support\Facades\Route;

// Protected routes
Route::middleware('auth:sanctum')->prefix('profile')->group(function () {
    Route::post('update', [ProfileApiController::class, 'updateProfile']);
    Route::get('details', [ProfileApiController::class, 'profile']);
    Route::get('my-trips', [ProfileApiController::class, 'myTrip']);
});