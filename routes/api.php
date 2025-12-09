<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ActividadController;
use App\Http\Controllers\Api\SemestreController;
use App\Http\Controllers\Api\EstudianteController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the framework through the `bootstrap/app.php` config
| where `api` route file is included. These routes are typically stateless
| and are intended for mobile/JS clients.
|
*/

Route::post('login', [AuthController::class, 'login']);

// Ruta  para obtener actividades 
Route::get('/actividades/{unidad}', [ActividadController::class, 'getActividadesPorUnidad']);
// Ruta  para obtener estudiantes de una actividad
Route::get('/actividades/{id}/estudiantes', [ActividadController::class, 'getEstudiantesPorActividad']);

// Obtener semestre activo
Route::get('/semestre-activo', [SemestreController::class, 'activo']);

// Obtener usuarios por id de semestre
Route::get('/usuarios/semestre/{id}', [UserController::class, 'usuariosPorSemestre']);

// Rutas para estudiantes (actualización desde API)
Route::put('/estudiantes/{id}', [EstudianteController::class, 'update']);
Route::patch('/estudiantes/{id}', [EstudianteController::class, 'update']);

// Rutas públicas para usuarios (alias en español)
Route::get('/usuarios', [UserController::class, 'index']);
Route::get('/usuarios/{id}', [UserController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', [UserController::class, 'me']);
    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{id}', [UserController::class, 'show']);
    // Rutas para actualizar usuarios (protegidas)
    Route::put('/usuarios/{id}', [UserController::class, 'update']);
    Route::patch('/usuarios/{id}', [UserController::class, 'update']);
});



