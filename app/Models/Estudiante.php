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

    /**
     * Reglas de validación para número de control dentro de una actividad.
     */
    public static function reglasNumeroControlEnActividad(int $idActividad, ?int $ignorarIdAlumno = null): array
    {
        $rule = \Illuminate\Validation\Rule::unique('estudiantes', 'numero_control')
            ->where(fn ($q) => $q->where('id_actividad', $idActividad));

        if ($ignorarIdAlumno !== null) {
            $rule = $rule->ignore($ignorarIdAlumno, 'id_alumno');
        }

        return ['required', 'string', 'max:100', $rule];
    }

    public static function yaInscritoEnActividad(string $numeroControl, int $idActividad, ?int $exceptAlumnoId = null): bool
    {
        $query = static::query()
            ->where('numero_control', $numeroControl)
            ->where('id_actividad', $idActividad);

        if ($exceptAlumnoId !== null) {
            $query->where('id_alumno', '!=', $exceptAlumnoId);
        }

        return $query->exists();
    }
}