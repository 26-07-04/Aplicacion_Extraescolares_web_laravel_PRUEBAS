
<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Administrador\SemestresCursadosController;
use App\Http\Controllers\Administrador\PrincipalAdministradorController;
use App\Http\Controllers\Administrador\UserController as AdminUserController;
use App\Http\Controllers\Coordinador\SemestresCursadosValleController as CoordinadorSemestresValleController;
use App\Http\Controllers\Coordinador\SemestresCursadosUnionController as CoordinadorSemestresUnionController;
use App\Http\Controllers\Coordinador\PanelUnionHidalgoController;
use App\Http\Controllers\Coordinador\PanelValleEtlaController;
use App\Http\Controllers\Administrador\DocumentoController;
use App\Http\Controllers\Coordinador\PanelTlahuitoltepecController;
use App\Http\Controllers\Coordinador\SemestresCursadosTlahuitoltepecController as CoordinadorSemestresTlahController;
use App\Http\Controllers\Coordinador\SemestresCursadosDemetrioController as CoordinadorSemestresDemetrioController;
use App\Http\Controllers\Coordinador\PanelDemetrioVallejoController;
use App\Http\Controllers\Coordinador\ImportEstudiantesController;
use App\Http\Controllers\Coordinador\InformeDemetrioController;
use App\Http\Controllers\Coordinador\InformeTlahuitoltepecController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UnidadController;
use App\Http\Controllers\Administrador\ActividadController;
use App\Http\Controllers\Administrador\EstudianteController;
use Illuminate\Http\Request;
/*
Route::get('/', function () {
    return view('welcome');
});
*/
Route::get('/', function () {
    return view('home');
})->name('home');

// Note: authentication routes are defined in routes/auth.php

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::get('/dashboard', [UserController::class, 'home'])
->middleware(['auth', 'verified'])
->name('dashboard');

Route::get('admin/dashboard', function (\Illuminate\Http\Request $request) {
    $user = $request->user();
    if (!$user || $user->rol !== 'Administrador') {
        abort(403);
    }
    return view('admin.dashboard', ['user' => $user]);
})->middleware('auth')->name('admin.dashboard');

// Panel principal filtrable por semestre
Route::get('admin/principal/{id?}', [PrincipalAdministradorController::class, 'index'])
    ->middleware('auth')
    ->name('admin.principal');

// Gestión de semestres (ya existente, equivalente a "semestres")
Route::get('admin/semestres', [SemestresCursadosController::class, 'index'])
    ->middleware('auth')
    ->name('admin.semestres');

Route::post('admin/semestres', [SemestresCursadosController::class, 'store'])
    ->middleware('auth')
    ->name('admin.semestres.store');

Route::put('admin/semestres/{id}', [SemestresCursadosController::class, 'update'])
    ->middleware('auth')
    ->name('admin.semestres.update');

Route::delete('admin/semestres/{id}', [SemestresCursadosController::class, 'destroy'])
    ->middleware('auth')
    ->name('admin.semestres.destroy');

// Rutas para documentos (subir y descargar)
Route::post('admin/documentos', [DocumentoController::class, 'store'])
    ->middleware('auth')
    ->name('admin.documentos.store');

Route::get('admin/documentos/{id}/download', [DocumentoController::class, 'download'])
    ->middleware('auth')
    ->name('admin.documentos.download');

Route::delete('admin/documentos/{id}', [DocumentoController::class, 'destroy'])
    ->middleware('auth')
    ->name('admin.documentos.destroy');

// ============ RUTAS PARA GESTIÓN DE USUARIOS (ADMINISTRADOR) ============
Route::middleware(['auth'])->prefix('admin')->group(function () {    // Gestión de usuarios
    Route::prefix('usuarios')->group(function () {
        Route::get('/', [AdminUserController::class, 'index'])->name('admin.usuarios.index');
        Route::post('/', [AdminUserController::class, 'store'])->name('admin.usuarios.store');
        Route::get('/{id}/edit', [AdminUserController::class, 'edit'])->name('admin.usuarios.edit');
        Route::put('/{id}', [AdminUserController::class, 'update'])->name('admin.usuarios.update');
        Route::delete('/{id}', [AdminUserController::class, 'destroy'])->name('admin.usuarios.destroy');
    });

    // Gestión de documentos
    Route::prefix('documentos')->group(function () {
        Route::get('/', [DocumentoController::class, 'index'])->name('admin.documentos.index');
        Route::post('/', [DocumentoController::class, 'store'])->name('admin.documentos.store');
        Route::delete('/{id}', [DocumentoController::class, 'destroy'])->name('admin.documentos.destroy');
    });
});

// Ruta para activar un semestre (solo un semestre activo a la vez)
Route::post('admin/semestres/{id}/activar', [SemestresCursadosController::class, 'activarSemestre'])
    ->middleware('auth')
    ->name('admin.semestres.activar');

// Versiones por Unidad Académica (solo lectura) para Coordinador
Route::get('coordinador/semestres/valle-de-etla', [CoordinadorSemestresValleController::class, 'index'])
    ->middleware('auth')
    ->name('coordinador.semestres.valle');

Route::get('coordinador/semestres/union-hidalgo', [CoordinadorSemestresUnionController::class, 'index'])
    ->middleware('auth')
    ->name('coordinador.semestres.union');

// Panel principal del Coordinador para la Unidad Unión Hidalgo (vista por semestre)
Route::get('coordinador/union-hidalgo/panel/{id}', [PanelUnionHidalgoController::class, 'show'])
    ->middleware('auth')
    ->name('coordinador.union.panel');

// Panel principal del Coordinador para la Unidad Valle de Etla (vista por semestre)
Route::get('coordinador/valle-de-etla/panel/{id}', [PanelValleEtlaController::class, 'show'])
    ->middleware('auth')
    ->name('coordinador.valle.panel');


    // Rutas para PDFs membretados (coordinador)
Route::prefix('coordinador')->group(function () {
    // Listar todos los PDFs membretados
    Route::get('/documento-base', [App\Http\Controllers\Coordinador\DocumentoBaseController::class, 'index'])->name('documento-base.index');

    // Ver información de un PDF membretado
    Route::get('/documento-base/{id}', [App\Http\Controllers\Coordinador\DocumentoBaseController::class, 'show'])->name('documento-base.show');

    // Descargar o visualizar el PDF membretado
    Route::get('/documento-base/cargar/{id}', [App\Http\Controllers\Coordinador\DocumentoBaseController::class, 'cargar'])
        ->name('documento-base.cargar');
});







// Rutas para el informe de Tlahuitoltepec (unidad)
Route::get('/coordinador/tlahuitoltepec/informe', [InformeTlahuitoltepecController::class, 'index'])->name('informe_tlahuitoltepec.index');
Route::post('/coordinador/tlahuitoltepec/informe/guardar', [InformeTlahuitoltepecController::class, 'guardarDatos'])->name('informe_tlahuitoltepec.guardar');
Route::post('/coordinador/tlahuitoltepec/informe/subir-pdf', [InformeTlahuitoltepecController::class, 'subirPDF'])->name('informe_tlahuitoltepec.subir_pdf');
Route::post('/coordinador/tlahuitoltepec/informe/generar-pdf', [InformeTlahuitoltepecController::class, 'generarPDFLaravel'])->name('informe_tlahuitoltepec.generar_pdf');




// ============ RUTAS PARA INFORME FINAL (DEMETRIO VALLEJO) ============
Route::middleware(['auth'])->prefix('coordinador/demetrio-vallejo')->group(function () {
    Route::get('informe', [InformeDemetrioController::class, 'index'])
        ->name('demetrio.informe.index');
    Route::post('informe/guardar', [InformeDemetrioController::class, 'guardarDatos'])
        ->name('demetrio.informe.guardar');
    Route::post('informe/subir-pdf', [InformeDemetrioController::class, 'subirPDF'])
        ->name('demetrio.informe.subir');
    Route::post('informe/generar', [InformeDemetrioController::class, 'generarPDFLaravel'])
        ->name('demetrio.informe.generar');
});


// Panel principal del Coordinador para la Unidad Tlahuitoltepec (vista por semestre)
Route::get('coordinador/tlahuitoltepec/panel/{id}', [PanelTlahuitoltepecController::class, 'show'])
    ->middleware('auth')
    ->name('coordinador.tlahuitoltepec.panel');

// Panel principal del Coordinador para la Unidad Demetrio Vallejo (vista por semestre)
Route::get('coordinador/demetrio-vallejo/panel/{id}', [PanelDemetrioVallejoController::class, 'show'])
    ->middleware('auth')
    ->name('coordinador.demetrio.panel');
// Ruta de impresión de resultados (Demetrio Vallejo)
Route::get('coordinador/demetrio-vallejo/resultados/print/{id}', [PanelDemetrioVallejoController::class, 'printResultados'])
    ->middleware('auth')
    ->name('coordinador.demetrio.resultados.print');

// Ruta de impresión de resultados (Tlahuitoltepec)
Route::get('coordinador/tlahuitoltepec/resultados/print/{id}', [PanelTlahuitoltepecController::class, 'printResultados'])
    ->middleware('auth')
    ->name('coordinador.tlahuitoltepec.resultados.print');

// Ruta de impresión de resultados (Unión Hidalgo)
Route::get('coordinador/union-hidalgo/resultados/print/{id}', [PanelUnionHidalgoController::class, 'printResultados'])
    ->middleware('auth')
    ->name('coordinador.union_hidalgo.resultados.print');

// Ruta de impresión de resultados (Valle de Etla)
Route::get('coordinador/valle-de-etla/resultados/print/{id}', [PanelValleEtlaController::class, 'printResultados'])
    ->middleware('auth')
    ->name('coordinador.valle.resultados.print');

Route::get('coordinador/semestres/tlahuitoltepec', [CoordinadorSemestresTlahController::class, 'index'])
    ->middleware('auth')
    ->name('coordinador.semestres.tlahuitoltepec');

Route::get('coordinador/semestres/demetrio-vallejo', [CoordinadorSemestresDemetrioController::class, 'index'])
    ->middleware('auth')
    ->name('coordinador.semestres.demetrio');

// Rutas placeholder para evitar errores cuando las vistas fueron eliminadas
// Usar redirect()->back() para permanecer en la misma unidad en lugar
// de volver al panel que depende de `auth()->user()->unidad_academica`.
Route::get('coordinador/ver-estudiantes', function (Request $request) {
    return redirect()->back();
})->middleware('auth')->name('coordinador.verestudiantes');

Route::get('coordinador/constancia', function (Request $request) {
    return redirect()->back();
})->middleware('auth')->name('coordinador.constancia');




Route::get('admin/about', [UserController::class, 'about'])
    ->middleware(['auth', 'admin'])
    ->name('admin.about');

Route::get('admin/contact', [UserController::class, 'contact'])
    ->middleware(['auth', 'admin'])
    ->name('admin.contact');

// Coordinator dashboard
Route::get('coordinator/dashboard', function (\Illuminate\Http\Request $request) {
    $user = $request->user();
    if (!$user || $user->rol !== 'Coordinador') {
        abort(403);
    }
    return view('dashboard.coordinator', ['user' => $user]);
})->middleware('auth')->name('coordinator.dashboard');

Route::get('admin/vista-previa', [PrincipalAdministradorController::class, 'vistaPrevia'])
    ->name('administrador.vista_previa');

// Ruta para el detalle de actividad (Unión Hidalgo)
Route::get('administrador/vista_previa_U/D_actividades_UH', [PrincipalAdministradorController::class, 'vistaDetalle'])
    ->name('administrador.vista_detalle');

// Nueva ruta para el detalle de actividad (Demetrio Vallejo)
Route::get('administrador/vista_previa_U/D_actividades_DV', [PrincipalAdministradorController::class, 'vistaDetalleDV'])
    ->name('administrador.vista_detalle_dv');

// Ruta detalle para SMT (Tlahuiltoltepec)
Route::get('administrador/vista_previa_U/D_actividades_SMT', [PrincipalAdministradorController::class, 'vistaDetalleSMT'])
    ->name('administrador.vista_detalle_smt');

Route::get('administrador/vista_previa_U/D_actividades_VE', [PrincipalAdministradorController::class, 'vistaDetalleVE'])
    ->name('administrador.vista_detalle_ve');


// PANEL ADMIN - TODAS LAS UNIDADES
Route::get('/admin/unidades', [UnidadController::class, 'index'])
    ->middleware(['auth'])
    ->name('admin.unidades');

// PANEL COORDINADOR - SOLO SU UNIDAD
Route::get('/coordinator/unidad', [UnidadController::class, 'miUnidad'])
    ->middleware(['auth'])
    ->name('coordinator.unidad');

// Panel principal de coordinador - muestra el panel correspondiente por unidad (si existe)
Route::get('/coordinator/panel', function (Request $request) {
    $user = $request->user();
    if (! $user || $user->rol !== 'Coordinador') {
        abort(403);
    }
    $ua = $user->unidad_academica ?? '';
    if (stripos($ua, 'Unión') !== false || stripos($ua, 'Union') !== false || stripos($ua, 'Hidalgo') !== false) {
        return view('coordinador.union_hidalgo.panel', ['user' => $user, 'unidad' => 'Unidad Académica Unión Hidalgo']);
    }
    if (stripos($ua, 'Valle') !== false || stripos($ua, 'Etla') !== false) {
        return view('coordinador.valle_de_etla.panel', ['user' => $user, 'unidad' => 'Unidad Académica Valle de Etla']);
    }
    if (stripos($ua, 'Demetrio') !== false || stripos($ua, 'Vallejo') !== false) {
        return view('coordinador.demetrio_vallejo.panel', ['user' => $user, 'unidad' => 'Unidad Académica Demetrio Vallejo']);
    }
    if (stripos($ua, 'Tlahui') !== false || stripos($ua, 'Tlahuitoltepec') !== false) {
        return view('coordinador.tlahuitoltepec.panel', ['user' => $user, 'unidad' => 'Unidad Académica Santa María Tlahuitoltepec']);
    }
    // Fallback: redirigir a la lista de semestres o al dashboard
    return redirect()->route('coordinator.unidad');
})->middleware('auth')->name('coordinator.panel');

/*
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'admin'])->name('admin.dashboard');
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// CRUD y detalle para Actividades (usadas por public/js/actividades.js)
Route::prefix('administrador')->group(function () {
    Route::get('actividades', [ActividadController::class, 'index'])->name('administrador.actividades.index');
    Route::post('actividades', [ActividadController::class, 'store'])->name('administrador.actividades.store');
    Route::get('actividades/{id}', [ActividadController::class, 'show'])->name('administrador.actividades.show');
    Route::put('actividades/{id}', [ActividadController::class, 'update'])->name('administrador.actividades.update');
    Route::delete('actividades/{id}', [ActividadController::class, 'destroy'])->name('administrador.actividades.destroy');

    // Ruta de detalle que carga la vista D_actividades_UH con query params
    Route::get('D_actividades_UH', function (Request $request) {
        return view('administrador.vista_previa_U.D_actividades_UH', [
            'id_actividad' => $request->query('id_actividad'),
            'id_unidad'    => $request->query('id_unidad'),
            'id_semestre'  => $request->query('id_semestre'),
        ]);
    })->name('administrador.actividades.detalle');

    // Ruta de detalle que carga la vista D_actividades_DV con query params
    Route::get('D_actividades_DV', function (Request $request) {
        return view('administrador.vista_previa_U.D_actividades_DV', [
            'id_actividad' => $request->query('id_actividad'),
            'id_unidad'    => $request->query('id_unidad'),
            'id_semestre'  => $request->query('id_semestre'),
        ]);
    })->name('administrador.actividades.detalle');

    // Ruta de detalle que carga la vista D_actividades_SMT con query params
    Route::get('D_actividades_SMT', function (Request $request) {
        return view('administrador.vista_previa_U.D_actividades_SMT', [
            'id_actividad' => $request->query('id_actividad'),
            'id_unidad'    => $request->query('id_unidad'),
            'id_semestre'  => $request->query('id_semestre'),
        ]);
    })->name('administrador.actividades.detalle');

    // Ruta de detalle que carga la vista D_actividades_VE con query params
    Route::get('D_actividades_VE', function (Request $request) {
        return view('administrador.vista_previa_U.D_actividades_VE', [
            'id_actividad' => $request->query('id_actividad'),
            'id_unidad'    => $request->query('id_unidad'),
            'id_semestre'  => $request->query('id_semestre'),
        ]);
    })->name('administrador.actividades.detalle');
});

Route::prefix('administrador')->group(function () {
    Route::get('actividades/{actividad}/estudiantes', [EstudianteController::class, 'index']);
    Route::post('actividades/{actividad}/estudiantes', [EstudianteController::class, 'store']);
    // Ruta para importación masiva (usada por coordinador desde el panel)
    Route::post('actividades/{actividad}/estudiantes/import', [EstudianteController::class, 'bulkStore'])->name('administrador.actividades.estudiantes.import');
    Route::put('actividades/estudiantes/{id}', [EstudianteController::class, 'update']);
    Route::delete('actividades/estudiantes/{id}', [EstudianteController::class, 'destroy']);
});

// Ruta para importación usada por el panel del coordinador (controlador en carpeta Coordinador)
Route::post('coordinador/actividades/{actividad}/estudiantes/import', [ImportEstudiantesController::class, 'import'])
    ->middleware('auth')
    ->name('coordinador.actividades.estudiantes.import');

// Ruta para validar duplicados antes de subir
Route::post('coordinador/actividades/{actividad}/estudiantes/check-duplicates', [ImportEstudiantesController::class, 'checkDuplicates'])
    ->middleware('auth')
    ->name('coordinador.actividades.estudiantes.check-duplicates');

// Rutas para CRUD de estudiantes en Demetrio Vallejo
Route::post('coordinador/estudiantes/{id}/actualizar', [PanelDemetrioVallejoController::class, 'actualizarEstudiante'])
    ->middleware('auth')
    ->name('coordinador.estudiantes.actualizar');

Route::post('coordinador/estudiantes/{id}/eliminar', [PanelDemetrioVallejoController::class, 'eliminarEstudiante'])
    ->middleware('auth')
    ->name('coordinador.estudiantes.eliminar');

// Rutas para CRUD de estudiantes en Tlahuitoltepec (usar desde panel Tlahuitoltepec)
Route::post('coordinador/tlahuitoltepec/estudiantes/{id}/actualizar', [App\Http\Controllers\Coordinador\PanelTlahuitoltepecController::class, 'actualizarEstudiante'])
    ->middleware('auth')
    ->name('coordinador.tlahuitoltepec.estudiantes.actualizar');

Route::post('coordinador/tlahuitoltepec/estudiantes/{id}/eliminar', [App\Http\Controllers\Coordinador\PanelTlahuitoltepecController::class, 'eliminarEstudiante'])
    ->middleware('auth')
    ->name('coordinador.tlahuitoltepec.estudiantes.eliminar');

// Rutas para CRUD de estudiantes en Unión Hidalgo (usar desde panel Unión Hidalgo)
Route::post('coordinador/union-hidalgo/estudiantes/{id}/actualizar', [App\Http\Controllers\Coordinador\PanelUnionHidalgoController::class, 'actualizarEstudiante'])
    ->middleware('auth')
    ->name('coordinador.union_hidalgo.estudiantes.actualizar');

Route::post('coordinador/union-hidalgo/estudiantes/{id}/eliminar', [App\Http\Controllers\Coordinador\PanelUnionHidalgoController::class, 'eliminarEstudiante'])
    ->middleware('auth')
    ->name('coordinador.union_hidalgo.estudiantes.eliminar');

// Rutas para CRUD de estudiantes en Valle de Etla (usar desde panel Valle de Etla)
Route::post('coordinador/valle-de-etla/estudiantes/{id}/actualizar', [App\Http\Controllers\Coordinador\PanelValleEtlaController::class, 'actualizarEstudiante'])
    ->middleware('auth')
    ->name('coordinador.valle_de_etla.estudiantes.actualizar');

Route::post('coordinador/valle-de-etla/estudiantes/{id}/eliminar', [App\Http\Controllers\Coordinador\PanelValleEtlaController::class, 'eliminarEstudiante'])
    ->middleware('auth')
    ->name('coordinador.valle_de_etla.estudiantes.eliminar');

// ============ RUTAS PARA EVALUACIONES Y CONSTANCIAS (DEMETRIO VALLEJO) ============
use App\Http\Controllers\Coordinador\EvaluacionDemetrioController;
use App\Http\Controllers\Coordinador\EvaluacionTlahuitoltepecController;
use App\Http\Controllers\Coordinador\EvaluacionUnionHidalgoController;
use App\Http\Controllers\Coordinador\EvaluacionValleEtlaController;

Route::middleware(['auth'])->prefix('coordinador/demetrio-vallejo')->group(function () {
    // Guardar evaluación de estudiante
    Route::post('evaluacion/guardar', [EvaluacionDemetrioController::class, 'guardarEvaluacion'])
        ->name('demetrio.evaluacion.guardar');
    
    // Generar constancia en PDF
    Route::get('constancia/{id_evaluacion}/pdf', [EvaluacionDemetrioController::class, 'generarConstancia'])
        ->name('demetrio.constancia.pdf');
    
    // Obtener documentos membretados del semestre
    Route::get('documentos-membrete/{id_semestre}', [EvaluacionDemetrioController::class, 'obtenerDocumentosMembrete'])
        ->name('demetrio.documentos.membrete');
});

// ============ RUTAS PARA EVALUACIONES Y CONSTANCIAS (TLAHUITOLTEPEC) ============
Route::middleware(['auth'])->prefix('coordinador/tlahuitoltepec')->group(function () {
    // Guardar evaluación de estudiante
    Route::post('evaluacion/guardar', [EvaluacionTlahuitoltepecController::class, 'guardarEvaluacion'])
        ->name('tlahuitoltepec.evaluacion.guardar');
    
    // Generar constancia en PDF
    Route::get('constancia/{id_evaluacion}/pdf', [EvaluacionTlahuitoltepecController::class, 'generarConstancia'])
        ->name('tlahuitoltepec.constancia.pdf');
    
    // Obtener documentos membretados del semestre
    Route::get('documentos-membrete/{id_semestre}', [EvaluacionTlahuitoltepecController::class, 'obtenerDocumentosMembrete'])
        ->name('tlahuitoltepec.documentos.membrete');
});

// ============ RUTAS PARA EVALUACIONES Y CONSTANCIAS (UNIÓN HIDALGO) ============
Route::middleware(['auth'])->prefix('coordinador/union-hidalgo')->group(function () {
    // Guardar evaluación de estudiante
    Route::post('evaluacion/guardar', [EvaluacionUnionHidalgoController::class, 'guardarEvaluacion'])
        ->name('unionhidalgo.evaluacion.guardar');
    
    // Generar constancia en PDF
    Route::get('constancia/{id_evaluacion}/pdf', [EvaluacionUnionHidalgoController::class, 'generarConstancia'])
        ->name('unionhidalgo.constancia.pdf');
    
    // Obtener documentos membretados del semestre
    Route::get('documentos-membrete/{id_semestre}', [EvaluacionUnionHidalgoController::class, 'obtenerDocumentosMembrete'])
        ->name('unionhidalgo.documentos.membrete');
});

// ============ RUTAS PARA EVALUACIONES Y CONSTANCIAS (VALLE DE ETLA) ============
Route::middleware(['auth'])->prefix('coordinador/valle-de-etla')->group(function () {
    // Guardar evaluación de estudiante
    Route::post('evaluacion/guardar', [EvaluacionValleEtlaController::class, 'guardarEvaluacion'])
        ->name('valleetla.evaluacion.guardar');
    
    // Generar constancia en PDF
    Route::get('constancia/{id_evaluacion}/pdf', [EvaluacionValleEtlaController::class, 'generarConstancia'])
        ->name('valleetla.constancia.pdf');
    
    // Obtener documentos membretados del semestre
    Route::get('documentos-membrete/{id_semestre}', [EvaluacionValleEtlaController::class, 'obtenerDocumentosMembrete'])
        ->name('valleetla.documentos.membrete');
});

require __DIR__.'/auth.php';
