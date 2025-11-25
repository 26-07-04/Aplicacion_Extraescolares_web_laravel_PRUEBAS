<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unidad;

class UnidadController extends Controller
{
    // PANEL PARA ADMINISTRADORES (todas las unidades)
    public function index()
    {
        $unidades = Unidad::all();
        return view('unidades.admin_index', compact('unidades'));
    }

    // PANEL PARA COORDINADORES (solo su unidad)
 public function miUnidad(Request $request)
{
    $user = $request->user();

    if ($user->rol !== 'Coordinador') {
        abort(403);
    }

    if (!$user->unidad_id) {
        abort(403, 'No tienes una unidad asignada.');
    }

    // Seleccionar panel correspondiente
    $vista = match($user->unidad_id) {
        1 => 'unidades.demetriovallejo',
        2 => 'unidades.unionhidalgo',
        3 => 'unidades.tlahutitoltepec',
        4 => 'unidades.valledeetla',
        default => abort(404, 'Unidad no encontrada'),
    };

    return view($vista);
}

}
