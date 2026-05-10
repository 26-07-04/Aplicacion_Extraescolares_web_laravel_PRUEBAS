<?php

namespace App\Http\Controllers\Coordinador\Complementarias;

use App\Http\Controllers\Coordinador\PanelUnionHidalgoController;

/**
 * Panel Complementarias — Unidad Unión Hidalgo.
 * Misma lógica que el panel extraescolar; vista duplicada para personalizar después.
 */
class PanelUnionHidalgoComplementariasController extends PanelUnionHidalgoController
{
    protected function panelTipoPrograma(): string
    {
        return \App\Models\Actividad::TIPO_COMPLEMENTARIA;
    }

    protected function coordinadorPanelView(): string
    {
        return 'coordinador.union_hidalgo.panel_complementarias';
    }

    protected function resultadosPdfView(): string
    {
        return 'coordinador.union_hidalgo.complementarias.pdf.resultados';
    }
}
