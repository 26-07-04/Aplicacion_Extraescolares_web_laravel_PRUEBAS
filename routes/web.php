<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
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