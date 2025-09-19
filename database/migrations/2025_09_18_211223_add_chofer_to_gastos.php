<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('gastos', function (Blueprint $table) {
            $table->foreignId('chofer_id')->nullable()->after('vehiculo_id')->constrained('choferes')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('gastos', function (Blueprint $table) {
            $table->dropForeign(['chofer_id']);
            $table->dropColumn('chofer_id');
        });
    }
};