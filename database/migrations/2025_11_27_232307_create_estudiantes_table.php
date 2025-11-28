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
        Schema::create('estudiantes', function (Blueprint $table) {
            // Mantener nombres compatibles con tu SQL solicitado
            $table->increments('id_alumno'); // PK int auto-increment
            $table->string('nombre')->nullable(); // opcional: nombre del alumno
            $table->string('numero_control', 20)->unique();
            $table->string('carrera', 100)->nullable();
            $table->integer('semestre')->nullable(); // campo "semestre" normal (no FK)
            // FKs según tus tablas existentes
            $table->unsignedInteger('id_actividad');
            $table->unsignedBigInteger('id_unidad')->nullable();
            $table->unsignedInteger('id_semestre')->nullable();

            $table->timestamps();

            // Forzar motor InnoDB
            $table->engine = 'InnoDB';

            // Claves foráneas (asegúrate que las tablas referenciadas existen y usan esos nombres/PK)
            $table->foreign('id_actividad')->references('id_actividad')->on('actividades')->onDelete('cascade');
            $table->foreign('id_unidad')->references('id_unidad')->on('unidades')->onDelete('set null');
            $table->foreign('id_semestre')->references('id_semestre')->on('semestres')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estudiantes');
    }
};
