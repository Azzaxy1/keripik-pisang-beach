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
            // Add missing columns
            $table->decimal('subtotal', 10, 2)->default(0)->after('total_amount');
            $table->string('currency', 3)->default('IDR')->after('notes');
            
            // Add foreign key constraints for address IDs if they don't exist
            if (!Schema::hasColumn('orders', 'shipping_address_id')) {
                $table->foreignId('shipping_address_id')->nullable()->after('payment_id')->constrained('user_addresses')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('orders', 'billing_address_id')) {
                $table->foreignId('billing_address_id')->nullable()->after('shipping_address_id')->constrained('user_addresses')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['subtotal', 'currency']);
            
            if (Schema::hasColumn('orders', 'shipping_address_id')) {
                $table->dropForeign(['shipping_address_id']);
                $table->dropColumn('shipping_address_id');
            }
            
            if (Schema::hasColumn('orders', 'billing_address_id')) {
                $table->dropForeign(['billing_address_id']);
                $table->dropColumn('billing_address_id');
            }
        });
    }
};
