<?php

use App\Http\Controllers\API\Auth\AuthenticationApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('auth')->controller(AuthenticationApiController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('register/verify', 'verifyOtp');
    Route::post('login', 'login');
    Route::post('forget/password', 'forgotPassword');
    Route::post('forget/verify', 'verifyForgotOtp');
    Route::post('forget/password/update', 'resetPassword');
    Route::post('resend', 'resendOtp');
});
