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
}
