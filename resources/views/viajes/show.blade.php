@extends('layouts.app')

@section('content')
<div class="p-3">
    <h2><i class="fas fa-route text-primary"></i> Detalles del Viaje #{{ $viaje->id }}</h2>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="background-color: transparent; padding: 0; font-size: 0.9rem;">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" style="color: #198754;">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('viajes.index') }}" style="color: #198754;">Viajes</a></li>
            <li class="breadcrumb-item active" style="color: #ccc;">Detalle</li>
        </ol>
    </nav>

    <!-- Cliente asociado -->
    @if($viaje->cliente)
        <div style="
            background-color: #1a1a1a;
            border-radius: 0.375rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border: 1px solid #444;
        ">
            <h5 style="color: #fff; margin-top: 0; border-bottom: 1px solid #444; padding-bottom: 0.5rem;">
                <i class="fas fa-user me-1"></i> Cliente Asociado
            </h5>
            <div style="color: #ddd; line-height: 1.6;">
                <strong>{{ $viaje->cliente->nombre }}</strong><br>
                <small>
                    CUIT: {{ $viaje->cliente->cuit }} | 
                    Tipo: {{ ucfirst($viaje->cliente->tipo) }}
                </small>
            </div>
        </div>
    @endif

    <!-- Datos principales del viaje -->
    <div style="
        background-color: #1a1a1a;
        border-radius: 0.375rem;
        padding: 1.5rem;
        border: 1px solid #444;
        margin-bottom: 1.5rem;
    ">
        <div class="row" style="font-size: 0.95rem; color: #ccc;">
            <div class="col-md-6">
                <p style="margin: 0.4rem 0;"><strong>Origen:</strong> {{ $viaje->origen }}</p>
                <p style="margin: 0.4rem 0;"><strong>Destino:</strong> {{ $viaje->destino }}</p>
                
                <!-- Vehículo -->
                <p style="margin: 0.4rem 0;">
                    <strong>Vehículo:</strong> 
                    {{ $viaje->vehiculo ? $viaje->vehiculo->patente : 'No asignado' }}
                </p>

                <!-- Chofer -->
                <p style="margin: 0.4rem 0;">
                    <strong>Chofer:</strong> 
                    {{ $viaje->chofer ? $viaje->chofer->nombre : 'No asignado' }}
                </p>
            </div>
            <div class="col-md-6">
                <p style="margin: 0.4rem 0;"><strong>Salida:</strong> {{ \Carbon\Carbon::parse($viaje->fecha_salida)->format('d/m/Y') }}</p>
                <p style="margin: 0.4rem 0;"><strong>Llegada:</strong> 
                    @if($viaje->fecha_llegada)
                        {{ \Carbon\Carbon::parse($viaje->fecha_llegada)->format('d/m/Y') }}
                    @else
                        <span style="color: #888;">No definida</span>
                    @endif
                </p>
                <p style="margin: 0.4rem 0;"><strong>Kilómetros:</strong> {{ number_format($viaje->kilometros, 0, '', '.') }} km</p>
                <p style="margin: 0.4rem 0;">
                    <strong>Estado:</strong> 
                    <span style="
                        font-size: 0.8rem;
                        padding: 0.2em 0.5em;
                        border-radius: 4px;
                        background-color:
                            @if($viaje->estado == 'en curso') #ffc107; color: #000;
                            @elseif($viaje->estado == 'completado') #28a745; color: #fff;
                            @else #dc3545; color: #fff; @endif
                    ">
                        {{ ucfirst($viaje->estado) }}
                    </span>
                </p>

                <!-- Tipo de Viaje -->
                @if($viaje->tipo)
                    <p style="margin: 0.4rem 0;">
                        <strong>Tipo de Viaje:</strong> 
                        <span style="
                            font-size: 0.8rem;
                            padding: 0.2em 0.5em;
                            border-radius: 4px;
                            background-color: {{ $viaje->tipo == 'agrícola' ? '#28a745' : '#fd7e14' }};
                            color: white;
                        ">
                            {{ $viaje->tipo == 'agrícola' ? 'Agrícola' : 'Construcción' }}
                        </span>
                    </p>
                @endif
            </div>
        </div>

        <div style="margin-top: 1rem; padding: 0.8rem; background-color: #2a2a2a; border-radius: 0.375rem;">
            <strong style="color: #fff;">Descripción de carga:</strong>
            <p style="color: #ddd; margin: 0.5rem 0 0 0;">{{ $viaje->descripcion_carga }}</p>
        </div>
    </div>

    <!-- Productos transportados -->
    @if($viaje->productos && $viaje->productos->isNotEmpty())
        <div style="
            background-color: #1a1a1a;
            border-radius: 0.375rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border: 1px solid #444;
        ">
            <h5 style="color: #fff; margin-top: 0; border-bottom: 1px solid #444; padding-bottom: 0.5rem;">
                <i class="fas fa-box me-1"></i> Productos Transportados
            </h5>

            <div style="max-height: 300px; overflow-y: auto; border: 1px solid #444; border-radius: 0.375rem;">
                <table style="
                    width: 100%;
                    border-collapse: collapse;
                    font-size: 0.9rem;
                    background-color: #121212;
                    color: #eee;
                    margin: 0;
                ">
                    <thead style="background-color: #000; color: #fff;">
                        <tr>
                            <th style="padding: 0.5rem 0.6rem; text-align: left;">Producto</th>
                            <th style="padding: 0.5rem 0.6rem; text-align: left;">Categoría</th>
                            <th style="padding: 0.5rem 0.6rem; text-align: right;">Cantidad</th>
                            <th style="padding: 0.5rem 0.6rem; text-align: right;">Notas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($viaje->productos as $producto)
                            <tr style="border-bottom: 1px solid #333; background-color: #121212;">
                                <td style="padding: 0.4rem 0.6rem; color: #ddd;">
                                    <strong>{{ $producto->nombre }}</strong>
                                </td>
                                <td style="padding: 0.4rem 0.6rem;">
                                    <span style="
                                        font-size: 0.75rem;
                                        padding: 0.2em 0.5em;
                                        border-radius: 4px;
                                        background-color:
                                            {{ $producto->categoria == 'agrícola' ? '#28a745' : '#fd7e14' }};
                                        color: white;
                                    ">
                                        {{ ucfirst($producto->categoria) }}
                                    </span>
                                </td>
                                <td style="padding: 0.4rem 0.6rem; color: #fff; text-align: right;">
                                    {{ $producto->pivot->cantidad ?? 0 }}
                                </td>
                                <td style="padding: 0.4rem 0.6rem; color: #ccc; text-align: right;">
                                    @if(isset($producto->pivot->notas) && !empty($producto->pivot->notas))
                                        {{ $producto->pivot->notas }}
                                    @else
                                        <em style="color: #777;">Sin notas</em>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Botones -->
    <div class="d-flex gap-2">
        <a href="{{ route('viajes.edit', $viaje->id) }}" class="btn btn-primary">
            <i class="fas fa-edit me-1"></i>Editar Viaje
        </a>
        <a href="{{ route('viajes.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Volver al listado
        </a>
    </div>
</div>
@endsection