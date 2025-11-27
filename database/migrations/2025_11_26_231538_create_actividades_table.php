<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActividadesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('actividades', function (Blueprint $table) {
            $table->increments('id_actividad');
            $table->string('nombre_actividad', 100);
            $table->text('descripcion')->nullable();
            $table->string('imagen_url')->nullable();

            // id_unidad coincide con unidades.id_unidad (BIGINT UNSIGNED)
            $table->unsignedBigInteger('id_unidad');

            // id_semestre coincide con semestres.id_semestre (INT UNSIGNED)
            $table->unsignedInteger('id_semestre')->nullable();

            $table->timestamps();

            // FKs (requieren que las tablas referenciadas sean InnoDB y tipos compatibles)
            $table->foreign('id_unidad')->references('id_unidad')->on('unidades')->onDelete('cascade');
            $table->foreign('id_semestre')->references('id_semestre')->on('semestres')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actividades');
    }
}
