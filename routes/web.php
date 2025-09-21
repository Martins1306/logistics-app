<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChoferController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\GastoController;
use App\Http\Controllers\ViajeController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\MantenimientoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\CompraController;

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
Route::get('/dashboard/alertas', [DashboardController::class, 'alertas'])->name('dashboard.alertas');
Route::get('/dashboard/pdf', [DashboardController::class, '__invoke'])->name('dashboard.pdf');

// Recursos principales del sistema
Route::resource('vehiculos', VehiculoController::class);
Route::resource('choferes', ChoferController::class);
Route::resource('productos', ProductoController::class);
Route::resource('viajes', ViajeController::class);
Route::resource('gastos', GastoController::class);

// Mantenimientos: anidados bajo vehículos

    Route::prefix('mantenimientos')->group(function () {
    Route::post('/', [MantenimientoController::class, 'store'])->name('mantenimientos.store');
    Route::get('/{id}/edit', [MantenimientoController::class, 'edit'])->name('mantenimientos.edit');
    Route::put('/{id}', [MantenimientoController::class, 'update'])->name('mantenimientos.update');
    Route::delete('/{id}', [MantenimientoController::class, 'destroy'])->name('mantenimientos.destroy');
});

// Ruta específica: crear mantenimiento asociado a un vehículo
    Route::get('/vehiculos/{id}/mantenimientos/create', [
    MantenimientoController::class, 
    'create'
])->name('mantenimientos.create');
    Route::resource('productos', ProductoController::class);

// Clientes 15/9/25
    Route::resource('clientes', ClienteController::class);
    
    // Dentro del grupo auth
    Route::post('/productos/{id}/ajustar-stock', [ProductoController::class, 'ajustarStock'])->name('productos.ajustar.stock');
 // Proveedores auth
 Route::resource('proveedores', ProveedorController::class);
// 🔧 Compras
Route::resource('compras', \App\Http\Controllers\CompraController::class);