<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Estudiante;

class ImportEstudiantesController extends Controller
{
    /**
     * Valida si los estudiantes ya existen en la actividad (para previsualización).
     * Recibe JSON: { numeroControles: [...], id_actividad, id_semestre }
     * Retorna: { duplicados: { "20230016": true, ... } }
     * 
     * Un estudiante es "duplicado" si:
     * 1. Ya existe en ESTA actividad, O
     * 2. Ya existe en OTRA actividad del MISMO semestre
     */
    public function checkDuplicates($actividadId, Request $request)
    {
        $data = $request->all();
        $numeroControles = $data['numeroControles'] ?? [];
        $id_semestre = $data['id_semestre'] ?? null;

        if (!is_array($numeroControles) || count($numeroControles) === 0) {
            return response()->json(['duplicados' => []]);
        }

        // Normalizar números de control
        $numNormalizados = array_map(fn($n) => is_null($n) ? '' : trim((string)$n), $numeroControles);
        $numNormalizados = array_filter($numNormalizados); // Eliminar vacíos

        if (empty($numNormalizados)) {
            return response()->json(['duplicados' => []]);
        }

        // Buscar estudiantes que ya existen en ESTA ACTIVIDAD
        $existentesEnEstaActividad = Estudiante::where('id_actividad', $actividadId)
            ->whereIn('numero_control', $numNormalizados)
            ->pluck('numero_control')
            ->toArray();

        // Si hay id_semestre, también buscar en OTRAS actividades del MISMO semestre
        $existentesEnOtraActividadDelMismoSemestre = [];
        if ($id_semestre) {
            $existentesEnOtraActividadDelMismoSemestre = Estudiante::where('id_semestre', $id_semestre)
                ->where('id_actividad', '!=', $actividadId)
                ->whereIn('numero_control', $numNormalizados)
                ->pluck('numero_control')
                ->toArray();
        }

        // Combinar: es duplicado si existe en esta actividad O en otra actividad del mismo semestre
        $duplicados = [];
        foreach ($numNormalizados as $no) {
            $isDuplicado = in_array($no, $existentesEnEstaActividad) || in_array($no, $existentesEnOtraActividadDelMismoSemestre);
            $duplicados[$no] = $isDuplicado;
        }

        return response()->json(['duplicados' => $duplicados]);
    }

    /**
     * Importa estudiantes en masa para una actividad (desde el panel del coordinador).
     * Recibe JSON: { students: [...], id_unidad, id_semestre }
     */
    public function import($actividadId, Request $request)
    {
        // Dump request for debug (temporal)
        try {
            $dumpPath = storage_path('logs/import_debug.log');
            $payload = $request->all();
            @file_put_contents($dumpPath, "\n----- IMPORT START " . date('Y-m-d H:i:s') . " -----\n", FILE_APPEND);
            @file_put_contents($dumpPath, "ActividadId: {$actividadId}\n", FILE_APPEND);
            @file_put_contents($dumpPath, "Payload: " . json_encode($payload, JSON_UNESCAPED_UNICODE) . "\n", FILE_APPEND);
        } catch (\Throwable $ex) {
            // ignore
        }

        $data = $request->all();
        $students = $data['students'] ?? [];
        $id_unidad = $data['id_unidad'] ?? null;
        $id_semestre = $data['id_semestre'] ?? null;

        // Normalizar: si viene '0' o 0 en id_unidad tratar como null (evita violación de FK a unidades)
        // Pero preservar números válidos > 0
        if ($id_unidad === '0' || $id_unidad === 0) {
            $id_unidad = null;
        } elseif (is_string($id_unidad) && is_numeric($id_unidad)) {
            // Si viene como string numérico, convertir a int
            $id_unidad = (int)$id_unidad;
        }
        
        // Normalizar id_semestre numérico si viene como string
        if (is_string($id_semestre) && ctype_digit($id_semestre)) {
            $id_semestre = (int)$id_semestre;
        }

        if (!is_array($students) || count($students) === 0) {
            return response()->json(['message' => 'No hay estudiantes para importar'], 422);
        }

        $inserted = 0;
        $skipped = 0;
        $errors = [];

        DB::beginTransaction();
        try {
            foreach ($students as $i => $st) {
                $no = isset($st['No_control']) ? $st['No_control'] : (isset($st['numero_control']) ? $st['numero_control'] : (isset($st['Control']) ? $st['Control'] : ''));
                $no = is_null($no) ? '' : trim((string)$no);
                $nombre = $st['Nombre'] ?? $st['nombre'] ?? '';
                $carrera = $st['Carrera'] ?? $st['carrera'] ?? '';
                $sem = $st['Semestre'] ?? $st['semestre'] ?? null;

                if (!$no) {
                    $skipped++;
                    $errors[] = ['row' => $i+1, 'reason' => 'Número de control vacío'];
                    continue;
                }

                // Verificar si el número de control ya existe EN ESTA ACTIVIDAD (duplicado en misma actividad)
                $existeEnEstaActividad = Estudiante::where('numero_control', $no)
                    ->where('id_actividad', $actividadId)
                    ->first();
                
                if ($existeEnEstaActividad) {
                    $skipped++;
                    try { @file_put_contents(storage_path('logs/import_debug.log'), "SKIPPED ROW " . ($i+1) . ": numero_control {$no} already exists in activity {$actividadId}.\n", FILE_APPEND); } catch (\Throwable $__) {}
                    continue;
                }

                // Verificar si el estudiante ya existe en OTRA actividad del MISMO semestre
                // Si es así, no permitir (estudiante no puede estar en 2 actividades del mismo semestre)
                if ($id_semestre) {
                    $existeEnOtraActividadDelMismoSemestre = Estudiante::where('numero_control', $no)
                        ->where('id_semestre', $id_semestre)
                        ->where('id_actividad', '!=', $actividadId)
                        ->first();
                    
                    if ($existeEnOtraActividadDelMismoSemestre) {
                        $skipped++;
                        $errors[] = ['row' => $i+1, 'reason' => "Estudiante ya está en otra actividad de este semestre"];
                        try { @file_put_contents(storage_path('logs/import_debug.log'), "SKIPPED ROW " . ($i+1) . ": numero_control {$no} already exists in another activity of semestre {$id_semestre}.\n", FILE_APPEND); } catch (\Throwable $__) {}
                        continue;
                    }
                }

                try {
                    Estudiante::create([
                        'nombre' => $nombre,
                        'numero_control' => $no,
                        'carrera' => $carrera,
                        'semestre' => $sem,
                        'id_actividad' => $actividadId,
                        'id_unidad' => $id_unidad,
                        'id_semestre' => $id_semestre
                    ]);
                    $inserted++;
                } catch (\Exception $ex) {
                    // Si falla por UNIQUE constraint en numero_control, verificar si es porque existe en otra actividad
                    if (strpos($ex->getMessage(), 'UNIQUE') !== false || strpos($ex->getMessage(), 'numero_control') !== false) {
                        // El número de control ya existe en la BD pero en otra actividad/semestre
                        // Obtener el registro existente
                        $existing = Estudiante::where('numero_control', $no)->first();
                        
                        if ($existing) {
                            // Verificar si es la misma actividad (error real)
                            if ($existing->id_actividad == $actividadId) {
                                $skipped++;
                                try { @file_put_contents(storage_path('logs/import_debug.log'), "SKIPPED ROW " . ($i+1) . ": numero_control {$no} already exists in this activity.\n", FILE_APPEND); } catch (\Throwable $__) {}
                            } else {
                                // Es en otra actividad del mismo semestre - también es error
                                $skipped++;
                                $errors[] = ['row' => $i+1, 'reason' => "Estudiante ya está en otra actividad"];
                                try { @file_put_contents(storage_path('logs/import_debug.log'), "SKIPPED ROW " . ($i+1) . ": numero_control {$no} exists in another activity.\n", FILE_APPEND); } catch (\Throwable $__) {}
                            }
                        } else {
                            // No debería pasar esto
                            $skipped++;
                            $errors[] = ['row' => $i+1, 'reason' => $ex->getMessage()];
                        }
                    } else {
                        $skipped++;
                        $errors[] = ['row' => $i+1, 'reason' => $ex->getMessage()];
                        try { @file_put_contents(storage_path('logs/import_debug.log'), "INSERT ERROR ROW " . ($i+1) . ": " . $ex->getMessage() . "\n" . $ex->getTraceAsString() . "\n", FILE_APPEND); } catch (\Throwable $__) {}
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            // Log exception to debug file
            try { @file_put_contents(storage_path('logs/import_debug.log'), "EXCEPTION: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n", FILE_APPEND); } catch (\Throwable $__) {}
            return response()->json(['message' => 'Error durante la importación', 'error' => $e->getMessage()], 500);
        }

        // Footer debug
        try { @file_put_contents(storage_path('logs/import_debug.log'), "----- IMPORT END " . date('Y-m-d H:i:s') . " -----\n", FILE_APPEND); } catch (\Throwable $__) {}

        return response()->json([
            'inserted' => $inserted,
            'skipped' => $skipped,
            'errors' => $errors
        ], 200);
    }
}
