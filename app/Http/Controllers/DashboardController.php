<?php

namespace App\Http\Controllers;

use App\Models\Viaje;
use App\Models\Producto;
use App\Models\Vehiculo;
use App\Models\Chofer;
use App\Models\Gasto;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Mostrar el dashboard principal.
     */
    public function index()
    {
        // === VIAJES ===
        try {
            $totalViajes = Viaje::count();
            $viajesEnCurso = Viaje::where('estado', 'en curso')->count();
        } catch (\Exception $e) {
            $totalViajes = 0;
            $viajesEnCurso = 0;
        }

        // === GASTOS ===
        try {
            $totalGastos = Gasto::sum('monto');

            $gastosPorVehiculo = Gasto::join('vehiculos', 'gastos.vehiculo_id', '=', 'vehiculos.id')
                ->selectRaw('vehiculos.patente, SUM(gastos.monto) as total')
                ->groupBy('vehiculos.id', 'vehiculos.patente')
                ->orderByDesc('total')
                ->take(5)
                ->get();
        } catch (\Exception $e) {
            $totalGastos = 0;
            $gastosPorVehiculo = collect();
        }

        // === PRODUCTOS ===
        try {
            $totalProductos = Producto::count();
        } catch (\Exception $e) {
            $totalProductos = 0;
        }

        // === VEHÍCULOS PRÓXIMOS A MANTENIMIENTO ===
        try {
            $vehiculosParaMantenimiento = Vehiculo::whereNotNull('proximo_mantenimiento')
                ->where('proximo_mantenimiento', '<=', now()->addDays(15))
                ->get();
        } catch (\Exception $e) {
            $vehiculosParaMantenimiento = collect();
        }

        // === CHOFERES CON LICENCIA VENCIDA ===
        try {
            $choferesVencidos = Chofer::whereNotNull('licencia_vencimiento')
                ->where('licencia_vencimiento', '<', now())
                ->get();
        } catch (\Exception $e) {
            $choferesVencidos = collect();
        }

        // === VIAJES COMPLETADOS POR MES (últimos 12 meses) ===
        $meses = [];
        $viajesPorMes = [];

        for ($i = 11; $i >= 0; $i--) {
            $mes = Carbon::now()->subMonths($i);
            $meses[] = $mes->format('F');
            
            try {
                $count = Viaje::where('estado', 'completado')
                    ->whereYear('fecha_llegada', $mes->year)
                    ->whereMonth('fecha_llegada', $mes->month)
                    ->count();
            } catch (\Exception $e) {
                $count = 0;
            }

            $viajesPorMes[] = $count;
        }

        // === ESTADÍSTICAS POR TIPO DE VIAJE ===
        try {
            $viajes = Viaje::select('tipo')->get();

            $totalAgricola = $viajes->filter(function ($v) {
                $tipo = strtolower($v->tipo ?? '');
                return str_contains($tipo, 'agricol') || 
                       str_contains($tipo, 'hortaliza') || 
                       str_contains($tipo, 'fruta') || 
                       str_contains($tipo, 'cereal');
            })->count();

            $totalConstruccion = $viajes->filter(function ($v) {
                $tipo = strtolower($v->tipo ?? '');
                return str_contains($tipo, 'construccion') || 
                       str_contains($tipo, 'materiales') || 
                       str_contains($tipo, 'herramienta') || 
                       str_contains($tipo, 'cemento') || 
                       str_contains($tipo, 'acero') || 
                       str_contains($tipo, 'ladrillo');
            })->count();

            $sinTipo = $viajes->filter(function ($v) {
                return empty(trim($v->tipo));
            })->count();
        } catch (\Exception $e) {
            $totalAgricola = 0;
            $totalConstruccion = 0;
            $sinTipo = 0;
        }

        // === ALERTAS DE INVENTARIO: STOCK BAJO Y AGOTADO ===
        try {
            $productosBajoStock = Producto::whereColumn('stock_actual', '<=', 'stock_minimo')
                ->where('stock_minimo', '>', 0)
                ->where('stock_actual', '>', 0)
                ->orderBy('stock_actual')
                ->limit(5)
                ->get();

            $productosAgotados = Producto::where('stock_actual', 0)
                ->orderBy('nombre')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            \Log::error('Error al cargar productos con bajo stock o agotados: ' . $e->getMessage());
            $productosBajoStock = collect();
            $productosAgotados = collect();
        }

        // Retornar vista con todas las variables
        return view('dashboard', compact(
            'totalViajes',
            'totalGastos',
            'viajesEnCurso',
            'totalProductos',
            'gastosPorVehiculo',
            'vehiculosParaMantenimiento',
            'choferesVencidos',
            'viajesPorMes',
            'meses',
            'totalAgricola',
            'totalConstruccion',
            'sinTipo',
            'productosBajoStock',      // ← Nueva variable
            'productosAgotados'        // ← Nueva variable
        ));
    }

    /**
     * Mostrar la página de alertas.
     */
    public function alertas()
    {
        // Vehículos próximos a mantenimiento (en los próximos 15 días)
        try {
            $vehiculosParaMantenimiento = Vehiculo::whereNotNull('proximo_mantenimiento')
                ->where('proximo_mantenimiento', '<=', now()->addDays(15))
                ->get();
        } catch (\Exception $e) {
            $vehiculosParaMantenimiento = collect();
        }

        // Choferes con licencia vencida
        try {
            $choferesVencidos = Chofer::whereNotNull('licencia_vencimiento')
                ->where('licencia_vencimiento', '<', now())
                ->get();
        } catch (\Exception $e) {
            $choferesVencidos = collect();
        }

        // Viajes en curso
        try {
            $viajesEnCurso = Viaje::where('estado', 'en curso')->count();
        } catch (\Exception $e) {
            $viajesEnCurso = 0;
        }

        // Retornar vista de alertas
        return view('dashboard.alertas', compact(
            'vehiculosParaMantenimiento',
            'choferesVencidos',
            'viajesEnCurso'
        ));
    }
}