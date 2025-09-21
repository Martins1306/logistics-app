@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="h4 mb-0 text-light">📊 Dashboard General</h2>
            <p class="text-muted small">Resumen de operaciones y alertas</p>
        </div>
    </div>

    <!-- Tarjetas de resumen -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card bg-dark text-white shadow-sm">
                <div class="card-body p-3">
                    <h6 class="card-title text-truncate mb-1">Viajes</h6>
                    <p class="h5 mb-0 fw-bold">{{ $totalViajes }}</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card bg-dark text-white shadow-sm">
                <div class="card-body p-3">
                    <h6 class="card-title text-truncate mb-1">Gastos</h6>
                    <p class="h5 mb-0 fw-bold">${{ number_format($totalGastos, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card bg-dark text-white shadow-sm">
                <div class="card-body p-3">
                    <h6 class="card-title text-truncate mb-1">En Curso</h6>
                    <p class="h5 mb-0 fw-bold">{{ $viajesEnCurso }}</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card bg-dark text-white shadow-sm">
                <div class="card-body p-3">
                    <h6 class="card-title text-truncate mb-1">Productos</h6>
                    <p class="h5 mb-0 fw-bold">{{ $totalProductos }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertas rápidas -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card bg-danger bg-opacity-10 border-danger border shadow-sm">
                <div class="card-body p-3">
                    <h6 class="card-title mb-1 text-danger">
                        <i class="bi bi-exclamation-triangle"></i> Mantenimiento
                    </h6>
                    <p class="small mb-0">
                        @if(isset($vehiculosParaMantenimiento) && !$vehiculosParaMantenimiento->isEmpty())
                            <strong>{{ $vehiculosParaMantenimiento->count() }}</strong> pendientes
                        @else
                            Todo ok
                        @endif
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning bg-opacity-10 border-warning border shadow-sm">
                <div class="card-body p-3">
                    <h6 class="card-title mb-1 text-warning">
                        <i class="bi bi-person-slash"></i> Licencias
                    </h6>
                    <p class="small mb-0">
                        @if(isset($choferesVencidos) && !$choferesVencidos->isEmpty())
                            <strong>{{ $choferesVencidos->count() }}</strong> vencidas
                        @else
                            Todas vigentes
                        @endif
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info bg-opacity-10 border-info border shadow-sm">
                <div class="card-body p-3">
                    <h6 class="card-title mb-1 text-info">
                        <i class="bi bi-truck"></i> En Curso
                    </h6>
                    <p class="small mb-0">
                        <strong>{{ $viajesEnCurso }}</strong> activos
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos pequeños -->
    <div class="row g-3">
        <!-- Gastos por vehículo -->
        <div class="col-md-6">
            <div class="card bg-dark text-white shadow-sm">
                <div class="card-header p-2">
                    <h6 class="mb-0 h6">Gastos por Vehículo</h6>
                </div>
                <div class="card-body p-3" style="height: 200px; overflow-y: auto;">
                    @if(isset($gastosPorVehiculo) && !$gastosPorVehiculo->isEmpty())
                        <ul class="list-group list-group-flush list-group-sm">
                            @foreach($gastosPorVehiculo as $g)
                                <li class="list-group-item bg-dark border-secondary text-light py-1">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="small">{{ $g->patente ?? 'Sin patente' }}</span>
                                        <span class="badge bg-secondary">{{ number_format($g->total ?? 0, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        @php
                                            $max = $gastosPorVehiculo->max('total') ?: 1;
                                            $width = ($g->total / $max) * 100;
                                        @endphp
                                        <div class="progress-bar bg-success" role="progressbar"
                                             style="width: {{ $width }}%">
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted small mb-0">No hay gastos registrados</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Viajes completados por mes -->
        <div class="col-md-6">
            <div class="card bg-dark text-white shadow-sm">
                <div class="card-header p-2">
                    <h6 class="mb-0 h6">Viajes Completados (últimos 12 meses)</h6>
                </div>
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-end" style="height: 167px">
                        @foreach($viajesPorMes as $index => $count)
                            <div class="text-center" style="flex: 1; min-width: 20px;">
                                <div style="
                                    height: {{ min($count * 10, 120) }}px;
                                    background: #0dcaf0;
                                    width: 80%;
                                    border-radius: 4px;
                                    margin: 0 auto;
                                "></div>
                                <small class="text-muted d-block mt-1">
                                    {{ substr($meses[$index] ?? '', 0, 3) }}
                                </small>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Alertas de Inventario -->
<div class="row g-3 mt-4">
    <!-- Stock Bajo -->
    <div class="col-md-6">
        <div class="card bg-dark text-white shadow-sm" style="height: 250px; display: flex; flex-direction: column;">
            <div class="card-header p-2 d-flex align-items-center">
                <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                <h6 class="mb-0 h6">Stock Bajo</h6>
            </div>
            <div class="card-body p-0 flex-grow-1" style="overflow-y: auto; padding: 0.5rem !important;">
                @if($productosBajoStock->isEmpty())
                    <p class="text-muted small mb-0 p-3">No hay productos con bajo stock</p>
                @else
                    <ul class="list-group list-group-flush">
                        @foreach($productosBajoStock as $p)
                            <li class="list-group-item bg-dark border-secondary text-light d-flex justify-content-between align-items-center"
                                style="padding: 0.5rem 0.75rem;">
                                <a href="{{ route('productos.show', $p->id) }}"
                                   style="flex-grow: 1; color: #fff; text-decoration: none; font-size: 0.9rem;">
                                    <strong>{{ $p->nombre }}</strong><br>
                                    <small>Stock: {{ $p->stock_actual }} / Mín: {{ $p->stock_minimo }}</small>
                                </a>
                                <span class="badge bg-warning text-dark" style="font-size: 0.8rem;">Bajo</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>

    <!-- Agotados -->
    <div class="col-md-6">
        <div class="card bg-dark text-white shadow-sm" style="height: 250px; display: flex; flex-direction: column;">
            <div class="card-header p-2 d-flex align-items-center">
                <i class="fas fa-times-circle text-danger me-2"></i>
                <h6 class="mb-0 h6">Agotados</h6>
            </div>
            <div class="card-body p-0 flex-grow-1" style="overflow-y: auto; padding: 0.5rem !important;">
                @if($productosAgotados->isEmpty())
                    <p class="text-muted small mb-0 p-3">No hay productos agotados</p>
                @else
                    <ul class="list-group list-group-flush">
                        @foreach($productosAgotados as $p)
                            <li class="list-group-item bg-dark border-secondary text-light d-flex justify-content-between align-items-center"
                                style="padding: 0.5rem 0.75rem;">
                                <a href="{{ route('productos.show', $p->id) }}"
                                   style="flex-grow: 1; color: #fff; text-decoration: none; font-size: 0.9rem;">
                                    <strong>{{ $p->nombre }}</strong><br>
                                    <small>Stock: 0</small>
                                </a>
                                <span class="badge bg-danger" style="font-size: 0.8rem;">Agotado</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection