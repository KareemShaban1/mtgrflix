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
            $table->timestamp('viewed_at')->nullable()->after('review_requested_at');
        });
        Schema::table('testimonials', function (Blueprint $table) {
            $table->timestamp('viewed_at')->nullable()->after('user_id');
        });
        Schema::table('reviews', function (Blueprint $table) {
            $table->timestamp('viewed_at')->nullable()->after('approved');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('viewed_at');
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropColumn('viewed_at');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn('viewed_at');
        });
    }
};
