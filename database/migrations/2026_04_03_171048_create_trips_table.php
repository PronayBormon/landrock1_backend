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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            // Locations (can be text or foreign key later)
            $table->string('from_location');
            $table->decimal('from_latitude', 10, 7);
            $table->decimal('from_longitude', 10, 7);

            $table->string('to_location');
            $table->decimal('to_latitude', 10, 7);
            $table->decimal('to_longitude', 10, 7);

            // Ride details
            $table->date('ride_date');
            $table->time('ride_time');

            $table->integer('available_seat');
            $table->decimal('price_per_seat', 10, 2);

            $table->enum('ride_status', ['active', 'completed', 'cancelled'])->default('active');

            // Publisher (user)
            $table->foreignId('publisher_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
