@extends('layouts.app')

@section('content')
<div class="p-3">
    <h2><i class="fas fa-truck text-primary"></i> Detalles del Vehículo</h2>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="background-color: transparent; padding: 0; font-size: 0.9rem;">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" style="color: #198754;">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('vehiculos.index') }}" style="color: #198754;">Vehículos</a></li>
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
                <p style="margin: 0.4rem 0;"><strong>Patente:</strong> 
                    <span style="color: #fff; font-weight: 500;">{{ $vehiculo->patente }}</span>
                </p>
                <p style="margin: 0.4rem 0;"><strong>Marca:</strong> {{ $vehiculo->marca }}</p>
                <p style="margin: 0.4rem 0;"><strong>Modelo:</strong> {{ $vehiculo->modelo }}</p>
                <p style="margin: 0.4rem 0;"><strong>Tipo:</strong> 
                    @php
                        $tipo = strtolower($vehiculo->tipo ?? '');
                        if ($tipo === 'camion' || $tipo === 'tractocamion') {
                            $bgColor = '#0dcaf0';
                            $textColor = 'white';
                        } elseif ($tipo === 'camioneta') {
                            $bgColor = '#fd7e14';
                            $textColor = '#000';
                        } else {
                            $bgColor = '#6c757d';
                            $textColor = 'white';
                        }
                    @endphp
                    <span style="
                        font-size: 0.8rem;
                        padding: 0.2em 0.5em;
                        border-radius: 4px;
                        background-color: {{ $bgColor }};
                        color: {{ $textColor }};
                    ">{{ ucfirst($vehiculo->tipo) }}</span>
                </p>
            </div>
            <div class="col-md-6">
                <p style="margin: 0.4rem 0;"><strong>Capacidad:</strong> 
                    {{ number_format($vehiculo->capacidad_kg ? $vehiculo->capacidad_kg : 0) }} kg
                </p>
                <p style="margin: 0.4rem 0;"><strong>Fecha de Compra:</strong> 
                    @if(!empty($vehiculo->fecha_compra))
                        {{ \Carbon\Carbon::parse($vehiculo->fecha_compra)->format('d/m/Y') }}
                    @else
                        –
                    @endif
                </p>
                 <p style="margin: 0.4rem 0;"><strong>Estado:</strong> 
                    @php
                        switch ($vehiculo->estado) {
                            case 'activo':
                                $estadoColor = '#28a745';
                                $estadoTexto = '✅ Activo';
                                break;
                            case 'en mantenimiento':
                                $estadoColor = '#fd7e14';
                                $estadoTexto = '🛠️ En mantenimiento';
                                break;
                            default:
                                $estadoColor = '#6c757d';
                                $estadoTexto = '⏸️ Inactivo';
                        }
                    @endphp
                    <span style="color: {{ $estadoColor }}; font-weight: 500;">{{ $estadoTexto }}</span>
                </p>
                   
            </div>
        </div>

        <!-- Alertas de mantenimiento -->
        @php
            $tieneIntervalo = !empty($vehiculo->intervalo_mantenimiento);
            $kmActual = $vehiculo->kilometraje_actual ? $vehiculo->kilometraje_actual : 0;
            $proximoMantenimiento = $vehiculo->proximo_mantenimiento;
            $necesita = $tieneIntervalo && $kmActual >= $proximoMantenimiento;
            $proximo = $tieneIntervalo && !$necesita && $kmActual >= ($proximoMantenimiento - 1000);
        @endphp

        @if($necesita)
            <div style="margin-top: 1rem; padding: 0.8rem; background-color: #4e1313; border: 1px solid #dc3545; border-radius: 0.375rem; color: #f8d7da;">
                <strong>⚠️ REQUIERE MANTENIMIENTO INMEDIATO</strong><br>
                Kilometraje actual: <strong>{{ number_format($kmActual) }} km</strong><br>
                Próximo mantenimiento: <strong>{{ number_format($proximoMantenimiento) }} km</strong>
            </div>
        @elseif($proximo)
            <div style="margin-top: 1rem; padding: 0.8rem; background-color: #3f3000; border: 1px solid #ffc107; border-radius: 0.375rem; color: #fffbe6;">
                <strong>🟡 Próximo a mantenimiento</strong><br>
                A solo {{ number_format($proximoMantenimiento - $kmActual) }} km del próximo servicio.
            </div>
        @elseif(!$tieneIntervalo)
            <div style="margin-top: 1rem; padding: 0.8rem; background-color: #2a2a2a; border: 1px solid #6c757d; border-radius: 0.375rem; color: #888;">
                <strong>ℹ️ Sin programa de mantenimiento</strong><br>
                No se ha definido un intervalo de mantenimiento para este vehículo.
            </div>
        @endif

        <div style="margin-top: 1rem; padding: 0.8rem; background-color: #2a2a2a; border-radius: 0.375rem;">
            <strong style="color: #fff;">Registrado el:</strong>
            <span style="color: #ddd;">
                {{ \Carbon\Carbon::parse($vehiculo->created_at)->format('d/m/Y H:i') }}
            </span>
        </div>
    </div>

    <!-- Acciones -->
    <div class="d-flex gap-2 mb-4 flex-wrap">
        <a href="{{ route('vehiculos.edit', $vehiculo->id) }}" class="btn btn-primary">
            <i class="fas fa-edit me-1"></i>Editar Vehículo
        </a>
        <a href="{{ route('mantenimientos.create', $vehiculo->id) }}" class="btn btn-warning">
            <i class="fas fa-wrench me-1"></i>+ Registro de Mantenimiento
        </a>
        <a href="{{ route('vehiculos.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Volver al listado
        </a>
    </div>

    <!-- Viajes realizados -->
    @if(isset($vehiculo->viajes) && count($vehiculo->viajes) > 0)
        <h5><i class="fas fa-route text-info"></i> Historial de Viajes</h5>
        <div style="max-height: 300px; overflow-y: auto; border: 1px solid #444; border-radius: 0.375rem; margin-bottom: 1.5rem;">
            <table class="table table-dark table-striped table-sm" style="font-size: 0.9rem; margin: 0;">
                <thead style="background-color: #000;">
                    <tr>
                        <th>Origen</th>
                        <th>Destino</th>
                        <th>Salida</th>
                        <th>Km</th>
                        <th>Chofer</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vehiculo->viajes as $viaje)
                        @php
                            $choferNombre = '';
                            if (is_object($viaje->chofer)) {
                                $choferNombre = $viaje->chofer->nombre . ' ' . ($viaje->chofer->apellido ?? '');
                            } else {
                                $choferNombre = '–';
                            }

                            switch ($viaje->estado) {
                                case 'en curso': $estadoBg = '#fd7e14'; break;
                                case 'completado': $estadoBg = '#28a745'; break;
                                case 'cancelado': $estadoBg = '#dc3545'; break;
                                default: $estadoBg = '#6c757d';
                            }
                        @endphp
                        <tr>
                            <td>{{ $viaje->origen ?? '–' }}</td>
                            <td>{{ $viaje->destino }}</td>
                            <td>{{ \Carbon\Carbon::parse($viaje->fecha_salida)->format('d/m') }}</td>
                            <td>{{ number_format($viaje->kilometros ? $viaje->kilometros : 0) }}</td>
                            <td>{{ $choferNombre }}</td>
                            <td>
                                <span style="
                                    padding: 0.2em 0.5em;
                                    border-radius: 4px;
                                    background-color: {{ $estadoBg }};
                                    color: white;
                                    font-size: 0.8rem;
                                ">{{ ucfirst($viaje->estado) }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div style="padding: 1rem; background-color: #1a1a1a; border-radius: 0.375rem; border: 1px solid #444; color: #888; text-align: center; margin-bottom: 1.5rem;">
            No hay viajes registrados para este vehículo.
        </div>
    @endif

    <!-- Mantenimientos -->
    @if(isset($vehiculo->mantenimientos) && count($vehiculo->mantenimientos) > 0)
        <h5><i class="fas fa-tools text-warning"></i> Historial de Mantenimientos</h5>
        <div style="max-height: 300px; overflow-y: auto; border: 1px solid #444; border-radius: 0.375rem; margin-bottom: 1.5rem;">
            <table class="table table-dark table-striped table-sm" style="font-size: 0.9rem; margin: 0;">
                <thead style="background-color: #000;">
                    <tr>
                        <th>Tipo</th>
                        <th>Fecha</th>
                        <th>Km</th>
                        <th>Costo</th>
                        <th>Proveedor</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vehiculo->mantenimientos as $m)
                        @php
                            $fecha = !empty($m->fecha) ? \Carbon\Carbon::parse($m->fecha)->format('d/m/Y') : '–';
                            $km = $m->kilometraje ? number_format($m->kilometraje) : '–';
                            $costo = $m->costo ? '$ ' . number_format($m->costo, 2, ',', '.') : '–';
                        @endphp
                        <tr>
                            <td>{{ ucfirst($m->tipo) }}</td>
                            <td>{{ $fecha }}</td>
                            <td>{{ $km }}</td>
                            <td>{{ $costo }}</td>
                            <td>{{ $m->proveedor ?? '–' }}</td>
                            <td>
                                <a href="{{ route('mantenimientos.edit', $m->id) }}" class="btn btn-sm btn-outline-warning">Editar</a>
                                <form action="{{ route('mantenimientos.destroy', $m->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('¿Eliminar este mantenimiento?')">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div style="padding: 1rem; background-color: #1a1a1a; border-radius: 0.375rem; border: 1px solid #444; color: #888; text-align: center;">
            No hay registros de mantenimiento.
        </div>
    @endif
</div>
@endsection