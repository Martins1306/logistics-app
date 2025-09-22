<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDetailedAddressToChoferes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('choferes', function (Blueprint $table) {
            $table->string('calle')->nullable();
            $table->string('numero')->nullable();
            $table->string('codigo_postal')->nullable();
            $table->string('localidad')->nullable();
            $table->string('partido')->nullable();
            $table->string('provincia')->nullable();
    });
}

public function down()
{
    Schema::table('choferes', function (Blueprint $table) {
        $table->dropColumn(['calle', 'numero', 'codigo_postal', 'localidad', 'partido', 'provincia']);
    });
}
}
