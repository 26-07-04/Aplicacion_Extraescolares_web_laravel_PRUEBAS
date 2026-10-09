<?php

namespace App\Http\Controllers\Coordinador\Concerns;

use App\Models\Actividad;
use App\Models\Documento;
use App\Models\Evaluacion;
use App\Models\Semestre;
use App\Support\ComplementariasEvaluacionEncabezado;
use App\Support\EvaluacionExtraescolarFormulario;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use ZipArchive;

trait ImprimeEvaluacionFormularioCoordinador
{
    /**
     * @return list<string>
     */
    protected function encabezadoEvaluacionFormulario(): array
    {
        if ($this->panelTipoPrograma() === Actividad::TIPO_COMPLEMENTARIA) {
            return ComplementariasEvaluacionEncabezado::defaults();
        }

        return [
            'INSTITUTO TECNOLÓGICO DEL VALLE DE ETLA',
            'Subdirección de Planeación y Vinculación',
            'DEPARTAMENTO DE ACTIVIDADES EXTRAESCOLARES',
            'OFICINA DE PROMOCIÓN CULTURAL O DEPORTIVA',
        ];
    }

    protected function etiquetaCampoActividadEvaluacion(): string
    {
        if ($this->panelTipoPrograma() === Actividad::TIPO_COMPLEMENTARIA) {
            return 'Actividad complementaria académica';
        }

        return 'Actividad Cultural y/o Deportiva';
    }

    /**
     * @return list<string>
     */
    protected function criteriosEvaluacionFormulario(): array
    {
        return EvaluacionExtraescolarFormulario::criteriosParaTipoPrograma($this->panelTipoPrograma());
    }

    protected function evaluacionFormularioPdfView(): string
    {
        return 'coordinador.pdf.evaluacion_formulario_print';
    }

    public function printEvaluacionFormulario($id_evaluacion, Request $request)
    {
        $user = Auth::user();
        $this->autorizarCoordinadorFormato($user);

        $evaluacion = Evaluacion::with(['estudiante', 'actividad', 'semestre'])->findOrFail($id_evaluacion);
        EvaluacionExtraescolarFormulario::asegurarEvaluacionTipoPrograma($evaluacion, $this->panelTipoPrograma());

        $semestre = $evaluacion->semestre;
        if (! $semestre) {
            abort(404);
        }

        [$actividadesPrint] = $this->actividadesDelPanelQuery($user, $semestre);
        $idsActividades = $actividadesPrint->pluck('id_actividad')->all();
        if (! in_array((int) $evaluacion->id_actividad, array_map('intval', $idsActividades), true)) {
            abort(403);
        }

        return view($this->evaluacionFormularioPdfView(), $this->datosVistaEvaluacionFormularioPrint(
            $semestre,
            collect([$evaluacion]),
            $request
        ));
    }

    public function printAllEvaluacionesFormulario($id, Request $request)
    {
        $user = Auth::user();
        $this->autorizarCoordinadorFormato($user);

        $semestre = Semestre::find($id);
        if (! $semestre) {
            abort(404);
        }

        [$actividadesPrint, $ctxUnidad] = $this->actividadesDelPanelQuery($user, $semestre);
        $actividadesPrint = $actividadesPrint->get();
        $idActividad = (int) $request->query('id_actividad');
        $idsActividades = $actividadesPrint->pluck('id_actividad')->map(fn ($id) => (int) $id)->all();
        if ($idActividad <= 0 || ! in_array($idActividad, $idsActividades, true)) {
            abort(404, 'Selecciona una actividad válida para este semestre.');
        }

        $evaluaciones = $this->evaluacionesDelPanelQuery($user, $semestre, $actividadesPrint, $ctxUnidad)
            ->where('id_actividad', $idActividad)
            ->get()
            ->sortBy(fn ($e) => $e->estudiante->nombre ?? '')
            ->values();

        if ($evaluaciones->isEmpty()) {
            abort(404, 'No hay evaluaciones registradas para imprimir.');
        }

        return view($this->evaluacionFormularioPdfView(), $this->datosVistaEvaluacionFormularioPrint(
            $semestre,
            $evaluaciones,
            $request
        ));
    }

    public function downloadAllEvaluacionesFormulario($id, Request $request)
    {
        $user = Auth::user();
        $this->autorizarCoordinadorFormato($user);

        $semestre = Semestre::find($id);
        if (! $semestre) {
            abort(404);
        }

        [$actividadesPrint, $ctxUnidad] = $this->actividadesDelPanelQuery($user, $semestre);
        $actividadesPrint = $actividadesPrint->get();
        $idActividad = (int) $request->query('id_actividad');
        $idsActividades = $actividadesPrint->pluck('id_actividad')->map(fn ($id) => (int) $id)->all();
        if ($idActividad <= 0 || ! in_array($idActividad, $idsActividades, true)) {
            abort(404, 'Selecciona una actividad válida para este semestre.');
        }

        $evaluaciones = $this->evaluacionesDelPanelQuery($user, $semestre, $actividadesPrint, $ctxUnidad)
            ->where('id_actividad', $idActividad)
            ->get()
            ->sortBy(fn ($e) => $e->estudiante->nombre ?? '')
            ->values();

        if ($evaluaciones->isEmpty()) {
            abort(404, 'No hay evaluaciones registradas para descargar.');
        }

        $actividadSeleccionada = $actividadesPrint->firstWhere('id_actividad', $idActividad);
        $zipName = 'evaluaciones_' . Str::slug($actividadSeleccionada->nombre_actividad ?? 'actividad') . '_' . Str::slug($semestre->nombre ?? 'semestre') . '_' . now()->format('Ymd_His') . '.zip';
        $zipPath = storage_path('app/temp/' . $zipName);
        $directory = dirname($zipPath);
        if (! is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            abort(500, 'No se pudo crear el archivo ZIP.');
        }

        foreach ($evaluaciones as $index => $evaluacion) {
            $nombreEstudiante = trim((string) ($evaluacion->estudiante->nombre ?? 'estudiante'));
            $nombreArchivo = ($index + 1) . '_' . Str::slug($nombreEstudiante ?: 'estudiante') . '_' . ($evaluacion->id_evaluacion ?? $index + 1) . '.pdf';
            $pdf = Pdf::loadView($this->evaluacionFormularioPdfView(), $this->datosVistaEvaluacionFormularioPrint(
                $semestre,
                collect([$evaluacion]),
                $request
            ));

            $pdf->setPaper('letter');
            $zip->addFromString($nombreArchivo, $pdf->output());
        }

        $zip->close();

        return response()->download($zipPath, $zipName)
            ->deleteFileAfterSend(true)
            ->header('Content-Type', 'application/zip');
    }

    /**
     * @return array<string, mixed>
     */
    protected function datosVistaEvaluacionFormularioPrint(Semestre $semestre, $evaluaciones, Request $request): array
    {
        return [
            'semestre' => $semestre,
            'evaluaciones' => $evaluaciones,
            'membreteArchivoUrl' => $this->membreteArchivoUrlDesdeRequest($request, $semestre),
            'encabezadoEvaluacion' => $this->encabezadoEvaluacionFormulario(),
            'criteriosEvaluacion' => $this->criteriosEvaluacionFormulario(),
            'etiquetaCampoActividad' => $this->etiquetaCampoActividadEvaluacion(),
            'esComplementariasEvaluacion' => $this->panelTipoPrograma() === Actividad::TIPO_COMPLEMENTARIA,
        ];
    }

    protected function membreteArchivoUrlDesdeRequest(Request $request, Semestre $semestre): ?string
    {
        $idDoc = $request->query('id_documento');
        if (! $idDoc) {
            return null;
        }

        $doc = Documento::where('id_semestre', $semestre->id_semestre)
            ->where('id', $idDoc)
            ->first();

        if ($doc && ! empty($doc->archivo)) {
            return asset($doc->archivo);
        }

        return null;
    }
}
