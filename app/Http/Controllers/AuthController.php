<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'usuario' => 'required|string',
            'contrasena' => 'required|string',
        ]);

        $user = User::where('nombre', $request->input('usuario'))->first();

        if (!$user) {
            return back()->withErrors(['usuario' => 'Usuario no encontrado'])->withInput();
        }

        $inputPassword = $request->input('contrasena');
        $storedPassword = $user->contrasena;

        $passwordVerified = false;

        // Detectar si la contraseña almacenada parece ser bcrypt/argon2 (hash moderno)
        $isHashed = is_string($storedPassword) && (
            str_starts_with($storedPassword, '$2y$') ||
            str_starts_with($storedPassword, '$2a$') ||
            str_starts_with($storedPassword, '$argon2')
        );

        if ($isHashed) {
            // Usar Hash::check de Laravel (puede lanzar excepción si hash no es válido para el driver)
            $passwordVerified = Hash::check($inputPassword, $storedPassword);
        } else {
            // Fallback: comparar como texto plano (uso hash_equals para evitar timing attacks)
            if (is_string($storedPassword) && hash_equals($storedPassword, $inputPassword)) {
                $passwordVerified = true;
                // Rehashear la contraseña y guardar para mejorar seguridad
                try {
                    $user->contrasena = Hash::make($inputPassword);
                    $user->save();
                } catch (\Throwable $e) {
                    // No bloquear el login si el rehash falla; sólo registramos si es necesario.
                }
            }
        }

        if (! $passwordVerified) {
            return back()->withErrors(['contrasena' => 'Contraseña incorrecta'])->withInput();
        }

        Auth::login($user);

        // Redirigir según rol
        if ($user->rol === 'Administrador') {
            return redirect()->route('admin.semestres');
        }

        return redirect()->route('coordinator.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
