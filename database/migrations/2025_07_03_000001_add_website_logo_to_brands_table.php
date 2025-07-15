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
        Schema::table('brands', function (Blueprint $table) {
            if (!Schema::hasColumn('brands', 'logo')) {
                $table->string('logo')->nullable()->after('image');
            }

            if (!Schema::hasColumn('brands', 'website')) {
                $table->string('website')->nullable()->after('logo');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('brands', function (Blueprint $table) {
            if (Schema::hasColumn('brands', 'logo')) {
                $table->dropColumn('logo');
            }

            if (Schema::hasColumn('brands', 'website')) {
                $table->dropColumn('website');
            }
        });
    }
};
