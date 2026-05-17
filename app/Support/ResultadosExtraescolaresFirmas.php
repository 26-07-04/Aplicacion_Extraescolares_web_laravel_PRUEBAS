<?php

namespace App\Support;

/**
 * Firmas del formato de resultados / formatos extraescolares por unidad.
 * La firma de Jefe de Departamento (derecha) es la misma en todas las unidades.
 */
class ResultadosExtraescolaresFirmas
{
    public const JEFE_DEPARTAMENTO_NOMBRE = 'M.C. Alejandro Loma Bolaños';

    public const JEFE_DEPARTAMENTO_CARGO = 'Jefe de Departamento de Actividades Extraescolares.';

    public const NOMBRE_PLACEHOLDER = 'Nombre Completo';

    /**
     * @return array{izquierda: array{nombre: string, cargo: string}, centro: array{nombre: string, cargo: string}, derecha: array{nombre: string, cargo: string}}
     */
    public static function forUnidad(string $unidadKey): array
    {
        $derecha = [
            'nombre' => self::JEFE_DEPARTAMENTO_NOMBRE,
            'cargo' => self::JEFE_DEPARTAMENTO_CARGO,
        ];

        $conNombresGenericos = [
            'izquierda' => [
                'nombre' => self::NOMBRE_PLACEHOLDER,
                'cargo' => 'Promotor Cultural o Deportivo.',
            ],
            'centro' => [
                'nombre' => self::NOMBRE_PLACEHOLDER,
                'cargo' => 'Jefe de Oficina de Promoción Cultural o Deportiva.',
            ],
            'derecha' => $derecha,
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
            'demetrio_vallejo', 'union_hidalgo', 'tlahuitoltepec' => $conNombresGenericos,
            default => $conNombresGenericos,
        };
    }
}
