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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->text('bio')->nullable();
            $table->string('avatar')->nullable();

            $table->enum('ride_style', [
                'chill',
                'talkative',
                'quiet',
                'work_friendly'
            ])->nullable();

            $table->enum('music_preference', [
                'rap',
                'pop',
                'afro',
                'rock',
                'no_music'
            ])->nullable();

            $table->enum('conversation_level', [
                'yes',
                'little',
                'prefer_quiet'
            ])->nullable();
            $table->enum('smoke', [
                'yes',
                'no',
            ])->nullable();

            $table->json('interested')->nullable();
            $table->json('personalization')->nullable();

            // $table->boolean('smoke')->default(false);
            $table->string('pet')->nullable();
            $table->string('connect_like_rider')->nullable();
            $table->string('what_kind_ride')->nullable();

            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
