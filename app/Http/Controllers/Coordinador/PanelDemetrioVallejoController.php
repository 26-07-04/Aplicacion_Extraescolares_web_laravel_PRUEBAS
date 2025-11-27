<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Semestre;

class PanelDemetrioVallejoController extends Controller
{
    public function show($id)
    {
        $user = Auth::user();
        if (!$user || ($user->rol ?? '') !== 'Coordinador') {
            abort(403);
        }

        $ua = $user->unidad_academica ?? '';
        if (stripos($ua, 'Demetrio') === false && stripos($ua, 'Vallejo') === false && stripos($ua, 'Espinal') === false) {
            abort(403);
        }

        $semestre = Semestre::find($id);
        if (!$semestre) {
            abort(404);
        }

        return view('coordinador.demetrio_vallejo.panel', ['user' => $user, 'semestre' => $semestre, 'unidad' => 'Unidad Académica Demetrio Vallejo Martínez - El Espinal']);
    }
}
