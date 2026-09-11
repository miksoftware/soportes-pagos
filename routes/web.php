<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PublicSoporteController;
use App\Http\Controllers\SoportePagoController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Redirect root to dashboard or login
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// ==========================================
// Public routes for clients (Mobile First)
// ==========================================
Route::get('/soporte', [PublicSoporteController::class, 'create'])->name('soporte.create');
Route::get('/pago', fn () => redirect()->route('soporte.create'));
Route::post('/soporte', [PublicSoporteController::class, 'store'])->name('soporte.store');
Route::get('/soporte/confirmacion', [PublicSoporteController::class, 'success'])->name('soporte.exito');

// Authentication routes (Guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

// Authenticated routes (Both Admin and Standard User)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Management of received payment supports (Accessible by Admin and User)
    Route::prefix('soportes')->name('soportes.')->group(function () {
        Route::get('/', [SoportePagoController::class, 'index'])->name('index');
        Route::get('/descargar-lote', [SoportePagoController::class, 'downloadBatch'])->name('descargarLote');
        Route::get('/{soporte}/descargar', [SoportePagoController::class, 'download'])->name('descargar');
        Route::delete('/{soporte}', [SoportePagoController::class, 'destroy'])->name('destroy');
    });

    // Admin-only module: User Management CRUD
    Route::middleware('admin')->prefix('usuarios')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/crear', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}/editar', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });
});
