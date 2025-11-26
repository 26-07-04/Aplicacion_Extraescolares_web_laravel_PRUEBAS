<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUbicacionFromUnidadesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('unidades', 'ubicacion')) {
            Schema::table('unidades', function (Blueprint $table) {
                $table->dropColumn('ubicacion');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restaurar la columna como nullable string (ajusta tipo si era distinto)
        if (! Schema::hasColumn('unidades', 'ubicacion')) {
            Schema::table('unidades', function (Blueprint $table) {
                $table->string('ubicacion')->nullable()->after('nombre_unidad');
            });
        }
    }
}
