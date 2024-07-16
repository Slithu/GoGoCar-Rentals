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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('brand');
            $table->string('model');
            $table->enum('car_body', ['Small Car', 'Coupe', 'Convertible', 'Hatchback', 'Estate Car', 'Sedan', 'SUV', 'Minivan']);
            $table->enum('engine_type', ['Gasoline', 'Diesel', 'Hybrid', 'Electric']);
            $table->enum('transmission', ['Automatic', 'Manual']);
            $table->integer('engine_power');
            $table->integer('seats');
            $table->integer('doors');
            $table->integer('suitcases');
            $table->decimal('price');
            $table->string('description');
            $table->string('image_path', 150)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
