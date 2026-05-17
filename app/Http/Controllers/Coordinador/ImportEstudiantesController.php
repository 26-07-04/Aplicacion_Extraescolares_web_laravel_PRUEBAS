<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use App\Models\Actividad;
use App\Models\Estudiante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImportEstudiantesController extends Controller
{
    /**
     * Valida si los estudiantes ya existen en la actividad (para previsualización).
     * Recibe JSON: { numeroControles: [...], id_actividad, id_semestre }
     * Retorna: { duplicados: { "20230016": true, ... } }
     *
     * Duplicado = ya inscrito en esta misma actividad.
     */
    public function checkDuplicates($actividadId, Request $request)
    {
        $data = $request->all();
        $numeroControles = $data['numeroControles'] ?? [];

        if (! is_array($numeroControles) || count($numeroControles) === 0) {
            return response()->json(['duplicados' => []]);
        }

        $numNormalizados = array_map(fn ($n) => is_null($n) ? '' : trim((string) $n), $numeroControles);
        $numNormalizados = array_filter($numNormalizados);

        if (empty($numNormalizados)) {
            return response()->json(['duplicados' => []]);
        }

        $existentesEnEstaActividad = Estudiante::where('id_actividad', $actividadId)
            ->whereIn('numero_control', $numNormalizados)
            ->pluck('numero_control')
            ->toArray();

        $repetidosEnArchivo = array_count_values($numNormalizados);

        $duplicados = [];
        foreach ($numNormalizados as $no) {
            $duplicados[$no] = in_array($no, $existentesEnEstaActividad, true)
                || (($repetidosEnArchivo[$no] ?? 0) > 1);
        }

        return response()->json(['duplicados' => $duplicados]);
    }

    /**
     * Importa estudiantes en masa para una actividad (desde el panel del coordinador).
     * Recibe JSON: { students: [...], id_unidad, id_semestre }
     */
    public function import($actividadId, Request $request)
    {
        try {
            $dumpPath = storage_path('logs/import_debug.log');
            $payload = $request->all();
            @file_put_contents($dumpPath, "\n----- IMPORT START " . date('Y-m-d H:i:s') . " -----\n", FILE_APPEND);
            @file_put_contents($dumpPath, "ActividadId: {$actividadId}\n", FILE_APPEND);
            @file_put_contents($dumpPath, 'Payload: ' . json_encode($payload, JSON_UNESCAPED_UNICODE) . "\n", FILE_APPEND);
        } catch (\Throwable $ex) {
        }

        $data = $request->all();
        $students = $data['students'] ?? [];
        $id_unidad = $data['id_unidad'] ?? null;
        $id_semestre = $data['id_semestre'] ?? null;

        if ($id_unidad === '0' || $id_unidad === 0) {
            $id_unidad = null;
        } elseif (is_string($id_unidad) && is_numeric($id_unidad)) {
            $id_unidad = (int) $id_unidad;
        }

        if (is_string($id_semestre) && ctype_digit($id_semestre)) {
            $id_semestre = (int) $id_semestre;
        }

        if (! is_array($students) || count($students) === 0) {
            return response()->json(['message' => 'No hay estudiantes para importar'], 422);
        }

        $actividad = Actividad::find($actividadId);
        if (! $actividad) {
            return response()->json(['message' => 'Actividad no encontrada'], 404);
        }

        $tipoEsperado = $request->input('tipo_programa');
        if ($tipoEsperado) {
            $tipoEsperado = \App\Models\Informe::tipoProgramaValido($tipoEsperado);
            $tipoActividad = $actividad->tipo_programa ?? Actividad::TIPO_EXTRAESCOLAR;
            if ($tipoActividad !== $tipoEsperado
                && ! ($tipoEsperado === Actividad::TIPO_EXTRAESCOLAR && $tipoActividad === null)) {
                return response()->json([
                    'message' => 'La actividad no pertenece al panel seleccionado (extraescolar / complementaria).',
                ], 422);
            }
        }

        $inserted = 0;
        $skipped = 0;
        $errors = [];
        $controlesEnLote = [];

        DB::beginTransaction();
        try {
            foreach ($students as $i => $st) {
                $no = isset($st['No_control']) ? $st['No_control'] : (isset($st['numero_control']) ? $st['numero_control'] : (isset($st['Control']) ? $st['Control'] : ''));
                $no = is_null($no) ? '' : trim((string) $no);
                $nombre = $st['Nombre'] ?? $st['nombre'] ?? '';
                $carrera = $st['Carrera'] ?? $st['carrera'] ?? '';
                $sexo = $st['Sexo'] ?? $st['sexo'] ?? '';
                $sem = $st['Semestre'] ?? $st['semestre'] ?? null;

                if (! $no) {
                    $skipped++;
                    $errors[] = ['row' => $i + 1, 'reason' => 'Número de control vacío'];
                    continue;
                }

                if (isset($controlesEnLote[$no])) {
                    $skipped++;
                    $errors[] = ['row' => $i + 1, 'reason' => 'Número de control repetido en el archivo para esta actividad'];
                    continue;
                }

                if (Estudiante::yaInscritoEnActividad($no, (int) $actividadId)) {
                    $skipped++;
                    $errors[] = ['row' => $i + 1, 'reason' => 'El estudiante ya está inscrito en esta actividad'];
                    try {
                        @file_put_contents(storage_path('logs/import_debug.log'), 'SKIPPED ROW ' . ($i + 1) . ": numero_control {$no} already exists in activity {$actividadId}.\n", FILE_APPEND);
                    } catch (\Throwable $__) {
                    }
                    continue;
                }

                try {
                    Estudiante::create([
                        'nombre' => $nombre,
                        'numero_control' => $no,
                        'carrera' => $carrera,
                        'sexo' => isset($sexo) && $sexo !== '' ? trim((string) $sexo) : null,
                        'semestre' => $sem,
                        'id_actividad' => $actividadId,
                        'id_unidad' => $id_unidad,
                        'id_semestre' => $id_semestre,
                    ]);
                    $inserted++;
                    $controlesEnLote[$no] = true;
                } catch (\Exception $ex) {
                    if (strpos($ex->getMessage(), 'UNIQUE') !== false || strpos($ex->getMessage(), 'numero_control') !== false) {
                        $skipped++;
                        $errors[] = ['row' => $i + 1, 'reason' => 'El estudiante ya está inscrito en esta actividad'];
                        try {
                            @file_put_contents(storage_path('logs/import_debug.log'), 'SKIPPED ROW ' . ($i + 1) . ": duplicate enrollment {$no} in activity {$actividadId}.\n", FILE_APPEND);
                        } catch (\Throwable $__) {
                        }
                    } else {
                        $skipped++;
                        $errors[] = ['row' => $i + 1, 'reason' => $ex->getMessage()];
                        try {
                            @file_put_contents(storage_path('logs/import_debug.log'), 'INSERT ERROR ROW ' . ($i + 1) . ': ' . $ex->getMessage() . "\n" . $ex->getTraceAsString() . "\n", FILE_APPEND);
                        } catch (\Throwable $__) {
                        }
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            try {
                @file_put_contents(storage_path('logs/import_debug.log'), 'EXCEPTION: ' . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n", FILE_APPEND);
            } catch (\Throwable $__) {
            }

            return response()->json(['message' => 'Error durante la importación', 'error' => $e->getMessage()], 500);
        }

        try {
            @file_put_contents(storage_path('logs/import_debug.log'), '----- IMPORT END ' . date('Y-m-d H:i:s') . " -----\n", FILE_APPEND);
        } catch (\Throwable $__) {
        }

        return response()->json([
            'inserted' => $inserted,
            'skipped' => $skipped,
            'errors' => $errors,
        ], 200);
    }
}
