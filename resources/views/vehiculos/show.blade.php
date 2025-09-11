@extends('layouts.app')

@section('content')
<div class="card mb-4">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h3>🚛 Detalles del Vehículo</h3>
        <span class="badge bg-light text-dark">{{ $vehiculo->tipo }}</span>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>Patente:</strong> {{ $vehiculo->patente }}</p>
                <p><strong>Marca:</strong> {{ $vehiculo->marca }}</p>
                <p><strong>Modelo:</strong> {{ $vehiculo->modelo }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Capacidad:</strong> {{ number_format($vehiculo->capacidad_kg) }} kg</p>
                <p><strong>Fecha de Compra:</strong> {{ \Carbon\Carbon::parse($vehiculo->fecha_compra)->format('d/m/Y') }}</p>
            </div>
        </div>
    </div>
    <div class="card-footer text-end">
        <a href="{{ route('vehiculos.edit', $vehiculo) }}" class="btn btn-warning btn-sm">Editar</a>
        <a href="{{ route('vehiculos.index') }}" class="btn btn-secondary btn-sm">Volver</a>
    </div>
</div>

<!-- Viajes realizados -->
<div class="card mb-4">
    <div class="card-header bg-info text-white">
        <h5>📅 Viajes Realizados</h5>
    </div>
    <div class="card-body">
        @if($vehiculo->viajes->isEmpty())
            <p class="text-muted">No se han registrado viajes para este vehículo.</p>
        @else
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Origen</th>
                        <th>Destino</th>
                        <th>Fecha Salida</th>
                        <th>Km</th>
                        <th>Chofer</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vehiculo->viajes as $viaje)
                    <tr>
                        <td>{{ $viaje->origen }}</td>
                        <td>{{ $viaje->destino }}</td>
                        <td>{{ \Carbon\Carbon::parse($viaje->fecha_salida)->format('d/m') }}</td>
                        <td>{{ number_format($viaje->kilometros) }}</td>
                        <td>
                            @if($viaje->chofer)
                                {{ $viaje->chofer->nombre }} {{ $viaje->chofer->apellido }}
                            @else
                                <em class="text-muted">—</em>
                            @endif
                        </td>
                        <td>
                            <span class="badge 
                                @if($viaje->estado == 'en curso') bg-warning 
                                @elseif($viaje->estado == 'completado') bg-success 
                                @else bg-danger @endif">
                                {{ ucfirst($viaje->estado) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

<!-- Mantenimientos -->
<div class="card mb-4">
    <div class="card-header bg-warning text-white d-flex justify-content-between align-items-center">
        <h5>🔧 Historial de Mantenimiento</h5>
        <a href="{{ route('mantenimientos.create', $vehiculo->id) }}" class="btn btn-dark btn-sm">
            + Nuevo Registro
        </a>
    </div>
    <div class="card-body">
        @if($vehiculo->mantenimientos->isEmpty())
            <p class="text-muted">No hay registros de mantenimiento.</p>
        @else
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Fecha</th>
                        <th>Km</th>
                        <th>Costo</th>
                        <th>Proveedor</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vehiculo->mantenimientos as $m)
                    <tr>
                        <td>{{ $m->tipo }}</td>
                        <td>{{ \Carbon\Carbon::parse($m->fecha)->format('d/m/Y') }}</td>
                        <td>{{ number_format($m->kilometraje) }}</td>
                        <td>$ {{ number_format($m->costo, 2) }}</td>
                        <td>{{ $m->proveedor ?? '—' }}</td>
                        <td>
                            <a href="{{ route('mantenimientos.edit', $m) }}" class="btn btn-sm btn-outline-warning">Editar</a>
                            <form action="{{ route('mantenimientos.destroy', $m) }}" method="POST" style="display: inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                        onclick="return confirm('¿Eliminar este mantenimiento?')">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection