<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Estudiante;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class EstudianteController extends Controller
{
    public function index($actividadId, Request $request)
    {
        $items = Estudiante::where('id_actividad', $actividadId)
            ->orderByDesc('id_alumno')
            ->get();

        return response()->json($items, 200);
    }

    public function store($actividadId, Request $request)
    {
        $v = Validator::make($request->all(), [
            'nombre' => 'nullable|string|max:255',
            'numero_control' => [
                'required',
                'string',
                'max:20',
                Rule::unique('estudiantes', 'numero_control')->where(fn ($q) => $q->where('id_actividad', $actividadId)),
            ],
            'carrera' => 'nullable|string|max:100',
            'semestre' => 'nullable|integer',
            'id_unidad' => 'nullable|integer',
            'id_semestre' => 'nullable|integer',
        ]);

        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()], 422);
        }

        $est = Estudiante::create([
            'nombre' => $request->input('nombre'),
            'numero_control' => $request->input('numero_control'),
            'carrera' => $request->input('carrera'),
            'semestre' => $request->input('semestre'),
            'id_actividad' => $actividadId,
            'id_unidad' => $request->input('id_unidad'),
            'id_semestre' => $request->input('id_semestre'),
        ]);

        return response()->json($est, 201);
    }

    public function update(Request $request, $id)
    {
        $est = Estudiante::find($id);
        if (! $est) {
            return response()->json(['message' => 'Estudiante no encontrado'], 404);
        }

        $reqActividad = $request->input('id_actividad');
        if ($reqActividad !== null && intval($reqActividad) !== intval($est->id_actividad)) {
            return response()->json(['message' => 'Operación no permitida: estudiante no pertenece a la actividad'], 403);
        }

        $v = Validator::make($request->all(), [
            'nombre' => 'sometimes|nullable|string|max:255',
            'numero_control' => [
                'sometimes',
                'required',
                'string',
                'max:20',
                Rule::unique('estudiantes', 'numero_control')
                    ->where(fn ($q) => $q->where('id_actividad', $est->id_actividad))
                    ->ignore($est->id_alumno, 'id_alumno'),
            ],
            'carrera' => 'nullable|string|max:100',
            'semestre' => 'nullable|integer',
            'id_unidad' => 'nullable|integer',
            'id_semestre' => 'nullable|integer',
        ]);

        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()], 422);
        }

        $est->fill($request->only(['nombre', 'numero_control', 'carrera', 'semestre', 'id_unidad', 'id_semestre']));
        $est->save();

        return response()->json($est, 200);
    }

    public function destroy(Request $request, $id)
    {
        $est = Estudiante::find($id);
        if (! $est) {
            return response()->json(['message' => 'Estudiante no encontrado'], 404);
        }

        $reqActividad = $request->query('id_actividad');
        if ($reqActividad !== null && intval($reqActividad) !== intval($est->id_actividad)) {
            return response()->json(['message' => 'Operación no permitida: estudiante no pertenece a la actividad'], 403);
        }

        $est->delete();

        return response()->json(['success' => true], 200);
    }
}
