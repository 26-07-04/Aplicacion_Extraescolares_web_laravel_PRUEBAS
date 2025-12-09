<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Estudiante;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class EstudianteController extends Controller
{
    /**
     * Actualiza un estudiante por id (id_alumno).
     * Se pueden enviar sólo los campos a actualizar.
     */
    public function update(Request $request, $id)
    {
        // Busca el estudiante por su ID
        $estudiante = Estudiante::find($id);

        if (! $estudiante) {
            return response()->json(['message' => 'Estudiante no encontrado'], 404);
        }

        // Si se envía id_actividad, validar que coincida con la actividad del estudiante
        $reqActividad = $request->input('id_actividad');
        if ($reqActividad !== null && intval($reqActividad) !== intval($estudiante->id_actividad)) {
            return response()->json(['message' => 'Operación no permitida: estudiante no pertenece a la actividad'], 403);
        }

        // Loguear valores para depuración: id, numero_control actual y entrante
        try {
            Log::info('Estudiante actualización: comprobando unicidad', [
                'id_alumno' => $estudiante->id_alumno,
                'numero_control_actual' => $estudiante->numero_control,
                'numero_control_entrante' => $request->input('numero_control'),
            ]);
        } catch (\Throwable $e) {
            // No bloquear en caso de fallo de logging
        }

        // Construir reglas para numero_control: si el valor entrante es igual
        // al actual del registro, evitamos la regla unique (evita falso positivo).
        $incomingNumero = $request->input('numero_control');

        if ($incomingNumero !== null && $incomingNumero === $estudiante->numero_control) {
            $numeroRules = ['sometimes', 'required', 'string', 'max:20'];
        } else {
            $numeroRules = [
                'sometimes',
                'required',
                'string',
                'max:20',
                Rule::unique('estudiantes', 'numero_control')->ignore($estudiante->id_alumno, 'id_alumno'),
            ];
        }

        // Validar los datos recibidos
        $validatedData = $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'carrera' => 'sometimes|required|string|max:255',
            'semestre' => 'sometimes|required|string',
            'numero_control' => $numeroRules,
        ]);

        // Actualiza el estudiante con los datos validados
        $estudiante->update($validatedData);

        return response()->json($estudiante);
    }
}
