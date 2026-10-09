<?php

namespace App\Http\Controllers\Coordinador\Concerns;

use App\Models\Actividad;
use App\Models\Documento;
use App\Models\Evaluacion;
use App\Models\Semestre;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use DOMDocument;
use DOMXPath;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait GeneraConstanciasActividad
{
    protected function actualizarResponsablesDeActividad(Request $request, int $idUnidadPanel)
    {
        $datos = $request->validate([
            'id_actividad' => 'required|integer|exists:actividades,id_actividad',
            'id_semestre' => 'required|integer|exists:semestres,id_semestre',
            'nombre_profesor' => 'required|string|max:255',
            'jefe_extraescolares' => 'required|string|max:255',
            'jefe_servicios_escolares' => 'required|string|max:255',
        ]);

        $actividad = Actividad::where('id_actividad', $datos['id_actividad'])
            ->where('id_unidad', $idUnidadPanel)
            ->where('id_semestre', $datos['id_semestre'])
            ->firstOrFail();

        if (($actividad->tipo_programa ?? Actividad::TIPO_EXTRAESCOLAR) !== Actividad::TIPO_EXTRAESCOLAR) {
            abort(404);
        }

        $actualizadas = Evaluacion::where('id_actividad', $actividad->id_actividad)
            ->where('id_semestre', $datos['id_semestre'])
            ->update([
                'nombre_profesor' => trim($datos['nombre_profesor']),
                'jefe_extraescolares' => trim($datos['jefe_extraescolares']),
                'jefe_servicios_escolares' => trim($datos['jefe_servicios_escolares']),
                'updated_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'actualizadas' => $actualizadas,
        ]);
    }

    protected function generarPdfConstanciasActividad(Request $request, string $vista, int $idUnidadPanel)
    {
        $datos = $request->validate([
            'id_actividad' => 'required|integer|exists:actividades,id_actividad',
            'id_semestre' => 'required|integer|exists:semestres,id_semestre',
            'id_documento' => 'required|integer|exists:documentos,id',
            'fecha_constancia' => 'required|date',
            'nombre_profesor' => 'nullable|string|max:255',
            'jefe_extraescolares' => 'nullable|string|max:255',
            'jefe_servicios_escolares' => 'nullable|string|max:255',
        ]);

        $actividad = Actividad::where('id_actividad', $datos['id_actividad'])
            ->where('id_unidad', $idUnidadPanel)
            ->where('id_semestre', $datos['id_semestre'])
            ->firstOrFail();
        if (($actividad->tipo_programa ?? Actividad::TIPO_EXTRAESCOLAR) !== Actividad::TIPO_EXTRAESCOLAR) {
            abort(404);
        }

        $evaluaciones = Evaluacion::with(['estudiante', 'actividad', 'semestre'])
            ->where('id_actividad', $actividad->id_actividad)
            ->where('id_semestre', $datos['id_semestre'])
            ->get()
            ->sortBy(fn ($evaluacion) => $evaluacion->estudiante->nombre ?? '')
            ->values();

        if ($evaluaciones->isEmpty()) {
            abort(404, 'No hay constancias evaluadas para imprimir en esta actividad.');
        }

        $semestre = Semestre::findOrFail($datos['id_semestre']);
        $documentoMembrete = Documento::where('id_semestre', $semestre->id_semestre)
            ->findOrFail($datos['id_documento']);
        $fecha = Carbon::parse($datos['fecha_constancia']);
        $estilos = '';
        $paginas = [];

        foreach ($evaluaciones as $evaluacion) {
            $this->aplicarFirmasDesdeSolicitud($request, $evaluacion);
            $html = view($vista, [
                'evaluacion' => $evaluacion,
                'estudiante' => $evaluacion->estudiante,
                'actividad' => $evaluacion->actividad,
                'semestre' => $evaluacion->semestre,
                'documentoMembrete' => $documentoMembrete,
                'fecha' => $fecha,
            ])->render();

            $documento = new DOMDocument('1.0', 'UTF-8');
            $documento->loadHTML('<?xml encoding="UTF-8">' . $html, LIBXML_HTML_NODEFDTD | LIBXML_NONET);
            $xpath = new DOMXPath($documento);
            if ($estilos === '') {
                foreach ($xpath->query('//head/style') as $estilo) {
                    $estilos .= $documento->saveHTML($estilo);
                }
            }

            $cuerpo = $documento->getElementsByTagName('body')->item(0);
            $contenido = '';
            if ($cuerpo) {
                foreach ($cuerpo->childNodes as $nodo) {
                    $contenido .= $documento->saveHTML($nodo);
                }
            }
            $paginas[] = '<section class="constancia-actividad-page">' . $contenido . '</section>';
        }

        $htmlCompleto = '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8">' . $estilos
            . '<style>.constancia-actividad-page + .constancia-actividad-page{page-break-before:always;}</style>'
            . '</head><body>' . implode('', $paginas) . '</body></html>';

        $pdf = Pdf::loadHTML($htmlCompleto)->setPaper('letter', 'portrait');
        $nombreArchivo = 'Constancias_' . Str::slug($actividad->nombre_actividad ?? 'actividad')
            . '_' . $fecha->format('Y-m-d') . '.pdf';

        return $pdf->stream($nombreArchivo);
    }

    protected function aplicarFirmasDesdeSolicitud(Request $request, Evaluacion $evaluacion): void
    {
        foreach ([
            'nombre_profesor',
            'jefe_extraescolares',
            'jefe_servicios_escolares',
        ] as $campo) {
            if ($request->filled($campo)) {
                $evaluacion->{$campo} = $request->input($campo);
            }
        }
    }
}