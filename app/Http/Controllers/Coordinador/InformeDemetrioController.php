<?php

namespace App\Http\Controllers\Coordinador;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facades\PDF;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF as DomPDFPDF;

class InformeDemetrioController extends Controller
{
    /**
     * Muestra la vista principal donde está tu tabla,
     * los inputs y los botones de generar PDF.
     */
    public function index()
    {
        return view('Coordinador.informe_demetrio');
    }

    /**
     * Guarda o recibe los datos que se envían desde el frontend.
     * Esta función es opcional, pero la dejo completa por si la necesitas.
     */
    public function guardarDatos(Request $request)
    {
        // Aquí recibes datos del informe si deseas guardarlos o procesarlos.
        // Ejemplo:
        // $periodo = $request->periodo;
        // $eventos = $request->eventos;

        return response()->json([
            'status' => 'ok',
            'mensaje' => 'Datos recibidos correctamente.',
        ]);
    }

    /**
     * Recibe el PDF membretado desde el navegador y lo guarda.
     * Funciona solo si deseas subir ese PDF al servidor.
     */
    public function subirPDF(Request $request)
    {
        $request->validate([
            'pdf_membrete' => 'required|file|mimes:pdf'
        ]);

        // Guardar el PDF en storage/app/public/pdf_membrete
        $path = $request->file('pdf_membrete')->store('pdf_membrete', 'public');

        return response()->json([
            'status' => 'ok',
            'path' => $path,
        ]);
    }

    /**
     * Genera un PDF desde Laravel usando los datos recibidos.
     * Esta función es OPCIONAL, solo si algún día quieres generar
     * el PDF en backend en vez de usar jsPDF en el navegador.
     */
    /** @var \Barryvdh\DomPDF\PDF $pdf */
    public function generarPDFLaravel(Request $request)
    {
        // Aquí recibes todos los datos enviados desde JS
        $datos = $request->all();

        // Cargar una vista Blade para el PDF
        $pdf = FacadePdf::loadView('Coordinador.pdf_demetrio', $datos);

        return $pdf->download('informe_eventos.pdf');
    }
}
