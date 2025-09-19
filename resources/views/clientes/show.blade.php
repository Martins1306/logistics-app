@extends('layouts.app')

@section('content')
<div class="p-3">
    <h2><i class="fas fa-user text-primary"></i> Detalles del Cliente #{{ $cliente->id }}</h2>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="background-color: transparent; padding: 0; font-size: 0.9rem;">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" style="color: #198754;">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('clientes.index') }}" style="color: #198754;">Clientes</a></li>
            <li class="breadcrumb-item active" style="color: #ccc;">Detalle</li>
        </ol>
    </nav>

    <!-- Tarjeta principal -->
    <div style="
        background-color: #1a1a1a;
        border-radius: 0.375rem;
        padding: 1.5rem;
        border: 1px solid #444;
        margin-bottom: 1.5rem;
    ">
        <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h4 style="color: #fff; margin: 0;">{{ $cliente->nombre }}</h4>
                <p style="color: #ccc; margin: 0;">
                    <strong>CUIT:</strong> {{ $cliente->cuit }} |
                    <span style="color: #aaa;">{{ $cliente->razon_social }}</span>
                </p>
            </div>
            <div style="text-align: right;">
                <span style="
                    font-size: 0.8rem;
                    padding: 0.3em 0.6em;
                    border-radius: 4px;
                    background-color:
                        {{ $cliente->tipo == 'agrícola' ? '#28a745' :
                           ($cliente->tipo == 'construccion' ? '#fd7e14' :
                           ($cliente->tipo == 'industrial' ? '#0d6efd' :
                           ($cliente->tipo == 'transporte' ? '#6f42c1' : '#6c757d'))) }};
                    color: white;
                ">{{ ucfirst($cliente->tipo) }}</span>
            </div>
        </div>

        <hr style="border-color: #444; margin: 1.2rem 0;">

        <div class="row" style="font-size: 0.95rem;">
            <div class="col-md-6">
                <p style="color: #ccc; margin: 0.4rem 0;"><strong>Dirección:</strong> {{ $cliente->direccion ?? '–' }}</p>
                <p style="color: #ccc; margin: 0.4rem 0;"><strong>Localidad:</strong> {{ $cliente->localidad ?? '–' }}</p>
                <p style="color: #ccc; margin: 0.4rem 0;"><strong>Provincia:</strong> {{ $cliente->provincia ?? '–' }}</p>
            </div>
            <div class="col-md-6">
                <p style="color: #ccc; margin: 0.4rem 0;"><strong>Teléfono:</strong> {{ $cliente->telefono ?? '–' }}</p>
                <p style="color: #ccc; margin: 0.4rem 0;"><strong>Email:</strong> {{ $cliente->email ?? '–' }}</p>
            </div>
        </div>

        @if($cliente->notas)
            <div style="margin-top: 1rem; padding: 0.8rem; background-color: #2a2a2a; border-radius: 0.375rem; border-left: 4px solid #6c757d;">
                <strong style="color: #fff;">Notas:</strong>
                <p style="color: #ccc; margin: 0.5rem 0 0 0;">{{ $cliente->notas }}</p>
            </div>
        @endif
    </div>

    <!-- Viajes asociados (opcional - puedes agregar después) -->
    <!-- Botones -->
    <div class="d-flex gap-2">
        <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn btn-primary">
            <i class="fas fa-edit me-1"></i>Editar Cliente
        </a>
        <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Volver al listado
        </a>
    </div>
</div>
@endsection