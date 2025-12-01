<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Semestre;
use App\Models\User;
use App\Models\Actividad;
use App\Models\Estudiante;
use App\Models\Unidad;

class PrincipalAdministradorController extends Controller
{
    /**
     * Display the administrator main page.
     */
    public function index(Request $request, $id = null)
    {
        $user = Auth::user();

        // Obtener parámetros de la URL
        $view = $request->query('view');
        $unidadNombre = $request->query('unidad');

        // Variables iniciales
        $semestre = null;
        $actividades = collect();
        $estudiantes = collect();
        $usuarios = collect();
        $id_semestre = null; // Inicialmente null

        // CASO 1: Si NO hay id en la ruta y NO hay parámetros (ESTO ES EL INICIO)
        if (!$id && !$view && !$unidadNombre) {
            // ESTE ES EL CASO DEL ENLACE "INICIO" - mostrar vista de bienvenida
            return view('administrador.Principal_administrador', [
                'user' => $user,
                'semestre' => null,
                'actividades' => $actividades,
                'estudiantes' => $estudiantes,
                'unidad' => null,
                'view' => null,
                'usuarios' => $usuarios,
                'id_semestre' => null // IMPORTANTE: null para que INICIO no tenga semestre
            ]);
        }

        // Guardar id_semestre en la sesión si viene
        if ($id) {
            session(['id_semestre_actual' => $id]);
        }
        
        // Obtener id_semestre de la sesión o del parámetro
        $id_semestre = $id ?: session('id_semestre_actual', null);

        // CASO 2: Si hay una vista específica (documentos o usuarios)
        if ($view) {
            // Determinar el semestre a usar
            $semestreIdParaView = $id_semestre;
            
            if (!$semestreIdParaView) {
                // Intentar obtener el semestre activo
                $semestreIdParaView = Semestre::where('estatus', 1)->value('id_semestre');
            }
            
            if ($semestreIdParaView) {
                // Cargar el semestre para la vista
                $semestre = Semestre::find($semestreIdParaView);
                
                // Si es vista de usuarios, cargar usuarios del semestre
                if ($view === 'usuarios') {
                    $usuarios = User::where('id_semestre', $semestreIdParaView)
                        ->orderBy('nombre')
                        ->get();
                }
            }
            
            return view('administrador.Principal_administrador', 
                compact('user', 'semestre', 'actividades', 'estudiantes', 'unidadNombre', 'view', 'usuarios', 'id_semestre'));
        }

        // CASO 3: Si hay una unidad seleccionada
        if ($unidadNombre) {
            // Convertir nombre de unidad a ID
            $unidadId = $this->convertirNombreUnidadAId($unidadNombre);
            
            // Obtener el semestre actual si no hay id_semestre
            if (!$id_semestre) {
                $semestreActivo = Semestre::where('estatus', 1)->first();
                if ($semestreActivo) {
                    $id_semestre = $semestreActivo->id_semestre;
                    $semestre = $semestreActivo;
                }
            } else {
                // Cargar el semestre si hay id_semestre
                $semestre = Semestre::find($id_semestre);
            }
            
            // Obtener actividades para esta unidad y semestre (si hay)
            $query = Actividad::where('id_unidad', $unidadId);
            
            if ($id_semestre) {
                $query->where('id_semestre', $id_semestre);
            }
            
            $actividades = $query->get();
            
            // Obtener estudiantes para estas actividades
            $estudianteIds = [];
            foreach ($actividades as $actividad) {
                if (method_exists($actividad, 'estudiantes')) {
                    $estudianteIds = array_merge($estudianteIds, 
                        $actividad->estudiantes->pluck('id')->toArray()
                    );
                }
            }
            
            if (!empty($estudianteIds)) {
                $estudiantes = Estudiante::whereIn('id', array_unique($estudianteIds))->get();
            }

            return view('administrador.Principal_administrador', [
                'user' => $user,
                'semestre' => $semestre,
                'actividades' => $actividades,
                'estudiantes' => $estudiantes,
                'unidad' => $unidadNombre,
                'view' => null,
                'usuarios' => $usuarios,
                'id_semestre' => $id_semestre
            ]);
        }

        // CASO 4: Si hay un id_semestre pero no unidad ni view (semestre seleccionado)
        if ($id_semestre) {
            $semestre = Semestre::find($id_semestre);
            
            if ($semestre) {
                // Obtener actividades para este semestre
                $actividades = Actividad::where('id_semestre', $id_semestre)->get();
                
                // Obtener estudiantes para este semestre
                $estudianteIds = [];
                foreach ($actividades as $actividad) {
                    if (method_exists($actividad, 'estudiantes')) {
                        $estudianteIds = array_merge($estudianteIds, 
                            $actividad->estudiantes->pluck('id')->toArray()
                        );
                    }
                }
                
                if (!empty($estudianteIds)) {
                    $estudiantes = Estudiante::whereIn('id', array_unique($estudianteIds))->get();
                }
                
                return view('administrador.Principal_administrador', [
                    'user' => $user,
                    'semestre' => $semestre,
                    'actividades' => $actividades,
                    'estudiantes' => $estudiantes,
                    'unidad' => null,
                    'view' => null,
                    'usuarios' => $usuarios,
                    'id_semestre' => $id_semestre
                ]);
            }
        }

        // CASO DE FALLO: Si llegamos aquí, mostrar vista de bienvenida
        return view('administrador.Principal_administrador', [
            'user' => $user,
            'semestre' => null,
            'actividades' => collect(),
            'estudiantes' => collect(),
            'unidad' => null,
            'view' => null,
            'usuarios' => $usuarios,
            'id_semestre' => null
        ]);
    }

    /**
     * Convierte el nombre de la unidad a ID numérico
     */
    private function convertirNombreUnidadAId($nombre)
    {
        $unidades = [
            'Union Hidalgo' => 1,
            'Unión Hidalgo' => 1,
            'Demetrio Vallejo' => 2,
            'Demetrio+Vallejo' => 2,
            'Tlahuitoltepec' => 3,
            'Santa María Tlahuitoltepec' => 3,
            'Valle de Etla' => 4,
            'Valle+de+Etla' => 4
        ];
        
        return $unidades[$nombre] ?? 1; // Default a Unión Hidalgo
    }

    // Los demás métodos permanecen igual...
    public function vistaPrevia(Request $request)
    {
        $unidad = (string) $request->query('unidad', '');
        $id_semestre = (int) $request->query('id_semestre', 0);
        
        $u = Str::of($unidad)->ascii()->lower()->trim()->__toString();

        if (Str::of($u)->contains('union hidalgo')) {
            return view('administrador.vista_previa_U.actividadesUH', 
                compact('unidad', 'id_semestre'));
        }

        if (Str::of($u)->contains('demetr') || Str::of($u)->contains('vallej') || Str::of($u)->contains('demetria')) {
            return view('administrador.vista_previa_U.actividadesDV', 
                compact('unidad', 'id_semestre'));
        }

        if (Str::of($u)->contains('tlahui') || Str::of($u)->contains('tlahuitol') || Str::of($u)->contains('tlahuitoltepec')) {
            return view('administrador.vista_previa_U.actividadesSMT', 
                compact('unidad', 'id_semestre'));
        }

        if (Str::of($u)->contains('valle') || Str::of($u)->contains('valle de etla') || Str::of($u)->contains('valle de')) {
            return view('administrador.vista_previa_U.actividadesVE', 
                compact('unidad', 'id_semestre'));
        }

        $general = 'administrador.vista_previa_U.actividades_general';
        if (view()->exists($general)) {
            return view($general, compact('unidad', 'id_semestre'));
        }

        return redirect()->route('home')->with('warning', "Vista previa no disponible para la unidad: {$unidad}");
    }

    public function vistaDetalle(Request $request)
    {
        $id = $request->query('id_actividad');
        $id_semestre = $request->query('id_semestre');
        $actividad = null;

        if ($id) {
            try {
                $actividad = DB::table('actividades')->where('id', $id)->first();
            } catch (\Throwable $e) {
                $actividad = null;
            }
        }

        return view('administrador.vista_previa_U.D_actividades_UH', 
            ['actividad' => $actividad, 'id_semestre' => $id_semestre]);
    }

    public function vistaDetalleDV(Request $request)
    {
        $id = $request->query('id_actividad');
        $id_semestre = $request->query('id_semestre');
        $actividad = null;
        if ($id) {
            try {
                $actividad = DB::table('actividades')->where('id', $id)->first();
            } catch (\Throwable $e) {
                $actividad = null;
            }
        }
        return view('administrador.vista_previa_U.D_actividades_DV', 
            ['actividad' => $actividad, 'id_semestre' => $id_semestre]);
    }

    public function vistaDetalleSMT(Request $request)
    {
        $id = $request->query('id_actividad');
        $id_semestre = $request->query('id_semestre');
        $actividad = null;
        if ($id) {
            try {
                $actividad = DB::table('actividades')->where('id', $id)->first();
            } catch (\Throwable $e) {
                $actividad = null;
            }
        }
        return view('administrador.vista_previa_U.D_actividades_SMT', 
            ['actividad' => $actividad, 'id_semestre' => $id_semestre]);
    }

    public function vistaDetalleVE(Request $request)
    {
        $id = $request->query('id_actividad');
        $id_semestre = $request->query('id_semestre');
        $actividad = null;
        if ($id) {
            try {
                $actividad = DB::table('actividades')->where('id', $id)->first();
            } catch (\Throwable $e) {
                $actividad = null;
            }
        }
        return view('administrador.vista_previa_U.D_actividades_VE', 
            ['actividad' => $actividad, 'id_semestre' => $id_semestre]);
    }
    
    /**
     * Muestra la lista de semestres para seleccionar
     */
    public function semestres()
    {
        $semestres = Semestre::orderBy('created_at', 'desc')->get();
        return view('admin.semestres', compact('semestres'));
    }
}