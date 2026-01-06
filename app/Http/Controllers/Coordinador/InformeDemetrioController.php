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
	 * Muestra la vista principal del informe de unidad.
	 */
	public function index(Request $request)
	{
		$id_semestre = $request->input('id_semestre');
		// Si no se recibe id_semestre, buscar el semestre activo
		if (!$id_semestre) {
			$semestreActual = \App\Models\Semestre::where('estatus', 1)->orderByDesc('fecha_inicio')->first();
			$id_semestre = $semestreActual ? $semestreActual->id_semestre : null;
		} else {
			$semestreActual = \App\Models\Semestre::find($id_semestre);
		}

		$documentos = \App\Models\Documento::where('id_semestre', $id_semestre)
			->where('tipo_documento', 'membrete')
			->get();

		$informes = [];
		$id_unidad = 2; // Forzar id_unidad=2 para Demetrio Vallejo
		if ($id_semestre && $id_unidad) {
			$informes = \App\Models\Informe::where('id_semestre', $id_semestre)
				->where('id_unidad', 2)
				->whereNotNull('archivo')
				->where('archivo', '!=', '')
				->orderByDesc('fecha_generacion')
				->get();
		}

		return view('coordinador.demetrio_vallejo.informe_actividad', [
			'documentos' => $documentos,
			'informes' => $informes,
			'id_semestre' => $id_semestre,
			'id_unidad' => $id_unidad,
			'semestreActual' => $semestreActual,
		]);
	}

	/**
	 * Guarda o recibe los datos enviados desde el frontend.
	 */
	public function guardarDatos(Request $request)
	{
		$request->validate([
			'pdf' => 'required|file|mimes:pdf|max:10240', // 10MB
			'titulo' => 'required|string|max:255',
			'id_semestre' => 'nullable|exists:semestres,id_semestre',
			'descripcion' => 'nullable|string',
			'fecha_generacion' => 'nullable|date',
		]);

		$id_semestre = $request->input('id_semestre');
		if (!$id_semestre) {
			$semestreActual = \App\Models\Semestre::where('estatus', 1)->orderByDesc('fecha_inicio')->first();
			$id_semestre = $semestreActual ? $semestreActual->id_semestre : null;
		}
		if (!$id_semestre) {
			return response()->json([
				'status' => 'error',
				'mensaje' => 'No se pudo determinar el semestre actual.'
			], 422);
		}

		$file = $request->file('pdf');
		$fileName = 'informe_' . time() . '.pdf';
		$filePath = $file->storeAs('informes', $fileName, 'public');

		$id_unidad = 2;
		$data = [
			'titulo' => $request->titulo,
			'archivo' => 'storage/' . $filePath,
			'id_semestre' => $id_semestre,
			'id_unidad' => $id_unidad,
		];
		if ($request->filled('descripcion')) {
			$data['descripcion'] = $request->descripcion;
		}
		$data['fecha_generacion'] = $request->filled('fecha_generacion') ? $request->fecha_generacion : now();

		$informe = \App\Models\Informe::create($data);

		return response()->json([
			'status' => 'ok',
			'mensaje' => 'Informe guardado correctamente.',
			'informe' => $informe,
		]);
	}

	/**
	 * Elimina un informe generado por su ID (replicado de Demetrio Vallejo)
	 */
	public function destroy($id)
	{
		$informe = \App\Models\Informe::find($id);
		if (!$informe) {
			return back()->with('error', 'Informe no encontrado.');
		}
		$id_semestre = request('id_semestre', $informe->id_semestre);
		if ($informe->archivo && file_exists(public_path($informe->archivo))) {
			@unlink(public_path($informe->archivo));
		}
		$informe->delete();
		return redirect()->back()->with('success', 'Informe eliminado correctamente.');
	}

	/**
	 * Recibe el PDF membretado y lo guarda en el servidor.
	 */
	public function subirPDF(Request $request)
	{
		$request->validate([
			'pdf_membrete' => 'required|file|mimes:pdf',
			'id_semestre' => 'nullable|exists:semestres,id_semestre',
		]);
		$id_semestre = $request->input('id_semestre');
		if (!$id_semestre) {
			$semestreActual = \App\Models\Semestre::where('estatus', 1)->orderByDesc('fecha_inicio')->first();
			$id_semestre = $semestreActual ? $semestreActual->id_semestre : null;
		}
		if (!$id_semestre) {
			return response()->json([
				'status' => 'error',
				'mensaje' => 'No se pudo determinar el semestre actual.'
			], 422);
		}
		$path = $request->file('pdf_membrete')->store('pdf_membrete', 'public');
		return response()->json([
			'status' => 'ok',
			'path' => $path,
			'id_semestre' => $id_semestre,
		]);
	}

	/**
	 * Genera un PDF desde Laravel usando los datos recibidos (opcional).
	 */
	public function generarPDFLaravel(Request $request)
	{
		$datos = $request->all();
		$pdf = FacadePdf::loadView('coordinador.pdf_demetrio', $datos);
		return $pdf->download('informe_eventos_demetrio.pdf');
	}
}

