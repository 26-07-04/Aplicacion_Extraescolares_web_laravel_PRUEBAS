<?php

namespace App\Http\Controllers\Coordinador\Complementarias;

use App\Http\Controllers\Coordinador\Complementarias\Concerns\ResultadosLiberacionesComplementarias;
use App\Http\Controllers\Coordinador\PanelTlahuitoltepecController;

/**
 * Panel Complementarias — Unidad Santa María Tlahuitoltepec.
 */
class PanelTlahuitoltepecComplementariasController extends PanelTlahuitoltepecController
{
    use ResultadosLiberacionesComplementarias;

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

    protected function lugarOficioLiberaciones(): string
    {
        return 'Santa María Tlahuitoltepec, Oax.';
    }
}
