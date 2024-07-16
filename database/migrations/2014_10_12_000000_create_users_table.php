<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\UserRole;
use App\Models\Review;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surname');
            $table->enum('sex', ['male', 'female']);
            $table->enum('role', UserRole::TYPES)->default(UserRole::USER);
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('license')->unique();
            $table->date('birth');
            $table->string('town');
            $table->string('zip_code');
            $table->string('country');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
