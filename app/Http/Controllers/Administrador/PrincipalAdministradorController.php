<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Semestre;
use App\Models\User;

class PrincipalAdministradorController extends Controller
{
    /**
     * Display the administrator main page.
     * If a semester id is provided, load data related to that semester only.
     */
    public function index(Request $request, $id = null)
    {
        $user = Auth::user();

        $semestre = null;
        $actividades = collect();
        $estudiantes = collect();

        // Soporta filtrado por unidad (query string ?unidad=Nombre+Unidad)
        $unidad = $request->query('unidad');
        // Soporta selección de vista (query string ?view=usuarios)
        $view = $request->query('view');

        if ($id) {
            $semestre = Semestre::find($id);

            // Cargar dinámicamente Actividad y Estudiante solo si existen las clases
            $actividadClass = '\\App\\Models\\Actividad';
            $estudianteClass = '\\App\\Models\\Estudiante';

            if (class_exists($actividadClass)) {
                $actividades = $actividadClass::where('id_semestre', $id)->get();
            }

            if (class_exists($estudianteClass)) {
                $estudiantes = $estudianteClass::where('id_semestre', $id)->get();
            }
        }

        // Si se solicitó la vista de usuarios, cargar los usuarios desde la BD
        $usuarios = collect();
        if (!empty($view) && $view === 'usuarios') {
            // Cargar usuarios ordenados por nombre
            $usuarios = User::orderBy('nombre')->get();
        }

        // Pasamos el nombre de unidad, la vista solicitada y la colección de usuarios a la vista
        return view('administrador.Principal_administrador', compact('user', 'semestre', 'actividades', 'estudiantes', 'unidad', 'view', 'usuarios'));
    }
}
