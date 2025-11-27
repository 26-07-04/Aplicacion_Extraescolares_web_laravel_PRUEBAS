<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InformeController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $unidad = $request->query('unidad', $user->unidad_academica ?? null);

        // Ensure coordinators cannot view other units
        if (($user->rol ?? '') === 'Coordinador') {
            $userUnidad = strtolower($user->unidad_academica ?? '');
            $requested = strtolower($unidad ?? '');
            if ($userUnidad && $requested && strpos($requested, $userUnidad) === false && strpos($userUnidad, $requested) === false) {
                $unidad = $user->unidad_academica ?? $unidad;
            }
        }

        return view('coordinador.informe', ['unidad' => $unidad, 'user' => $user]);
    }
}
