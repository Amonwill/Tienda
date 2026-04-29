<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\CajaController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\MetricasController;

Route::get('/', function () {
    return Inertia::render('Bienvenido', [
        'canLogin' => Route::has('Iniciar sesión'),
        'canRegister' => Route::has('Registrarse'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas para productos (Limpias)
    Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');
    Route::post('/productos', [ProductoController::class, 'store'])->name('productos.store');
    Route::delete('/productos/{id}', [ProductoController::class, 'destroy'])->name('productos.destroy');

    // Rutas para ventas
    Route::get('/ventas', [VentaController::class, 'index'])->name('ventas.index');
    Route::post('/ventas', [VentaController::class, 'store'])->name('ventas.store');

    // Rutas para caja
    Route::get('/caja', [CajaController::class, 'index'])->name('caja.index');
    Route::post('/caja/abrir', [CajaController::class, 'abrirCaja'])->name('caja.abrir');
    Route::post('/caja/cerrar', [CajaController::class, 'cerrarCaja'])->name('caja.cerrar');
    Route::get('/caja/corte', [CajaController::class, 'showCorte'])->name('caja.corte');
    Route::get('/caja/descargar-corte', [CajaController::class, 'descargarCorteCaja'])->name('caja.descargar.pdf');
    Route::get('/caja/descargar-pdf', [CajaController::class, 'descargarCorteCaja'])->name('caja.descargar.pdf');
    Route::post('/caja/cerrar-automatico', [CajaController::class, 'cerrarTurnoAutomatico'])->name('caja.cerrar.automatico');

    // Rutas para proveedores
    // --- Rutas para proveedores (Limpias y controladas) ---
    Route::get('/proveedores', [ProveedorController::class, 'index'])->name('proveedores.index');
    Route::post('/proveedores', [ProveedorController::class, 'store'])->name('proveedores.store');
    Route::put('/proveedores/{id}', [ProveedorController::class, 'update'])->name('proveedores.update');
    Route::delete('/proveedores/{id}', [ProveedorController::class, 'destroy'])->name('proveedores.destroy');
    
    Route::get('/Metricas', [MetricasController::class, 'index'])->name('Metricas.index');
    Route::get('/Metricas/descargar-semanal', [MetricasController::class, 'descargarResumenSemanal'])->name('Metricas.semanal.pdf');
});

require __DIR__.'/auth.php';
