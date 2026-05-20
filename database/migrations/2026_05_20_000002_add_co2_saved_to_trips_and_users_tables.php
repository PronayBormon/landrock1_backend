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
        Schema::table('trips', function (Blueprint $table) {
            $table->decimal('co2_saved_kg', 10, 2)->default(0)->after('ride_status');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->decimal('co2_saved_kg', 10, 2)->default(0)->after('phone_verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->dropColumn('co2_saved_kg');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('co2_saved_kg');
        });
    }
};
