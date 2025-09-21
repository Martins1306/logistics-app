@extends('layouts.app')

@section('content')
<div class="p-3">
    <h2><i class="fas fa-route text-primary"></i> Viajes</h2>

    <!-- Mensaje de éxito -->
    @if(session('success'))
        <div style="
            background-color: #28a745;
            color: white;
            padding: 0.5rem;
            border-radius: 4px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        ">
            {{ session('success') }}
        </div>
    @endif

    <!-- Búsqueda y filtro -->
    <div class="mb-3 d-flex flex-wrap gap-2 align-items-center">
        <div class="flex-grow-1" style="min-width: 200px;">
            <input type="text" id="search-viajes" class="form-control form-control-sm" placeholder="Buscar destino o vehículo..."
                   style="background-color: #333; border: 1px solid #555; color: #eee;">
        </div>
        <select id="filter-estado" class="form-select form-select-sm" style="width: auto; background-color: #333; border: 1px solid #555; color: #eee;">
            <option value="">Todos los estados</option>
            <option value="pendiente">Pendiente</option>
            <option value="en curso">En Curso</option>
            <option value="completado">Completado</option>
            <option value="cancelado">Cancelado</option>
        </select>
        <a href="{{ route('viajes.create') }}" class="btn btn-sm btn-success">
            <i class="fas fa-plus-circle"></i> Nuevo Viaje
        </a>
    </div>

    <!-- Estadísticas rápidas -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div style="background: #1a1a1a; padding: 1rem; border-radius: 8px; text-align: center; border: 1px solid #444;">
                <strong style="color: #fff;">Total</strong><br>
                <span style="color: #0dcaf0; font-size: 1.2rem;">{{ count($viajes) }}</span>
            </div>
        </div>
        <div class="col-md-3">
            <div style="background: #1a1a1a; padding: 1rem; border-radius: 8px; text-align: center; border: 1px solid #444;">
                <strong style="color: #fff;">En Curso</strong><br>
                @php
                    $enCurso = 0;
                    foreach($viajes as $v) {
                        if (($v->estado ?? '') == 'en curso') $enCurso++;
                    }
                @endphp
                <span style="color: #fd7e14; font-size: 1.2rem;">{{ $enCurso }}</span>
            </div>
        </div>
       <!--  <div class="col-md-3">
            <div style="background: #1a1a1a; padding: 1rem; border-radius: 8px; text-align: center; border: 1px solid #444;">
                <strong style="color: #fff;">Agrícolas</strong><br>
                @php
                    $agrícola = 0;
                    foreach($viajes as $v) {
                        $tipo = strtolower($v->tipo ?? '');
                        if (strpos($tipo, 'agricol') !== false || 
                            strpos($tipo, 'hortaliza') !== false || 
                            strpos($tipo, 'fruta') !== false) {
                            $agrícola++;
                        }
                    }
                @endphp
                <span style="color: #28a745; font-size: 1.2rem;">{{ $agrícola }}</span>
            </div>
        </div>
        <div class="col-md-3">
            <div style="background: #1a1a1a; padding: 1rem; border-radius: 8px; text-align: center; border: 1px solid #444;">
                <strong style="color: #fff;">Construcción</strong><br>
                @php
                    $construccion = 0;
                    foreach($viajes as $v) {
                        $tipo = strtolower($v->tipo ?? '');
                        if (strpos($tipo, 'construccion') !== false || 
                            strpos($tipo, 'materiales') !== false || 
                            strpos($tipo, 'cemento') !== false ||
                            strpos($tipo, 'acero') !== false) {
                            $construccion++;
                        }
                    }
                @endphp
                <span style="color: #ffc107; font-size: 1.2rem;">{{ $construccion }}</span>
            </div>
        </div>-->
    </div>

    <!-- Tabla con scroll -->
    <div style="
        max-height: 60vh; 
        overflow-y: auto; 
        border-radius: 0.375rem; 
        border: 1px solid #444;
        background-color: #121212;
        padding: 0;
    ">
        <table id="tabla-viajes" style="
            width: 100%; 
            border-collapse: collapse; 
            font-size: 0.9rem; 
            margin: 0; 
            background-color: #121212;
            color: #eee;
        ">
            <thead style="background-color: #000; color: #fff;">
                <tr>
                    <th style="padding: 0.5rem 0.6rem; text-align: left;">ID</th>
                    <th style="padding: 0.5rem 0.6rem; text-align: left;">Destino</th>
                    <th style="padding: 0.5rem 0.6rem; text-align: left;">Vehículo</th>
                    <th style="padding: 0.5rem 0.6rem; text-align: left;">Chofer</th>
                    <th style="padding: 0.5rem 0.6rem; text-align: left;">Tipo</th>
                    <th style="padding: 0.5rem 0.6rem; text-align: left;">Estado</th>
                    <th style="padding: 0.5rem 0.6rem; text-align: right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($viajes as $viaje)
                    @php
                        // Normalizar tipo
                        $tipo = strtolower($viaje->tipo ?? '');
                        $esAgricola = (strpos($tipo, 'agricol') !== false || 
                                      strpos($tipo, 'hortaliza') !== false || 
                                      strpos($tipo, 'fruta') !== false);
                        $esConstruccion = (strpos($tipo, 'construccion') !== false || 
                                          strpos($tipo, 'materiales') !== false || 
                                          strpos($tipo, 'cemento') !== false);

                        // Color de estado
                        switch ($viaje->estado) {
                            case 'en curso': $colorEstado = '#fd7e14'; break;
                            case 'completado': $colorEstado = '#28a745'; break;
                            case 'cancelado': $colorEstado = '#dc3545'; break;
                            default: $colorEstado = '#6c757d';
                        }

                        // Color y nombre de tipo
                        $colorTipo = $esAgricola ? '#28a745' : ($esConstruccion ? '#ffc107' : '#6c757d');
                        $nombreTipo = $esAgricola ? 'Agrícola' : ($esConstruccion ? 'Construcción' : 'Otro');

                        // Datos para filtro
                        $destino = strtolower($viaje->destino ?? '');
                        $choferNombre = is_object($viaje->chofer) ? strtolower($viaje->chofer->nombre) : '';
                        $vehiculoPatente = is_object($viaje->vehiculo) ? strtolower($viaje->vehiculo->patente) : '';
                    @endphp
                    <tr 
                        data-destino="{{ $destino }}"
                        data-chofer="{{ $choferNombre }}"
                        data-vehiculo="{{ $vehiculoPatente }}"
                        data-tipo="{{ $esAgricola ? 'agrícola' : ($esConstruccion ? 'construccion' : 'otro') }}"
                        data-estado="{{ strtolower($viaje->estado ?? '') }}"
                        style="border-bottom: 1px solid #333;">
                        <td style="padding: 0.4rem 0.6rem; color: #fff; font-weight: 500;">
                            {{ $viaje->id }}
                        </td>
                        <td style="padding: 0.4rem 0.6rem; color: #ddd;">
                            {{ $viaje->destino }}
                        </td>
                        <td style="padding: 0.4rem 0.6rem; color: #ccc;">
                            {{ is_object($viaje->vehiculo) ? $viaje->vehiculo->patente : '–' }}
                        </td>
                        <td style="padding: 0.4rem 0.6rem; color: #ccc;">
                            {{ is_object($viaje->chofer) ? $viaje->chofer->nombre : '–' }}
                        </td>
                        <td style="padding: 0.4rem 0.6rem;">
                            <span style="
                                font-size: 0.75rem;
                                padding: 0.2em 0.5em;
                                border-radius: 4px;
                                background-color: {{ $colorTipo }};
                                color: {{ $colorTipo == '#ffc107' ? '#000' : 'white' }};
                            ">
                                {{ $nombreTipo }}
                            </span>
                        </td>
                        <td style="padding: 0.4rem 0.6rem;">
                            <span style="
                                font-size: 0.75rem;
                                padding: 0.2em 0.5em;
                                border-radius: 4px;
                                background-color: {{ $colorEstado }};
                                color: white;
                            ">
                                {{ ucfirst($viaje->estado ?? '–') }}
                            </span>
                        </td>
                        <td class="text-end" style="padding: 0.4rem 0.6rem; text-align: right;">
                            <a href="{{ route('viajes.show', $viaje->id) }}" 
                               style="display: inline-block; padding: 0.15rem 0.3rem; font-size: 0.8rem; color: #fff; background-color: #198754; border-radius: 4px; text-decoration: none; margin-right: 0.25rem;">
                                <i class="fas fa-eye" style="margin-right: 0.2rem;"></i>Ver
                            </a>
                            <a href="{{ route('viajes.edit', $viaje->id) }}" 
                               style="display: inline-block; padding: 0.15rem 0.3rem; font-size: 0.8rem; color: #fff; background-color: #0d6efd; border-radius: 4px; text-decoration: none;">
                                <i class="fas fa-edit" style="margin-right: 0.2rem;"></i>Editar
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var tabla = document.getElementById('tabla-viajes');
    var searchInput = document.getElementById('search-viajes');
    var filterSelect = document.getElementById('filter-estado');

    function filtrar() {
        var searchTerm = (searchInput && searchInput.value) ? searchInput.value.toLowerCase().trim() : '';
        var filterValue = (filterSelect && filterSelect.value) ? filterSelect.value.toLowerCase().trim() : '';

        var filas = tabla.querySelectorAll('tbody tr');

        for (var i = 0; i < filas.length; i++) {
            var row = filas[i];
            var destino = (row.getAttribute('data-destino') || '').toLowerCase();
            var chofer = (row.getAttribute('data-chofer') || '').toLowerCase();
            var vehiculo = (row.getAttribute('data-vehiculo') || '').toLowerCase();
            var estado = (row.getAttribute('data-estado') || '').toLowerCase();

            var matchSearch = !searchTerm || 
                destino.includes(searchTerm) || 
                chofer.includes(searchTerm) || 
                vehiculo.includes(searchTerm);

            var matchFilter = !filterValue || estado === filterValue;

            row.style.display = matchSearch && matchFilter ? '' : 'none';
        }
    }

    if (searchInput) searchInput.addEventListener('input', filtrar);
    if (filterSelect) filterSelect.addEventListener('change', filtrar);
});
</script>
@endpush
@endsection