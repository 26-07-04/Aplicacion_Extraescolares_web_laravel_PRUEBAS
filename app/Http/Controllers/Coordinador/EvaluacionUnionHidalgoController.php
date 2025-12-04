<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use App\Models\Evaluacion;
use App\Models\Estudiante;
use App\Models\Documento;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EvaluacionUnionHidalgoController extends Controller
{
    public function guardarEvaluacion(Request $request)
    {
        try {
            $request->validate([
                'id_alumno' => 'required|exists:estudiantes,id_alumno',
                'nivel_desempeno' => 'required|string',
                'calificacion_numerica' => 'required|numeric|min:0|max:100',
                'creditos' => 'required|integer|min:1|max:10',
            ]);

            $estudiante = Estudiante::find($request->id_alumno);

            $evaluacion = Evaluacion::create([
                'id_alumno' => $request->id_alumno,
                'id_actividad' => $estudiante->id_actividad,
                'id_semestre' => $estudiante->id_semestre,
                'id_unidad' => $estudiante->id_unidad,
                'nivel_desempeno' => $request->nivel_desempeno,
                'calificacion_numerica' => $request->calificacion_numerica,
                'creditos' => $request->creditos,
                'observaciones' => $request->observaciones,
                'jefe_extraescolares' => $request->jefe_extraescolares,
                'jefe_servicios_escolares' => $request->jefe_servicios_escolares,
                'ciudad' => $request->ciudad ?? 'Oaxaca',
                'fecha_evaluacion' => now(),
                'nombre_profesor' => $request->nombre_profesor ?? Auth::user()->name ?? 'Sin especificar',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Evaluación guardada correctamente',
                'id_evaluacion' => $evaluacion->id_evaluacion ?? $evaluacion->id,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar la evaluación: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function generarConstancia(Request $request, $id_evaluacion)
    {
        try {
            $evaluacion = Evaluacion::with(['estudiante','actividad','semestre'])->findOrFail($id_evaluacion);

            $documentoMembrete = null;
            if ($request->has('id_documento')) {
                $documentoMembrete = Documento::find($request->id_documento);
            }

            $data = [
                'evaluacion' => $evaluacion,
                'estudiante' => $evaluacion->estudiante,
                'actividad' => $evaluacion->actividad,
                'semestre' => $evaluacion->semestre,
                'documentoMembrete' => $documentoMembrete,
                // Usar la fecha actual en la zona de Oaxaca/México para evitar desfases por UTC
                'fecha' => Carbon::now('America/Mexico_City'),
            ];

                $pdf = Pdf::loadView('coordinador.union_hidalgo.pdf.constancia', $data);
            $pdf->setPaper('letter', 'portrait');
                $nombreArchivo = 'Constancia_' . $evaluacion->estudiante->numero_control . '_' . Carbon::now('America/Mexico_City')->format('Y-m-d') . '.pdf';
            return $pdf->download($nombreArchivo);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al generar la constancia: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function obtenerDocumentosMembrete($id_semestre)
    {
        try {
            $documentos = Documento::where('id_semestre', $id_semestre)
                                   ->where('tipo_documento', 'membrete')
                                   ->get();

            return response()->json([
                'success' => true,
                'documentos' => $documentos,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener documentos: ' . $e->getMessage(),
            ], 500);
        }
    }
}
