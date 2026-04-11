<?php

use App\Http\Controllers\API\ReviewController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('reviews')->group(function () {
    Route::post('/', [ReviewController::class, 'store']);
    Route::get('/trip/{tripId}', [ReviewController::class, 'getByTrip']);
    Route::delete('/{id}', [ReviewController::class, 'delete']);
});