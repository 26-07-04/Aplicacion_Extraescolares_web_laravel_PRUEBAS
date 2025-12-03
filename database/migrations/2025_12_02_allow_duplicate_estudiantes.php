<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Cambia la restricción UNIQUE de numero_control (global) a una restricción compuesta:
     * UNIQUE(numero_control, id_semestre)
     * 
     * Esto permite:
     * ✅ Mismo estudiante en DIFERENTES semestres
     * ❌ Mismo estudiante en múltiples actividades del MISMO semestre
     */
    public function up(): void
    {
        Schema::table('estudiantes', function (Blueprint $table) {
            // Remover el índice UNIQUE simple de numero_control
            $table->dropUnique('estudiantes_numero_control_unique');
            
            // Agregar índice UNIQUE compuesto en (numero_control, id_semestre)
            // Esto asegura que no pueda haber duplicados del mismo número en el mismo semestre
            $table->unique(['numero_control', 'id_semestre']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('estudiantes', function (Blueprint $table) {
            // Remover el índice UNIQUE compuesto
            $table->dropUnique('estudiantes_numero_control_id_semestre_unique');
            
            // Restaurar el índice UNIQUE simple de numero_control
            $table->unique('numero_control');
        });
    }
};
