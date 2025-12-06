<?php

namespace App\Http\Controllers\Coordinador;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facades\PDF;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF as DomPDFPDF;

class informeValleDeEtlaController extends Controller
{
	/**
	 * Muestra la vista principal del informe de unidad.
	 */
	public function index(Request $request)
	{
		$id_semestre = $request->input('id_semestre');
		$documentos = \App\Models\Documento::where('id_semestre', $id_semestre)
			->where('tipo_documento', 'membrete')
			->get();
		return view('coordinador.valle_de_etla.informe_actividad', compact('documentos'));
	}

	/**
	 * Guarda o recibe los datos enviados desde el frontend.
	 */
	public function guardarDatos(Request $request)
	{
		// Ejemplo de recepción de datos:
		// $periodo = $request->periodo;
		// $eventos = $request->eventos;
		return response()->json([
			'status' => 'ok',
			'mensaje' => 'Datos recibidos correctamente.',
		]);
	}

	/**
	 * Recibe el PDF membretado y lo guarda en el servidor.
	 */
	public function subirPDF(Request $request)
	{
		$request->validate([
			'pdf_membrete' => 'required|file|mimes:pdf'
		]);
		$path = $request->file('pdf_membrete')->store('pdf_membrete', 'public');
		return response()->json([
			'status' => 'ok',
			'path' => $path,
		]);
	}

	/**
	 * Genera un PDF desde Laravel usando los datos recibidos (opcional).
	 */
	public function generarPDFLaravel(Request $request)
	{
		$datos = $request->all();
		$pdf = FacadePdf::loadView('coordinador.pdf_valle_de_etla', $datos);
		return $pdf->download('informe_eventos_unidad.pdf');
	}
}
