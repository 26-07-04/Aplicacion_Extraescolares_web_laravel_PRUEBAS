<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;

class ResultadosTipoFiltro
{
    /** @return list<string> */
    public static function keywordsCultural(): array
    {
        return [
            'cultural', 'arte', 'artística', 'artistica', 'danzas', 'danza', 'folklor', 'folklórica', 'folklorica', 'baile',
            'música', 'musica', 'teatro', 'pintura', 'coro', 'orquesta', 'ajedrez', 'lectura', 'fotografía', 'fotografia',
            'rondalla', 'escolta', 'banda de guerra',
        ];
    }

    /** @return list<string> */
    public static function keywordsDeportiva(): array
    {
        return [
            'deportiva', 'deporte', 'fútbol', 'futbol', 'basquetbol', 'basket', 'voleibol', 'atletismo', 'natación', 'natacion',
            'tenis', 'gimnasia', 'acondicionamiento', 'acondicionamiento fisico', 'acondicionamiento físico', 'preparacion fisica',
        ];
    }

    /**
     * Restringe la consulta de evaluaciones por tipo de actividad (palabras clave en nombre_actividad).
     *
     * @param  Builder<\App\Models\Evaluacion>  $evaluacionesQuery
     */
    public static function apply(Builder $evaluacionesQuery, string $tipo, string $tipoPrograma): void
    {
        $tipo = strtolower($tipo);
        if (! in_array($tipo, ['cultural', 'deportiva', 'academica'], true)) {
            return;
        }

        $evaluacionesQuery->whereHas('actividad', function ($q) use ($tipo, $tipoPrograma) {
            $q->where('tipo_programa', $tipoPrograma);

            if ($tipo === 'cultural') {
                $q->where(function ($qw) {
                    foreach (self::keywordsCultural() as $kw) {
                        $qw->orWhere('nombre_actividad', 'like', '%' . $kw . '%');
                    }
                });
                return;
            }

            if ($tipo === 'deportiva') {
                $q->where(function ($qw) {
                    foreach (self::keywordsDeportiva() as $kw) {
                        $qw->orWhere('nombre_actividad', 'like', '%' . $kw . '%');
                    }
                });
                return;
            }

            // Académica: actividades que no coinciden con listas culturales ni deportivas
            $q->where(function ($outer) {
                foreach (self::keywordsCultural() as $kw) {
                    $outer->where('nombre_actividad', 'not like', '%' . $kw . '%');
                }
            })->where(function ($outer) {
                foreach (self::keywordsDeportiva() as $kw) {
                    $outer->where('nombre_actividad', 'not like', '%' . $kw . '%');
                }
            });
        });
    }
}
