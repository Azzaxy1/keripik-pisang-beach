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
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('rating')->unsigned()->default(1);
            $table->string('title')->nullable();
            $table->text('comment')->nullable();
            $table->boolean('status')->default(false);
            $table->integer('helpful_count')->default(0);
            $table->timestamps();
            
            $table->index(['product_id', 'status']);
            $table->index(['user_id', 'product_id']);
            // Removed check constraint for MySQL compatibility
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_reviews');
    }
};
