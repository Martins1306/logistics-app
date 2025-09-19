<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTipoToViajes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('viajes', function (Blueprint $table) {
        $table->string('tipo')->nullable()->after('descripcion_carga');
    });
}

public function down()
{
    Schema::table('viajes', function (Blueprint $table) {
        $table->dropColumn('tipo');
    });
}
}
