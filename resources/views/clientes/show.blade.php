@extends('layouts.app')

@section('content')
<div class="p-3">
    <h2><i class="fas fa-users text-primary"></i> Detalles del Cliente</h2>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="background-color: transparent; padding: 0; font-size: 0.9rem;">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" style="color: #198754;">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('clientes.index') }}" style="color: #198754;">Clientes</a></li>
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
        color: #ddd;
    ">
        <p style="margin: 0.4rem 0;"><strong>Nombre:</strong> 
            <span style="color: #fff; font-weight: 500;">{{ $cliente->nombre }}</span>
        </p>
        <p style="margin: 0.4rem 0;"><strong>Tipo:</strong> 
            @if($cliente->tipo)
                @php
                    switch ($cliente->tipo) {
                        case 'agricola': $bg = '#28a745'; break;
                        case 'construccion': $bg = '#ffc107'; break;
                        default: $bg = '#6c757d';
                    }
                @endphp
                <span style="
                    font-size: 0.8rem;
                    padding: 0.2em 0.5em;
                    border-radius: 4px;
                    background-color: {{ $bg }};
                    color: {{ $bg == '#ffc107' ? '#000' : 'white' }};
                ">{{ ucfirst($cliente->tipo) }}</span>
            @else
                –
            @endif
        </p>
        <p style="margin: 0.4rem 0;"><strong>Teléfono:</strong> 
            {{ $cliente->telefono ?? '–' }}
        </p>
        <p style="margin: 0.4rem 0;"><strong>Email:</strong> 
            @if($cliente->email)
                <a href="mailto:{{ $cliente->email }}" style="color: #0dcaf0; text-decoration: none;">
                    {{ $cliente->email }}
                </a>
            @else
                –
            @endif
        </p>

        <!-- Dirección detallada -->
        <p style="margin: 1rem 0 0.4rem 0;"><strong>Dirección:</strong></p>
        <div style="margin-left: 1rem; color: #ccc; line-height: 1.6;">
            @if($cliente->calle || $cliente->numero)
                <strong>Calle:</strong> {{ $cliente->calle ?? '–' }} 
                @if($cliente->numero) N° {{ $cliente->numero }}@endif<br>
            @endif

            @if($cliente->localidad)
                <strong>Localidad:</strong> {{ $cliente->localidad }}<br>
            @endif

            @if($cliente->partido)
                <strong>Partido:</strong> {{ $cliente->partido }}<br>
            @endif

            @if($cliente->provincia)
                <strong>Provincia:</strong> {{ $cliente->provincia }}<br>
            @endif

            @if($cliente->codigo_postal)
                <strong>Código Postal:</strong> {{ $cliente->codigo_postal }}<br>
            @endif

            @if(!($cliente->calle || $cliente->localidad || $cliente->provincia))
                <em>– No especificada</em>
            @endif
        </div>

        <!-- Notas -->
        @if($cliente->notas)
            <p style="margin: 1rem 0 0.4rem 0;"><strong>Notas:</strong></p>
            <div style="
                white-space: pre-line;
                padding: 0.8rem;
                background-color: #2a2a2a;
                border-radius: 0.375rem;
                border: 1px solid #444;
                color: #ccc;
                font-size: 0.9rem;
                line-height: 1.5;
            ">
                {{ $cliente->notas }}
            </div>
        @endif

        <!-- Fecha de registro -->
        <div style="margin-top: 1.5rem; padding: 0.8rem; background-color: #2a2a2a; border-radius: 0.375rem; border: 1px solid #444;">
            <strong style="color: #fff;">Registrado el:</strong>
            <span style="color: #ddd;">
                {{ \Carbon\Carbon::parse($cliente->created_at)->format('d/m/Y H:i') }}
            </span>
        </div>
    </div>

    <!-- Acciones -->
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn btn-primary">
            <i class="fas fa-edit me-1"></i>Editar Cliente
        </a>
        <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Volver al Listado
        </a>
    </div>
</div>
@endsection