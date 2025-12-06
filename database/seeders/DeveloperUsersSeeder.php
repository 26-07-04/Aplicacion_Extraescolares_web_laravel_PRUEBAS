<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DeveloperUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // No crear estos usuarios en producción
        if (app()->environment('production')) {
            $this->command->info('Skipping DeveloperUsersSeeder in production.');
            return;
        }

        // Contraseña compartida para desarrollo; se puede sobrescribir con APP_ENV var
        $password = env('DEV_USER_PASSWORD', 'DevPass123!');

        $users = [
            [
                'nombre' => 'Dev Administrador',
                'contrasena' => Hash::make($password),
                'contrasena_texto' => $password,
                'rol' => 'Administrador',
                'unidad_academica' => 'Desarrollo',
                'contacto' => 'dev-admin@example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Dev Coordinador',
                'contrasena' => Hash::make($password),
                'contrasena_texto' => $password,
                'rol' => 'Coordinador',
                'unidad_academica' => 'Desarrollo',
                'contacto' => 'dev-coord@example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($users as $u) {
            DB::table('usuarios')->updateOrInsert(
                ['nombre' => $u['nombre']],
                $u
            );
        }

        $this->command->info('Developer users seeded (non-production).');
    }
}
