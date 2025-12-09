<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'usuario' => 'required|string',
            'contrasena' => 'required|string',
        ]);

        $identifier = $request->input('usuario');

        $user = User::where('nombre', $identifier)->first();

        if (! $user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $inputPassword = $request->input('contrasena');
        $storedPassword = $user->contrasena;

        $isHashed = is_string($storedPassword) && (
            str_starts_with($storedPassword, '$2y$') ||
            str_starts_with($storedPassword, '$2a$') ||
            str_starts_with($storedPassword, '$argon2')
        );

        $passwordVerified = false;

        if ($isHashed) {
            $passwordVerified = Hash::check($inputPassword, $storedPassword);
        } else {
            if (is_string($storedPassword) && hash_equals($storedPassword, $inputPassword)) {
                $passwordVerified = true;
                try {
                    $user->contrasena = Hash::make($inputPassword);
                    $user->save();
                } catch (\Throwable $e) {
                    // no bloquear login si rehash falla
                }
            }
        }

        if (! $passwordVerified) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        $token = $user->createToken('mobile-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        if ($user && $request->user()->currentAccessToken()) {
            $request->user()->currentAccessToken()->delete();
        }

        return response()->json(['message' => 'Sesión cerrada']);
    }
}
