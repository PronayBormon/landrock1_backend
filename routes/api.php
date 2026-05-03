<?php

use App\Http\Controllers\API\Auth\AuthenticationApiController;
use App\Http\Controllers\API\Auth\PhoneVerificationController;
use App\Http\Controllers\Api\SubscriberApiController;
use App\Http\Controllers\API\TwilioController;
use App\Models\SystemSetting;
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

Route::get('systems', function () {
    $system = SystemSetting::firstOrCreate(
        ['id' => 1],
        [
            'site_name' => 'My App',
            'currency' => 'USD',
            'timezone' => 'UTC'
        ]
    );

    return response()->json([
        'status' => true,
        'message' => 'system',
        'data' => $system
    ]);
});


Route::post('/subscribe', [SubscriberApiController::class, 'subscribe']);


Route::prefix('v1')->group(function () {
    require base_path('routes/api/v1/trip.php');
    require base_path('routes/api/v1/profile.php');
    require base_path('routes/api/v1/review.php');
});
