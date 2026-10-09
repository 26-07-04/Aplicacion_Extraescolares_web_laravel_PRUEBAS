<?php

namespace App\Http\Controllers\Coordinador\Concerns;

use App\Models\Actividad;
use App\Models\Estudiante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

trait GestionaEstudiantesActividad
{
    public function listarEstudiantesDeActividad(Request $request, int $actividadId)
    {
        $this->autorizarCoordinadorFormato(Auth::user());

        $datos = $request->validate([
            'id_semestre' => 'required|integer|exists:semestres,id_semestre',
        ]);

        $actividad = $this->actividadAutorizadaParaEditar($actividadId, (int) $datos['id_semestre']);
        $estudiantes = Estudiante::where('id_actividad', $actividad->id_actividad)
            ->orderBy('nombre')
            ->get(['id_alumno', 'nombre', 'numero_control', 'carrera', 'sexo', 'semestre']);

        return response()->json(['estudiantes' => $estudiantes]);
    }

    public function actualizarEstudiantesDeActividad(Request $request, int $actividadId)
    {
        $this->autorizarCoordinadorFormato(Auth::user());

        $datos = $request->validate([
            'id_semestre' => 'required|integer|exists:semestres,id_semestre',
            'estudiantes' => 'required|array|min:1',
            'estudiantes.*.id_alumno' => 'required|integer|distinct',
            'estudiantes.*.nombre' => 'required|string|max:255',
            'estudiantes.*.numero_control' => 'required|string|max:20',
            'estudiantes.*.carrera' => 'nullable|string|max:100',
            'estudiantes.*.sexo' => 'nullable|string|max:20',
            'estudiantes.*.semestre' => 'nullable|integer|min:1|max:12',
        ]);

        $actividad = $this->actividadAutorizadaParaEditar($actividadId, (int) $datos['id_semestre']);
        $estudiantes = collect($datos['estudiantes']);
        $ids = $estudiantes->pluck('id_alumno')->map(fn ($id) => (int) $id);

        $enActividad = Estudiante::where('id_actividad', $actividad->id_actividad)
            ->whereIn('id_alumno', $ids)
            ->count();
        if ($enActividad !== $estudiantes->count()) {
            abort(422, 'La lista contiene alumnos que no pertenecen a esta actividad.');
        }

        $controlesNormalizados = $estudiantes->map(fn ($estudiante) => mb_strtolower(trim($estudiante['numero_control']), 'UTF-8'));
        if ($controlesNormalizados->count() !== $controlesNormalizados->unique()->count()) {
            throw ValidationException::withMessages([
                'estudiantes' => 'Hay números de control repetidos en la lista.',
            ]);
        }

        foreach ($estudiantes as $estudiante) {
            $duplicado = Estudiante::where('id_actividad', $actividad->id_actividad)
                ->where('numero_control', trim($estudiante['numero_control']))
                ->where('id_alumno', '!=', $estudiante['id_alumno'])
                ->exists();

            if ($duplicado) {
                throw ValidationException::withMessages([
                    'estudiantes' => 'El número de control ' . trim($estudiante['numero_control']) . ' ya existe en esta actividad.',
                ]);
            }
        }

        DB::transaction(function () use ($estudiantes) {
            foreach ($estudiantes as $estudiante) {
                Estudiante::where('id_alumno', $estudiante['id_alumno'])->update([
                    'nombre' => trim($estudiante['nombre']),
                    'numero_control' => trim($estudiante['numero_control']),
                    'carrera' => trim((string) ($estudiante['carrera'] ?? '')) ?: null,
                    'sexo' => trim((string) ($estudiante['sexo'] ?? '')) ?: null,
                    'semestre' => $estudiante['semestre'] ?? null,
                    'updated_at' => now(),
                ]);
            }
        });

        return response()->json([
            'success' => true,
            'actualizados' => $estudiantes->count(),
        ]);
    }

    private function actividadAutorizadaParaEditar(int $actividadId, int $semestreId): Actividad
    {
        return Actividad::where('id_actividad', $actividadId)
            ->where('id_semestre', $semestreId)
            ->whereIn('id_unidad', $this->idsUnidadPanel())
            ->delTipoPrograma($this->panelTipoPrograma())
            ->firstOrFail();
    }
}
