<?php

namespace App\Providers;

use App\Models\Credential;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }



    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            $smtp = Credential::where('service', 'smtp')
                ->where('is_active', 1)
                ->pluck('key_value', 'key_name')
                ->toArray();

            if (!empty($smtp)) {
                config([
                    'mail.default' => $smtp['MAIL_MAILER'] ?? 'smtp',

                    'mail.mailers.smtp.host'       => $smtp['MAIL_HOST'] ?? null,
                    'mail.mailers.smtp.port'       => (int) ($smtp['MAIL_PORT'] ?? 587),
                    'mail.mailers.smtp.username'   => $smtp['MAIL_USERNAME'] ?? null,
                    'mail.mailers.smtp.password'   => $smtp['MAIL_PASSWORD'] ?? null,
                    'mail.mailers.smtp.encryption' => $smtp['MAIL_ENCRYPTION'] ?? null,

                    'mail.from.address' => $smtp['MAIL_FROM_ADDRESS'] ?? null,
                    'mail.from.name'    => $smtp['MAIL_FROM_NAME'] ?? config('app.name'),
                ]);
            }
        } catch (\Throwable $e) {
            // Prevent app crash if DB not ready
            logger()->error('SMTP load failed: ' . $e->getMessage());
        }
    }
}
