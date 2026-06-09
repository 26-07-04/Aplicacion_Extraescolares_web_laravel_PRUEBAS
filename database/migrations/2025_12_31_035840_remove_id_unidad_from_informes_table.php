<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('informes', 'id_unidad')) {
            Schema::table('informes', function (Blueprint $table) {
                // Primero eliminar la FK (si existe) para que MySQL permita borrar la columna
                $table->dropForeign(['id_unidad']);
                $table->dropColumn('id_unidad');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('informes', function (Blueprint $table) {
            $table->unsignedBigInteger('id_unidad');
            $table->foreign('id_unidad')->references('id_unidad')->on('unidades')->onDelete('cascade');
        });
    }
};
