<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChoferIdToVehiculosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
        public function up()
    {
        Schema::table('vehiculos', function (Blueprint $table) {
            $table->unsignedBigInteger('chofer_id')->nullable();
            $table->foreign('chofer_id')->references('id')->on('choferes')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('vehiculos', function (Blueprint $table) {
            $table->dropForeign(['chofer_id']);
            $table->dropColumn('chofer_id');
        });
    }
}
