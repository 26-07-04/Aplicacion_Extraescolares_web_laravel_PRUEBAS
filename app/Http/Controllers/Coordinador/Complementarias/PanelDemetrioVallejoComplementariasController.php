<?php

namespace App\Http\Controllers\Coordinador\Complementarias;

use App\Http\Controllers\Coordinador\PanelDemetrioVallejoController;

/**
 * Panel Complementarias — Unidad Demetrio Vallejo Martínez.
 * Misma lógica que el panel extraescolar; vista duplicada para personalizar después.
 */
class PanelDemetrioVallejoComplementariasController extends PanelDemetrioVallejoController
{
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
}
