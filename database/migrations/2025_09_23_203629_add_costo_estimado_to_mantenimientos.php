<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCostoEstimadoToMantenimientos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mantenimientos', function (Blueprint $table) {
            $table->decimal('costo_estimado', 10, 2)->nullable()->after('descripcion');
        });
    }
    
    public function down()
    {
        Schema::table('mantenimientos', function (Blueprint $table) {
            $table->dropColumn('costo_estimado');
        });
    }
}
