<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Actividad;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ActividadController extends Controller
{
    // Listado JSON (filtros: id_unidad, id_semestre)
    public function index(Request $request)
    {
        $query = Actividad::query();

        if ($request->has('id_unidad') && $request->query('id_unidad') !== '') {
            $query->where('id_unidad', $request->query('id_unidad'));
        }

        if ($request->has('id_semestre') && $request->query('id_semestre') !== '') {
            $query->where('id_semestre', $request->query('id_semestre'));
        }

        $actividades = $query->get()->map(function ($a) {
            $item = $a->toArray();
            $item['id_actividad'] = $a->getKey();
            $item['imagen_url'] = $a->imagen_url ?? ($a->imagen ?? null);
            return $item;
        });

        return response()->json($actividades);
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

        // Si la petición acepta JSON, devolvemos la entidad
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($actividad);
        }

        // Si no es petición AJAX, renderizar vista de detalle (opcional)
        // Ajusta la vista si quieres mostrar una página html directamente
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
            'imagen' => 'nullable|image|max:5120'
        ]);

        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()], 422);
        }

        try {
            $data = $request->only(['nombre_actividad','descripcion','id_unidad','id_semestre']);

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
                'imagen_url' => $actividad->imagen_url ?? null
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

        $v = Validator::make($request->all(), [
            'nombre_actividad' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'id_unidad' => 'nullable|integer',
            'id_semestre' => 'nullable|integer',
            'imagen' => 'nullable|image|max:5120'
        ]);

        if ($v->fails()) return response()->json(['errors' => $v->errors()], 422);

        $actividad->nombre_actividad = $request->input('nombre_actividad', $actividad->nombre_actividad);
        $actividad->descripcion = $request->input('descripcion', $actividad->descripcion);
        if ($request->filled('id_unidad')) $actividad->id_unidad = $request->input('id_unidad');
        if ($request->filled('id_semestre')) $actividad->id_semestre = $request->input('id_semestre');

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

        if (!empty($actividad->imagen_url) && File::exists(public_path($actividad->imagen_url))) {
            @unlink(public_path($actividad->imagen_url));
        }

        $actividad->delete();

        return response()->json(['success' => true]);
    }
}
