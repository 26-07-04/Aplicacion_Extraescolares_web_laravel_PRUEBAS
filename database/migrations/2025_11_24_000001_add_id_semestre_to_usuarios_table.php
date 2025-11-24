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
        Schema::table('usuarios', function (Blueprint $table) {
            // Añadimos columna nullable para permitir registros existentes sin semestre
            $table->unsignedInteger('id_semestre')->nullable()->after('id_usuario');

            // Añadimos FK a `semestres.id_semestre` si la tabla existe
            if (Schema::hasTable('semestres')) {
                $table->foreign('id_semestre')
                    ->references('id_semestre')
                    ->on('semestres')
                    ->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            // Removemos FK si existe
            try {
                $table->dropForeign(['id_semestre']);
            } catch (\Throwable $e) {
                // ignorar si no existe
            }

            // Removemos la columna
            if (Schema::hasColumn('usuarios', 'id_semestre')) {
                $table->dropColumn('id_semestre');
            }
        });
    }
};
