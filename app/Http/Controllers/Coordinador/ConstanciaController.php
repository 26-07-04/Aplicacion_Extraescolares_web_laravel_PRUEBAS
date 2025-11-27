<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConstanciaController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        // Determine unidad to show; prefer query param if given, otherwise use the user's unidad
        $unidad = $request->query('unidad', $user->unidad_academica ?? null);

        // For coordinators, ensure they can't view other units — fallback to their unidad
        if (($user->rol ?? '') === 'Coordinador') {
            $userUnidad = strtolower($user->unidad_academica ?? '');
            $requested = strtolower($unidad ?? '');
            if ($userUnidad && $requested && strpos($requested, $userUnidad) === false && strpos($userUnidad, $requested) === false) {
                $unidad = $user->unidad_academica ?? $unidad;
            }
        }

        // Any data for constancias would be loaded here; for now we just pass unit & user
        return view('coordinador.constancia', ['unidad' => $unidad, 'user' => $user]);
    }
}
