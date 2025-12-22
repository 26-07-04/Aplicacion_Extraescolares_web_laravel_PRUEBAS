<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Semestre;

class SemestresCursadosUnionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user && ($user->rol ?? '') === 'Coordinador') {
            $ua = $user->unidad_academica ?? '';
            if (stripos($ua, 'Unión') === false && stripos($ua, 'Union') === false && stripos($ua, 'Hidalgo') === false) {
                abort(403);
            }
        }
        // Si no hay usuario o no es coordinador, permitir acceso (público o admin)

        $semestres = Semestre::orderBy('fecha_inicio', 'desc')->get();
        return view('coordinador.union_hidalgo.semestres_cursados', ['user' => $user, 'semestres' => $semestres, 'unidad' => 'Unidad Académica Unión Hidalgo']);
    }
}
