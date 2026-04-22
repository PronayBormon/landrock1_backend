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

    /**
     * Send normal SMS
     */
    public function sendSMS(Request $request)
    {
        $request->validate([
            'phone'   => 'required|string',
            'message' => 'required|string',
        ]);

        $response = $this->twilio->sendSMS(
            $request->phone,
            $request->message
        );

        return response()->json($response, $response['success'] ? 200 : 422);
    }

    /**
     * Send OTP
     */
    public function sendOTP(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
        ]);

        $response = $this->twilio->sendOTP($request->phone);

        return response()->json($response, $response['success'] ? 200 : 422);
    }

    /**
     * Verify OTP
     */
    public function verifyOTP(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'code'  => 'required|string',
        ]);

        $response = $this->twilio->verifyOTP(
            $request->phone,
            $request->code
        );

        return response()->json($response, $response['success'] ? 200 : 422);
    }
}
