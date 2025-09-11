@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Detalles del Viaje #{{ $viaje->id }}</h3>
    </div>
    <div class="card-body">
        <p><strong>Vehículo:</strong> {{ $viaje->vehiculo->patente }} ({{ $viaje->vehiculo->marca }})</p>
        <p><strong>Recorrido:</strong> {{ $viaje->origen }} → {{ $viaje->destino }}</p>
        <p><strong>Salida:</strong> {{ \Carbon\Carbon::parse($viaje->fecha_salida)->format('d/m/Y') }}</p>
        <p><strong>Llegada:</strong> 
            {{ $viaje->fecha_llegada ? \Carbon\Carbon::parse($viaje->fecha_llegada)->format('d/m/Y') : 'Aún no completado' }}
        </p>
        <p><strong>Kilómetros:</strong> {{ number_format($viaje->kilometros, 0, '.', '.') }} km</p>
        <p><strong>Carga:</strong></p>
        <p><strong>Productos transportados:</strong></p>
@if($viaje->productos->isEmpty())
    <p class="text-muted">No se han registrado productos para este viaje.</p>
@else
    <ul class="list-group mb-3">
        @foreach($viaje->productos as $p)
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <div>
                <strong>{{ $p->nombre }}</strong> 
                ({{ $p->pivot->cantidad }} {{ $p->unidad }})
                @if($p->pivot->notas)
                    <br><small class="text-muted">Notas: {{ $p->pivot->notas }}</small>
                @endif
            </div>
            <span class="badge bg-primary rounded-pill">{{ $p->categoria }}</span>
        </li>
        @endforeach
    </ul>
@endif
        <div class="alert alert-light">{{ $viaje->descripcion_carga }}</div>
        <p><strong>Estado:</strong>
            @if($viaje->estado == 'en curso')
                <span class="badge bg-warning text-dark">En curso</span>
            @elseif($viaje->estado == 'completado')
                <span class="badge bg-success">Completado</span>
            @else
                <span class="badge bg-danger">Cancelado</span>
            @endif
        </p>
    </div>
    <div class="card-footer">
        <a href="{{ route('viajes.edit', $viaje) }}" class="btn btn-warning">Editar</a>
        <a href="{{ route('viajes.index') }}" class="btn btn-secondary">Volver al listado</a>
    </div>
</div>
@endsection