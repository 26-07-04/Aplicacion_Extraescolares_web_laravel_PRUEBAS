<?php

namespace App\Http\Controllers\Coordinador\Complementarias;

use App\Http\Controllers\Coordinador\Complementarias\Concerns\ResultadosLiberacionesComplementarias;
use App\Http\Controllers\Coordinador\PanelDemetrioVallejoController;

/**
 * Panel Complementarias — Unidad Demetrio Vallejo Martínez.
 */
class PanelDemetrioVallejoComplementariasController extends PanelDemetrioVallejoController
{
    use ResultadosLiberacionesComplementarias;

    protected function panelTipoPrograma(): string
    {
        return \App\Models\Actividad::TIPO_COMPLEMENTARIA;
    }

    protected function coordinadorPanelView(): string
    {
        return 'coordinador.demetrio_vallejo.panel_complementarias';
    }

    protected function resultadosPdfView(): string
    {
        return 'coordinador.demetrio_vallejo.complementarias.pdf.resultados';
    }

    protected function lugarOficioLiberaciones(): string
    {
        return 'El Espinal, Oax.';
    }
}
