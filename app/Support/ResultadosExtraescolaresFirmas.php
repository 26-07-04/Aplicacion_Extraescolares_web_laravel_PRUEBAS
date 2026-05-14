<?php

namespace App\Support;

/**
 * Firmas del formato de resultados extraescolares por unidad.
 * La tercera firma (Jefe de Departamento) es la misma en todas las unidades.
 */
class ResultadosExtraescolaresFirmas
{
    public const JEFE_DEPARTAMENTO_NOMBRE = 'M.C. Alejandro Loma Bolaños';

    public const JEFE_DEPARTAMENTO_CARGO = 'Jefe de Departamento de Actividades Extraescolares.';

    /**
     * @return array{izquierda: array{nombre: string, cargo: string}, centro: array{nombre: string, cargo: string}, derecha: array{nombre: string, cargo: string}}
     */
    public static function forUnidad(string $unidadKey): array
    {
        $derecha = [
            'nombre' => self::JEFE_DEPARTAMENTO_NOMBRE,
            'cargo' => self::JEFE_DEPARTAMENTO_CARGO,
        ];

        return match ($unidadKey) {
            'valle_etla' => [
                'izquierda' => [
                    'nombre' => 'Dra. Vianii Cruz López',
                    'cargo' => 'Promotor Cultural o Deportivo.',
                ],
                'centro' => [
                    'nombre' => 'D.C. Fernando Adrihel Sarubbi Baltazar',
                    'cargo' => 'Jefe de Oficina de Promoción Cultural o Deportiva.',
                ],
                'derecha' => $derecha,
            ],
            default => [
                'izquierda' => [
                    'nombre' => '',
                    'cargo' => 'Promotor Cultural o Deportivo.',
                ],
                'centro' => [
                    'nombre' => '',
                    'cargo' => 'Jefe de Oficina de Promoción Cultural o Deportiva.',
                ],
                'derecha' => $derecha,
            ],
        };
    }
}
