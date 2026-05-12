<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Un mismo número de control puede inscribirse en varias actividades
     * del mismo semestre (extraescolar y/o complementaria), una fila por actividad.
     */
    public function up(): void
    {
        Schema::table('estudiantes', function (Blueprint $table) {
            $table->dropUnique(['numero_control', 'id_semestre']);
        });

        Schema::table('estudiantes', function (Blueprint $table) {
            $table->unique(['numero_control', 'id_actividad']);
        });
    }

    public function down(): void
    {
        Schema::table('estudiantes', function (Blueprint $table) {
            $table->dropUnique(['numero_control', 'id_actividad']);
        });

        Schema::table('estudiantes', function (Blueprint $table) {
            $table->unique(['numero_control', 'id_semestre']);
        });
    }
};
