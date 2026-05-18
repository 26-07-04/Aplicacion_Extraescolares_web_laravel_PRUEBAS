<?php

namespace App\Support;

/**
 * Firmas del oficio de liberaciones (complementarias — Valle de Etla).
 */
class ComplementariasLiberacionesFirmas
{
    /**
     * @return array{
     *     destinatario: array{nombre: string, cargo: string},
     *     firmante: array{nombre: string, cargo: string},
     *     sello: array{institucion: string, departamento: string}
     * }
     */
    public static function defaults(): array
    {
        return [
            'destinatario' => [
                'nombre' => 'LIC. HIRAM GALLEGOS FELIPE',
                'cargo' => 'JEFE DE DEPARTAMENTO DE SERVICIOS ESCOLARES',
            ],
            'firmante' => [
                'nombre' => 'M.A. FERNANDO ADRIHEL SARUBBI BALTAZAR',
                'cargo' => 'SUBDIRECTOR DE PLANEACIÓN Y VINCULACIÓN',
            ],
            'sello' => [
                'institucion' => 'INSTITUTO TECNOLÓGICO DEL VALLE DE ETLA',
                'departamento' => 'SUBDIRECCIÓN DE PLANEACIÓN Y VINCULACIÓN',
            ],
        ];
    }
}
