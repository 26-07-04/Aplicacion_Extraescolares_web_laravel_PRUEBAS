<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Coordinador\Concerns\ImprimeEvaluacionFormularioCoordinador;
use App\Http\Controllers\Coordinador\Concerns\ImprimeFormatoActividadCoordinador;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Semestre;
use App\Models\Actividad;
use App\Models\Evaluacion;
use App\Models\Informe;
use App\Models\Estudiante;
use App\Models\Documento;
use App\Support\ResultadosExtraescolaresFirmas;
use App\Support\ResultadosTipoFiltro;

class PanelValleEtlaController extends Controller
{
    use ImprimeFormatoActividadCoordinador;
    use ImprimeEvaluacionFormularioCoordinador;
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

    protected function firmasUnidadKey(): string
    {
        return 'valle_etla';
    }

    protected function formatoActividadLugar(): string
    {
        return 'Santiago Suchilquitongo';
    }

    protected function autorizarCoordinadorFormato($user): void
    {
        if ($user && ($user->rol ?? '') === 'Coordinador') {
            $ua = $user->unidad_academica ?? '';
            if (stripos($ua, 'Valle') === false && stripos($ua, 'Etla') === false) {
                abort(403);
            }
        }
    }

    protected function keywordsActividadPanel(): array
    {
        return ['Valle de Etla', 'Etla'];
    }

    protected function fallbackLikeActividadPanel(): array
    {
        return ['%Valle de Etla%'];
    }

    protected function idsUnidadPanel(): array
    {
        return [4];
    }

    public function show($id, Request $request)
    {
        if ($redirect = $this->redirigirResultadosAFormatos($request)) {
            return $redirect;
        }

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

        $ctxUnidad = $this->resolverContextoUnidadPanel($user);

        [$actividadesQuery] = $this->actividadesDelPanelQuery($user, $semestre);
        $actividades = $actividadesQuery->orderBy('created_at', 'desc')->get();

        $evaluaciones = $this->evaluacionesDelPanelQuery($user, $semestre, $actividades, $ctxUnidad)
            ->get()
            ->sortBy(function ($evaluacion) {
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

        [$actividadesPrintQuery, $ctxUnidad] = $this->actividadesDelPanelQuery($user, $semestre);
        $actividadesPrint = $actividadesPrintQuery->get();

        $evaluacionesQuery = $this->evaluacionesDelPanelQuery($user, $semestre, $actividadesPrint, $ctxUnidad);

        $tipo = 'cultural';
        if ($this->filtrarResultadosPorTipoEnImpresion()) {
            $tipo = strtolower((string) $request->query('tipo', 'cultural'));
            if (! in_array($tipo, ['cultural', 'deportiva', 'academica'], true)) {
                $tipo = 'cultural';
            }
            ResultadosTipoFiltro::apply($evaluacionesQuery, $tipo, $this->panelTipoPrograma());
        }

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

        return view($this->resultadosPdfView(), array_merge([
            'user' => $user,
            'semestre' => $semestre,
            'unidad' => 'Unidad Académica Valle de Etla',
            'evaluaciones' => $evaluaciones,
            'tipo' => $tipo,
            'lugar' => 'Santiago Suchilquitongo, Oax',
            'firmas' => ResultadosExtraescolaresFirmas::forUnidad('valle_etla'),
            'membreteArchivoUrl' => $membreteArchivoUrl,
        ], $this->datosAdicionalesVistaResultadosPdf($semestre, $evaluaciones, $request)));
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
