<?php

namespace App\Http\Controllers\Coordinador;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Actividad;
use App\Models\Informe;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facades\PDF;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF as DomPDFPDF;

class InformeUnionHidalgoController extends Controller
{
	/**
	 * Muestra la vista principal del informe de unidad Unión Hidalgo.
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
			->orderByDesc('created_at')
			->get();

		$id_unidad = 1;
		$tipoInformes = Informe::tipoProgramaValido($request->query('tipo_programa'));
		$informes = Informe::listadoGeneradosPorUnidad((int) $id_semestre, $id_unidad, $tipoInformes);

		return view('coordinador.union_hidalgo.informe_actividad', [
			'documentos' => $documentos,
			'informes' => $informes,
			'id_semestre' => $id_semestre,
			'id_unidad' => $id_unidad,
			'semestreActual' => $semestreActual,
			'tipo_programa_informes_panel' => $tipoInformes,
		]);
	}

	/**
	 * Guarda o recibe los datos enviados desde el frontend para Unión Hidalgo.
	 */
	public function guardarDatos(Request $request)
	{
		$request->validate([
			'pdf' => 'required|file|mimes:pdf|max:10240', // 10MB
			'titulo' => 'required|string|max:255',
			'id_semestre' => 'nullable|exists:semestres,id_semestre',
			'descripcion' => 'nullable|string',
			'fecha_generacion' => 'nullable|date',
			'tipo_programa' => ['nullable', 'string', Rule::in([Actividad::TIPO_EXTRAESCOLAR, Actividad::TIPO_COMPLEMENTARIA])],
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

		$id_unidad = 1;
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
		$data['tipo_programa'] = Informe::tipoProgramaValido($request->input('tipo_programa'));

		$informe = Informe::create($data);

		return response()->json([
			'status' => 'ok',
			'mensaje' => 'Informe guardado correctamente.',
			'informe' => $informe,
		]);
	}

	/**
	 * Elimina un informe generado por su ID (Unión Hidalgo)
	 */
	public function destroy($id)
	{
		$informe = Informe::find($id);
		$tipoEsperado = Informe::tipoProgramaValido(request()->input('tipo_programa'));
		if (! $informe || (int) $informe->id_unidad !== 1) {
			return back()->with('error', 'Informe no encontrado o no pertenece a esta unidad.');
		}
		if (($informe->tipo_programa ?? Actividad::TIPO_EXTRAESCOLAR) !== $tipoEsperado) {
			return back()->with('error', 'No se puede eliminar este informe desde este panel.');
		}
		$id_semestre = request('id_semestre', $informe->id_semestre);
		if ($informe->archivo && file_exists(public_path($informe->archivo))) {
			@unlink(public_path($informe->archivo));
		}
		$informe->delete();
		return redirect()->back()->with('success', 'Informe eliminado correctamente.');
	}

	/**
	 * Recibe el PDF membretado y lo guarda en el servidor (Unión Hidalgo).
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
	 * Genera un PDF desde Laravel usando los datos recibidos (Unión Hidalgo).
	 */
	public function generarPDFLaravel(Request $request)
	{
		$datos = $request->all();
		$pdf = FacadePdf::loadView('coordinador.pdf_union_hidalgo', $datos);
		return $pdf->download('informe_eventos_union_hidalgo.pdf');
	}
}

