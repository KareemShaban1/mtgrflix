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
        Schema::create('components', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->enum('type', ['single', 'multi']);
            $table->string('title')->nullable();
            $table->text('sub_title')->nullable();
            $table->string('button_name')->nullable();
            $table->text('description')->nullable();
            $table->text('image')->nullable();
            $table->text('button_link')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('components');
    }
};
