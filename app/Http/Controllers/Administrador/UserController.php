<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Models\Semestre;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->only(['nombreUsuario','passwordUsuario','unidadUsuario','contactoUsuario','rolUsuario']);

        $validator = Validator::make($data, [
            'nombreUsuario' => ['required','string','max:100'],
            'passwordUsuario' => ['required','string','min:6','max:255'],
            'unidadUsuario' => ['required','string','max:120'],
            'contactoUsuario' => ['required','string','max:50'],
            'rolUsuario' => ['required','in:Administrador,Coordinador'],
        ], [
            'nombreUsuario.required' => 'El campo nombre es obligatorio.',
            'passwordUsuario.required' => 'El campo contraseña es obligatorio.',
            'passwordUsuario.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'unidadUsuario.required' => 'El campo unidad académica es obligatorio.',
            'contactoUsuario.required' => 'El campo contacto es obligatorio.',
            'rolUsuario.required' => 'El campo rol es obligatorio.',
        ]);

        // Validación adicional: contacto puede ser email o teléfono (números, espacios, +, -, paréntesis)
        $validator->after(function ($validator) use ($data) {
            $c = $data['contactoUsuario'] ?? '';
            $isEmail = filter_var($c, FILTER_VALIDATE_EMAIL) !== false;
            $isPhone = preg_match('/^[0-9\s\+\-\(\)]+$/', $c);
            if (!$isEmail && !$isPhone) {
                $validator->errors()->add('contactoUsuario', 'El contacto debe ser un correo electrónico o un teléfono válido.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Determinar semestre: preferir el id enviado desde el formulario (p. ej. cuando se está
        // viendo un semestre concreto). Si no se envía, usar el semestre activo (estatus = 1).
        $idSemestre = $request->input('id_semestre');
        if (!$idSemestre) {
            $idSemestre = Semestre::where('estatus', 1)->value('id_semestre');
        }

        $u = User::create([
            'nombre' => $data['nombreUsuario'],
            'contrasena' => bcrypt($data['passwordUsuario']),
            'contrasena_texto' => $data['passwordUsuario'],
            'rol' => $data['rolUsuario'],
            'id_semestre' => $idSemestre ?? null,
            'unidad_academica' => $data['unidadUsuario'],
            'contacto' => $data['contactoUsuario'],
        ]);

        return redirect()->back()->with('success', 'Usuario creado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $data = $request->only(['nombreUsuario','passwordUsuario','unidadUsuario','contactoUsuario','rolUsuario']);

        $validator = Validator::make($data, [
            'nombreUsuario' => ['required','string','max:100'],
            'passwordUsuario' => ['nullable','string','min:6','max:255'],
            'unidadUsuario' => ['required','string','max:120'],
            'contactoUsuario' => ['required','string','max:50'],
            'rolUsuario' => ['required','in:Administrador,Coordinador'],
        ], [
            'nombreUsuario.required' => 'El campo nombre es obligatorio.',
            'passwordUsuario.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'unidadUsuario.required' => 'El campo unidad académica es obligatorio.',
            'contactoUsuario.required' => 'El campo contacto es obligatorio.',
            'rolUsuario.required' => 'El campo rol es obligatorio.',
        ]);

        // Validación adicional: contacto puede ser email o teléfono
        $validator->after(function ($validator) use ($data) {
            $c = $data['contactoUsuario'] ?? '';
            $isEmail = filter_var($c, FILTER_VALIDATE_EMAIL) !== false;
            $isPhone = preg_match('/^[0-9\s\+\-\(\)]+$/', $c);
            if (!$isEmail && !$isPhone) {
                $validator->errors()->add('contactoUsuario', 'El contacto debe ser un correo electrónico o un teléfono válido.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::find($id);
        if (!$user) return redirect()->back()->with('error','Usuario no encontrado.');

        $user->nombre = $data['nombreUsuario'];
        if (!empty($data['passwordUsuario'])) {
            $user->contrasena = bcrypt($data['passwordUsuario']);
            $user->contrasena_texto = $data['passwordUsuario'];
        }
        $user->unidad_academica = $data['unidadUsuario'];
        $user->contacto = $data['contactoUsuario'];
        $user->rol = $data['rolUsuario'];
        $user->save();

        return redirect()->back()->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) return redirect()->back()->with('error', 'Usuario no encontrado.');
        $user->delete();
        return redirect()->back()->with('success', 'Usuario eliminado correctamente.');
    }
}
