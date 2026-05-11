<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * La columna legacy `tipo` quedó sin uso (siempre NULL); el tipo de programa
     * se define en `tipo_programa`.
     */
    public function up(): void
    {
        if (Schema::hasColumn('actividades', 'tipo')) {
            Schema::table('actividades', function (Blueprint $table) {
                $table->dropColumn('tipo');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('actividades', 'tipo')) {
            Schema::table('actividades', function (Blueprint $table) {
                $table->string('tipo', 64)->nullable()->after('tipo_programa');
            });
        }
    }
};
