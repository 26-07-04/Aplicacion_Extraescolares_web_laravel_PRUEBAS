<?php

namespace App\Http\Controllers\Coordinador\Complementarias;

use App\Http\Controllers\Coordinador\PanelValleEtlaController;

/**
 * Panel Complementarias — Unidad Valle de Etla.
 * Misma lógica que el panel extraescolar; vista duplicada para personalizar después.
 */
class PanelValleEtlaComplementariasController extends PanelValleEtlaController
{
    protected function panelTipoPrograma(): string
    {
        return \App\Models\Actividad::TIPO_COMPLEMENTARIA;
    }

    protected function coordinadorPanelView(): string
    {
        return 'coordinador.valle_de_etla.panel_complementarias';
    }

    protected function resultadosPdfView(): string
    {
        return 'coordinador.valle_de_etla.complementarias.pdf.resultados';
    }
}
