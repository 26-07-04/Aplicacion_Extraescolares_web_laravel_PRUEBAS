<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEstatusToSemestresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('semestres', function (Blueprint $table) {
            $table->tinyInteger('estatus')->default(0)->after('fecha_fin'); // 0 = inactivo, 1 = activo
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('semestres', function (Blueprint $table) {
            $table->dropColumn('estatus');
        });
    }
}
