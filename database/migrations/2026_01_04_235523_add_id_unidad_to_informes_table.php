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
        // Añadir la columna sólo si no existe y crear FK hacia `unidades.id_unidad`
        if (! Schema::hasColumn('informes', 'id_unidad')) {
            Schema::table('informes', function (Blueprint $table) {
                $table->unsignedBigInteger('id_unidad')->nullable()->after('id');
            });

            if (Schema::hasTable('unidades') && Schema::hasColumn('unidades', 'id_unidad')) {
                Schema::table('informes', function (Blueprint $table) {
                    $table->foreign('id_unidad')->references('id_unidad')->on('unidades')->onDelete('set null');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('informes', 'id_unidad')) {
            Schema::table('informes', function (Blueprint $table) {
                try {
                    $table->dropForeign(['id_unidad']);
                } catch (\Exception $e) {
                    // Ignorar si la FK no existe
                }
                $table->dropColumn('id_unidad');
            });
        }
    }
};
