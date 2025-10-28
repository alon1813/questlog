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
        Schema::create('item_user', function (Blueprint $table) {
            $table->id();

            //creamos las foreign keys
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            //columnas adicionales con la info
            $table->string('status')->default('Pendiente'); //estado del item (jugando, completado, pendiente, abandonado)
            $table->unsignedTinyInteger('score')->nullable(); //puntuacion del item (1-10)
            $table->text('review')->nullable(); //reseña del item
            

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_user');
    }
};
