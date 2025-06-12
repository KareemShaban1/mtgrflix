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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('currency_id')->constrained('currencies')->cascadeOnDelete();

            $table->decimal('total', 10, 2)->nullable();
            $table->decimal('sub_total', 10, 2)->nullable();
            $table->json('discount')->nullable();
            $table->decimal('saving', 10, 2)->nullable();
            $table->decimal('tax', 10, 2)->default(0);
            $table->string('paymentMethod')->nullable();
            $table->string('invoice_id')->nullable();
            $table->string('transaction_ref')->nullable();
            $table->string('status')->nullable();
            $table->string('response_message')->nullable();
            $table->text('data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
