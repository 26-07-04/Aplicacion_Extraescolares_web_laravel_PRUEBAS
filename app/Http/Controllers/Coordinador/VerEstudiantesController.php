<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class VerEstudiantesController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        // Determine unidad to filter if provided or use current user's unidad
        $unidad = $request->query('unidad', $user->unidad_academica ?? null);

        // If the logged-in user is a Coordinador, ensure they cannot request students
        // from a different unidad by passing a different query parameter. In that case
        // default to their own unidad.
        if (($user->rol ?? '') === 'Coordinador' && $unidad) {
            $requested = strtolower($unidad);
            $userUnidad = strtolower($user->unidad_academica ?? '');
            if ($userUnidad && strpos($requested, $userUnidad) === false && strpos($userUnidad, $requested) === false) {
                // fallback to the authenticated user's unidad
                $unidad = $user->unidad_academica ?? $unidad;
            }
        }

        // Basic student list: users with role 'Estudiante' or all users for now
        $estudiantes = User::query()
            ->when($unidad, function($q, $unidad) {
                return $q->where('unidad_academica', 'like', "%{$unidad}%");
            })
            ->get();

        return view('coordinador.verestudiantes', ['estudiantes' => $estudiantes, 'unidad' => $unidad, 'user' => $user]);
    }

    public function store(Request $request)
    {
        try {
            // Validar los datos del formulario
            $validated = $request->validate([
                'numero_control' => 'required|string|unique:users,numero_control|max:20',
                'nombre' => 'required|string|max:255',
                'carrera' => 'required|string|max:255',
                'semestre' => 'required|integer|min:1|max:12',
                'actividad_extraescolar' => 'required|string|max:255',
                'contacto' => 'nullable|string|max:100',
                'unidad_academica' => 'required|string|max:255',
            ]);

            // Crear el nuevo estudiante
            $estudiante = User::create([
                'numero_control' => $validated['numero_control'],
                'nombre' => $validated['nombre'],
                'carrera' => $validated['carrera'],
                'semestre' => $validated['semestre'],
                'actividad_extraescolar' => $validated['actividad_extraescolar'],
                'contacto' => $validated['contacto'] ?? null,
                'unidad_academica' => $validated['unidad_academica'],
                'rol' => 'Estudiante',
                'email' => $validated['numero_control'] . '@estudiante.tecnm.mx', // Email temporal
                'password' => bcrypt($validated['numero_control']), // Password temporal
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Estudiante agregado exitosamente',
                'estudiante' => $estudiante
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar el estudiante: ' . $e->getMessage()
            ], 500);
        }
    }
}
