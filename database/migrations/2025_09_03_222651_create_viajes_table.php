<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViajesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('viajes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehiculo_id')->constrained('vehiculos')->onDelete('cascade');
            $table->string('origen');
            $table->string('destino');
            $table->date('fecha_salida');
            $table->date('fecha_llegada')->nullable();
            $table->integer('kilometros');
            $table->text('descripcion_carga');
            $table->string('estado')->default('en curso'); // en curso, completado, cancelado
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('viajes');
    }
}
