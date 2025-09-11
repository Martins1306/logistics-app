@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h3>Detalles del Chofer</h3>
    </div>
    <div class="card-body">
        <p><strong>Nombre:</strong> {{ $chofer->nombre }} {{ $chofer->apellido }}</p>
        <p><strong>DNI:</strong> {{ $chofer->dni }}</p>
        <p><strong>Licencia:</strong> {{ $chofer->licencia_tipo }} 
            (vence {{ \Carbon\Carbon::parse($chofer->licencia_vencimiento)->format('d/m/Y') }})
            @if($chofer->licencia_vencimiento < now())
                <span class="badge bg-danger">VENCIDA</span>
            @endif
        </p>
        <p><strong>Teléfono:</strong> {{ $chofer->telefono }}</p>
        <p><strong>Observaciones:</strong> 
            {{ $chofer->observaciones ?? '—' }}
        </p>
        <p><strong>Viajes realizados:</strong> {{ $chofer->viajes->count() }}</p>
    </div>
    <div class="card-footer">
        <a href="{{ route('choferes.edit', $chofer) }}" class="btn btn-warning btn-sm">Editar</a>
        <a href="{{ route('choferes.index') }}" class="btn btn-secondary btn-sm">Volver</a>
    </div>
</div>
@endsection