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
        Schema::create('evaluaciones', function (Blueprint $table) {
            $table->increments('id_evaluacion');
            $table->unsignedInteger('id_alumno');
            $table->unsignedInteger('id_actividad');
            $table->unsignedInteger('id_semestre');
            $table->unsignedBigInteger('id_unidad'); // Unidad Académica
            $table->string('nombre_profesor', 255)->nullable();
            
            // Datos de la evaluación
            $table->string('nivel_desempeno', 50); // Excelente, Muy bien, Bien, Suficiente
            $table->decimal('calificacion_numerica', 5, 2); // 0-100
            $table->integer('creditos')->default(5);
            $table->text('observaciones')->nullable();
            
            // Datos para la constancia
            $table->string('jefe_extraescolares')->nullable();
            $table->string('jefe_servicios_escolares')->nullable();
            $table->string('ciudad', 100)->nullable();
            $table->date('fecha_evaluacion');
            
            $table->timestamps();
            
            $table->engine = 'InnoDB';
            
            // Claves foráneas
            $table->foreign('id_alumno')->references('id_alumno')->on('estudiantes')->onDelete('cascade');
            $table->foreign('id_actividad')->references('id_actividad')->on('actividades')->onDelete('cascade');
            $table->foreign('id_semestre')->references('id_semestre')->on('semestres')->onDelete('cascade');
            $table->foreign('id_unidad')->references('id_unidad')->on('unidades')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluaciones');
    }
};
