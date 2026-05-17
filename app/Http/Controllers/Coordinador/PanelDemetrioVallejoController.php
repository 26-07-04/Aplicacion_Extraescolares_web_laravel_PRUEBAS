<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Coordinador\Concerns\ImprimeFormatoActividadCoordinador;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Semestre;
use App\Models\Actividad;
use App\Models\Unidad;
use App\Models\Estudiante;
use App\Models\Evaluacion;
use App\Models\Informe;
use App\Models\Documento;
use App\Support\ResultadosExtraescolaresFirmas;
use App\Support\ResultadosTipoFiltro;

class PanelDemetrioVallejoController extends Controller
{
    use ImprimeFormatoActividadCoordinador;
    /**
     * Vista Blade del panel (Extraescolares). Las subclases en Complementarias\ devuelven panel_complementarias.
     */
    protected function coordinadorPanelView(): string
    {
        return 'coordinador.demetrio_vallejo.panel';
    }

    /**
     * Vista PDF de resultados (extraescolares). El panel complementarias sobreescribe.
     */
    protected function resultadosPdfView(): string
    {
        return 'coordinador.demetrio_vallejo.pdf.resultados';
    }

    protected function panelTipoPrograma(): string
    {
        return Actividad::TIPO_EXTRAESCOLAR;
    }

    protected function firmasUnidadKey(): string
    {
        return 'demetrio_vallejo';
    }

    protected function formatoActividadLugar(): string
    {
        return 'El Espinal';
    }

    protected function autorizarCoordinadorFormato($user): void
    {
        if ($user && ($user->rol ?? '') === 'Coordinador') {
            $ua = $user->unidad_academica ?? '';
            if (stripos($ua, 'Demetrio') === false && stripos($ua, 'Vallejo') === false && stripos($ua, 'Espinal') === false) {
                abort(403);
            }
        }
    }

    protected function keywordsActividadPanel(): array
    {
        return ['Demetrio', 'Vallejo', 'Espinal'];
    }

    protected function fallbackLikeActividadPanel(): array
    {
        return ['%Demetrio%', '%Vallejo%', '%Espinal%'];
    }

    public function show($id, Request $request)
    {
        if ($redirect = $this->redirigirResultadosAFormatos($request)) {
            return $redirect;
        }

        $user = Auth::user();
        if ($user && ($user->rol ?? '') === 'Coordinador') {
            $ua = $user->unidad_academica ?? '';
            if (stripos($ua, 'Demetrio') === false && stripos($ua, 'Vallejo') === false && stripos($ua, 'Espinal') === false) {
                abort(403);
            }
        }
        // Si no hay usuario o no es coordinador, permitir acceso (público o admin)

        $semestre = Semestre::find($id);
        if (!$semestre) {
            abort(404);
        }

        $documentos = \App\Models\Documento::where('id_semestre', $id)->orderBy('created_at', 'desc')->get();

        $informes = Informe::listadoGeneradosPorUnidad((int) $semestre->id_semestre, 2, $this->panelTipoPrograma());

        // Filtrar actividades por semestre y por la unidad académica del usuario
        // Nota: la tabla `unidades` no tiene columna `id_semestre`, por eso usamos whereHas para filtrar
        $uaName = $user->unidad_academica ?? '';

        // Intentar usar unidad_id si existe (más fiable)
        $user_unidad_id = $user->unidad_id ?? $user->unidad ?? null;

        // Derivar palabra clave a buscar en `unidades.nombre_unidad`
        $uaKeyword = null;
        $candidates = ['Demetrio','Vallejo','Unión','Union','Valle','Etla','Tlahuitoltepec','Tlahui','Santa','Espinal'];
        foreach ($candidates as $cand) {
            if (!empty($uaName) && stripos($uaName, $cand) !== false) {
                $uaKeyword = $cand;
                break;
            }
        }

        // Todas las actividades del semestre (sin filtrar por unidad) — útil para depuración
        $actividades_semestre = Actividad::with('unidad')
            ->where('id_semestre', $semestre->id_semestre)
            ->delTipoPrograma($this->panelTipoPrograma())
            ->orderBy('created_at', 'desc')
            ->get();

        // Actividades filtradas por la unidad académica del usuario
        $actividadesQuery = Actividad::with('unidad')
            ->where('id_semestre', $semestre->id_semestre)
            ->delTipoPrograma($this->panelTipoPrograma());

        if (!empty($user_unidad_id)) {
            $actividadesQuery->where('id_unidad', $user_unidad_id);
        } elseif (!empty($uaKeyword)) {
            $actividadesQuery->whereHas('unidad', function ($q) use ($uaKeyword) {
                $q->where('nombre_unidad', 'like', '%' . $uaKeyword . '%');
            });
        } else {
            // Si no se detecta nada, intentar con 'Demetrio' por compatibilidad
            $actividadesQuery->whereHas('unidad', function ($q) {
                $q->where('nombre_unidad', 'like', '%Demetrio%');
            });
        }

        $actividades = $actividadesQuery->orderBy('created_at', 'desc')->get();

        // Pasar también los conjuntos para diagnóstico en la vista
        $count_semestre = $actividades_semestre->count();
        $count_filtradas = $actividades->count();

        // Preparar detalles para debug: incluir nombre de unidad asociado a cada actividad
        $actividades_semestre_data = $actividades_semestre->map(function ($a) {
            return [
                'id_actividad' => $a->id_actividad,
                'nombre_actividad' => $a->nombre_actividad,
                'id_unidad' => $a->id_unidad,
                'id_semestre' => $a->id_semestre,
                'unidad_nombre' => $a->unidad->nombre_unidad ?? null,
            ];
        })->toArray();

        // Obtener evaluaciones del semestre y unidad actual
        $evaluacionesQuery = Evaluacion::with(['estudiante', 'actividad'])
            ->where('id_semestre', $id)
            ->whereHas('actividad', function ($q) {
                $q->delTipoPrograma($this->panelTipoPrograma());
            });
        
        // Filtrar por unidad solo si tenemos id_unidad
        if (!empty($user_unidad_id)) {
            $evaluacionesQuery->where('id_unidad', $user_unidad_id);
        } elseif (!empty($uaKeyword)) {
            // Si no hay id_unidad, filtrar por nombre de unidad usando relación
            $evaluacionesQuery->whereHas('unidad', function ($q) use ($uaKeyword) {
                $q->where('nombre_unidad', 'like', '%' . $uaKeyword . '%');
            });
        }
        
        $evaluaciones = $evaluacionesQuery->get()->sortBy(function($evaluacion) {
            return $evaluacion->estudiante->nombre ?? '';
        })->values();

        return view($this->coordinadorPanelView(), [
            'user' => $user,
            'semestre' => $semestre,
            'unidad' => 'Unidad Académica Demetrio Vallejo Martínez - El Espinal',
            'documentos' => $documentos,
            'informes' => $informes,
            'tipo_programa_informes_panel' => $this->panelTipoPrograma(),
            'actividades' => $actividades,
            'actividades_semestre' => $actividades_semestre,
            'actividades_semestre_data' => $actividades_semestre_data,
            'count_semestre' => $count_semestre,
            'count_filtradas' => $count_filtradas,
            'uaName' => $uaName,
            'uaKeyword' => $uaKeyword,
            'user_unidad_id' => $user_unidad_id,
            'evaluaciones' => $evaluaciones,
        ]);
    }

    public function printResultados($id, Request $request)
    {
        $user = Auth::user();
        if ($user && ($user->rol ?? '') === 'Coordinador') {
            $ua = $user->unidad_academica ?? '';
            if (stripos($ua, 'Demetrio') === false && stripos($ua, 'Vallejo') === false && stripos($ua, 'Espinal') === false) {
                abort(403);
            }
        }
        // Si no hay usuario o no es coordinador, permitir acceso (público o admin)

        $semestre = Semestre::find($id);
        if (!$semestre) {
            abort(404);
        }

        $uaName = $user->unidad_academica ?? '';
        $user_unidad_id = $user->unidad_id ?? $user->unidad ?? null;
        $uaKeyword = null;
        $candidates = ['Demetrio','Vallejo','Unión','Union','Espinal'];
        foreach ($candidates as $cand) {
            if (!empty($uaName) && stripos($uaName, $cand) !== false) {
                $uaKeyword = $cand;
                break;
            }
        }

        $evaluacionesQuery = Evaluacion::with(['estudiante', 'actividad'])
            ->where('id_semestre', $id)
            ->whereHas('actividad', function ($q) {
                $q->delTipoPrograma($this->panelTipoPrograma());
            });

        if (!empty($user_unidad_id)) {
            $evaluacionesQuery->where('id_unidad', $user_unidad_id);
        } elseif (!empty($uaKeyword)) {
            $evaluacionesQuery->whereHas('unidad', function ($q) use ($uaKeyword) {
                $q->where('nombre_unidad', 'like', '%' . $uaKeyword . '%');
            });
        }

        $tipo = strtolower((string) $request->query('tipo', 'cultural'));
        if (! in_array($tipo, ['cultural', 'deportiva', 'academica'], true)) {
            $tipo = 'cultural';
        }
        ResultadosTipoFiltro::apply($evaluacionesQuery, $tipo, $this->panelTipoPrograma());

        $evaluaciones = $evaluacionesQuery->get()->sortBy(function($evaluacion) {
            return $evaluacion->estudiante->nombre ?? '';
        })->values();

        $membreteArchivoUrl = null;
        $idDoc = $request->query('id_documento');
        if ($idDoc) {
            $doc = Documento::where('id_semestre', $semestre->id_semestre)
                ->where('id', $idDoc)
                ->first();
            if ($doc && ! empty($doc->archivo)) {
                $membreteArchivoUrl = asset($doc->archivo);
            }
        }

        return view($this->resultadosPdfView(), [
            'user' => $user,
            'semestre' => $semestre,
            'unidad' => 'Unidad Académica Demetrio Vallejo Martínez - El Espinal',
            'evaluaciones' => $evaluaciones,
            'tipo' => $tipo,
            'lugar' => 'El Espinal, Oax.',
            'firmas' => ResultadosExtraescolaresFirmas::forUnidad('demetrio_vallejo'),
            'membreteArchivoUrl' => $membreteArchivoUrl,
        ]);
    }

    public function actualizarEstudiante($id, Request $request)
    {
        $user = Auth::user();
        if (!$user || ($user->rol ?? '') !== 'Coordinador') {
            return response()->json(['success' => false, 'message' => 'No autorizado'], 403);
        }

        try {
            $estudiante = Estudiante::findOrFail($id);
            
            // Validar que el estudiante pertenezca a una actividad en la unidad del coordinador
            $actividad = Actividad::findOrFail($estudiante->id_actividad);
            
            $user_unidad_id = $user->unidad_id ?? $user->unidad ?? null;
            if ($user_unidad_id && $actividad->id_unidad != $user_unidad_id) {
                return response()->json(['success' => false, 'message' => 'No tienes permiso para editar este estudiante'], 403);
            }
            if (($actividad->tipo_programa ?? Actividad::TIPO_EXTRAESCOLAR) !== $this->panelTipoPrograma()) {
                return response()->json(['success' => false, 'message' => 'No tienes permiso para editar este estudiante'], 403);
            }

            $validated = $request->validate([
                'nombre' => 'required|string|max:255',
                'numero_control' => Estudiante::reglasNumeroControlEnActividad((int) $estudiante->id_actividad, (int) $estudiante->id_alumno),
                'carrera' => 'required|string|max:255',
                'sexo' => 'nullable|string|max:20',
                'semestre' => 'required|string|max:50'
            ]);

            $estudiante->update($validated);

            return response()->json(['success' => true, 'message' => 'Estudiante actualizado exitosamente', 'data' => $estudiante]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 400);
        }
    }

    public function eliminarEstudiante($id, Request $request)
    {
        $user = Auth::user();
        if (!$user || ($user->rol ?? '') !== 'Coordinador') {
            return response()->json(['success' => false, 'message' => 'No autorizado'], 403);
        }

        try {
            $estudiante = Estudiante::findOrFail($id);
            
            // Validar que el estudiante pertenezca a una actividad en la unidad del coordinador
            $actividad = Actividad::findOrFail($estudiante->id_actividad);
            
            $user_unidad_id = $user->unidad_id ?? $user->unidad ?? null;
            if ($user_unidad_id && $actividad->id_unidad != $user_unidad_id) {
                return response()->json(['success' => false, 'message' => 'No tienes permiso para eliminar este estudiante'], 403);
            }
            if (($actividad->tipo_programa ?? Actividad::TIPO_EXTRAESCOLAR) !== $this->panelTipoPrograma()) {
                return response()->json(['success' => false, 'message' => 'No tienes permiso para eliminar este estudiante'], 403);
            }

            $estudiante->delete();

            return response()->json(['success' => true, 'message' => 'Estudiante eliminado exitosamente']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 400);
        }
    }
}
