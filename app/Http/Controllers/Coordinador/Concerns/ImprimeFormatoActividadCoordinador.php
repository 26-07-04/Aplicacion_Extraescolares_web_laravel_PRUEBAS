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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

trait ImprimeFormatoActividadCoordinador
{
    abstract protected function firmasUnidadKey(): string;

    abstract protected function formatoActividadLugar(): string;

    /** @param  object|null  $user */
    abstract protected function autorizarCoordinadorFormato($user): void;

    /** @return list<string> */
    abstract protected function keywordsActividadPanel(): array;

    /** @return list<string> */
    abstract protected function fallbackLikeActividadPanel(): array;

    protected function redirigirResultadosAFormatos(Request $request): ?\Illuminate\Http\RedirectResponse
    {
        if ($request->query('show') === 'resultados') {
            return redirect()->to(url()->current() . '?show=formatos');
        }

        return null;
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
        ]);
    }

    /**
     * @param  object|null  $user
     */
    protected function findActividadDelPanel(int $semestreId, int $actividadId, $user): ?Actividad
    {
        $uaName = $user->unidad_academica ?? '';
        $user_unidad_id = $user->unidad_id ?? $user->unidad ?? null;
        $uaKeyword = null;

        foreach ($this->keywordsActividadPanel() as $cand) {
            if (! empty($uaName) && stripos($uaName, $cand) !== false) {
                $uaKeyword = $cand;
                break;
            }
        }

        $query = Actividad::where('id_actividad', $actividadId)
            ->where('id_semestre', $semestreId)
            ->delTipoPrograma($this->panelTipoPrograma());

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
