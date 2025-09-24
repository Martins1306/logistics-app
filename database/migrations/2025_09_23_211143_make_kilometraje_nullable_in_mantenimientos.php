<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeKilometrajeNullableInMantenimientos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('mantenimientos', function (Blueprint $table) {
        $table->integer('kilometraje')->nullable()->change();
    });
}

public function down()
{
    Schema::table('mantenimientos', function (Blueprint $table) {
        $table->integer('kilometraje')->nullable(false)->change(); // vuelve a NOT NULL
    });
}
}
