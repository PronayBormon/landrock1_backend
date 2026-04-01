<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();

            // Core Identity
            $table->string('site_name');
            $table->string('site_tagline')->nullable();
            $table->string('logo')->nullable();        // Light Logo
            $table->string('dark_logo')->nullable();   // Dark Logo
            $table->string('favicon')->nullable();

            // Contact Info
            $table->string('contact_email');
            $table->string('support_email')->nullable();
            $table->string('phone');
            $table->string('phone_alt')->nullable();
            $table->text('address');
            $table->string('city');
            $table->string('state')->nullable();
            $table->string('country');
            $table->string('postal_code')->nullable();

            // Social Links
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('instagram')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('youtube')->nullable();
            $table->string('tiktok')->nullable();

            // Branding
            $table->string('primary_color')->nullable();
            $table->string('secondary_color')->nullable();
            $table->string('currency');
            $table->string('currency_symbol');
            $table->string('timezone');
            $table->string('date_format');

            // System Controls
            $table->boolean('maintenance_mode')->default(false);
            $table->boolean('allow_registration')->default(true);
            $table->boolean('email_verification')->default(true);
            $table->boolean('sms_verification')->default(false);

            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();

            // Legal
            $table->text('footer_text')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
