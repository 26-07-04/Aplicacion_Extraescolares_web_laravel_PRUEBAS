<?php

namespace App\Http\Controllers\Coordinador\Concerns;

use App\Models\Actividad;
use App\Models\Documento;
use App\Models\Evaluacion;
use App\Models\Semestre;
use App\Support\EvaluacionExtraescolarFormulario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait ImprimeEvaluacionFormularioCoordinador
{
    /**
     * @return list<string>
     */
    protected function encabezadoEvaluacionFormulario(): array
    {
        if ($this->panelTipoPrograma() === Actividad::TIPO_COMPLEMENTARIA) {
            return [
                'INSTITUTO TECNOLÓGICO DEL VALLE DE ETLA',
                'Subdirección Académica',
                'DEPARTAMENTO DE ACTIVIDADES COMPLEMENTARIAS',
                'FORMATO DE EVALUACIÓN DE DESEMPEÑO',
            ];
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
        $evaluaciones = $this->evaluacionesDelPanelQuery($user, $semestre, $actividadesPrint, $ctxUnidad)
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
            'mostrarEncabezadoInstitucional' => $this->panelTipoPrograma() !== Actividad::TIPO_COMPLEMENTARIA,
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
