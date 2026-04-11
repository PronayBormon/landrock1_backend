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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();     // person being reviewed
            $table->foreignId('review_by')->constrained('users')->cascadeOnDelete(); // reviewer
            $table->foreignId('trip_id')->constrained()->cascadeOnDelete();

            // Review data
            $table->text('review')->nullable();
            $table->tinyInteger('star')->default(5);

            $table->timestamps();

            // Prevent duplicate review for same trip
            $table->unique(['user_id', 'review_by', 'trip_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
