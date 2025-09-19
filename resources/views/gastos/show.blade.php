@extends('layouts.app')

@section('content')
<div class="p-3">
    <h2><i class="fas fa-receipt text-primary"></i> Detalles del Gasto #{{ $gasto->id }}</h2>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="background-color: transparent; padding: 0; font-size: 0.9rem;">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" style="color: #198754;">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('gastos.index') }}" style="color: #198754;">Gastos</a></li>
            <li class="breadcrumb-item active" style="color: #ccc;">Detalle</li>
        </ol>
    </nav>

    <!-- Datos principales -->
    <div style="
        background-color: #1a1a1a;
        border-radius: 0.375rem;
        padding: 1.5rem;
        border: 1px solid #444;
        margin-bottom: 1.5rem;
    ">
        <div class="row" style="font-size: 0.95rem; color: #ccc;">
            <div class="col-md-6">
                <p style="margin: 0.4rem 0;"><strong>Concepto:</strong> {{ ucfirst($gasto->tipo) }}</p>
                <p style="margin: 0.4rem 0;"><strong>Monto:</strong> <span style="color: #fff; font-weight: 500;">${{ number_format($gasto->monto, 2, ',', '.') }}</span></p>
                <p style="margin: 0.4rem 0;"><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($gasto->fecha)->format('d/m/Y') }}</p>
            </div>
            <div class="col-md-6">
                <p style="margin: 0.4rem 0;">
                    <strong>Vehículo:</strong> 
                    {{ $gasto->vehiculo ? $gasto->vehiculo->patente . ' (' . $gasto->vehiculo->marca . ')' : '–' }}
                </p>
                <p style="margin: 0.4rem 0;">
                    <strong>Chofer:</strong> 
                    {{ $gasto->chofer ? $gasto->chofer->nombre : '–' }}
                </p>
                <p style="margin: 0.4rem 0;">
                    <strong>Tipo:</strong> 
                    <span style="
                        font-size: 0.8rem;
                        padding: 0.2em 0.5em;
                        border-radius: 4px;
                        background-color:
                            @if($gasto->tipo == 'combustible') #fd7e14;
                            @elseif(in_array($gasto->tipo, ['mantenimiento','repuestos'])) #dc3545;
                            @elseif($gasto->tipo == 'lavado') #0dcaf0;
                            @elseif($gasto->tipo == 'seguro') #6f42c1;
                            @else #6c757d; @endif
                        color: white;
                    ">
                        {{ ucfirst($gasto->tipo) }}
                    </span>
                </p>
            </div>
        </div>

        @if($gasto->descripcion)
            <div style="margin-top: 1rem; padding: 0.8rem; background-color: #2a2a2a; border-radius: 0.375rem;">
                <strong style="color: #fff;">Descripción:</strong>
                <p style="color: #ddd; margin: 0.5rem 0 0 0;">{{ $gasto->descripcion }}</p>
            </div>
        @endif
    </div>

    <!-- Acciones -->
    <div class="d-flex gap-2">
        <a href="{{ route('gastos.edit', $gasto->id) }}" class="btn btn-primary">
            <i class="fas fa-edit me-1"></i>Editar Gasto
        </a>
        <a href="{{ route('gastos.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Volver al listado
        </a>
    </div>
</div>
@endsection