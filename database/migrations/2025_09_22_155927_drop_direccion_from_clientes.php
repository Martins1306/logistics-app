<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropDireccionFromClientes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    // database/migrations/xxxx_drop_direccion_from_clientes.php

public function up()
{
    Schema::table('clientes', function (Blueprint $table) {
        if (Schema::hasColumn('clientes', 'direccion')) {
            $table->dropColumn('direccion');
        }
    });
}

public function down()
{
    Schema::table('clientes', function (Blueprint $table) {
        $table->string('direccion')->nullable();
    });
}
}
