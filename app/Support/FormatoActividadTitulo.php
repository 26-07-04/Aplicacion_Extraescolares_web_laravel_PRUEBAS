<?php

namespace App\Support;

use App\Models\Actividad;

class FormatoActividadTitulo
{
    public static function linea(Actividad $actividad): string
    {
        $cat = $actividad->categoriaParaInforme();
        $prefix = match ($cat) {
            'deportiva' => 'ACTIVIDAD DEPORTIVA',
            'cultural' => 'ACTIVIDAD CULTURAL',
            default => 'ACTIVIDAD',
        };
        $nombre = mb_strtoupper(trim((string) ($actividad->nombre_actividad ?? '')), 'UTF-8');
        if ($nombre === '') {
            return $prefix;
        }

        return $prefix . ' (' . $nombre . ')';
    }
}
