<?php

namespace App\Http\Controllers\Coordinador\Complementarias;

use App\Http\Controllers\Coordinador\PanelTlahuitoltepecController;

/**
 * Panel Complementarias — Unidad Santa María Tlahuitoltepec.
 * Misma lógica que el panel extraescolar; vista duplicada para personalizar después.
 */
class PanelTlahuitoltepecComplementariasController extends PanelTlahuitoltepecController
{
    protected function panelTipoPrograma(): string
    {
        return \App\Models\Actividad::TIPO_COMPLEMENTARIA;
    }

    protected function coordinadorPanelView(): string
    {
        return 'coordinador.tlahuitoltepec.panel_complementarias';
    }

    protected function resultadosPdfView(): string
    {
        return 'coordinador.tlahuitoltepec.complementarias.pdf.resultados';
    }
}
