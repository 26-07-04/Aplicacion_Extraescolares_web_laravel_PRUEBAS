<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Semestre;

class SemestresCursadosDemetrioController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user && ($user->rol ?? '') === 'Coordinador') {
            $ua = $user->unidad_academica ?? '';
            if (stripos($ua, 'Demetrio') === false && stripos($ua, 'Vallejo') === false) {
                abort(403);
            }
        }
        // Si no hay usuario o no es coordinador, permitir acceso (público o admin)

        $semestres = Semestre::orderBy('fecha_inicio', 'desc')->get();
        return view('coordinador.demetrio_vallejo.semestres_cursados', ['user' => $user, 'semestres' => $semestres, 'unidad' => 'Unidad Académica Demetrio Vallejo']);
    }
}
