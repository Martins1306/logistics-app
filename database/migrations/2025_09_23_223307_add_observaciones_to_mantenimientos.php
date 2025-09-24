<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddObservacionesToMantenimientos extends Migration
{
    public function up()
    {
        Schema::table('mantenimientos', function (Blueprint $table) {
            $table->text('observaciones')->nullable()->after('estado');
        });
    }

    public function down()
    {
        Schema::table('mantenimientos', function (Blueprint $table) {
            $table->dropColumn('observaciones');
        });
    }
}