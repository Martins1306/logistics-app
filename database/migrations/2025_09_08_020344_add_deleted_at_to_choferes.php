<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\SoftDeletes; // No se usa aquí directamente, pero lo dejamos si hace falta

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('choferes', function (Blueprint $table) {
            $table->softDeletes(); // Agrega la columna deleted_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('choferes', function (Blueprint $table) {
            $table->dropSoftDeletes(); // Elimina la columna deleted_at
        });
    }
};