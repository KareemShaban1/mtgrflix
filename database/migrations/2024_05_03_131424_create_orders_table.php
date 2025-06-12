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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('grand_total', 10, 2)->nullable();
            $table->decimal('sub_total', 10, 2)->nullable();
            $table->decimal('discount', 10, 2)->nullable();

            $table->string('payment_method')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('number')->nullable();
            $table->enum('status', ['new', 'processing','completed', 'delivered', 'cancelled'])->nullable()->default('new');
            $table->foreignId('currency_id')->constrained('currencies')->cascadeOnDelete();
            $table->foreignId('coupon_id')->nullable()->constrained('coupons')->cascadeOnDelete();
            $table->foreignId('status_id')->nullable()->constrained('statuses')->cascadeOnDelete();

            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
