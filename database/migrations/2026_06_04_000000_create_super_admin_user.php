<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Verificar si ya existe un usuario administrador
        $adminExists = DB::table('usuarios')
            ->where('nombre', 'Administrador')
            ->exists();

        if (!$adminExists) {
            DB::table('usuarios')->insert([
                'nombre' => 'Administrador',

                'contrasena' => Hash::make('Admin@ITVE2026!'),
                'contrasena_texto' => null, // No almacenar en texto plano
                'rol' => 'Administrador',
                'unidad_academica' => 'Administración',
                'contacto' => 'admin@itve.edu.co',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('usuarios')
            ->where('nombre', 'Administrador')
            ->delete();
    }
};
