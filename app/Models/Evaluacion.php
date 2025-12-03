<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    protected $table = 'evaluaciones';
    protected $primaryKey = 'id_evaluacion';
    
    protected $fillable = [
        'id_alumno',
        'id_actividad',
        'id_semestre',
        'id_unidad',
        'nivel_desempeno',
        'calificacion_numerica',
        'creditos',
        'observaciones',
        'nombre_profesor',
        'jefe_extraescolares',
        'jefe_servicios_escolares',
        'ciudad',
        'fecha_evaluacion',
    ];

    protected $casts = [
        'fecha_evaluacion' => 'date',
        'calificacion_numerica' => 'decimal:2',
        'creditos' => 'integer',
    ];

    // Relaciones
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'id_alumno', 'id_alumno');
    }

    public function actividad()
    {
        return $this->belongsTo(Actividad::class, 'id_actividad', 'id_actividad');
    }

    public function semestre()
    {
        return $this->belongsTo(Semestre::class, 'id_semestre', 'id_semestre');
    }

    public function unidad()
    {
        return $this->belongsTo(Unidad::class, 'id_unidad', 'id_unidad');
    }
}
