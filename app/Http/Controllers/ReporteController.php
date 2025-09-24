<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Chofer;
use App\Models\Viaje;
use App\Models\MovimientoInventario;
use App\Models\Mantenimiento;
use App\Models\Vehiculo;
use Barryvdh\DomPDF\Facade\Pdf; // Para generar PDF

class ReporteController extends Controller
{
    /**
     * Muestra el formulario de generación de reportes.
     */
    public function index()
    {
        $clientes = Cliente::orderBy('nombre')->get();
        $productos = Producto::orderBy('nombre')->get();
        $choferes = Chofer::orderBy('apellido')->orderBy('nombre')->get();

        return view('reportes.index', compact('clientes', 'productos', 'choferes'));
    }

    /**
     * Genera un reporte según los filtros recibidos.
     */
    public function generar(Request $request)
    {
        $request->validate([
            'tipo' => 'required|in:viajes,stock,choferes,mantenimientos',
            'fecha_desde' => 'nullable|date',
            'fecha_hasta' => 'nullable|date|after_or_equal:fecha_desde',
        ]);

        // Definir rango de fechas
        $desde = $request->filled('fecha_desde') ? Carbon::parse($request->fecha_desde) : now()->subMonths(1);
        $hasta = $request->filled('fecha_hasta') ? Carbon::parse($request->fecha_hasta)->endOfDay() : now();

        $data = [];

        switch ($request->tipo) {
            case 'viajes':
                $query = Viaje::with(['cliente', 'vehiculo'])
                              ->whereBetween('created_at', [$desde, $hasta]);

                if ($request->has('cliente_id') && $request->cliente_id !== null) {
                    $query->where('cliente_id', $request->cliente_id);
                }

                if ($request->has('tipo_viaje') && $request->tipo_viaje !== null) {
                    $query->where('tipo', $request->tipo_viaje);
                }

                $viajes = $query->get();

                $data = $viajes->map(function ($v) {
                    return [
                        'ID' => $v->id,
                        'Fecha' => $v->created_at->format('d/m/Y'),
                        'Cliente' => optional($v->cliente)->nombre ?? '-',
                        'Origen' => $v->origen_direccion ?: '-',
                        'Destino' => $v->destino_direccion ?: '-',
                        'Tipo' => ucfirst($v->tipo),
                        'Estado' => ucfirst($v->estado),
                        'Vehículo' => optional($v->vehiculo)->patente ?? '-',
                        'Chofer' => optional(optional($v->vehiculo)->choferActual())->nombre_completo ?? '-'
                    ];
                })->toArray();
                break;

            case 'stock':
                $query = MovimientoInventario::with(['producto', 'viaje'])
                                            ->whereBetween('created_at', [$desde, $hasta]);

                if ($request->has('producto_id') && $request->producto_id !== null) {
                    $query->where('producto_id', $request->producto_id);
                }

                $movimientos = $query->get();

                $data = $movimientos->map(function ($m) {
                    return [
                        'Fecha' => $m->created_at->format('d/m/Y H:i'),
                        'Producto' => optional($m->producto)->nombre ?? '-',
                        'Cantidad' => $m->cantidad,
                        'Tipo' => $m->tipo === 'entrada' ? 'Entrada' : 'Salida',
                        'Viaje ID' => $m->viaje_id ? "#{$m->viaje_id}" : 'Manual',
                        'Observación' => $m->observacion ?: '-'
                    ];
                })->toArray();
                break;

            case 'choferes':
                $query = Viaje::with('vehiculo')
                             ->whereBetween('created_at', [$desde, $hasta]);

                if ($request->has('chofer_id') && $request->chofer_id !== null) {
                    $query->whereHas('vehiculo', function ($q) use ($request) {
                        $q->where('chofer_id', $request->chofer_id);
                    });
                }

                $viajes = $query->get();

                $resumen = collect();
                foreach ($viajes as $v) {
                    $chofer = optional($v->vehiculo)->choferActual();
                    if (!$chofer) continue;

                    $key = $chofer->id;
                    if (!$resumen->has($key)) {
                        $resumen->put($key, [
                            'Chofer' => $chofer->nombre_completo,
                            'Total Viajes' => 0,
                            'Km Totales' => 0,
                            'Último Viaje' => '-'
                        ]);
                    }
                    $item = $resumen->get($key);
                    $item['Total Viajes']++;
                    $item['Km Totales'] += $v->distancia ?: 0;
                    $item['Último Viaje'] = $v->created_at->format('d/m/Y');
                    $resumen->put($key, $item);
                }

                $data = $resumen->values()->toArray();
                break;

            case 'mantenimientos':
                $query = Mantenimiento::with(['vehiculo', 'proveedor'])
                                     ->whereBetween('fecha_programada', [$desde, $hasta]);

                $mantenimientos = $query->get();

                $data = $mantenimientos->map(function ($m) {
                    return [
                        'Vehículo' => optional($m->vehiculo)->patente ?? '-',
                        'Tipo' => ucfirst($m->tipo),
                        'Fecha Programada' => optional($m->fecha_programada)->format('d/m/Y') ?? '-',
                        'Estado' => ucfirst($m->estado),
                        'Costo Estimado' => number_format($m->costo_estimado ?: 0, 2),
                        'Proveedor' => optional($m->proveedor)->nombre ?? '-'
                    ];
                })->toArray();
                break;
        }

        return redirect()->back()->with('reporte', $data);
    }

    /**
     * Descargar el reporte actual como PDF
     */
    public function descargar(Request $request)
    {
        $tipo = $request->query('tipo');
        $reporte = session('reporte');

        if (!$reporte) {
            return redirect()->back()->with('error', 'No hay ningún reporte generado para exportar.');
        }

        $titulo = '';
        switch ($tipo) {
            case 'viajes':
                $titulo = 'Reporte de Viajes';
                break;
            case 'stock':
                $titulo = 'Reporte de Movimientos de Stock';
                break;
            case 'choferes':
                $titulo = 'Reporte de Rendimiento de Choferes';
                break;
            case 'mantenimientos':
                $titulo = 'Reporte de Mantenimientos Programados';
                break;
            default:
                $titulo = 'Reporte';
        }

        $fechaGeneracion = now()->format('d/m/Y H:i');
        $data = [
            'titulo' => $titulo,
            'reporte' => $reporte,
            'fechaGeneracion' => $fechaGeneracion,
            'headers' => array_keys($reporte[0] ?? []),
        ];

        try {
            $pdf = Pdf::loadView('reportes.pdf', $data)
                      ->setPaper('a4', 'landscape');

            return $pdf->download("{$titulo}_" . now()->format('Y-m-d') . ".pdf");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al generar el PDF: ' . $e->getMessage());
        }
    }
}