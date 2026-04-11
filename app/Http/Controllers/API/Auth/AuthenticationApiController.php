<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use function Symfony\Component\Clock\now;

class AuthenticationApiController extends Controller
{

    use ApiResponse;

    public function register(Request $request)
    {
        $validate = Validator::make($request->all(), [
            // 'fullname' => 'required|string|min:3|max:255',
            'email' => 'required|email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validate->fails()) {
            return $this->validationErrorResponse($validate->errors(), 422);
        }

        $user = User::where('email', $request->email)->first();


        if ($user && $user->email_verified_at) {
            return $this->errorResponse('Email already registered', 422);
        }

        if (!$user) {
            $user = User::create([
                // 'name' => $request->fullname,
                'name' => Str::before($request->email, '@'),
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
        }


        $otp = (new Otp)->generate(
            $request->email,
            'numeric',
            6,
            5,
        );

        Mail::raw("Your OTP is: " . $otp->token, function ($message) use ($request) {
            $message->to($request->email)
                ->subject('Your OTP Code');
        });

        return $this->successResponse('OTP sent successfully', $otp->token);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required'
        ]);

        $otp = (new Otp)->validate($request->email, $request->otp);

        if (!$otp->status) {
            return $this->errorResponse('Invalid OTP', 400);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $this->errorResponse('Invalid email address', 404);
        }

        $user->update([
            'email_verified_at' => now()
        ]);


        $token = $user->createToken('auth_token')->plainTextToken;

        $data = [
            'token' => $token,
            'user' => $user,
        ];

        return $this->successResponse('User registered successfully',  $data);
    }


    public function login(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        if ($validate->fails()) {
            return $this->validationErrorResponse($validate->errors(), 422);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->errorResponse('Invalid email or password', 401);
        }

        $user = Auth::user();

        // Check email verified
        if (!$user->email_verified_at) {
            return $this->errorResponse('Email not verified', 403);
        }

        // Create Sanctum token
        $token = $user->createToken('auth_token')->plainTextToken;


        $data = [
            'token' => $token,
            'user' => $user,
        ];

        return $this->successResponse('User registered successfully',  $data);
        // return response()->json([
        //     'status' => true,
        //     'message' => 'Login successful',
        //     'token' => $token,
        //     'user' => $user
        // ]);
    }

    public function forgotPassword(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email'
        ]);

        if ($validate->fails()) {
            return $this->validationErrorResponse($validate->errors(), 422);
        }

        $otp = (new Otp)->generate(
            $request->email,
            'numeric',
            6,
            5
        );

        Mail::raw("Your password reset OTP is: " . $otp->token, function ($message) use ($request) {
            $message->to($request->email)
                ->subject('Password Reset OTP');
        });


        return $this->successResponse('OTP sent to your email',  $otp->token);
    }

    public function verifyForgotOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required'
        ]);

        $otp = (new Otp)->validate($request->email, $request->otp);

        if (!$otp->status) {
            return $this->errorResponse('Invalid or expired OTP', 400);
        }

        $user = User::where('email', $request->email)->first();

        $resetToken = Str::random(60);

        $user->update([
            'remember_token' => $resetToken
        ]);

        $data = [
            'remember_token' => $resetToken
        ];

        return $this->successResponse('OTP verified successfully',  $data);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'reset_token' => 'required',
            'password' => 'required|min:6|confirmed'
        ]);

        $user = User::where('email', $request->email)
            ->where('remember_token', $request->reset_token)
            ->first();

        if (!$user) {
            return $this->errorResponse('Invalid reset token', 400);
        }

        $user->update([
            'password' => Hash::make($request->password),
            'remember_token' => null
        ]);

        // return response()->json([
        //     'status' => true,
        //     'message' => 'Password reset successfully'
        // ]);


        // $data = [
        //     'remember_token' => $resetToken
        // ];

        return $this->successResponse('Password reset successfully');
    }


    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $this->errorResponse('User not found', 404);
        }

        // generate new OTP
        $otp = (new Otp)->generate(
            $request->email,
            'numeric',
            6,
            5
        );

        Mail::raw("Your OTP is: " . $otp->token, function ($message) use ($request) {
            $message->to($request->email)
                ->subject('Your OTP Code');
        });

        return $this->successResponse('OTP resent successfully', $otp->token);
    }
}
