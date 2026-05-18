<?php

namespace App\Http\Controllers\Coordinador\Complementarias\Concerns;

use App\Models\Semestre;
use App\Support\ComplementariasLiberacionesOficio;
use Illuminate\Http\Request;

trait ResultadosLiberacionesComplementarias
{
    /**
     * Lugar que aparece en la fecha del oficio (por unidad).
     */
    abstract protected function lugarOficioLiberaciones(): string;

    protected function redirigirResultadosAFormatos(Request $request): ?\Illuminate\Http\RedirectResponse
    {
        if ($request->query('show') === 'formatos') {
            return redirect()->to(url()->current() . '?show=resultados');
        }

        return null;
    }

    protected function filtrarResultadosPorTipoEnImpresion(): bool
    {
        return false;
    }

    protected function datosAdicionalesVistaResultadosPdf(Semestre $semestre, $evaluaciones, Request $request): array
    {
        $datos = ComplementariasLiberacionesOficio::datosPdf($semestre, $this->lugarOficioLiberaciones());
        $datos['totalLiberaciones'] = $evaluaciones->count();

        return $datos;
    }
}
