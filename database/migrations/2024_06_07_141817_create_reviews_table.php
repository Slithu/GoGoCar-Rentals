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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('car_id')->constrained()->onDelete('cascade');
            $table->foreignId('reservation_id')->constrained()->onDelete('cascade');
            $table->unsignedTinyInteger('comfort_rating')->check('comfort_rating >= 1 and comfort_rating <= 5');
            $table->unsignedTinyInteger('driving_experience_rating')->check('driving_experience_rating >= 1 and driving_experience_rating <= 5');
            $table->unsignedTinyInteger('fuel_efficiency_rating')->check('fuel_efficiency_rating >= 1 and fuel_efficiency_rating <= 5');
            $table->unsignedTinyInteger('safety_rating')->check('safety_rating >= 1 and safety_rating <= 5');
            $table->decimal('overall_rating', 3, 2)->virtualAs('(
                (comfort_rating + driving_experience_rating + fuel_efficiency_rating + safety_rating) / 4
            )');
            $table->text('comment')->nullable();
            $table->timestamps();
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
