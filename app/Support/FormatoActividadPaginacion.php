<?php

namespace App\Support;

use Illuminate\Support\Collection;

class FormatoActividadPaginacion
{
    /** Filas máximas en la primera hoja (cabecera) y la última (firmas). */
    public const FILAS_PRIMERA_ULTIMA = 21;

    /** Filas máximas en hojas intermedias (sin cabecera ni pie de firmas). */
    public const FILAS_INTERMEDIAS = 35;

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
