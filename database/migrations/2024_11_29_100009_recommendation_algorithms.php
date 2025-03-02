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
        Schema::create('recommendation_algorithms', function (Blueprint $table) {
            $table->id();
            $table->string('algorithm_name');
            $table->boolean('is_active')->default(false);
            $table->json('activated_at')->nullable();
            $table->json('deactivated_at')->nullable();
            $table->unsignedBigInteger('activation_count')->default(0);
            $table->unsignedBigInteger('reservations_count')->default(0);
            $table->integer('knn_k')->nullable();
            $table->integer('mlp_hidden_layer_1')->nullable();
            $table->integer('mlp_hidden_layer_2')->nullable();
            $table->integer('mlp_iterations')->nullable();
            $table->integer('decision_tree_depth')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recommendation_algorithms');
    }
};
