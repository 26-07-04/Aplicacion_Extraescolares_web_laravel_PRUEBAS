<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Documento;
use App\Models\Semestre;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class DocumentoController extends Controller
{
    /**
     * Store a newly uploaded documento.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            // semestres primary key is `id_semestre`
            'id_semestre' => 'required|exists:semestres,id_semestre',
            // permitir PDF y membretes en imagen (png/jpg/jpeg)
            'archivo' => 'required|file|mimes:pdf,png,jpg,jpeg|max:10240',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $file = $request->file('archivo');

        // Guardar directamente en public/Documentos (carpeta en español)
        $destination = public_path('Documentos');
        if (! File::exists($destination)) {
            File::makeDirectory($destination, 0755, true);
        }

        $originalName = $file->getClientOriginalName();
        // Normalizar nombre de archivo para evitar caracteres especiales
        $safeName = preg_replace('/[^A-Za-z0-9._-]/', '_', $originalName);
        $filename = time() . '_' . $safeName;
        $file->move($destination, $filename);
        $path = 'Documentos/' . $filename; // ruta relativa dentro de public

        if (strtolower($file->getClientOriginalExtension()) === 'pdf') {
            $pdfPath = $destination . DIRECTORY_SEPARATOR . $filename;
            $previewPath = $destination . DIRECTORY_SEPARATOR . pathinfo($filename, PATHINFO_FILENAME) . '.png';
            $process = new Process([
                'pdftoppm',
                '-png',
                '-f',
                '1',
                '-singlefile',
                $pdfPath,
                pathinfo($previewPath, PATHINFO_DIRNAME) . DIRECTORY_SEPARATOR . pathinfo($previewPath, PATHINFO_FILENAME),
            ]);
            $process->setTimeout(60);
            try {
                $process->run();
            } catch (\Throwable $e) {
                // El PDF original se conserva aunque no haya conversor disponible.
            }
        }

        $documento = Documento::create([
            'nombre' => $request->input('nombre'),
            'descripcion' => $request->input('descripcion'),
            'id_semestre' => $request->input('id_semestre'),
            'archivo' => $path,
        ]);

        return redirect()->back()->with('success', 'Documento subido correctamente.');
    }

    /**
     * Download a documento file.
     */
    public function download($id)
    {
        $documento = Documento::findOrFail($id);
        if (! $documento->archivo) {
            return redirect()->back()->with('error', 'Archivo no encontrado.');
        }

        $filePath = public_path($documento->archivo);
        if (! File::exists($filePath)) {
            return redirect()->back()->with('error', 'Archivo no encontrado.');
        }

        // determinar extensión original para descargar con nombre coherente
        $ext = pathinfo($filePath, PATHINFO_EXTENSION);
        $downloadName = $documento->nombre . '.' . strtolower($ext ?: 'pdf');
        return response()->download($filePath, $downloadName);
    }

    /**
     * Remove the specified documento.
     */
    public function destroy(Request $request, $id)
    {
        $documento = Documento::findOrFail($id);

        // Eliminar archivo físico si existe
        if ($documento->archivo) {
            $filePath = public_path($documento->archivo);
            if (File::exists($filePath)) {
                try {
                    File::delete($filePath);
                } catch (\Throwable $e) {
                    // seguir adelante si falla la eliminación física
                }
            }
        }

        $documento->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Documento eliminado correctamente.']);
        }

        return redirect()->back()->with('success', 'Documento eliminado correctamente.');
    }
}
