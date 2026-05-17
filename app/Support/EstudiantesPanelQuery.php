<?php

namespace App\Support;

use App\Models\Actividad;
use App\Models\Estudiante;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class EstudiantesPanelQuery
{
    /**
     * Estudiantes inscritos solo en actividades del tipo del panel actual.
     *
     * @param  \Illuminate\Support\Collection<int, Actividad>|array<int>  $actividades
     */
    public static function listar($actividades, ?string $tipoPrograma): Collection
    {
        if ($actividades instanceof Collection) {
            $ids = $actividades->pluck('id_actividad')->filter()->values()->all();
        } else {
            $ids = array_filter((array) $actividades);
        }

        if ($ids === []) {
            return collect();
        }

        return self::queryBase($ids, $tipoPrograma)
            ->select('estudiantes.*', 'actividades.nombre_actividad', 'actividades.tipo_programa')
            ->orderBy('estudiantes.nombre', 'asc')
            ->get();
    }

    /**
     * @param  array<int, int|string>  $actividadIds
     * @return Builder<Estudiante>
     */
    public static function queryBase(array $actividadIds, ?string $tipoPrograma): Builder
    {
        $query = Estudiante::query()
            ->whereIn('estudiantes.id_actividad', $actividadIds)
            ->join('actividades', 'estudiantes.id_actividad', '=', 'actividades.id_actividad');

        Actividad::restringirJoinTipoPrograma($query, $tipoPrograma);

        return $query;
    }
}
