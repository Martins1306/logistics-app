@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Detalles del Gasto</h3>
    </div>
    <div class="card-body">
        <p><strong>Vehículo:</strong> 
            {{ $gasto->vehiculo->patente }} 
            ({{ $gasto->vehiculo->marca }} {{ $gasto->vehiculo->modelo }})
        </p>
        <p><strong>Tipo de Gasto:</strong> 
            <span class="badge bg-primary">{{ ucfirst($gasto->tipo) }}</span>
        </p>
        <p><strong>Monto:</strong> 
            <strong>$ {{ number_format($gasto->monto, 2, ',', '.') }}</strong>
        </p>
        <p><strong>Fecha:</strong> 
            {{ \Carbon\Carbon::parse($gasto->fecha)->format('d/m/Y') }}
        </p>
        <p><strong>Descripción:</strong></p>
        <div class="alert alert-light">
            {{ $gasto->descripcion ?? 'No hay descripción.' }}
        </div>
    </div>
    <div class="card-footer">
        <a href="{{ route('gastos.edit', $gasto) }}" class="btn btn-warning">Editar</a>
        <a href="{{ route('gastos.index') }}" class="btn btn-secondary">Volver al listado</a>
    </div>
</div>
@endsection