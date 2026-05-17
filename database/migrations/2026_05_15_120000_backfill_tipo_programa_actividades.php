<?php

use App\Models\Actividad;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('actividades', 'tipo_programa')) {
            return;
        }

        DB::table('actividades')
            ->whereNull('tipo_programa')
            ->update(['tipo_programa' => Actividad::TIPO_EXTRAESCOLAR]);
    }

    public function down(): void
    {
        // No revertir: los registros podrían haber sido creados ya con tipo explícito.
    }
};
