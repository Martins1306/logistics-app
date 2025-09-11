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
                        @if($vehiculosParaMantenimiento->isEmpty())
                            Todo ok
                        @else
                            <strong>{{ $vehiculosParaMantenimiento->count() }}</strong> pendientes
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
                        @if($choferesVencidos->isEmpty())
                            Todas vigentes
                        @else
                            <strong>{{ $choferesVencidos->count() }}</strong> vencidas
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
        <!-- Gastos por vehículo (gráfico de barras horizontal) -->
        <div class="col-md-6">
            <div class="card bg-dark text-white shadow-sm">
                <div class="card-header p-2">
                    <h6 class="mb-0 h6">Gastos por Vehículo</h6>
                </div>
                <div class="card-body p-3" style="height: 200px; overflow-y: auto;">
                    @if($gastosPorVehiculo->isEmpty())
                        <p class="text-muted small mb-0">No hay gastos registrados</p>
                    @else
                        <ul class="list-group list-group-flush list-group-sm">
                            @foreach($gastosPorVehiculo as $g)
                                <li class="list-group-item bg-dark border-secondary text-light py-1">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="small">{{ $g->patente }}</span>
                                        <span class="badge bg-secondary">{{ number_format($g->total, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-success" role="progressbar"
                                             style="width: {{ $g->total / $gastosPorVehiculo->max('total') * 100 }}%">
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        <!-- Viajes por mes (gráfico de barras vertical) -->
        <div class="col-md-6">
            <div class="card bg-dark text-white shadow-sm">
                <div class="card-header p-2">
                    <h6 class="mb-0 h6">Viajes Completados (últimos 12 meses)</h6>
                </div>
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-end" style="height: 150px">
                        @foreach($viajesPorMes as $index => $count)
                            <div class="text-center" style="flex: 1; min-width: 20px;">
                                <div style="height: {{ min($count * 10, 120) }}px; background: #0dcaf0; width: 80%; border-radius: 4px; margin: 0 auto;"></div>
                                <small class="text-muted d-block mt-1">{{ substr($meses[$index], 0, 3) }}</small>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection