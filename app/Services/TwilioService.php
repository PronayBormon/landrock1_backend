<?php

namespace App\Services;

use Twilio\Rest\Client;

class TwilioService
{
    protected $client;
    protected $from;

    public function __construct()
    {
        $this->client = new Client(
            config('services.twilio.sid'),
            config('services.twilio.token')
        );

        $this->from = config('services.twilio.from');
    }

    // public function sendOtp($phone, $otp)
    // {
    //     return $this->client->messages->create(
    //         $phone,
    //         [
    //             'from' => $this->from,
    //             'body' => "Your OTP is: $otp"
    //         ]
    //     );
    // }

    public function sendOtp($phone, $otp)
    {
        return $this->client->messages->create(
            $phone,
            [
                'messagingServiceSid' => config('services.twilio.messaging_service_sid'),
                'body' => "Your OTP is: $otp"
            ]
        );
    }
}
