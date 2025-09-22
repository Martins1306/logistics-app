<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->string('calle')->nullable();
            $table->string('numero')->nullable();
            $table->string('codigo_postal')->nullable();
            $table->string('partido')->nullable();
        });
    }

    public function down()
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn(['calle', 'numero', 'codigo_postal', 'localidad', 'partido', 'provincia']);
        });
    }
};