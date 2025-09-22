<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('proveedores', function (Blueprint $table) {
            $table->string('calle')->nullable()->after('direccion');
            $table->string('numero')->nullable()->after('calle');
            $table->string('codigo_postal')->nullable()->after('numero');
            $table->string('localidad')->nullable()->after('codigo_postal');
            $table->string('partido')->nullable()->after('localidad');
            $table->string('provincia')->nullable()->after('partido');
            
            // Opcional: eliminar el campo antiguo 'direccion' si ya no lo usas
            // $table->dropColumn('direccion');
        });
    }

    public function down()
    {
        Schema::table('proveedores', function (Blueprint $table) {
            $table->dropColumn(['calle', 'numero', 'codigo_postal', 'localidad', 'partido', 'provincia']);
            // $table->string('direccion')->nullable();
        });
    }
};