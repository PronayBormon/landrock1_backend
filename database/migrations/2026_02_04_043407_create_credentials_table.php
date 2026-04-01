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
        Schema::create('credentials', function (Blueprint $table) {
            $table->id();
            $table->string('service');
            // stripe | paypal | smtp
            $table->string('key_name');
            // secret_key | client_id | host | port
            $table->text('key_value');
            // encrypted value
            $table->string('environment')->default('live');
            // live | sandbox | test
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['service', 'key_name', 'environment']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credentials');
    }
};
