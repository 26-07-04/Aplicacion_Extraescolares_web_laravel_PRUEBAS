<?php
namespace App\Http\Controllers\Coordinador;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facades\PDF;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Illuminate\Support\Facades\Auth;

class InformeDemetrioController extends Controller {
    /**
     * Elimina un informe generado por su ID.
     */
    public function destroy($id)
    {
        $informe = \App\Models\Informe::find($id);
        if (!$informe) {
            return back()->with('error', 'Informe no encontrado.');
        }
        // Obtener id_semestre del request si viene (preferente), si no del informe
        $id_semestre = request('id_semestre', $informe->id_semestre);
        // Eliminar archivo físico si existe
        if ($informe->archivo && file_exists(public_path($informe->archivo))) {
            @unlink(public_path($informe->archivo));
        }
        $informe->delete();
        // Redirigir a la misma página después de eliminar
        return redirect()->back()
            ->with('success', 'Informe eliminado correctamente.');
    }

    /**
     * Recibe el PDF generado como archivo, lo guarda en el servidor y registra el informe en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pdf' => 'required|file|mimes:pdf|max:10240', // 10MB
            'titulo' => 'required|string|max:255',
            'id_semestre' => 'required|exists:semestres,id_semestre',
            'descripcion' => 'nullable|string',
            'fecha_generacion' => 'nullable|date',
        ]);

        $id_semestre = $request->input('id_semestre');

        $file = $request->file('pdf');
        $fileName = 'informe_' . time() . '.pdf';
        $filePath = $file->storeAs('informes', $fileName, 'public');

        $data = [
            'titulo' => $request->titulo,
            'archivo' => 'storage/' . $filePath,
            'id_semestre' => $id_semestre,
        ];
        if ($request->filled('descripcion')) {
            $data['descripcion'] = $request->descripcion;
        }
        if ($request->filled('fecha_generacion')) {
            $data['fecha_generacion'] = $request->fecha_generacion;
        } else {
            $data['fecha_generacion'] = now();
        }

        $informe = \App\Models\Informe::create($data);

        return response()->json([
            'status' => 'ok',
            'mensaje' => 'Informe guardado correctamente.',
            'informe' => $informe,
        ]);
    }

    /**
     * Muestra la vista principal con los informes filtrados por unidad y semestre.
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

        $informes = [];
        if ($id_semestre) {
            $informes = \App\Models\Informe::where('id_semestre', $id_semestre)
                ->orderByDesc('fecha_generacion')
                ->get();
        }

        return view(
            'coordinador.demetrio_vallejo.informe_actividad',
            [
                'informes' => $informes,
                'id_semestre' => $id_semestre,
                'semestreActual' => $semestreActual,
            ]
        );
    }

    /**
     * Guarda o recibe los datos que se envían desde el frontend.
     * Esta función es opcional, pero la dejo completa por si la necesitas.
     */
    public function guardarDatos(Request $request)
    {
        // Validar los datos esperados
        $request->validate([
            'pdf' => 'required|file|mimes:pdf|max:10240', // 10MB
            'titulo' => 'required|string|max:255',
            'id_semestre' => 'required|exists:semestres,id_semestre',
            'descripcion' => 'nullable|string',
            'fecha_generacion' => 'nullable|date',
        ]);

        $id_semestre = $request->input('id_semestre');

        $file = $request->file('pdf');
        $fileName = 'informe_' . time() . '.pdf';
        $filePath = $file->storeAs('informes', $fileName, 'public');

        $data = [
            'titulo' => $request->titulo,
            'archivo' => 'storage/' . $filePath,
            'id_semestre' => $id_semestre,
        ];
        if ($request->filled('descripcion')) {
            $data['descripcion'] = $request->descripcion;
        }
        // Siempre usar la fecha del servidor para fecha_generacion
        $data['fecha_generacion'] = now();

        $informe = \App\Models\Informe::create($data);

        return response()->json([
            'status' => 'ok',
            'mensaje' => 'Informe guardado correctamente.',
            'informe' => $informe,
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
