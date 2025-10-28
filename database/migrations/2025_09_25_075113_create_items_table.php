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
        Schema::create('items', function (Blueprint $table) {
            $table->id();

            //DATOS QUE OBTENDREMOS DE LA API EXTERNA
            $table->unsignedBigInteger('api_id')->unique(); //ID del item en la API externa
            $table->string('type'); //game o anime
            $table->string('title'); //titulo del item
            $table->string('cover_image_url'); //url de la imagen del item
            $table->text('synopsis')->nullable(); //sinopsis del item
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
