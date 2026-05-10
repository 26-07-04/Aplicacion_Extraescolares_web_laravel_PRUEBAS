<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Actividad;
use App\Models\Unidad;
use App\Models\Semestre;
use App\Models\Estudiante;

class ActividadController extends Controller
{
    /**
     * Devuelve las actividades de una unidad académica para el semestre activo.
     * El parámetro $unidad puede ser el id de la unidad (numérico) o el nombre
     * exacto de la unidad (`nombre_unidad`).
     */
    public function getActividadesPorUnidad(Request $request, $unidad)
    {
        // Soportar id numérico o nombre (insensible a mayúsculas y con búsqueda parcial)
        if (is_numeric($unidad)) {
            $unidadModel = Unidad::find($unidad);
        } else {
            $term = mb_strtolower(urldecode($unidad));
            $unidadModel = Unidad::whereRaw('LOWER(nombre_unidad) = ?', [$term])->first();
            if (! $unidadModel) {
                // Intentar búsqueda parcial
                $unidadModel = Unidad::whereRaw('LOWER(nombre_unidad) LIKE ?', ['%'.$term.'%'])->first();
            }
        }

        if (! $unidadModel) {
            return response()->json(['message' => 'Unidad no encontrada'], 404);
        }

        // Obtener el semestre activo (campo 'estatus' true)
        $semestreActivo = Semestre::where('estatus', true)->first();
        if (! $semestreActivo) {
            return response()->json(['message' => 'No hay semestre activo'], 404);
        }

        $tipo = $request->query('tipo_programa', Actividad::TIPO_EXTRAESCOLAR);
        if (! in_array($tipo, [Actividad::TIPO_EXTRAESCOLAR, Actividad::TIPO_COMPLEMENTARIA], true)) {
            $tipo = Actividad::TIPO_EXTRAESCOLAR;
        }

        // Obtener actividades que coincidan con la unidad y el semestre activo
        $actividades = Actividad::with(['unidad','semestre'])
            ->where('id_unidad', $unidadModel->id_unidad)
            ->where('id_semestre', $semestreActivo->id_semestre)
            ->where('tipo_programa', $tipo)
            ->get();

        return response()->json([
            'unidad' => $unidadModel,
            'semestre_activo' => $semestreActivo,
            'actividades' => $actividades,
        ]);
    }

    /**
     * Devuelve los estudiantes registrados en una actividad (por id de actividad).
     */
    public function getEstudiantesPorActividad(Request $request, $id)
    {
        $actividad = Actividad::find($id);
        if (! $actividad) {
            return response()->json(['message' => 'Actividad no encontrada'], 404);
        }

        $estudiantes = Estudiante::where('id_actividad', $id)->get();

        return response()->json([
            'actividad' => $actividad,
            'estudiantes' => $estudiantes,
        ]);
    }
}
