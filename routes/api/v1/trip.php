<?php

use App\Http\Controllers\API\TripBookingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\TripController;

// Public routes
Route::get('unauthenticated/trips', [TripController::class, 'index']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {

    Route::get('trips', [TripController::class, 'index']);
    Route::get('trips/{trip}', [TripController::class, 'show']);

    Route::post('trips', [TripController::class, 'store']);
    Route::post('trips/{trip}', [TripController::class, 'update']);
    Route::delete('trips/{trip}', [TripController::class, 'destroy']);
    Route::post('trips/complete/{trip}', [TripController::class, 'completeTrip']);
    Route::post('trips/calcelled/{trip}', [TripController::class, 'calcelledTrip']);

    /**
     * ******************************************************
     * ******************* Booking **************************
     * ******************************************************
     */
    Route::post('trip/booking', [TripBookingController::class, 'requestSeat']);
    Route::get('trips/request/list', [TripBookingController::class, 'tripRequestList']);
    Route::get('trips/users/list/{id}', [TripBookingController::class, 'tripusersList']);
    Route::post('trips/request/accept/{id}', [TripBookingController::class, 'tripRequestAccept']);
    Route::post('trips/request/reject/{id}', [TripBookingController::class, 'tripRequestReject']);
});
