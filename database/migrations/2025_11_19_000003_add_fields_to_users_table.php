<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Nota: se usan nombres ASCII para las columnas ('contrasena' en lugar de 'contraseña')
        // porque los nombres de columna con caracteres especiales pueden causar problemas.
        Schema::create('usuarios', function (Blueprint $table) {
            $table->increments('id_usuario');
            $table->string('nombre', 100);
            $table->string('contrasena', 255);
            $table->string('contrasena_texto', 255)->nullable();
            $table->string('rol', 20)->default('Coordinador'); // Cambiado de enum a string para compatibilidad con PostgreSQL
            $table->string('unidad_academica', 120);
            $table->string('contacto', 50);
            $table->timestamps();
        });

        // Si existe tabla 'users', copiamos datos básicos hacia 'usuarios' para conservar usuarios.
        if (Schema::hasTable('users')) {
            $users = DB::table('users')->get();
            foreach ($users as $u) {
                DB::table('usuarios')->insert([
                    'id_usuario' => $u->id,
                    'nombre' => $u->name ?? '',
                    'contrasena' => $u->password ?? '',
                    'contrasena_texto' => null,
                    'rol' => $u->usertype ?? 'Coordinador',
                    'unidad_academica' => '',
                    'contacto' => '',
                    'created_at' => $u->created_at ?? now(),
                    'updated_at' => $u->updated_at ?? now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
