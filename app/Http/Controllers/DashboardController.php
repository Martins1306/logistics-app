<?php

namespace App\Http\Controllers;

use App\Models\Gasto;
use App\Models\Viaje;
use App\Models\Producto;
use App\Models\Vehiculo;
use App\Models\Chofer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    /**
     * Mostrar el dashboard con estadísticas generales y alertas
     */
    public function index()
    {
        // 1. Totales generales
        $totalViajes = Viaje::count();
        $totalGastos = Gasto::sum('monto');
        $viajesEnCurso = Viaje::where('estado', 'en curso')->count();
        $totalProductos = Producto::count();

        // 2. Gastos por vehículo (gráfico de barras)
        $gastosPorVehiculo = Gasto::select(
                'vehiculos.patente',
                DB::raw('SUM(gastos.monto) as total')
            )
            ->join('vehiculos', 'gastos.vehiculo_id', '=', 'vehiculos.id')
            ->groupBy('vehiculos.id', 'vehiculos.patente')
            ->orderBy('total', 'desc')
            ->get();

        // 3. Viajes completados por mes (últimos 12 meses)
        $viajesPorMes = [];
        $meses = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $viajesPorMes[] = Viaje::whereYear('fecha_salida', $month->year)
                ->whereMonth('fecha_salida', $month->month)
                ->where('estado', 'completado')
                ->count();
            $meses[] = $month->format('M'); // Ej: "Sep", "Ago"
        }

        // 4. Alertas: Vehículos que necesitan mantenimiento
        $vehiculosParaMantenimiento = Vehiculo::whereNotNull('kilometraje_actual')
            ->whereRaw('kilometraje_actual >= ultimo_mantenimiento_km + intervalo_mantenimiento')
            ->get();

        // 5. Alertas: Choferes con licencia vencida
        $choferesVencidos = Chofer::where('licencia_vencimiento', '<', now())->get();

        // 6. Viajes en curso (para mostrar en el dashboard)
        $viajesActivos = Viaje::with('chofer', 'vehiculo')
            ->where('estado', 'en curso')
            ->orderBy('fecha_salida', 'desc')
            ->limit(5)
            ->get();

        // 7. Retornar vista con todas las variables
        return view('dashboard.index', compact(
            'totalViajes',
            'totalGastos',
            'viajesEnCurso',
            'totalProductos',
            'gastosPorVehiculo',
            'viajesPorMes',
            'meses',
            'vehiculosParaMantenimiento',
            'choferesVencidos',
            'viajesActivos'
        ));
    }

    /**
     * Mostrar el panel de alertas (opcional, si querés una página aparte)
     */
    public function alertas()
    {
        $vehiculosParaMantenimiento = Vehiculo::whereNotNull('kilometraje_actual')
            ->whereRaw('kilometraje_actual >= ultimo_mantenimiento_km + intervalo_mantenimiento')
            ->get();

        $choferesVencidos = Chofer::where('licencia_vencimiento', '<', now())->get();

        $viajesEnCurso = Viaje::where('estado', 'en curso')
            ->with('chofer')
            ->get();

        $proximosMantenimientos = Vehiculo::whereNotNull('kilometraje_actual')
            ->whereRaw('kilometraje_actual + 5000 >= ultimo_mantenimiento_km + intervalo_mantenimiento')
            ->whereRaw('kilometraje_actual < ultimo_mantenimiento_km + intervalo_mantenimiento')
            ->get();

        return view('dashboard.alertas', compact(
            'vehiculosParaMantenimiento',
            'choferesVencidos',
            'viajesEnCurso',
            'proximosMantenimientos'
        ));
    }
}