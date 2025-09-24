<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddViajeIdToMovimientosInventario extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('movimientos_inventario', function (Blueprint $table) {
            $table->unsignedBigInteger('viaje_id')->nullable();
            $table->foreign('viaje_id')->references('id')->on('viajes')->onDelete('set null');
        });
    }
    
    public function down()
    {
        Schema::table('movimientos_inventario', function (Blueprint $table) {
            $table->dropForeign(['viaje_id']);
            $table->dropColumn('viaje_id');
        });
    }
}
