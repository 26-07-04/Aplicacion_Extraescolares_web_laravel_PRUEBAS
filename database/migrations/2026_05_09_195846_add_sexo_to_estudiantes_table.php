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
        // Añadir la columna sólo si no existe (permite ejecutar migrate en entornos distintos)
        if (! Schema::hasColumn('estudiantes', 'sexo')) {
            Schema::table('estudiantes', function (Blueprint $table) {
                $table->string('sexo', 20)->nullable()->after('carrera');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('estudiantes', 'sexo')) {
            Schema::table('estudiantes', function (Blueprint $table) {
                $table->dropColumn('sexo');
            });
        }
    }
};