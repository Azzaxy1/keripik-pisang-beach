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
        // Update payment_status enum values
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('payment_status');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])
                ->default('pending')
                ->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback to old enum values
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('payment_status');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->enum('payment_status', ['pending', 'completed', 'failed', 'refunded'])
                ->default('pending')
                ->after('status');
        });
    }
};
