<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TwilioService;

class TwilioController extends Controller
{
    protected $twilio;

    public function __construct(TwilioService $twilio)
    {
        $this->twilio = $twilio;
    }

    public function sendOtp(Request $request, TwilioService $twilio)
    {
        $request->validate([
            'phone' => 'required'
        ]);

        $otp = rand(100000, 999999);

        // Save OTP
        // Otp::updateOrCreate(
        //     ['phone' => $request->phone],
        //     [
        //         'otp' => $otp,
        //         'expires_at' => Carbon::now()->addMinutes(5)
        //     ]
        // );

        // Send SMS
        $twilio->sendOtp($request->phone, $otp);

        return response()->json([
            'message' => 'OTP sent successfully'
        ]);
    }
}
