<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mantenimientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehiculo_id')->constrained()->onDelete('cascade');
            $table->string('tipo'); // aceite, correas, caja, cubiertas, etc.
            $table->integer('kilometraje'); // km del vehículo
            $table->date('fecha');
            $table->decimal('costo', 10, 2)->nullable();
            $table->text('descripcion')->nullable();
            $table->string('proveedor')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mantenimientos');
    }
};