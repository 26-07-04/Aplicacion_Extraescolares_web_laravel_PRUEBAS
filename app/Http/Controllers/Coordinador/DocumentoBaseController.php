<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Documento;

class DocumentoBaseController extends Controller

{
    // Muestra los PDFs membretados disponibles para Demetrio Vallejo
    public function indexDemetrioVallejo()
    {
        $documentos = Documento::orderBy('created_at', 'desc')->get();
        return view('coordinador.demetrio_vallejo.informe_actividad', compact('documentos'));
    }

    // Muestra los PDFs membretados disponibles para Tlahuitoltepec
    public function indexTlahuitoltepec()
    {
        $documentos = Documento::orderBy('created_at', 'desc')->get();
        return view('coordinador.tlahuitoltepec.informe_actividad', compact('documentos'));
    }

    // Muestra los PDFs membretados disponibles para Valle de Etla
    public function indexValleEtla()
    {
        $documentos = Documento::orderBy('created_at', 'desc')->get();
        return view('coordinador.valle_etla.informe_actividad', compact('documentos'));
    }

    // Muestra los PDFs membretados disponibles para El Espinal
    public function indexElEspinal()
    {
        $documentos = Documento::orderBy('created_at', 'desc')->get();
        return view('coordinador.el_espinal.informe_actividad', compact('documentos'));
    }

    // Muestra los PDFs membretados disponibles para Juchitán
    public function indexJuchitan()
    {
        $documentos = Documento::orderBy('created_at', 'desc')->get();
        return view('coordinador.juchitan.informe_actividad', compact('documentos'));
    }

    // Acción para seleccionar/cargar el PDF membretado
    public function cargar($id)
    {
        $documento = Documento::findOrFail($id);
        // Aquí podrías retornar la ruta del archivo o procesar la selección
        // Por ejemplo, podrías redirigir con el PDF seleccionado
        return response()->json([
            'archivo' => asset($documento->archivo),
            'nombre' => $documento->nombre,
            'descripcion' => $documento->descripcion
        ]);
    }
}
