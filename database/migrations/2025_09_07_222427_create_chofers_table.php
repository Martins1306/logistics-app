<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('choferes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('dni')->unique();
            $table->string('licencia_tipo');
            $table->date('licencia_vencimiento');
            $table->string('telefono');
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->softDeletes(); // ← Esta línea es clave para SoftDeletes
        });
    }

    public function down()
    {
        Schema::dropIfExists('choferes');
    }
};
