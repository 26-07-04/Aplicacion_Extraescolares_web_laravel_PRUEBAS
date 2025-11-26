<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class UnidadesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $unidades = [
            ['id_unidad' => 1, 'nombre_unidad' => 'Unidad Académica: Unión Hidalgo',                'created_at' => $now, 'updated_at' => $now],
            ['id_unidad' => 2, 'nombre_unidad' => 'Unidad Académica: Demetrio Vallejo Martínez',   'created_at' => $now, 'updated_at' => $now],
            ['id_unidad' => 3, 'nombre_unidad' => 'Unidad Académica: Santa María Tlahuitoltepec',  'created_at' => $now, 'updated_at' => $now],
            ['id_unidad' => 4, 'nombre_unidad' => 'Unidad Académica: Valle de Etla',               'created_at' => $now, 'updated_at' => $now],
        ];

        $hasIdSemestre = Schema::hasColumn('unidades', 'id_semestre');

        foreach ($unidades as $u) {
            if ($hasIdSemestre) {
                $u['id_semestre'] = null;
            }

            DB::table('unidades')->updateOrInsert(
                ['id_unidad' => $u['id_unidad']],
                $u
            );
        }
    }
}
