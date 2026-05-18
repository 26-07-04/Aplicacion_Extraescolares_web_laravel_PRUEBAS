<?php

namespace App\Http\Controllers\Coordinador\Complementarias;

use App\Http\Controllers\Coordinador\Complementarias\Concerns\ResultadosLiberacionesComplementarias;
use App\Http\Controllers\Coordinador\PanelValleEtlaController;

/**
 * Panel Complementarias — Unidad Valle de Etla.
 */
class PanelValleEtlaComplementariasController extends PanelValleEtlaController
{
    use ResultadosLiberacionesComplementarias;

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

    protected function lugarOficioLiberaciones(): string
    {
        return 'Santiago Suchilquitongo, Oax.';
    }
}
