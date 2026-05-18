<?php

namespace App\Support;

use App\Models\Semestre;
use Carbon\Carbon;

class ComplementariasLiberacionesOficio
{
    /**
     * Datos del oficio de liberaciones para la vista PDF.
     *
     * @return array<string, mixed>
     */
    public static function datosPdf(Semestre $semestre, string $lugarOficio): array
    {
        $fechaMx = Carbon::now('America/Mexico_City');
        $meses = [
            1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril',
            5 => 'mayo', 6 => 'junio', 7 => 'julio', 8 => 'agosto',
            9 => 'septiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre',
        ];

        return [
            'fechaOficio' => sprintf(
                '%02d/%s/%d',
                $fechaMx->day,
                $meses[(int) $fechaMx->month] ?? 'enero',
                $fechaMx->year
            ),
            'oficioNumero' => sprintf('SPV/%02d/%d', $fechaMx->month, $fechaMx->year),
            'asuntoOficio' => 'Entrega de Constancias',
            'totalLiberaciones' => 0,
            'periodoSemestre' => $semestre->nombre ?? '',
            'lugarOficio' => $lugarOficio,
            'firmasLiberaciones' => ComplementariasLiberacionesFirmas::defaults(),
        ];
    }
}
