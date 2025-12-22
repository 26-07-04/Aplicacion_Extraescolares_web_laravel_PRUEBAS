<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Semestre;

class SemestresCursadosValleController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user && ($user->rol ?? '') === 'Coordinador') {
            // Aseguramos que el coordinador pertenece a la unidad esperada (flexible con substrings)
            $ua = $user->unidad_academica ?? '';
            if (stripos($ua, 'Valle') === false && stripos($ua, 'Etla') === false) {
                abort(403);
            }
        }
        // Si no hay usuario o no es coordinador, permitir acceso (público o admin)

        $semestres = Semestre::orderBy('fecha_inicio', 'desc')->get();
        return view('coordinador.valle_de_etla.semestres_cursados', ['user' => $user, 'semestres' => $semestres, 'unidad' => 'Unidad Académica Valle de Etla']);
    }
}
