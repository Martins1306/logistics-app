<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChoferController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\GastoController;
use App\Http\Controllers\ViajeController;
use App\Http\Controllers\ProductoController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aquí se registran todas las rutas de la aplicación.
| Todas estas rutas son cargadas por el RouteServiceProvider
| y apuntan a los controladores correspondientes.
|
*/

// Página de inicio
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Recursos principales del sistema
Route::resource('vehiculos', VehiculoController::class);
Route::resource('gastos', GastoController::class);
Route::resource('viajes', ViajeController::class);
Route::resource('productos', ProductoController::class);
Route::resource('choferes', ChoferController::class);

// Ruta para el PDF del Dashboard (si lo implementamos)
Route::get('/dashboard/pdf', [DashboardController::class, '__invoke'])->name('dashboard.pdf');
Route::resource('mantenimientos', \App\Http\Controllers\MantenimientoController::class)->except(['index', 'show']);

// Ruta para el dashboard principal
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Ruta para el panel de alertas
Route::get('/alertas', [DashboardController::class, 'alertas'])->name('dashboard.alertas');
// Asegurarse de que esta ruta esté (o ya existe)
Route::get('/vehiculos/{id}', [\App\Http\Controllers\VehiculoController::class, 'show'])->name('vehiculos.show');