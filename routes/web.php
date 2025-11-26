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
use App\Http\Controllers\Coordinador\SemestresCursadosTlahuitoltepecController as CoordinadorSemestresTlahController;
use App\Http\Controllers\Coordinador\SemestresCursadosDemetrioController as CoordinadorSemestresDemetrioController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UnidadController;
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

Route::get('coordinador/semestres/tlahuitoltepec', [CoordinadorSemestresTlahController::class, 'index'])
    ->middleware('auth')
    ->name('coordinador.semestres.tlahuitoltepec');

Route::get('coordinador/semestres/demetrio-vallejo', [CoordinadorSemestresDemetrioController::class, 'index'])
    ->middleware('auth')
    ->name('coordinador.semestres.demetrio');

Route::get('admin/about', [UserController::class, 'about'])
    ->middleware(['auth', 'admin'])
    ->name('admin.about');

Route::get('admin/contact', [UserController::class, 'contact'])
    ->middleware(['auth', 'admin'])
    ->name('admin.about');

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

require __DIR__.'/auth.php';
