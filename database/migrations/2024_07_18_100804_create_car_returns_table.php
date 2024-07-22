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
        Schema::create('car_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained('reservations')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->dateTime('return_date');
            $table->enum('exterior_condition', ['No damage, scratches or dents', 'Minor scratches or dents that are difficult to notice', 'Visible scratches or dents, but do not affect the functionality of the vehicle', 'Significant damage that may require repair, but vehicle is still functional', 'Serious damage that affects the functionality of the vehicle']);
            $table->enum('interior_condition', ['The interior is in perfect condition, no signs of use', 'Minor signs of use, no damage to the upholstery or equipment', 'Visible signs of use, minor damage to the upholstery or equipment', 'Significant signs of use, damage to upholstery or equipment that may require repair', 'Serious damage to the interior that affects the comfort of use of the vehicle']);
            $table->string('exterior_damage_description')->nullable();
            $table->string('interior_condition_description')->nullable();
            $table->string('car_parts_condition')->nullable();
            $table->decimal('penalty_amount', 10, 2)->default(0.00);
            $table->string('comments')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('returns');
    }
};
