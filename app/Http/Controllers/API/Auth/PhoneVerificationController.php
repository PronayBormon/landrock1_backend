<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\PhoneVerification;
use App\Models\User;
use App\Services\TwilioService;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PhoneVerificationController extends Controller
{
    use ApiResponse;
    public function sendOtp(Request $request, TwilioService $twilio)
    {
        $request->validate([
            'phone' => 'required|string'
        ]);

        $otp = rand(100000, 999999);

        $data = PhoneVerification::updateOrCreate(
            ['phone' => $request->phone],
            [
                'otp' => $otp,
                'expires_at' => Carbon::now()->addMinutes(5),
                'verified' => false
            ]
        );

        $twilio->sendOtp($request->phone, $otp);

        return $this->successResponse('OTP sent successfully', $data);
    }


    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'otp' => 'required'
        ]);

        $record = PhoneVerification::where('phone', $request->phone)->first();

        if (!$record) {
            return response()->json(['message' => 'Invalid phone'], 404);
        }

        if ($record->expires_at < now()) {
            return response()->json(['message' => 'OTP expired'], 400);
        }

        if ($record->otp != $request->otp) {
            return response()->json(['message' => 'Invalid OTP'], 400);
        }

        // mark verification
        $record->update(['verified' => true]);

        // get logged-in user
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // update phone
        $user->update([
            'phone' => $request->phone,
            'phone_verified_at' => now()
        ]);

        // optional: delete OTP
        $record->delete();

        return response()->json([
            'message' => 'Phone verified successfully',
            'user' => $user
        ]);
    }
}
