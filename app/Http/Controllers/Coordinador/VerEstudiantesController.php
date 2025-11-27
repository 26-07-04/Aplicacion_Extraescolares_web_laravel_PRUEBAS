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

        // Basic student list: users with role 'Estudiante' or all users for now
        $estudiantes = User::query()
            ->when($unidad, function($q, $unidad) {
                return $q->where('unidad_academica', 'like', "%{$unidad}%");
            })
            ->get();

        return view('coordinador.verestudiantes', ['estudiantes' => $estudiantes, 'unidad' => $unidad, 'user' => $user]);
    }
}
