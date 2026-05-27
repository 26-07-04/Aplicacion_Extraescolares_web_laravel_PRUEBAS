<?php

namespace App\Http\Controllers\Coordinador\Concerns;

use App\Models\Actividad;
use App\Models\Documento;
use App\Models\Estudiante;
use App\Models\Evaluacion;
use App\Models\Semestre;
use App\Models\Unidad;
use App\Support\FormatoActividadTitulo;
use App\Support\ResultadosExtraescolaresFirmas;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

trait ImprimeFormatoActividadCoordinador
{
    abstract protected function firmasUnidadKey(): string;

    abstract protected function formatoActividadLugar(): string;

    /**
     * Posición de «Página X de Y» en formatos (misma calibración en todas las unidades).
     *
     * @return array{top: string, right: string}|null
     */
    protected function paginaIndicadorFormatoActividad(string $formato): ?array
    {
        if ($formato === 'registro') {
            return [
                'top' => '1.06in',
                'right' => '1.52in',
            ];
        }

        return [
            'top' => '1.24in',
            'right' => '1.70in',
        ];
    }

    /**
     * Datos extra para la vista PDF de resultados extraescolares.
     *
     * @return array<string, mixed>
     */
    protected function datosAdicionalesVistaResultadosPdf(Semestre $semestre, $evaluaciones, Request $request): array
    {
        $unidadSlug = str_replace('_', '-', $this->firmasUnidadKey());

        return [
            'resultadosUnidadBodyClass' => 'unidad-' . $unidadSlug . '-resultados-print',
            'paginaIndicadorTop' => '1.02in',
            'paginaIndicadorRight' => '0.20in',
        ];
    }

    /** @param  object|null  $user */
    abstract protected function autorizarCoordinadorFormato($user): void;

    /** @return list<string> */
    abstract protected function keywordsActividadPanel(): array;

    /** @return list<string> */
    abstract protected function fallbackLikeActividadPanel(): array;

    /**
     * IDs de unidad académica del panel (catálogo unidades). Evita filtros LIKE ambiguos (p. ej. «Valle» en Demetrio Vallejo).
     *
     * @return list<int>
     */
    abstract protected function idsUnidadPanel(): array;

    protected function redirigirResultadosAFormatos(Request $request): ?\Illuminate\Http\RedirectResponse
    {
        if ($request->query('show') === 'resultados') {
            return redirect()->to(url()->current() . '?show=formatos');
        }

        return null;
    }

    /** En complementarias debe ser false (trait ResultadosLiberacionesComplementarias). */
    protected function filtrarResultadosPorTipoEnImpresion(): bool
    {
        return true;
    }

    /**
     * Resuelve la unidad del panel desde unidad_academica (como Valle de Etla), no solo unidad_id en usuarios.
     *
     * @param  object|null  $user
     * @return array{uaName: string, user_unidad_id: ?int, uaKeyword: ?string, unidadesIds: list<int>, stored_unidad_id: ?int}
     */
    protected function resolverContextoUnidadPanel($user): array
    {
        $uaName = $user->unidad_academica ?? '';
        $storedUnidadId = $user->unidad_id ?? $user->unidad ?? null;
        if ($storedUnidadId !== null && $storedUnidadId !== '') {
            $storedUnidadId = (int) $storedUnidadId;
        } else {
            $storedUnidadId = null;
        }

        $unidadesIds = $this->idsUnidadPanel();

        $uaKeyword = null;
        foreach ($this->keywordsActividadPanel() as $cand) {
            if ($uaName !== '' && stripos($uaName, $cand) !== false) {
                $uaKeyword = $cand;
                break;
            }
        }

        $user_unidad_id = $unidadesIds[0] ?? null;
        if ($storedUnidadId !== null && in_array($storedUnidadId, $unidadesIds, true)) {
            $user_unidad_id = $storedUnidadId;
        }

        return [
            'uaName' => $uaName,
            'user_unidad_id' => $user_unidad_id,
            'uaKeyword' => $uaKeyword,
            'unidadesIds' => $unidadesIds,
            'stored_unidad_id' => $storedUnidadId,
        ];
    }

    /**
     * @param  Builder<Actividad>  $query
     * @param  array{uaName: string, user_unidad_id: ?int, uaKeyword: ?string, unidadesIds: list<int>, stored_unidad_id: ?int}  $ctx
     */
    protected function aplicarFiltroUnidadEnQueryActividades(Builder $query, array $ctx): void
    {
        $idsPanel = $this->idsUnidadPanel();
        if ($idsPanel !== []) {
            $query->whereIn('id_unidad', $idsPanel);

            return;
        }

        if (! empty($ctx['unidadesIds'])) {
            $query->whereIn('id_unidad', $ctx['unidadesIds']);

            return;
        }

        if (! empty($ctx['user_unidad_id'])) {
            $query->where('id_unidad', $ctx['user_unidad_id']);

            return;
        }

        if (! empty($ctx['uaKeyword'])) {
            $query->whereHas('unidad', function ($q) use ($ctx) {
                $q->where('nombre_unidad', 'like', '%' . $ctx['uaKeyword'] . '%');
            });

            return;
        }

        $fallback = $this->fallbackLikeActividadPanel();
        $query->whereHas('unidad', function ($q) use ($fallback) {
            $q->where(function ($inner) use ($fallback) {
                foreach ($fallback as $i => $like) {
                    if ($i === 0) {
                        $inner->where('nombre_unidad', 'like', $like);
                    } else {
                        $inner->orWhere('nombre_unidad', 'like', $like);
                    }
                }
            });
        });
    }

    /**
     * @param  object|null  $user
     * @return array{0: Builder<Actividad>, 1: array}
     */
    protected function actividadesDelPanelQuery($user, Semestre $semestre): array
    {
        $ctx = $this->resolverContextoUnidadPanel($user);
        $query = Actividad::with('unidad')
            ->where('id_semestre', $semestre->id_semestre)
            ->delTipoPrograma($this->panelTipoPrograma());
        $this->aplicarFiltroUnidadEnQueryActividades($query, $ctx);

        return [$query, $ctx];
    }

    /**
     * Misma lógica de evaluaciones que PanelValleEtlaController (actividad del panel, no evaluaciones.id_unidad).
     *
     * @param  Collection<int, Actividad>|iterable<Actividad>  $actividades
     * @param  array{uaName: string, user_unidad_id: ?int, uaKeyword: ?string, unidadesIds: list<int>, stored_unidad_id: ?int}  $ctx
     * @return Builder<Evaluacion>
     */
    protected function evaluacionesDelPanelQuery($user, Semestre $semestre, $actividades, array $ctx): Builder
    {
        $evaluacionesQuery = Evaluacion::with(['estudiante', 'actividad'])
            ->where('id_semestre', $semestre->id_semestre)
            ->whereHas('actividad', function ($q) {
                $q->delTipoPrograma($this->panelTipoPrograma());
            });

        $actividadIds = collect($actividades)->pluck('id_actividad')->filter()->values()->all();
        if ($actividadIds !== []) {
            $evaluacionesQuery->whereIn('id_actividad', $actividadIds);

            return $evaluacionesQuery;
        }

        $panelUnidadIds = $ctx['unidadesIds'] ?? $this->idsUnidadPanel();
        if ($panelUnidadIds !== []) {
            $evaluacionesQuery->whereHas('actividad', function ($q) use ($panelUnidadIds, $semestre) {
                $q->whereIn('id_unidad', $panelUnidadIds)
                    ->where('id_semestre', $semestre->id_semestre);
            });

            return $evaluacionesQuery;
        }

        $user_unidad_id = $ctx['user_unidad_id'] ?? null;
        $uaKeyword = $ctx['uaKeyword'] ?? null;

        if (! empty($user_unidad_id)) {
            $evaluacionesQuery->whereHas('actividad', function ($q) use ($user_unidad_id, $semestre) {
                $q->where('id_unidad', $user_unidad_id)
                    ->where('id_semestre', $semestre->id_semestre);
            });
        } elseif (! empty($uaKeyword)) {
            $evaluacionesQuery->whereHas('actividad.unidad', function ($q) use ($uaKeyword, $semestre) {
                $q->where('nombre_unidad', 'like', '%' . $uaKeyword . '%')
                    ->whereHas('actividades', function ($q2) use ($semestre) {
                        $q2->where('id_semestre', $semestre->id_semestre);
                    });
            });
            $evaluacionesQuery->whereHas('actividad', function ($q) use ($semestre) {
                $q->where('id_semestre', $semestre->id_semestre);
            });
        } else {
            $evaluacionesQuery->whereHas('actividad', function ($q) use ($semestre) {
                $q->where('id_semestre', $semestre->id_semestre);
            });
            if (! empty($ctx['unidadesIds'])) {
                $evaluacionesQuery->whereHas('actividad', function ($q) use ($ctx) {
                    $q->whereIn('id_unidad', $ctx['unidadesIds']);
                });
            } else {
                $fallback = $this->fallbackLikeActividadPanel();
                $evaluacionesQuery->whereHas('actividad.unidad', function ($q) use ($fallback) {
                    $q->where(function ($inner) use ($fallback) {
                        foreach ($fallback as $i => $like) {
                            if ($i === 0) {
                                $inner->where('nombre_unidad', 'like', $like);
                            } else {
                                $inner->orWhere('nombre_unidad', 'like', $like);
                            }
                        }
                    });
                });
            }
        }

        return $evaluacionesQuery;
    }

    public function printFormato($semestreId, $actividadId, Request $request)
    {
        $user = Auth::user();
        $this->autorizarCoordinadorFormato($user);

        $semestre = Semestre::find($semestreId);
        if (! $semestre) {
            abort(404);
        }

        $actividad = $this->findActividadDelPanel((int) $semestre->id_semestre, (int) $actividadId, $user);
        if (! $actividad) {
            abort(404);
        }

        $formato = strtolower((string) $request->query('formato', 'registro'));
        if (! in_array($formato, ['resultados', 'registro'], true)) {
            $formato = 'registro';
        }

        if ($formato === 'registro') {
            $filas = Estudiante::where('id_actividad', $actividad->id_actividad)
                ->orderBy('nombre')
                ->get()
                ->map(fn ($e) => [
                    'nombre' => $e->nombre,
                    'control' => $e->numero_control,
                    'carrera' => $e->carrera,
                    'sem' => $e->semestre,
                    'observaciones' => '',
                ])
                ->values();
        } else {
            $filas = Evaluacion::with('estudiante')
                ->where('id_semestre', $semestre->id_semestre)
                ->where('id_actividad', $actividad->id_actividad)
                ->get()
                ->sortBy(fn ($ev) => $ev->estudiante->nombre ?? '')
                ->values()
                ->map(fn ($ev) => [
                    'nombre' => $ev->estudiante->nombre ?? 'N/A',
                    'control' => $ev->estudiante->numero_control ?? 'N/A',
                    'carrera' => $ev->estudiante->carrera ?? 'N/A',
                    'sem' => $ev->estudiante->semestre ?? 'N/A',
                    'resultado' => $ev->nivel_desempeno ?? '',
                ])
                ->values();
        }

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

        return view('coordinador.pdf.formato_actividad_print', [
            'semestre' => $semestre,
            'actividad' => $actividad,
            'formato' => $formato,
            'filas' => $filas,
            'tituloActividad' => FormatoActividadTitulo::linea($actividad),
            'lugar' => $this->formatoActividadLugar(),
            'firmas' => ResultadosExtraescolaresFirmas::forUnidad($this->firmasUnidadKey()),
            'firmasUnidadKey' => $this->firmasUnidadKey(),
            'membreteArchivoUrl' => $membreteArchivoUrl,
            'paginaIndicadorFormato' => $this->paginaIndicadorFormatoActividad($formato),
        ]);
    }

    /**
     * @param  object|null  $user
     */
    protected function findActividadDelPanel(int $semestreId, int $actividadId, $user): ?Actividad
    {
        $query = Actividad::where('id_actividad', $actividadId)
            ->where('id_semestre', $semestreId)
            ->delTipoPrograma($this->panelTipoPrograma());

        $idsPanel = $this->idsUnidadPanel();
        if ($idsPanel !== []) {
            $query->whereIn('id_unidad', $idsPanel);

            return $query->first();
        }

        $uaName = $user->unidad_academica ?? '';
        $user_unidad_id = $user->unidad_id ?? $user->unidad ?? null;
        $uaKeyword = null;

        foreach ($this->keywordsActividadPanel() as $cand) {
            if (! empty($uaName) && stripos($uaName, $cand) !== false) {
                $uaKeyword = $cand;
                break;
            }
        }

        if (! empty($user_unidad_id)) {
            $query->where('id_unidad', $user_unidad_id);
        } else {
            $uaNorm = Str::ascii(Str::lower($uaName));
            $unidades = Unidad::all()->filter(function ($u) use ($uaNorm) {
                $nombreNorm = Str::ascii(Str::lower($u->nombre_unidad));

                return $uaNorm !== '' && (strpos($nombreNorm, $uaNorm) !== false || strpos($uaNorm, $nombreNorm) !== false);
            })->pluck('id_unidad')->toArray();

            if (! empty($unidades)) {
                $query->whereIn('id_unidad', $unidades);
            } elseif (! empty($uaKeyword)) {
                $query->whereHas('unidad', function ($q) use ($uaKeyword) {
                    $q->where('nombre_unidad', 'like', '%' . $uaKeyword . '%');
                });
            } else {
                $fallback = $this->fallbackLikeActividadPanel();
                $query->whereHas('unidad', function ($q) use ($fallback) {
                    $q->where(function ($inner) use ($fallback) {
                        foreach ($fallback as $i => $like) {
                            if ($i === 0) {
                                $inner->where('nombre_unidad', 'like', $like);
                            } else {
                                $inner->orWhere('nombre_unidad', 'like', $like);
                            }
                        }
                    });
                });
            }
        }

        return $query->first();
    }
}
