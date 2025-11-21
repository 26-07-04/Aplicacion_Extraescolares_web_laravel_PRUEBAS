<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Semestre;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;

class SemestresCursadosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Pasar el usuario autenticado a la vista para mostrar nombre en el menú
        $user = Auth::user();
        $semestres = Semestre::orderBy('fecha_inicio', 'desc')->get();
        return view('administrador.semestres_cursados', ['user' => $user, 'semestres' => $semestres]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after_or_equal:fecha_inicio'],
        ]);

        try {
            $semestre = Semestre::create([
                'nombre' => $data['nombre'],
                'fecha_inicio' => $data['fecha_inicio'],
                'fecha_fin' => $data['fecha_fin'],
            ]);

            return response()->json(['success' => true, 'semestre' => $semestre], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al guardar semestre'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $semestre = Semestre::find($id);
        if (!$semestre) {
            return response()->json(['success' => false, 'message' => 'Semestre no encontrado'], 404);
        }

        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after_or_equal:fecha_inicio'],
        ]);

        try {
            $semestre->nombre = $data['nombre'];
            $semestre->fecha_inicio = $data['fecha_inicio'];
            $semestre->fecha_fin = $data['fecha_fin'];
            $semestre->save();

            return response()->json(['success' => true, 'semestre' => $semestre]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al actualizar semestre'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $semestre = Semestre::find($id);
        if (!$semestre) {
            return response()->json(['success' => false, 'message' => 'Semestre no encontrado'], 404);
        }

        try {
            $semestre->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al eliminar semestre'], 500);
        }
    }
}
