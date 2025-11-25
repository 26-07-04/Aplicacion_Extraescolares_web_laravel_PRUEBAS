<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->unsignedBigInteger('unidad_id')->nullable()->after('rol');
        $table->foreign('unidad_id')->references('id_unidad')->on('unidades');
    });
}


    /**
     * Reverse the migrations.
     */
   public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropForeign(['unidad_id']);
        $table->dropColumn('unidad_id');
    });
}

};
