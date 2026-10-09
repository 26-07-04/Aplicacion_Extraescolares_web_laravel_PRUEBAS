<?php

namespace App\Support;

use Illuminate\Support\Collection;

class FormatoActividadPaginacion
{
    /** Filas máximas por hoja para mantener una vista limpia y evitar desbordamiento del formato. */
    public const FILAS_PRIMERA_ULTIMA = 16;

    /** Filas máximas en hojas intermedias. */
    public const FILAS_INTERMEDIAS = 16;

    /**
     * Divide las filas del formato: primera y última página con límite menor;
     * páginas del medio con más filas (hasta {@see FILAS_INTERMEDIAS}).
     *
     * @param  iterable<int, mixed>  $filas
     * @return Collection<int, Collection<int, mixed>>
     */
    public static function paginar(
        iterable $filas,
        int $primeraUltima = self::FILAS_PRIMERA_ULTIMA,
        int $intermedias = self::FILAS_INTERMEDIAS,
    ): Collection {
        $items = collect($filas)->values()->all();
        $total = count($items);

        if ($total === 0) {
            return collect([collect()]);
        }

        if ($total <= $primeraUltima) {
            return collect([collect($items)]);
        }

        $pages = [];
        $offset = 0;

        $pages[] = collect(array_slice($items, $offset, $primeraUltima));
        $offset += $primeraUltima;

        while ($offset < $total) {
            $remaining = $total - $offset;

            if ($remaining <= $primeraUltima) {
                $pages[] = collect(array_slice($items, $offset));
                break;
            }

            $take = min($intermedias, $remaining);
            $restanteUltima = $remaining - $take;

            if ($restanteUltima > $primeraUltima) {
                $take = $remaining - $primeraUltima;
            } elseif ($restanteUltima <= 0) {
                $take = min($intermedias, max(1, $remaining - 1));
            }

            $take = max(1, min($take, $intermedias));

            $pages[] = collect(array_slice($items, $offset, $take));
            $offset += $take;
        }

        return collect($pages);
    }
}
