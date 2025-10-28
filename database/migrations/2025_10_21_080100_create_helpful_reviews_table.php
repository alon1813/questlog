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
        Schema::create('helpful_reviews', function (Blueprint $table) {
            $table->id();
            // Usamos unsignedBigInteger porque los IDs de las tablas pivote pueden ser grandes
            $table->unsignedBigInteger('review_id');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['review_id', 'user_id']);
            $table->foreign('review_id')->references('id')->on('item_user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('helpful_reviews');
    }
};
