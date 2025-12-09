<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Models\Semestre;


class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function show($id)
    {
        $user = User::find($id);
        if (! $user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }
        return response()->json($user);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * Actualiza un usuario (por id_usuario).
     */
    public function update(Request $request, $id)
    {
        $usuario = User::find($id);
        if (! $usuario) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $validated = $request->validate([
            'nombre' => [
                'required',
                'string',
                Rule::unique('usuarios', 'nombre')->ignore($usuario->id_usuario, 'id_usuario'),
            ],
            'unidad_academica' => 'required|string',
           // 'contacto' => 'required|string',
            'contrasena_texto' => 'required|string',
            'rol' => 'required|string',
        ]);

        // Re-hashear la contraseña si se proporciona contrasena_texto
        if (isset($validated['contrasena_texto'])) {
            $validated['contrasena'] = Hash::make($validated['contrasena_texto']);
        }

        $usuario->update($validated);
        return response()->json($usuario);
    }

    /**
     * Devuelve los usuarios asociados a un semestre (por id de semestre).
     */
    public function usuariosPorSemestre($id)
    {
        $semestre = Semestre::find($id);
        if (! $semestre) {
            return response()->json(['message' => 'Semestre no encontrado'], 404);
        }

        $usuarios = User::where('id_semestre', $id)->get();

        return response()->json([
            'semestre' => $semestre,
            'usuarios' => $usuarios,
        ]);
    }
}
