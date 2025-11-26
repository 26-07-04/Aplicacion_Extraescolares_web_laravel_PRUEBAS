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
        // Determinar tabla de usuarios existente
        $tabla = Schema::hasTable('users') ? 'users' : (Schema::hasTable('usuarios') ? 'usuarios' : null);
        if (! $tabla) return;

        // Añadir columna unidad_id sólo si no existe
        if (! Schema::hasColumn($tabla, 'unidad_id')) {
            Schema::table($tabla, function (Blueprint $table) {
                // añadir sin usar after() para evitar depender de 'rol'
                $table->unsignedBigInteger('unidad_id')->nullable();
            });

            // Añadir FK sólo si la tabla unidades y su PK existen
            if (Schema::hasTable('unidades') && Schema::hasColumn('unidades', 'id_unidad')) {
                Schema::table($tabla, function (Blueprint $table) {
                    $table->foreign('unidad_id')->references('id_unidad')->on('unidades')->onDelete('set null');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tabla = Schema::hasTable('users') ? 'users' : (Schema::hasTable('usuarios') ? 'usuarios' : null);
        if (! $tabla) return;

        if (Schema::hasColumn($tabla, 'unidad_id')) {
            Schema::table($tabla, function (Blueprint $table) {
                // intentar eliminar FK (si existe) y la columna
                try {
                    $table->dropForeign(['unidad_id']);
                } catch (\Throwable $e) {
                    // silencioso si no existía FK
                }
                $table->dropColumn('unidad_id');
            });
        }
    }
};
