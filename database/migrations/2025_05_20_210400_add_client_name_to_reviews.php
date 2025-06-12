<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use phpDocumentor\Reflection\Types\Nullable;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->string('client_name')->nullable();
            $table->string('type')->nullable();
        });
        Schema::table('testimonials', function (Blueprint $table) {
            $table->string('client_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn('client_name');
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropColumn('client_name');
        });
    }
};
