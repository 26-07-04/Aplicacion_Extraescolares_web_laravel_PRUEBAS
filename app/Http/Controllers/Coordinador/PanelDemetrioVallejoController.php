<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Semestre;
use App\Models\Actividad;
use App\Models\Unidad;

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

        $documentos = \App\Models\Documento::orderBy('created_at', 'desc')->get();

        // Filtrar actividades por semestre y por la unidad académica del usuario
        // Nota: la tabla `unidades` no tiene columna `id_semestre`, por eso usamos whereHas para filtrar
        $uaName = $user->unidad_academica ?? '';

        // Intentar usar unidad_id si existe (más fiable)
        $user_unidad_id = $user->unidad_id ?? $user->unidad ?? null;

        // Derivar palabra clave a buscar en `unidades.nombre_unidad`
        $uaKeyword = null;
        $candidates = ['Demetrio','Vallejo','Unión','Union','Valle','Etla','Tlahuitoltepec','Tlahui','Santa','Espinal'];
        foreach ($candidates as $cand) {
            if (!empty($uaName) && stripos($uaName, $cand) !== false) {
                $uaKeyword = $cand;
                break;
            }
        }

        // Todas las actividades del semestre (sin filtrar por unidad) — útil para depuración
        $actividades_semestre = Actividad::with('unidad')
            ->where('id_semestre', $semestre->id_semestre)
            ->orderBy('created_at', 'desc')
            ->get();

        // Actividades filtradas por la unidad académica del usuario
        $actividadesQuery = Actividad::with('unidad')
            ->where('id_semestre', $semestre->id_semestre);

        if (!empty($user_unidad_id)) {
            $actividadesQuery->where('id_unidad', $user_unidad_id);
        } elseif (!empty($uaKeyword)) {
            $actividadesQuery->whereHas('unidad', function ($q) use ($uaKeyword) {
                $q->where('nombre_unidad', 'like', '%' . $uaKeyword . '%');
            });
        } else {
            // Si no se detecta nada, intentar con 'Demetrio' por compatibilidad
            $actividadesQuery->whereHas('unidad', function ($q) {
                $q->where('nombre_unidad', 'like', '%Demetrio%');
            });
        }

        $actividades = $actividadesQuery->orderBy('created_at', 'desc')->get();

        // Pasar también los conjuntos para diagnóstico en la vista
        $count_semestre = $actividades_semestre->count();
        $count_filtradas = $actividades->count();

        // Preparar detalles para debug: incluir nombre de unidad asociado a cada actividad
        $actividades_semestre_data = $actividades_semestre->map(function ($a) {
            return [
                'id_actividad' => $a->id_actividad,
                'nombre_actividad' => $a->nombre_actividad,
                'id_unidad' => $a->id_unidad,
                'id_semestre' => $a->id_semestre,
                'unidad_nombre' => $a->unidad->nombre_unidad ?? null,
            ];
        })->toArray();

        return view('coordinador.demetrio_vallejo.panel', [
            'user' => $user,
            'semestre' => $semestre,
            'unidad' => 'Unidad Académica Demetrio Vallejo Martínez - El Espinal',
            'documentos' => $documentos,
            'actividades' => $actividades,
            'actividades_semestre' => $actividades_semestre,
            'actividades_semestre_data' => $actividades_semestre_data,
            'count_semestre' => $count_semestre,
            'count_filtradas' => $count_filtradas,
            'uaName' => $uaName,
            'uaKeyword' => $uaKeyword,
            'user_unidad_id' => $user_unidad_id,
        ]);
    }
}
