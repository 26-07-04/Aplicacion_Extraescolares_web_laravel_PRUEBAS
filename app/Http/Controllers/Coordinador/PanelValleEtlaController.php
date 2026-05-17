<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Semestre;
use App\Models\Actividad;
use App\Models\Unidad;
use App\Models\Evaluacion;
use App\Models\Informe;
use App\Models\Estudiante;
use App\Models\Documento;
use App\Support\ResultadosExtraescolaresFirmas;
use App\Support\ResultadosTipoFiltro;
use Illuminate\Support\Str;

class PanelValleEtlaController extends Controller
{
    /**
     * Vista Blade del panel (Extraescolares). Las subclases en Complementarias\ devuelven panel_complementarias.
     */
    protected function coordinadorPanelView(): string
    {
        return 'coordinador.valle_de_etla.panel';
    }

    /**
     * Vista PDF de resultados (extraescolares). El panel complementarias sobreescribe.
     */
    protected function resultadosPdfView(): string
    {
        return 'coordinador.valle_de_etla.pdf.resultados';
    }

    /**
     * Actividades y evaluaciones del panel extraescolar vs complementarias.
     */
    protected function panelTipoPrograma(): string
    {
        return Actividad::TIPO_EXTRAESCOLAR;
    }

    public function show($id)
    {
        $user = Auth::user();
        if ($user && ($user->rol ?? '') === 'Coordinador') {
            $ua = $user->unidad_academica ?? '';
            if (stripos($ua, 'Valle') === false && stripos($ua, 'Etla') === false) {
                abort(403);
            }
        }
        // Si no hay usuario o no es coordinador, permitir acceso (público o admin)

        $semestre = Semestre::find($id);
        if (!$semestre) {
            abort(404);
        }

        $documentos = \App\Models\Documento::where('id_semestre', $id)->orderBy('created_at', 'desc')->get();

        $informes = Informe::listadoGeneradosPorUnidad((int) $semestre->id_semestre, 4, $this->panelTipoPrograma());

        // Filtrar actividades por semestre y por la unidad académica del usuario
        $uaName = $user->unidad_academica ?? '';
        $user_unidad_id = $user->unidad_id ?? $user->unidad ?? null;

        $candidates = ['Valle','Etla','Valle de Etla','Valle de Etla'];
        $uaKeyword = null;
        foreach ($candidates as $cand) {
            if (!empty($uaName) && stripos($uaName, $cand) !== false) {
                $uaKeyword = $cand;
                break;
            }
        }

        $actividadesQuery = Actividad::with('unidad')
            ->where('id_semestre', $semestre->id_semestre)
            ->delTipoPrograma($this->panelTipoPrograma());
        if (!empty($user_unidad_id)) {
            $actividadesQuery->where('id_unidad', $user_unidad_id);
        } else {
            $uaNorm = Str::ascii(Str::lower($uaName));
            $unidades = Unidad::all()->filter(function ($u) use ($uaNorm) {
                $nombreNorm = Str::ascii(Str::lower($u->nombre_unidad));
                return ($uaNorm !== '' && (strpos($nombreNorm, $uaNorm) !== false || strpos($uaNorm, $nombreNorm) !== false));
            })->pluck('id_unidad')->toArray();

            if (!empty($unidades)) {
                $actividadesQuery->whereIn('id_unidad', $unidades);
            } elseif (!empty($uaKeyword)) {
                $actividadesQuery->whereHas('unidad', function ($q) use ($uaKeyword) {
                    $q->where('nombre_unidad', 'like', '%' . $uaKeyword . '%');
                });
            } else {
                $actividadesQuery->whereHas('unidad', function ($q) {
                    $q->where('nombre_unidad', 'like', '%Valle%')->orWhere('nombre_unidad','like','%Etla%');
                });
            }
        }

        $actividades = $actividadesQuery->orderBy('created_at', 'desc')->get();

        // Obtener evaluaciones del semestre y unidad actual (si aplica)
        $evaluacionesQuery = Evaluacion::with(['estudiante', 'actividad'])
            ->where('id_semestre', $semestre->id_semestre)
            ->whereHas('actividad', function ($q) {
                $q->delTipoPrograma($this->panelTipoPrograma());
            });

        // Restringir a actividades que están en el panel (evita traer evaluaciones de actividades/otras unidades no listadas)
        $actividadIds = $actividades->pluck('id_actividad')->toArray();
        if (!empty($actividadIds)) {
            $evaluacionesQuery->whereIn('id_actividad', $actividadIds);
        }

        if (!empty($user_unidad_id)) {
            // Filtrar por la unidad asociada a la actividad (más fiable que el campo id_unidad en evaluaciones)
            $evaluacionesQuery->whereHas('actividad', function ($q) use ($user_unidad_id, $semestre) {
                $q->where('id_unidad', $user_unidad_id)
                  ->where('id_semestre', $semestre->id_semestre);
            });
        } elseif (!empty($uaKeyword)) {
            // Filtrar por coincidencia en el nombre de la unidad de la actividad y por semestre
            $evaluacionesQuery->whereHas('actividad.unidad', function ($q) use ($uaKeyword, $semestre) {
                $q->where('nombre_unidad', 'like', '%' . $uaKeyword . '%')
                  ->whereHas('actividades', function ($q2) use ($semestre) {
                      $q2->where('id_semestre', $semestre->id_semestre);
                  });
            });
            // además asegure que la actividad pertenece al mismo semestre
            $evaluacionesQuery->whereHas('actividad', function ($q) use ($semestre) {
                $q->where('id_semestre', $semestre->id_semestre);
            });
        } else {
            // Asegurar que la actividad asociada pertenece al mismo semestre
            $evaluacionesQuery->whereHas('actividad', function ($q) use ($semestre) {
                $q->where('id_semestre', $semestre->id_semestre);
            })->whereHas('actividad.unidad', function ($q) {
                $q->where('nombre_unidad', 'like', '%Valle%')->orWhere('nombre_unidad','like','%Etla%');
            });
        }

        $evaluaciones = $evaluacionesQuery->get()->sortBy(function($evaluacion) {
            return $evaluacion->estudiante->nombre ?? '';
        })->values();

        return view($this->coordinadorPanelView(), [
            'user' => $user,
            'semestre' => $semestre,
            'unidad' => 'Unidad Académica Valle de Etla',
            'documentos' => $documentos,
            'informes' => $informes,
            'tipo_programa_informes_panel' => $this->panelTipoPrograma(),
            'actividades' => $actividades,
            'evaluaciones' => $evaluaciones,
        ]);
    }

    public function printResultados($id, Request $request)
    {
        $user = Auth::user();
        if ($user && ($user->rol ?? '') === 'Coordinador') {
            $ua = $user->unidad_academica ?? '';
            if (stripos($ua, 'Valle') === false && stripos($ua, 'Etla') === false) {
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
        $candidates = ['Valle','Etla','Valle de Etla'];
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

        // Construir lista de actividades válidas para este semestre/unidad (usar la misma lógica que en show)
        $actividadesQuery = Actividad::with('unidad')
            ->where('id_semestre', $semestre->id_semestre)
            ->delTipoPrograma($this->panelTipoPrograma());
        if (!empty($user_unidad_id)) {
            $actividadesQuery->where('id_unidad', $user_unidad_id);
        } else {
            $uaNorm = Str::ascii(Str::lower($uaName));
            $unidades = Unidad::all()->filter(function ($u) use ($uaNorm) {
                $nombreNorm = Str::ascii(Str::lower($u->nombre_unidad));
                return ($uaNorm !== '' && (strpos($nombreNorm, $uaNorm) !== false || strpos($uaNorm, $nombreNorm) !== false));
            })->pluck('id_unidad')->toArray();

            if (!empty($unidades)) {
                $actividadesQuery->whereIn('id_unidad', $unidades);
            } elseif (!empty($uaKeyword)) {
                $actividadesQuery->whereHas('unidad', function ($q) use ($uaKeyword) {
                    $q->where('nombre_unidad', 'like', '%' . $uaKeyword . '%');
                });
            } else {
                $actividadesQuery->whereHas('unidad', function ($q) {
                    $q->where('nombre_unidad', 'like', '%Valle%')->orWhere('nombre_unidad','like','%Etla%');
                });
            }
        }

        $actividadIdsForPrint = $actividadesQuery->pluck('id_actividad')->toArray();

        if (!empty($actividadIdsForPrint)) {
            $evaluacionesQuery->whereIn('id_actividad', $actividadIdsForPrint);
        } else {
            // Fallback: si no hay actividades listadas, filtrar por unidad/semestre
            if (!empty($user_unidad_id)) {
                $evaluacionesQuery->whereHas('actividad', function ($q) use ($user_unidad_id, $semestre) {
                    $q->where('id_unidad', $user_unidad_id)
                      ->where('id_semestre', $semestre->id_semestre)
                      ->delTipoPrograma($this->panelTipoPrograma());
                });
            } elseif (!empty($uaKeyword)) {
                $evaluacionesQuery->whereHas('actividad.unidad', function ($q) use ($uaKeyword, $semestre) {
                    $q->where('nombre_unidad', 'like', '%' . $uaKeyword . '%')
                      ->whereHas('actividades', function ($q2) use ($semestre) {
                          $q2->where('id_semestre', $semestre->id_semestre);
                      });
                });
                $evaluacionesQuery->whereHas('actividad', function ($q) use ($semestre) {
                    $q->where('id_semestre', $semestre->id_semestre)
                      ->delTipoPrograma($this->panelTipoPrograma());
                });
            } else {
                $evaluacionesQuery->whereHas('actividad', function ($q) use ($semestre) {
                    $q->where('id_semestre', $semestre->id_semestre)
                      ->delTipoPrograma($this->panelTipoPrograma());
                })->whereHas('actividad.unidad', function ($q) {
                    $q->where('nombre_unidad', 'like', '%Valle%')->orWhere('nombre_unidad','like','%Etla%');
                });
            }
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
            'unidad' => 'Unidad Académica Valle de Etla',
            'evaluaciones' => $evaluaciones,
            'tipo' => $tipo,
            'lugar' => 'Santiago Suchilquitongo, Oax',
            'firmas' => ResultadosExtraescolaresFirmas::forUnidad('valle_etla'),
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
