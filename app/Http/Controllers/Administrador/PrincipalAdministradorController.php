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
            // Determinar el semestre a usar: primero el id en la ruta, si no existe usar el semestre activo
            $semestreId = null;
            if ($semestre) {
                $semestreId = $semestre->id_semestre;
            } else {
                $semestreId = Semestre::where('estatus', 1)->value('id_semestre');
            }

            if ($semestreId) {
                // Cargar solo usuarios pertenecientes a ese semestre
                $usuarios = User::where('id_semestre', $semestreId)->orderBy('nombre')->get();
                // Si no se tenía el modelo $semestre (porque venimos sin id en la ruta)
                // cargamos el modelo para que la vista conozca el semestre actual
                if (!$semestre) {
                    $semestre = Semestre::find($semestreId);
                }
            } else {
                // Si no hay semestre activo ni seleccionado, devolver colección vacía
                $usuarios = collect();
            }
        }

        // Pasamos el nombre de unidad, la vista solicitada y la colección de usuarios a la vista
        return view('administrador.Principal_administrador', compact('user', 'semestre', 'actividades', 'estudiantes', 'unidad', 'view', 'usuarios'));
    }
}
