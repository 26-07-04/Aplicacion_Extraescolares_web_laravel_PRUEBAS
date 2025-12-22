<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Unidad;
use App\Models\Actividad;
use App\Models\Estudiante;
use App\Models\Semestre;

class UnidadController extends Controller
{
    // PANEL PARA ADMINISTRADORES (todas las unidades)
    public function index()
    {
        $unidades = Unidad::all();
        // Devolver la vista administrativa `administrador.Unidades` creada por el equipo
        // para mantener el diseño personalizado. Se sigue pasando `$unidades` por si
        // en el futuro la vista la utiliza.
        return view('administrador.Unidades', compact('unidades'));
    }

    // PANEL PARA COORDINADORES (solo su unidad)
    public function miUnidad(Request $request)
    {
        $user = $request->user();

        if ($user->rol !== 'Coordinador') {
            abort(403);
        }

        if (!$user->unidad_id) {
            abort(403, 'No tienes una unidad asignada.');
        }

        // Seleccionar panel correspondiente
        $vista = match($user->unidad_id) {
            1 => 'unidades.demetriovallejo',
            2 => 'unidades.unionhidalgo',
            3 => 'unidades.tlahutitoltepec',
            4 => 'unidades.valledeetla',
            default => abort(404, 'Unidad no encontrada'),
        };

        return view($vista);
    }

    /**
     * Muestra las actividades y estudiantes de una unidad específica
     * Este método se llama desde PrincipalAdministradorController
     */
    public function mostrarUnidad(Request $request, $unidadNombre = null, $id_semestre = null)
    {
        // Si no viene unidad por parámetro, obtener de query string
        if (!$unidadNombre) {
            $unidadNombre = $request->query('unidad');
        }
        
        // Si no viene semestre por parámetro, obtener de query string o sesión
        if (!$id_semestre) {
            $id_semestre = $request->query('id_semestre') ?? session('id_semestre_actual');
        }
        
        // Si aún no hay semestre, buscar el activo
        if (!$id_semestre) {
            $semestreActivo = Semestre::where('estatus', 1)->first();
            if ($semestreActivo) {
                $id_semestre = $semestreActivo->id_semestre;
            }
        }

        // Convertir nombre de unidad a ID
        $unidadId = $this->convertirNombreUnidadAId($unidadNombre);
        
        // Obtener el semestre si existe
        $semestre = null;
        if ($id_semestre) {
            $semestre = Semestre::find($id_semestre);
        }

        // Obtener actividades para esta unidad y semestre
        $query = Actividad::where('id_unidad', $unidadId);
        
        if ($id_semestre) {
            $query->where('id_semestre', $id_semestre);
        }
        
        $actividades = $query->get();

        // Obtener estudiantes POR ACTIVIDAD
        $estudiantesPorActividad = [];
        
        foreach ($actividades as $actividad) {
            $actividadId = $actividad->id_actividad ?? $actividad->id;
            
            // Obtener estudiantes de esta actividad específica
            $estudiantesPorActividad[$actividadId] = $this->obtenerEstudiantesDeActividad($actividadId);
        }

        // También obtener todos los estudiantes para compatibilidad
        $estudiantes = Estudiante::whereHas('actividades', function($q) use ($id_semestre, $actividades) {
            $idsActividades = $actividades->pluck('id_actividad')->toArray();
            if (!empty($idsActividades)) {
                $q->whereIn('id_actividad', $idsActividades);
            }
            if ($id_semestre) {
                $q->where('id_semestre', $id_semestre);
            }
        })->get();

        return view('unidades.unidad', [
            'unidad' => $unidadNombre,
            'actividades' => $actividades,
            'estudiantes' => $estudiantes,
            'estudiantesPorActividad' => $estudiantesPorActividad,
            'semestre' => $semestre,
            'id_semestre' => $id_semestre
        ]);
    }

    /**
     * Obtiene estudiantes de una actividad específica
     */
    private function obtenerEstudiantesDeActividad($actividadId)
    {
        try {
            // Intentar obtener desde tabla pivot actividad_estudiante
            $estudiantes = DB::table('actividad_estudiante')
                ->where('id_actividad', $actividadId)
                ->join('estudiantes', 'actividad_estudiante.id_estudiante', '=', 'estudiantes.id')
                ->select('estudiantes.id', 'estudiantes.nombre', 'estudiantes.control', 
                         'estudiantes.semestre', 'estudiantes.carrera')
                ->get()
                ->map(function($estudiante) {
                    return [
                        'id' => $estudiante->id,
                        'nombre' => $estudiante->nombre,
                        'control' => $estudiante->control,
                        'semestre' => $estudiante->semestre,
                        'carrera' => $estudiante->carrera
                    ];
                })
                ->toArray();
                
            return $estudiantes;
            
        } catch (\Exception $e) {
            // Si hay error (tabla no existe, etc.), devolver array vacío
            return [];
        }
    }

    /**
     * Convierte el nombre de la unidad a ID numérico
     */
    private function convertirNombreUnidadAId($nombre)
    {
        $unidades = [
            'Union Hidalgo' => 1,
            'Unión Hidalgo' => 1,
            'Demetrio Vallejo' => 2,
            'Demetrio+Vallejo' => 2,
            'Tlahuitoltepec' => 3,
            'Santa María Tlahuitoltepec' => 3,
            'Valle de Etla' => 4,
            'Valle+de+Etla' => 4,
            // Para compatibilidad con nombres de vistas
            'demetriovallejo' => 2,
            'unionhidalgo' => 1,
            'tlahutitoltepec' => 3,
            'valledeetla' => 4
        ];
        
        return $unidades[$nombre] ?? 1; // Default a Unión Hidalgo
    }

    /**
     * Obtiene el nombre de la unidad por ID
     */
    private function obtenerNombreUnidad($id)
    {
        $unidades = [
            1 => 'Unión Hidalgo',
            2 => 'Demetrio Vallejo',
            3 => 'Santa María Tlahuitoltepec',
            4 => 'Valle de Etla'
        ];
        
        return $unidades[$id] ?? 'Unidad Desconocida';
    }
}