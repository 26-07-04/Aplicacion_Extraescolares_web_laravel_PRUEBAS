<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Semestre;

class PanelTlahuitoltepecController extends Controller
{
    public function show($id)
    {
        $user = Auth::user();
        if (!$user || ($user->rol ?? '') !== 'Coordinador') {
            abort(403);
        }

        $ua = $user->unidad_academica ?? '';
        // Aceptar variantes de nombre para Tlahuitoltepec
        if (stripos($ua, 'Tlahui') === false && stripos($ua, 'TLAHUI') === false) {
            abort(403);
        }

        $semestre = Semestre::find($id);
        if (!$semestre) {
            abort(404);
        }

        return view('coordinador.tlahuitoltepec.panel', ['user' => $user, 'semestre' => $semestre, 'unidad' => 'Unidad Académica Santa María Tlahuitoltepec']);
    }
}
