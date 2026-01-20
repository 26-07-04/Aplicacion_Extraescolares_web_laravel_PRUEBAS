<?php
namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Informe;
use App\Models\Semestre;
use Illuminate\Support\Facades\Auth;

class InformeController extends Controller
{
    public function index($id = null)
    {
        $user = Auth::user();
        $id_semestre = $id ?? session('id_semestre_actual');
        if (!$id_semestre) {
            $id_semestre = Semestre::where('estatus', 1)->value('id_semestre');
        }
        $informes = Informe::with('semestre')
            ->where('id_semestre', $id_semestre)
            ->orderBy('fecha_generacion', 'desc')
            ->get();

        // Documentos membretados filtrados por semestre
        $documentos = \App\Models\Documento::where('id_semestre', $id_semestre)
            ->orderBy('created_at', 'desc')
            ->get();

        // Obtener actividades del semestre con la unidad relacionada y contar estudiantes
        $actividades = \App\Models\Actividad::with('unidad')
            ->where('id_semestre', $id_semestre)
            ->get();

        // Contar estudiantes por actividad
        foreach ($actividades as $actividad) {
            $actividad->total_estudiantes = \App\Models\Estudiante::where('id_actividad', $actividad->id_actividad)
                ->where('id_semestre', $id_semestre)
                ->count();
        }

        return view('administrador.informe_panel', [
            'user' => $user,
            'id_semestre' => $id_semestre,
            'informes' => $informes,
            'documentos' => $documentos,
            'actividades' => $actividades,
        ]);
    }

    public function destroy($id)
    {
        try {
            $informe = Informe::findOrFail($id);
            
            // Eliminar el archivo PDF si existe
            if ($informe->archivo && file_exists(public_path($informe->archivo))) {
                unlink(public_path($informe->archivo));
            }
            
            $informe->delete();
            
            return redirect()->back()->with('success', 'Informe eliminado correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al eliminar el informe: ' . $e->getMessage());
        }
    }
}
