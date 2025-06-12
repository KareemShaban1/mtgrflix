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
            $table->string('first_name')->nullable();
            $table->string('name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('city')->nullable();
            $table->string('mobile')->nullable();
            $table->string('birthday')->nullable();
            // $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('email')->nullable();
            $table->string('gender')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone')->nullable();
            $table->string('country_code')->nullable();
            $table->string('password')->nullable();
            $table->boolean('prefer_ads')->default(false);
            $table->boolean('is_admin')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_active')->default(true);
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
