<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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

        // Si se solicitó la vista de documentos, asegurarnos de que la variable $semestre
        // esté definida usando el id en la ruta o el semestre activo (misma lógica que usuarios)
        if (!empty($view) && $view === 'documentos') {
            $semestreId = null;
            if ($semestre) {
                $semestreId = $semestre->id_semestre;
            } else {
                $semestreId = Semestre::where('estatus', 1)->value('id_semestre');
            }

            if ($semestreId && ! $semestre) {
                $semestre = Semestre::find($semestreId);
            }
        }

        // Pasamos el nombre de unidad, la vista solicitada y la colección de usuarios a la vista
        return view('administrador.Principal_administrador', compact('user', 'semestre', 'actividades', 'estudiantes', 'unidad', 'view', 'usuarios'));
    }

    public function vistaPrevia(Request $request)
    {
        $unidad = (string) $request->query('unidad', '');
        $u = Str::of($unidad)->ascii()->lower()->trim()->__toString();

        // Unión Hidalgo
        if (Str::of($u)->contains('union hidalgo')) {
            return view('administrador.vista_previa_U.actividadesUH', compact('unidad'));
        }

        // Demetrio / Vallejo
        if (Str::of($u)->contains('demetr') || Str::of($u)->contains('vallej') || Str::of($u)->contains('demetria')) {
            return view('administrador.vista_previa_U.actividadesDV', compact('unidad'));
        }

        // Tlahuitoltepec
        if (Str::of($u)->contains('tlahui') || Str::of($u)->contains('tlahuitol') || Str::of($u)->contains('tlahuitoltepec')) {
            return view('administrador.vista_previa_U.actividadesSMT', compact('unidad'));
        }

        // Valle de Etla / Valle
        if (Str::of($u)->contains('valle') || Str::of($u)->contains('valle de etla') || Str::of($u)->contains('valle de')) {
            return view('administrador.vista_previa_U.actividadesVE', compact('unidad'));
        }

        // Vista genérica si existe
        $general = 'administrador.vista_previa_U.actividades_general';
        if (view()->exists($general)) {
            return view($general, compact('unidad'));
        }

        return redirect()->route('home')->with('warning', "Vista previa no disponible para la unidad: {$unidad}");
    }

    // Mostrar la vista detalle de una actividad (Unión Hidalgo)
    public function vistaDetalle(Request $request)
    {
        $id = $request->query('id_actividad');
        $actividad = null;

        if ($id) {
            try {
                $actividad = DB::table('actividades')->where('id', $id)->first();
            } catch (\Throwable $e) {
                // Si no existe tabla/columna, dejar null y evitar crash
                $actividad = null;
            }
        }

        return view('administrador.vista_previa_U.D_actividades_UH', ['actividad' => $actividad]);
    }

    // Nueva: Vista detalle para Demetrio Vallejo
    public function vistaDetalleDV(Request $request)
    {
        $id = $request->query('id_actividad');
        $actividad = null;
        if ($id) {
            try {
                $actividad = DB::table('actividades')->where('id', $id)->first();
            } catch (\Throwable $e) {
                $actividad = null;
            }
        }
        return view('administrador.vista_previa_U.D_actividades_DV', ['actividad' => $actividad]);
    }

    // Nueva: Vista detalle para Tlahuitoltepec
    public function vistaDetalleSMT(Request $request)
    {
        $id = $request->query('id_actividad');
        $actividad = null;
        if ($id) {
            try {
                $actividad = DB::table('actividades')->where('id', $id)->first();
            } catch (\Throwable $e) {
                $actividad = null;
            }
        }
        return view('administrador.vista_previa_U.D_actividades_SMT', ['actividad' => $actividad]);
    }

    // Nueva: Vista detalle para Valle de Etla
    public function vistaDetalleVE(Request $request)
    {
        $id = $request->query('id_actividad');
        $actividad = null;
        if ($id) {
            try {
                $actividad = DB::table('actividades')->where('id', $id)->first();
            } catch (\Throwable $e) {
                $actividad = null;
            }
        }
        return view('administrador.vista_previa_U.D_actividades_VE', ['actividad' => $actividad]);
    }
}
