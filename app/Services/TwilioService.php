<?php

namespace App\Services;

use Twilio\Rest\Client;
use Exception;

class TwilioService
{
    protected $client;
    protected $from;
    protected $verifySid;
    protected $messagingSid;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->client = new Client(
            env('TWILIO_ACCOUNT_SID'),
            env('TWILIO_AUTH_TOKEN')
        );

        $this->messagingSid = env('TWILIO_MESSAGING_SERVICE_SID');
        $this->verifySid = env('TWILIO_VERIFY_SERVICE_SID');
    }

    /**
     * Send normal SMS
     */
    // public function sendSMS(string $to, string $message)
    // {
    //     try {
    //         $sms = $this->client->messages->create($to, [
    //             'from' => $this->from,
    //             'body' => $message,
    //         ]);

    //         return [
    //             'success' => true,
    //             'sid'     => $sms->sid,
    //             'message' => 'SMS sent successfully',
    //         ];
    //     } catch (Exception $e) {
    //         return [
    //             'success' => false,
    //             'message' => $e->getMessage(),
    //         ];
    //     }
    // }
    public function sendSMS(string $to, string $message)
    {
        try {
            $sms = $this->client->messages->create($to, [
                'messagingServiceSid' => $this->messagingSid,
                'body' => $message,
            ]);

            return [
                'success' => true,
                'sid' => $sms->sid,
                'message' => 'SMS sent successfully',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Send OTP using Twilio Verify
     */
    public function sendOTP(string $to)
    {
        try {
            $verification = $this->client->verify
                ->v2
                ->services($this->verifySid)
                ->verifications
                ->create($to, "sms");

            return [
                'success' => true,
                'status'  => $verification->status,
                'message' => 'OTP sent successfully',
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Verify OTP code
     */
    public function verifyOTP(string $to, string $code)
    {
        try {
            $check = $this->client->verify
                ->v2
                ->services($this->verifySid)
                ->verificationChecks
                ->create([
                    'to'   => $to,
                    'code' => $code,
                ]);

            return [
                'success' => $check->status === 'approved',
                'status'  => $check->status,
                'message' => $check->status === 'approved'
                    ? 'OTP verified successfully'
                    : 'Invalid OTP',
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
