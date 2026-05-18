<?php

namespace App\Http\Controllers\Coordinador\Complementarias;

use App\Http\Controllers\Coordinador\Complementarias\Concerns\ResultadosLiberacionesComplementarias;
use App\Http\Controllers\Coordinador\PanelUnionHidalgoController;

/**
 * Panel Complementarias — Unidad Unión Hidalgo.
 */
class PanelUnionHidalgoComplementariasController extends PanelUnionHidalgoController
{
    use ResultadosLiberacionesComplementarias;

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

    protected function lugarOficioLiberaciones(): string
    {
        return 'Unión Hidalgo, Oax.';
    }
}
