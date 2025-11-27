<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Semestre;

class PanelValleEtlaController extends Controller
{
    public function show($id)
    {
        $user = Auth::user();
        if (!$user || ($user->rol ?? '') !== 'Coordinador') {
            abort(403);
        }

        $ua = $user->unidad_academica ?? '';
        if (stripos($ua, 'Valle') === false && stripos($ua, 'Etla') === false) {
            abort(403);
        }

        $semestre = Semestre::find($id);
        if (!$semestre) {
            abort(404);
        }

        return view('coordinador.valle_de_etla.panel', ['user' => $user, 'semestre' => $semestre, 'unidad' => 'Unidad Académica Valle de Etla']);
    }
}
