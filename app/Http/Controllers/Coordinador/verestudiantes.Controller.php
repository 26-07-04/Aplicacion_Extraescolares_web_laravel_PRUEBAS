<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    /**
     * Obtener información del usuario autenticado
     */
    public function obtenerUsuario()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'error' => 'Usuario no autenticado'
                ], 401);
            }

            return response()->json([
                'nombre' => $user->name,
                'unidad_academica' => $user->unidad_academica,
                'email' => $user->email
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener información del usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener lista de estudiantes
     */
    public function obtenerEstudiantes(Request $request)
    {
        try {
            $user = Auth::user();
            $unidad = $request->get('unidad', $user->unidad_academica ?? '');

            $query = DB::table('estudiantes')
                ->select(
                    'id_alumno',
                    'numero_control',
                    'nombre',
                    'carrera',
                    'extraescolar',
                    'semestre',
                    'unidad_academica'
                );

            // Filtrar por unidad académica si está especificada
            if (!empty($unidad)) {
                $query->where('unidad_academica', $unidad);
            }

            $estudiantes = $query->get();

            return response()->json($estudiantes);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener estudiantes: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar información de estudiante
     */
    public function actualizarEstudiante(Request $request)
    {
        try {
            $user = Auth::user();
            
            $validated = $request->validate([
                'id_alumno' => 'required|integer',
                'nombre' => 'required|string|max:255',
                'numero_control' => 'required|string|max:50',
                'carrera' => 'required|string|max:255',
                'semestre' => 'required|string|max:50',
                'unidad_academica' => 'sometimes|string|max:255'
            ]);

            // Verificar permisos de unidad académica
            if (!empty($user->unidad_academica) && $user->unidad_academica !== $validated['unidad_academica']) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tiene permisos para modificar estudiantes de esta unidad académica'
                ], 403);
            }

            $actualizado = DB::table('estudiantes')
                ->where('id_alumno', $validated['id_alumno'])
                ->update([
                    'nombre' => $validated['nombre'],
                    'numero_control' => $validated['numero_control'],
                    'carrera' => $validated['carrera'],
                    'semestre' => $validated['semestre'],
                    'updated_at' => now()
                ]);

            if ($actualizado) {
                return response()->json([
                    'success' => true,
                    'message' => 'Estudiante actualizado correctamente'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pudo actualizar el estudiante'
                ], 400);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación: ' . $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar estudiante: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cerrar sesión
     */
    public function logout()
    {
        try {
            Auth::logout();
            session()->flush();
            
            return response()->json([
                'success' => true,
                'message' => 'Sesión cerrada correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cerrar sesión: ' . $e->getMessage()
            ], 500);
        }
    }
}