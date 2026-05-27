<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evaluaciones', function (Blueprint $table) {
            if (! Schema::hasColumn('evaluaciones', 'cargo_destinatario')) {
                $after = Schema::hasColumn('evaluaciones', 'cargo_vobo') ? 'cargo_vobo' : 'jefe_extraescolares';
                $table->string('cargo_destinatario', 255)->nullable()->after($after);
            }
        });

        if (Schema::hasColumn('evaluaciones', 'cargo_docente') && Schema::hasColumn('evaluaciones', 'cargo_destinatario')) {
            \DB::table('evaluaciones')
                ->whereNull('cargo_destinatario')
                ->whereNotNull('cargo_docente')
                ->update(['cargo_destinatario' => \DB::raw('cargo_docente')]);
        }
    }

    public function down(): void
    {
        Schema::table('evaluaciones', function (Blueprint $table) {
            if (Schema::hasColumn('evaluaciones', 'cargo_destinatario')) {
                $table->dropColumn('cargo_destinatario');
            }
        });
    }
};
