<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('producto_viaje', function (Blueprint $table) {
            // Agregar las columnas que faltan
            $table->foreignId('viaje_id')->constrained('viajes')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->integer('cantidad')->unsigned();
            $table->text('notas')->nullable();
        });
    }

    public function down()
    {
        Schema::table('producto_viaje', function (Blueprint $table) {
            $table->dropForeign(['viaje_id']);
            $table->dropForeign(['producto_id']);
            $table->dropColumn(['viaje_id', 'producto_id', 'cantidad', 'notas']);
        });
    }
};