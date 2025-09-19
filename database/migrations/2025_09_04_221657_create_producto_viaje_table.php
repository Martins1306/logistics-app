<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('producto_viaje', function (Blueprint $table) {
            $table->id();
            $table->foreignId('viaje_id')->constrained()->onDelete('cascade');
            $table->foreignId('producto_id')->constrained()->onDelete('cascade');
            $table->integer('cantidad'); // kg, unidades, bolsas, etc.
            $table->text('notas')->nullable(); // opcional
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('producto_viaje');
    }
};