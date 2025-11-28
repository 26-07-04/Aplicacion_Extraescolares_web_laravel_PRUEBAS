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
        'semestre',
        'id_actividad',
        'id_unidad',
        'id_semestre'
    ];

    public function actividad()
    {
        return $this->belongsTo(\App\Models\Actividad::class, 'id_actividad', 'id_actividad');
    }

    public function unidad()
    {
        return $this->belongsTo(\App\Models\Unidad::class, 'id_unidad', 'id_unidad');
    }
}