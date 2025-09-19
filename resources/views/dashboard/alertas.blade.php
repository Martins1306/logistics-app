@extends('layouts.app')

@section('content')
<div class="p-3">
    <h2><i class="fas fa-bell text-warning"></i> Alertas del Sistema</h2>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="background-color: transparent; padding: 0; font-size: 0.9rem;">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" style="color: #198754;">Inicio</a></li>
            <li class="breadcrumb-item active" style="color: #ccc;">Alertas</li>
        </ol>
    </nav>

    <!-- Alertas -->
    <div class="row g-3 mb-4">
        <!-- Mantenimiento de vehículos -->
        <div class="col-md-6">
            <div style="
                background-color: #1a1a1a;
                border-radius: 0.375rem;
                padding: 1rem;
                border: 1px solid #444;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            ">
                <h5 style="color: #fff; margin-top: 0; border-bottom: 1px solid #444; padding-bottom: 0.5rem;">
                    <i class="fas fa-wrench me-1 text-danger"></i> Mantenimiento
                </h5>
                @if($vehiculosParaMantenimiento->isEmpty())
                    <p style="color: #888; margin: 0;">No hay vehículos pendientes de mantenimiento.</p>
                @else
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        @foreach($vehiculosParaMantenimiento as $v)
                            <li style="padding: 0.4rem 0; color: #ddd;">
                                <strong>{{ $v->patente }}</strong> - 
                                {{ \Carbon\Carbon::parse($v->proximo_mantenimiento)->format('d/m/Y') }}
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <!-- Licencias vencidas -->
        <div class="col-md-6">
            <div style="
                background-color: #1a1a1a;
                border-radius: 0.375rem;
                padding: 1rem;
                border: 1px solid #444;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            ">
                <h5 style="color: #fff; margin-top: 0; border-bottom: 1px solid #444; padding-bottom: 0.5rem;">
                    <i class="fas fa-id-card-alt me-1 text-warning"></i> Licencias Vencidas
                </h5>
                @if($choferesVencidos->isEmpty())
                    <p style="color: #888; margin: 0;">Todos los choferes tienen licencias vigentes.</p>
                @else
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        @foreach($choferesVencidos as $c)
                            <li style="padding: 0.4rem 0; color: #ddd;">
                                <strong>{{ $c->nombre }}</strong> - 
                                {{ \Carbon\Carbon::parse($c->licencia_vencimiento)->format('d/m/Y') }}
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <!-- Viajes en curso -->
        <div class="col-md-6">
            <div style="
                background-color: #1a1a1a;
                border-radius: 0.375rem;
                padding: 1rem;
                border: 1px solid #444;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            ">
                <h5 style="color: #fff; margin-top: 0; border-bottom: 1px solid #444; padding-bottom: 0.5rem;">
                    <i class="fas fa-truck me-1 text-info"></i> Viajes en Curso
                </h5>
                <p style="color: #eee; margin: 0; font-size: 1.2rem; font-weight: 500;">
                    {{ $viajesEnCurso }} {{ Str::plural('viaje', $viajesEnCurso) }} activos
                </p>
            </div>
        </div>
    </div>

    <!-- Botón de regreso -->
    <div>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Volver al Dashboard
        </a>
    </div>
</div>
@endsection