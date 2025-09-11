<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('vehiculos', function (Blueprint $table) {
            $table->integer('ultimo_mantenimiento_km')->default(0);
            $table->integer('intervalo_mantenimiento')->default(10000); // cada 10.000 km
        });
    }

    public function down()
    {
        Schema::table('vehiculos', function (Blueprint $table) {
            $table->dropColumn(['ultimo_mantenimiento_km', 'intervalo_mantenimiento']);
        });
    }
};