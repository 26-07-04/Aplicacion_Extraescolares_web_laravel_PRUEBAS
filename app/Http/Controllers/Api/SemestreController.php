<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Semestre;

class SemestreController extends Controller
{
    /**
     * Devuelve el semestre activo (estatus = true) en formato JSON.
     */
    public function activo()
    {
        $semestre = Semestre::where('estatus', true)->first();
        if (! $semestre) {
            return response()->json(['message' => 'No hay semestre activo'], 404);
        }
        return response()->json($semestre);
    }
}
