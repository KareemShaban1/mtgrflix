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
        Schema::create('product_fields', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->string('name')->nullable();
            $table->enum('input_type', ['text', 'textarea', 'select', 'checkbox', 'radio']);
            $table->boolean('required')->default(true);
            $table->json('options')->nullable(); // for select, checkbox, radio
            $table->boolean('multiple')->default(false); // only for select
            $table->integer('order')->default(0);
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_fields');
    }
};
