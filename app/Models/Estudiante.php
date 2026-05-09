<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory;

    protected $table = 'estudiantes';
    protected $primaryKey = 'id_alumno';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nombre',
        'numero_control',
        'carrera',
        'sexo', // <-- Columna agregada 
        'semestre',
        'id_actividad',
        'id_unidad',
        'id_semestre'
    ];

    /**
     * Relación con el modelo Actividad
     */
    public function actividad()
    {
        return $this->belongsTo(\App\Models\Actividad::class, 'id_actividad', 'id_actividad');
    }

    /**
     * Relación con el modelo Unidad
     */
    public function unidad()
    {
        return $this->belongsTo(\App\Models\Unidad::class, 'id_unidad', 'id_unidad');
    }
}