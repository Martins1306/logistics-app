@extends('layouts.app')

@section('content')
<div class="p-3">
    <h2><i class="fas fa-building text-primary"></i> Detalles del Proveedor</h2>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="background-color: transparent; padding: 0; font-size: 0.9rem;">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" style="color: #198754;">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('proveedores.index') }}" style="color: #198754;">Proveedores</a></li>
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
            <span style="color: #fff; font-weight: 500;">{{ $proveedor->nombre }}</span>
        </p>
        <p style="margin: 0.4rem 0;"><strong>Contacto:</strong> 
            {{ $proveedor->contacto ?? '–' }}
        </p>
        <p style="margin: 0.4rem 0;"><strong>Teléfono:</strong> 
            {{ $proveedor->telefono ?? '–' }}
        </p>
        <p style="margin: 0.4rem 0;"><strong>Email:</strong> 
            @if($proveedor->email)
                <a href="mailto:{{ $proveedor->email }}" style="color: #0dcaf0; text-decoration: none;">
                    {{ $proveedor->email }}
                </a>
            @else
                –
            @endif
        </p>

        <!-- Dirección detallada -->
        <p style="margin: 0.4rem 0;"><strong>Dirección:</strong></p>
        <div style="margin-left: 1rem; color: #ccc; line-height: 1.6;">
            @if($proveedor->calle || $proveedor->numero)
                <strong>Calle:</strong> {{ $proveedor->calle ?? '–' }} 
                @if($proveedor->numero) N° {{ $proveedor->numero }}@endif<br>
            @endif

            @if($proveedor->localidad)
                <strong>Localidad:</strong> {{ $proveedor->localidad }}<br>
            @endif

            @if($proveedor->partido)
                <strong>Partido:</strong> {{ $proveedor->partido }}<br>
            @endif

            @if($proveedor->provincia)
                <strong>Provincia:</strong> {{ $proveedor->provincia }}<br>
            @endif

            @if($proveedor->codigo_postal)
                <strong>Código Postal:</strong> {{ $proveedor->codigo_postal }}<br>
            @endif

            @if(!($proveedor->calle || $proveedor->localidad || $proveedor->provincia))
                <em>– No especificada</em>
            @endif
        </div>

        <!-- Notas -->
        @if($proveedor->notas)
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
                {{ $proveedor->notas }}
            </div>
        @endif

        <!-- Fecha de registro -->
        <div style="margin-top: 1.5rem; padding: 0.8rem; background-color: #2a2a2a; border-radius: 0.375rem; border: 1px solid #444;">
            <strong style="color: #fff;">Registrado el:</strong>
            <span style="color: #ddd;">
                {{ \Carbon\Carbon::parse($proveedor->created_at)->format('d/m/Y H:i') }}
            </span>
        </div>
    </div>

    <!-- Acciones -->
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('proveedores.edit', $proveedor->id) }}" class="btn btn-primary">
            <i class="fas fa-edit me-1"></i>Editar Proveedor
        </a>
        <a href="{{ route('proveedores.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Volver al Listado
        </a>
    </div>
</div>
@endsection