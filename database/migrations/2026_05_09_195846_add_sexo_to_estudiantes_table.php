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
        // Comentamos esto porque la columna ya existe en phpMyAdmin
        /*
        Schema::table('estudiantes', function (Blueprint $table) {
            $table->string('sexo', 20)->nullable()->after('carrera');
        });
        */
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('estudiantes', function (Blueprint $table) {
            $table->dropColumn('sexo');
        });
    }
};