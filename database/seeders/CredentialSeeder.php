<?php

namespace Database\Seeders;

use App\Models\Credential;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CredentialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $environment = 'production';

        $credentials = [

            /*
            |--------------------------------------------------------------------------
            | SMTP Credentials
            |--------------------------------------------------------------------------
            */
            'smtp' => [
                'MAIL_MAILER'        => 'smtp',
                'MAIL_HOST'          => 'mail.thewarriors.team',
                'MAIL_PORT'          => '465',
                'MAIL_USERNAME'      => 'dev@thewarriors.team',
                'MAIL_PASSWORD'      => 'hPqMI~NM;TAwT9km',
                'MAIL_ENCRYPTION'    => 'ssl',
                'MAIL_FROM_ADDRESS' => 'dev@thewarriors.team',
                'MAIL_FROM_NAME'    => config('app.name'),
            ],

            /*
            |--------------------------------------------------------------------------
            | Stripe Credentials
            |--------------------------------------------------------------------------
            */
            'stripe' => [
                'STRIPE_KEY'         => 'pk_test_xxxxxxxxxx',
                'STRIPE_SECRET'      => 'sk_test_xxxxxxxxxx',
                'STRIPE_WEBHOOK'     => 'whsec_xxxxxxxxxx',
            ],

            /*
            |--------------------------------------------------------------------------
            | PayPal Credentials
            |--------------------------------------------------------------------------
            */
            'paypal' => [
                'PAYPAL_CLIENT_ID'     => 'paypal_client_id_here',
                'PAYPAL_CLIENT_SECRET' => 'paypal_client_secret_here',
                'PAYPAL_MODE'          => 'sandbox', // sandbox | live
            ],
        ];

        foreach ($credentials as $service => $keys) {
            foreach ($keys as $keyName => $value) {
                Credential::updateOrCreate(
                    [
                        'service'     => $service,
                        'key_name'    => $keyName,
                        'environment' => $environment,
                    ],
                    [
                        'key_value' => $value,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
