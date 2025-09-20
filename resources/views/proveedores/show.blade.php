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
        <p style="margin: 0.4rem 0;"><strong>Nombre:</strong> {{ $proveedor->nombre }}</p>
        <p style="margin: 0.4rem 0;"><strong>Contacto:</strong> {{ $proveedor->contacto ?? '–' }}</p>
        <p style="margin: 0.4rem 0;"><strong>Teléfono:</strong> {{ $proveedor->telefono ?? '–' }}</p>
        <p style="margin: 0.4rem 0;"><strong>Email:</strong> {{ $proveedor->email ?? '–' }}</p>
        <p style="margin: 0.4rem 0;"><strong>Dirección:</strong> {{ $proveedor->direccion ?? '–' }}</p>
        <p style="margin: 0.4rem 0;"><strong>Notas:</strong> 
            <span style="white-space: pre-line; color: #ccc;">{{ $proveedor->notas ?? '–' }}</span>
        </p>
        <div style="margin-top: 1rem; padding: 0.8rem; background-color: #2a2a2a; border-radius: 0.375rem;">
            <strong style="color: #fff;">Registrado el:</strong>
            <span style="color: #ddd;">
                {{ \Carbon\Carbon::parse($proveedor->created_at)->format('d/m/Y H:i') }}
            </span>
        </div>
    </div>

    <!-- Acciones -->
    <div class="d-flex gap-2">
        <a href="{{ route('proveedores.edit', $proveedor->id) }}" class="btn btn-primary">
            <i class="fas fa-edit me-1"></i>Editar
        </a>
        <a href="{{ route('proveedores.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Volver al listado
        </a>
    </div>
</div>
@endsection