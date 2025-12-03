<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Semestre;
use App\Models\Actividad;
use App\Models\Unidad;

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

        $documentos = \App\Models\Documento::where('id_semestre', $id)->orderBy('created_at', 'desc')->get();
        $documentos = \App\Models\Documento::orderBy('created_at', 'desc')->get();

        // Filtrar actividades por semestre y por la unidad académica del usuario
        $uaName = $user->unidad_academica ?? '';
        $user_unidad_id = $user->unidad_id ?? $user->unidad ?? null;

        $candidates = ['Tlahuitoltepec','Tlahui','Santa','Tlahuitoltepec','TlahuiToltepec'];
        $uaKeyword = null;
        foreach ($candidates as $cand) {
            if (!empty($uaName) && stripos($uaName, $cand) !== false) {
                $uaKeyword = $cand;
                break;
            }
        }

        $actividadesQuery = Actividad::with('unidad')->where('id_semestre', $semestre->id_semestre);
        if (!empty($user_unidad_id)) {
            $actividadesQuery->where('id_unidad', $user_unidad_id);
        } elseif (!empty($uaKeyword)) {
            $actividadesQuery->whereHas('unidad', function ($q) use ($uaKeyword) {
                $q->where('nombre_unidad', 'like', '%' . $uaKeyword . '%');
            });
        } else {
            $actividadesQuery->whereHas('unidad', function ($q) {
                $q->where('nombre_unidad', 'like', '%Tlahui%');
            });
        }

        $actividades = $actividadesQuery->orderBy('created_at', 'desc')->get();

        return view('coordinador.tlahuitoltepec.panel', [
            'user' => $user,
            'semestre' => $semestre,
            'unidad' => 'Unidad Académica Santa María Tlahuitoltepec',
            'documentos' => $documentos,
            'actividades' => $actividades,
        ]);
    }
}
