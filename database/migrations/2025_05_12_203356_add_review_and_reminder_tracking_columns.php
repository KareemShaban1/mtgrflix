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
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('review_requested_at')->nullable()->after('status_id');
        });

        Schema::table('carts', function (Blueprint $table) {
            $table->timestamp('reminder_sent_at')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('review_requested_at');
        });

        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn('reminder_sent_at');
        });
    }
};
