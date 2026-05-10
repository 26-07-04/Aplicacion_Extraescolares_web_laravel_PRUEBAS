<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Semestre;
use App\Models\Actividad;
use App\Models\Unidad;
use App\Models\Informe;

class PanelUnionHidalgoController extends Controller
{
    /**
     * Vista Blade del panel (Extraescolares). Las subclases en Complementarias\ devuelven panel_complementarias.
     */
    protected function coordinadorPanelView(): string
    {
        return 'coordinador.union_hidalgo.panel';
    }

    /**
     * Vista PDF de resultados (extraescolares). El panel complementarias sobreescribe.
     */
    protected function resultadosPdfView(): string
    {
        return 'coordinador.union_hidalgo.pdf.resultados';
    }

    protected function panelTipoPrograma(): string
    {
        return Actividad::TIPO_EXTRAESCOLAR;
    }

    public function show($id)
    {
        $user = Auth::user();
        if ($user && ($user->rol ?? '') === 'Coordinador') {
            $ua = $user->unidad_academica ?? '';
            if (stripos($ua, 'Unión') === false && stripos($ua, 'Union') === false && stripos($ua, 'Hidalgo') === false) {
                abort(403);
            }
        }
        // Si no hay usuario o no es coordinador, permitir acceso (público o admin)

        $semestre = Semestre::find($id);
        if (!$semestre) {
            abort(404);
        }

        $documentos = \App\Models\Documento::where('id_semestre', $id)->orderBy('created_at', 'desc')->get();

        $informes = Informe::listadoGeneradosPorUnidad((int) $semestre->id_semestre, 1, $this->panelTipoPrograma());

        // Filtrar actividades por semestre y por la unidad académica del usuario
        $uaName = $user->unidad_academica ?? '';
        $user_unidad_id = $user->unidad_id ?? $user->unidad ?? null;

        $candidates = ['Unión','Union','Hidalgo','Unión','Unio'];
        $uaKeyword = null;
        foreach ($candidates as $cand) {
            if (!empty($uaName) && stripos($uaName, $cand) !== false) {
                $uaKeyword = $cand;
                break;
            }
        }

        $actividadesQuery = Actividad::with('unidad')
            ->where('id_semestre', $semestre->id_semestre)
            ->where('tipo_programa', $this->panelTipoPrograma());
        if (!empty($user_unidad_id)) {
            $actividadesQuery->where('id_unidad', $user_unidad_id);
        } elseif (!empty($uaKeyword)) {
            $actividadesQuery->whereHas('unidad', function ($q) use ($uaKeyword) {
                $q->where('nombre_unidad', 'like', '%' . $uaKeyword . '%');
            });
        } else {
            $actividadesQuery->whereHas('unidad', function ($q) {
                $q->where('nombre_unidad', 'like', '%Unión%')->orWhere('nombre_unidad','like','%Union%');
            });
        }

        $actividades = $actividadesQuery->orderBy('created_at', 'desc')->get();

        // Obtener evaluaciones del semestre y unidad actual
        $evaluacionesQuery = \App\Models\Evaluacion::with(['estudiante', 'actividad'])
            ->where('id_semestre', $id)
            ->whereHas('actividad', function ($q) {
                $q->where('tipo_programa', $this->panelTipoPrograma());
            });

        if (!empty($user_unidad_id)) {
            $evaluacionesQuery->where('id_unidad', $user_unidad_id);
        } elseif (!empty($uaKeyword)) {
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
            'unidad' => 'Unidad Académica Unión Hidalgo',
            'documentos' => $documentos,
            'informes' => $informes,
            'tipo_programa_informes_panel' => $this->panelTipoPrograma(),
            'actividades' => $actividades,
            'evaluaciones' => $evaluaciones,
        ]);
    }

    public function printResultados($id)
    {
        $user = Auth::user();
        if ($user && ($user->rol ?? '') === 'Coordinador') {
            $ua = $user->unidad_academica ?? '';
            if (stripos($ua, 'Unión') === false && stripos($ua, 'Union') === false && stripos($ua, 'Hidalgo') === false) {
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
        $candidates = ['Unión','Union','Hidalgo','Unión','Unio'];
        foreach ($candidates as $cand) {
            if (!empty($uaName) && stripos($uaName, $cand) !== false) {
                $uaKeyword = $cand;
                break;
            }
        }

        $evaluacionesQuery = \App\Models\Evaluacion::with(['estudiante', 'actividad'])
            ->where('id_semestre', $id)
            ->whereHas('actividad', function ($q) {
                $q->where('tipo_programa', $this->panelTipoPrograma());
            });

        if (!empty($user_unidad_id)) {
            $evaluacionesQuery->where('id_unidad', $user_unidad_id);
        } elseif (!empty($uaKeyword)) {
            $evaluacionesQuery->whereHas('unidad', function ($q) use ($uaKeyword) {
                $q->where('nombre_unidad', 'like', '%' . $uaKeyword . '%');
            });
        }

        $tipo = strtolower(request()->get('tipo', 'cultural'));
        if (in_array($tipo, ['cultural', 'deportiva'])) {
            $evaluacionesQuery->whereHas('actividad', function ($q) use ($tipo) {
                $q->where('tipo_programa', $this->panelTipoPrograma());
                if ($tipo === 'cultural') {
                    $q->where(function($qw){
                        $keywords = [
                            'cultural','arte','artística','artistica','danzas','danza','folklor','folklórica','folklorica','baile',
                            'música','musica','teatro','pintura','coro','orquesta','ajedrez','lectura','fotografía','fotografia','rondalla','escolta','banda de guerra'
                        ];
                        foreach ($keywords as $kw) { $qw->orWhere('nombre_actividad', 'like', "%$kw%"); }
                    });
                } else {
                    $q->where(function($qw){
                        $keywords = [
                            'deportiva','deporte','fútbol','futbol','basquetbol','basket','voleibol','atletismo','natación','natacion',
                            'tenis','gimnasia','acondicionamiento','acondicionamiento fisico','acondicionamiento físico','preparacion fisica'
                        ];
                        foreach ($keywords as $kw) { $qw->orWhere('nombre_actividad', 'like', "%$kw%"); }
                    });
                }
            });
        }

        $evaluaciones = $evaluacionesQuery->get()->sortBy(function($evaluacion) {
            return $evaluacion->estudiante->nombre ?? '';
        })->values();

        return view($this->resultadosPdfView(), [
            'user' => $user,
            'semestre' => $semestre,
            'unidad' => 'Unidad Académica Unión Hidalgo',
            'evaluaciones' => $evaluaciones,
            'tipo' => in_array($tipo, ['cultural','deportiva']) ? $tipo : 'cultural',
            'lugar' => 'Santiago Suchilquitongo, Oax',
        ]);
    }

    public function actualizarEstudiante($id, Request $request)
    {
        $user = Auth::user();
        if (!$user || ($user->rol ?? '') !== 'Coordinador') {
            return response()->json(['success' => false, 'message' => 'No autorizado'], 403);
        }

        try {
            $estudiante = \App\Models\Estudiante::findOrFail($id);
            $actividad = \App\Models\Actividad::findOrFail($estudiante->id_actividad);

            $user_unidad_id = $user->unidad_id ?? $user->unidad ?? null;
            if ($user_unidad_id && $actividad->id_unidad != $user_unidad_id) {
                return response()->json(['success' => false, 'message' => 'No tienes permiso para editar este estudiante'], 403);
            }
            if (($actividad->tipo_programa ?? Actividad::TIPO_EXTRAESCOLAR) !== $this->panelTipoPrograma()) {
                return response()->json(['success' => false, 'message' => 'No tienes permiso para editar este estudiante'], 403);
            }

            $validated = $request->validate([
                'nombre' => 'required|string|max:255',
                'numero_control' => 'required|string|max:100',
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
            $estudiante = \App\Models\Estudiante::findOrFail($id);
            $actividad = \App\Models\Actividad::findOrFail($estudiante->id_actividad);

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
