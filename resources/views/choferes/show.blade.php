@extends('layouts.app')

@section('content')
<div class="p-3">
    <h2><i class="fas fa-user-tie text-primary"></i> Detalles del Chofer</h2>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="background-color: transparent; padding: 0; font-size: 0.9rem;">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" style="color: #198754;">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('choferes.index') }}" style="color: #198754;">Choferes</a></li>
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
            <span style="color: #fff; font-weight: 500;">{{ $chofer->nombre }}</span>
        </p>
        <p style="margin: 0.4rem 0;"><strong>Teléfono:</strong> 
            {{ $chofer->telefono ?? '–' }}
        </p>
        <p style="margin: 0.4rem 0;"><strong>Email:</strong> 
            @if($chofer->email)
                <a href="mailto:{{ $chofer->email }}" style="color: #0dcaf0; text-decoration: none;">
                    {{ $chofer->email }}
                </a>
            @else
                –
            @endif
        </p>

        <!-- Licencia -->
        <p style="margin: 0.4rem 0;"><strong>Licencia:</strong></p>
        <div style="margin-left: 1rem; line-height: 1.6; color: #ccc;">
            <p style="margin: 0.2rem 0;"><strong>Número:</strong> {{ $chofer->licencia_numero ?? '–' }}</p>
            <p style="margin: 0.2rem 0;"><strong>Categoría:</strong> {{ $chofer->licencia_categoria ?? '–' }}</p>
            <p style="margin: 0.2rem 0;"><strong>Vencimiento:</strong> 
                @if($chofer->licencia_vencimiento)
                    @php
                        $vencimiento = \Carbon\Carbon::parse($chofer->licencia_vencimiento);
                        $hoy = \Carbon\Carbon::now();
                        $clase = $vencimiento->lt($hoy) ? '#dc3545' : ($vencimiento->diffInDays($hoy) <= 30 ? '#fd7e14' : '#28a745');
                    @endphp
                    <span style="color: {{ $clase }}; font-weight: 500;">
                        {{ $vencimiento->format('d/m/Y') }}
                        @if($vencimiento->lt($hoy))
                            ⚠️ Vencida
                        @elseif($vencimiento->diffInDays($hoy) <= 30)
                            🟡 Próxima a vencer
                        @endif
                    </span>
                @else
                    –
                @endif
            </p>
        </div>

        <!-- Dirección detallada -->
        <p style="margin: 1rem 0 0.4rem 0;"><strong>Dirección:</strong></p>
        <div style="margin-left: 1rem; color: #ccc; line-height: 1.6;">
            @if($chofer->calle || $chofer->numero)
                <strong>Calle:</strong> {{ $chofer->calle ?? '–' }} 
                @if($chofer->numero) N° {{ $chofer->numero }}@endif<br>
            @endif

            @if($chofer->localidad)
                <strong>Localidad:</strong> {{ $chofer->localidad }}<br>
            @endif

            @if($chofer->partido)
                <strong>Partido:</strong> {{ $chofer->partido }}<br>
            @endif

            @if($chofer->provincia)
                <strong>Provincia:</strong> {{ $chofer->provincia }}<br>
            @endif

            @if($chofer->codigo_postal)
                <strong>Código Postal:</strong> {{ $chofer->codigo_postal }}<br>
            @endif

            @if(!($chofer->calle || $chofer->localidad || $chofer->provincia))
                <em>– No especificada</em>
            @endif
        </div>

        <!-- Notas -->
        @if($chofer->notas)
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
                {{ $chofer->notas }}
            </div>
        @endif

        <!-- Fecha de registro -->
        <div style="margin-top: 1.5rem; padding: 0.8rem; background-color: #2a2a2a; border-radius: 0.375rem; border: 1px solid #444;">
            <strong style="color: #fff;">Registrado el:</strong>
            <span style="color: #ddd;">
                {{ \Carbon\Carbon::parse($chofer->created_at)->format('d/m/Y H:i') }}
            </span>
        </div>
    </div>

    <!-- Acciones -->
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('choferes.edit', $chofer->id) }}" class="btn btn-primary">
            <i class="fas fa-edit me-1"></i>Editar Chofer
        </a>
        <a href="{{ route('choferes.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Volver al Listado
        </a>
    </div>
</div>
@endsection