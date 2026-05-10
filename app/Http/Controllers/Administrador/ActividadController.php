<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Actividad;
use App\Models\Semestre;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ActividadController extends Controller
{
    // Listado JSON (filtros: id_unidad, id_semestre) - CRÍTICO para el flujo
    public function index(Request $request)
    {
        $query = Actividad::query();

        // CAMBIO 1: Prioridad al id_semestre de la sesión si no viene en request
        $id_semestre = $request->query('id_semestre');
        
        if (!$id_semestre) {
            // Intentar obtener de la sesión (viene desde principaladministrador)
            $id_semestre = session('id_semestre_actual');
        }
        
        if (!$id_semestre) {
            // Último recurso: obtener semestre activo
            $semestreActivo = Semestre::where('estatus', 1)->first();
            $id_semestre = $semestreActivo ? $semestreActivo->id_semestre : null;
        }

        // CAMBIO 2: Filtrar SIEMPRE por id_semestre si existe
        if ($id_semestre) {
            $query->where('id_semestre', $id_semestre);
        } else {
            // Si no hay semestre, devolver vacío en lugar de todas las actividades
            return response()->json([]);
        }

        // CAMBIO 3: Filtrar también por unidad si viene en request
        if ($request->has('id_unidad') && $request->query('id_unidad') !== '') {
            $query->where('id_unidad', $request->query('id_unidad'));
        }

        if ($request->filled('tipo_programa')) {
            $tp = $request->query('tipo_programa');
            if (in_array($tp, [Actividad::TIPO_EXTRAESCOLAR, Actividad::TIPO_COMPLEMENTARIA], true)) {
                $query->where('tipo_programa', $tp);
            }
        }

        $actividades = $query->get()->map(function ($a) {
            $item = $a->toArray();
            $item['id_actividad'] = $a->getKey();
            $item['imagen_url'] = $a->imagen_url ?? ($a->imagen ?? null);
            $item['categorias'] = $a->categorias ?? ''; // Asegurar que categorias siempre esté presente
            return $item;
        });

        // CAMBIO 4: Devolver también el id_semestre usado para debug
        return response()->json([
            'actividades' => $actividades,
            'id_semestre_filtrado' => $id_semestre,
            'total' => $actividades->count()
        ]);
    }

    // Mostrar detalle de actividad (devuelve JSON si la petición lo solicita)
    public function show($id, Request $request)
    {
        // Buscar por la columna real (id_actividad) para evitar problemas si el modelo no usa 'id'
        $actividad = Actividad::where('id_actividad', $id)->first();

        if (! $actividad) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Actividad no encontrada'], 404);
            }
            abort(404, 'Actividad no encontrada');
        }

        // CAMBIO 5: Verificar que la actividad pertenezca al semestre actual
        $id_semestre_actual = $request->query('id_semestre') ?? session('id_semestre_actual');
        if ($id_semestre_actual && $actividad->id_semestre != $id_semestre_actual) {
            return response()->json(['message' => 'Actividad no pertenece al semestre actual'], 403);
        }

        // Si la petición acepta JSON, devolvemos la entidad
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($actividad);
        }

        // Si no es petición AJAX, renderizar vista de detalle (opcional)
        return view('administrador.vista_previa_U.D_actividades_UH', [
            'actividad' => $actividad,
            'id_actividad' => $actividad->id_actividad,
            'id_unidad' => $actividad->id_unidad ?? null,
            'id_semestre' => $actividad->id_semestre ?? null,
        ]);
    }

    // PATCH: store robusto para que no devuelva 500 silencioso
    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'nombre_actividad' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'id_unidad' => 'required|integer',
            'id_semestre' => 'required|integer',
            'imagen' => 'nullable|image|max:5120',
            'categorias' => 'nullable|string',
            'tipo_programa' => ['nullable', 'string', Rule::in([Actividad::TIPO_EXTRAESCOLAR, Actividad::TIPO_COMPLEMENTARIA])],
        ]);

        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()], 422);
        }

        // CAMBIO 6: Verificar que el semestre existe
        $id_semestre = $request->input('id_semestre');
        $semestre = Semestre::find($id_semestre);
        
        if (!$semestre) {
            return response()->json([
                'errors' => ['id_semestre' => ['El semestre especificado no existe']]
            ], 422);
        }

        try {
            $data = $request->only(['nombre_actividad','descripcion','id_unidad','id_semestre','categorias','tipo_programa']);
            
            // CAMBIO 7: Asegurar que el id_semestre se guarda correctamente
            $data['id_semestre'] = $id_semestre;
            $data['tipo_programa'] = $request->input('tipo_programa', Actividad::TIPO_EXTRAESCOLAR);
            if (! in_array($data['tipo_programa'], [Actividad::TIPO_EXTRAESCOLAR, Actividad::TIPO_COMPLEMENTARIA], true)) {
                $data['tipo_programa'] = Actividad::TIPO_EXTRAESCOLAR;
            }

            if ($request->hasFile('imagen')) {
                $file = $request->file('imagen');
                $safeName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                $dir = public_path('Imagenes');
                if (! File::exists($dir)) {
                    File::makeDirectory($dir, 0755, true);
                }
                $file->move($dir, $safeName);
                $data['imagen_url'] = 'Imagenes/' . $safeName;
            }

            $actividad = Actividad::create($data);

            return response()->json([
                'id_actividad' => $actividad->getKey(),
                'nombre_actividad' => $actividad->nombre_actividad,
                'descripcion' => $actividad->descripcion,
                'id_unidad' => $actividad->id_unidad,
                'id_semestre' => $actividad->id_semestre,
                'imagen_url' => $actividad->imagen_url ?? null,
                'categorias' => $actividad->categorias ?? null,
                'tipo_programa' => $actividad->tipo_programa ?? Actividad::TIPO_EXTRAESCOLAR,
            ], 201);
        } catch (\Throwable $e) {
            Log::error('Actividad store error: '.$e->getMessage(), ['trace' => $e->getTraceAsString(), 'input' => $request->all()]);
            return response()->json(['message' => 'Error interno al crear actividad', 'error' => $e->getMessage()], 500);
        }
    }

    // Actualizar
    public function update(Request $request, $id)
    {
        $actividad = Actividad::find($id);
        if (!$actividad) return response()->json(['message' => 'No encontrado'], 404);

        // CAMBIO 8: Verificar que la actividad pertenece al semestre actual
        $id_semestre_actual = $request->input('id_semestre') ?? session('id_semestre_actual');
        if ($id_semestre_actual && $actividad->id_semestre != $id_semestre_actual) {
            return response()->json(['message' => 'No puedes modificar actividades de otro semestre'], 403);
        }

        $v = Validator::make($request->all(), [
            'nombre_actividad' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'id_unidad' => 'nullable|integer',
            'id_semestre' => 'nullable|integer',
            'imagen' => 'nullable|image|max:5120',
            'categorias' => 'nullable|string',
            'tipo_programa' => ['nullable', 'string', Rule::in([Actividad::TIPO_EXTRAESCOLAR, Actividad::TIPO_COMPLEMENTARIA])],
        ]);

        if ($v->fails()) return response()->json(['errors' => $v->errors()], 422);

        $actividad->nombre_actividad = $request->input('nombre_actividad', $actividad->nombre_actividad);
        $actividad->descripcion = $request->input('descripcion', $actividad->descripcion);
        
        // CAMBIO 9: Solo actualizar unidad y semestre si se proporcionan y son válidos
        if ($request->filled('id_unidad')) {
            $actividad->id_unidad = $request->input('id_unidad');
        }
        
        if ($request->filled('id_semestre')) {
            // Verificar que el nuevo semestre existe
            $nuevoSemestre = Semestre::find($request->input('id_semestre'));
            if (!$nuevoSemestre) {
                return response()->json(['errors' => ['id_semestre' => ['El semestre no existe']]], 422);
            }
            $actividad->id_semestre = $request->input('id_semestre');
        }

        if ($request->filled('categorias')) {
            $actividad->categorias = $request->input('categorias');
        }

        if ($request->filled('tipo_programa')) {
            $tp = $request->input('tipo_programa');
            $actividad->tipo_programa = in_array($tp, [Actividad::TIPO_EXTRAESCOLAR, Actividad::TIPO_COMPLEMENTARIA], true)
                ? $tp
                : Actividad::TIPO_EXTRAESCOLAR;
        }

        if ($request->hasFile('imagen')) {
            if (!empty($actividad->imagen_url) && File::exists(public_path($actividad->imagen_url))) {
                @unlink(public_path($actividad->imagen_url));
            }
            $file = $request->file('imagen');
            $nombre = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $dir = public_path('Imagenes');
            if (!File::exists($dir)) File::makeDirectory($dir, 0755, true);
            $file->move($dir, $nombre);
            $actividad->imagen_url = 'Imagenes/' . $nombre;
        }

        $actividad->save();

        $resp = $actividad->toArray();
        $resp['id_actividad'] = $actividad->getKey();
        $resp['imagen_url'] = $actividad->imagen_url ?? ($actividad->imagen ?? null);

        return response()->json($resp);
    }

    // Eliminar
    public function destroy($id)
    {
        $actividad = Actividad::find($id);
        if (!$actividad) return response()->json(['message' => 'No encontrado'], 404);

        // CAMBIO 10: Verificar que la actividad pertenece al semestre actual
        $id_semestre_actual = session('id_semestre_actual');
        if ($id_semestre_actual && $actividad->id_semestre != $id_semestre_actual) {
            return response()->json(['message' => 'No puedes eliminar actividades de otro semestre'], 403);
        }

        if (!empty($actividad->imagen_url) && File::exists(public_path($actividad->imagen_url))) {
            @unlink(public_path($actividad->imagen_url));
        }

        $actividad->delete();

        return response()->json(['success' => true]);
    }
}