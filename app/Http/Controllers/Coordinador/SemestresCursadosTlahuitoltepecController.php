<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Semestre;

class SemestresCursadosTlahuitoltepecController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user || ($user->rol ?? '') !== 'Coordinador') {
            abort(403);
        }

        $ua = $user->unidad_academica ?? '';
        if (stripos($ua, 'Tlahuitoltepec') === false) {
            abort(403);
        }

        $semestres = Semestre::orderBy('fecha_inicio', 'desc')->get();
        return view('coordinador.tlahuitoltepec.semestres_cursados', ['user' => $user, 'semestres' => $semestres]);
    }
}
