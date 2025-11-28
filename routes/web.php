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
use App\Http\Controllers\Coordinador\VerEstudiantesController as CoordinadorVerEstudiantesController;
use App\Http\Controllers\Coordinador\ConstanciaController as CoordinadorConstanciaController;
use App\Http\Controllers\Coordinador\InformeController as CoordinadorInformeController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UnidadController;
use App\Http\Controllers\Administrador\ActividadController;
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

// Rutas CRUD para gestión de usuarios (formularios en modales)
Route::post('admin/usuarios', [AdminUserController::class, 'store'])
    ->middleware('auth')
    ->name('admin.usuarios.store');

Route::put('admin/usuarios/{id}', [AdminUserController::class, 'update'])
    ->middleware('auth')
    ->name('admin.usuarios.update');

Route::delete('admin/usuarios/{id}', [AdminUserController::class, 'destroy'])
    ->middleware('auth')
    ->name('admin.usuarios.destroy');

// Nueva ruta: Gestión de semestres para administradores
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


// Panel principal del Coordinador para la Unidad Tlahuitoltepec (vista por semestre)
Route::get('coordinador/tlahuitoltepec/panel/{id}', [PanelTlahuitoltepecController::class, 'show'])
    ->middleware('auth')
    ->name('coordinador.tlahuitoltepec.panel');

// Panel principal del Coordinador para la Unidad Demetrio Vallejo (vista por semestre)
Route::get('coordinador/demetrio-vallejo/panel/{id}', [PanelDemetrioVallejoController::class, 'show'])
    ->middleware('auth')
    ->name('coordinador.demetrio.panel');

Route::get('coordinador/semestres/tlahuitoltepec', [CoordinadorSemestresTlahController::class, 'index'])
    ->middleware('auth')
    ->name('coordinador.semestres.tlahuitoltepec');

Route::get('coordinador/semestres/demetrio-vallejo', [CoordinadorSemestresDemetrioController::class, 'index'])
    ->middleware('auth')
    ->name('coordinador.semestres.demetrio');

// Ver estudiantes - mostrar lista filtrada por unidad
Route::get('coordinador/ver-estudiantes', [CoordinadorVerEstudiantesController::class, 'index'])
    ->middleware('auth')
    ->name('coordinador.verestudiantes');

// Guardar nuevo estudiante
Route::post('coordinador/estudiantes', [CoordinadorVerEstudiantesController::class, 'store'])
    ->middleware('auth')
    ->name('coordinador.estudiantes.store');

// Ver constancias - mostrar por unidad
Route::get('coordinador/constancia', [CoordinadorConstanciaController::class, 'index'])
    ->middleware('auth')
    ->name('coordinador.constancia');

// Ver informes - mostrar por unidad
Route::get('coordinador/informe', [CoordinadorInformeController::class, 'index'])
    ->middleware('auth')
    ->name('coordinador.informe');

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

require __DIR__.'/auth.php';
