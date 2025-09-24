<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEstadoToMantenimientos extends Migration
{
    public function up()
    {
        Schema::table('mantenimientos', function (Blueprint $table) {
            $table->enum('estado', ['pendiente', 'en_proceso', 'completado', 'cancelado'])
                  ->default('pendiente')
                  ->after('descripcion');
        });
    }

    public function down()
    {
        Schema::table('mantenimientos', function (Blueprint $table) {
            $table->dropColumn('estado');
        });
    }
}