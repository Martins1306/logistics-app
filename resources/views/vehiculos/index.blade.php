@extends('layouts.app')

@section('content')
<div class="p-3">
    <h2><i class="fas fa-truck text-primary"></i> Vehículos</h2>

    <!-- Búsqueda y filtro -->
    <div class="mb-3 d-flex flex-wrap gap-2 align-items-center">
        <div class="flex-grow-1" style="min-width: 200px;">
            <input type="text" id="search-vehiculos" class="form-control form-control-sm" placeholder="Buscar patente, marca..." 
                   style="background-color: #333; border: 1px solid #555; color: #eee;">
        </div>
        <select id="filter-tipo" class="form-select form-select-sm" style="width: auto; background-color: #333; border: 1px solid #555; color: #eee;">
            <option value="">Todos los tipos</option>
            <option value="camion">Camión</option>
            <option value="camioneta">Camioneta</option>
            <option value="bascula">Báscula</option>
            <option value="acoplado">Acoplado</option>
            <option value="semirremolque">Semirremolque</option>
            <option value="tolva">Tolva</option>
        </select>
        <a href="{{ route('vehiculos.create') }}" class="btn btn-sm btn-success">
            <i class="fas fa-plus-circle"></i> Nuevo
        </a>
    </div>

    <!-- Contenedor con scroll -->
    <div style="
        max-height: 60vh; 
        overflow-y: auto; 
        border-radius: 0.375rem; 
        border: 1px solid #444;
        background-color: #121212;
        padding: 0;
    ">
        <!-- Tabla con fondo oscuro -->
        <table id="tabla-vehiculos" style="
            width: 100%; 
            border-collapse: collapse; 
            font-size: 0.9rem; 
            margin: 0; 
            background-color: #121212;
            color: #eee;
        ">
            <!-- ENCABEZADO: NEGRO + BLANCO -->
            <thead>
                <tr style="
                    background-color: #000; 
                    color: #fff; 
                    position: sticky; 
                    top: 0; 
                    z-index: 10;
                    border-bottom: 2px solid #555;
                ">
                    <th style="
                        padding: 0.5rem 0.6rem; 
                        text-align: left;
                        font-weight: 600;
                        white-space: nowrap;
                    ">ID</th>
                    <th style="
                        padding: 0.5rem 0.6rem; 
                        text-align: left;
                        font-weight: 600;
                    ">Patente</th>
                    <th style="
                        padding: 0.5rem 0.6rem; 
                        text-align: left;
                        font-weight: 600;
                    ">Marca/Modelo</th>
                    <th style="
                        padding: 0.5rem 0.6rem; 
                        text-align: left;
                        font-weight: 600;
                    ">Tipo</th>
                    <th style="
                        padding: 0.5rem 0.6rem; 
                        text-align: left;
                        font-weight: 600;
                    ">Km Actual</th>
                    <th style="
                        padding: 0.5rem 0.6rem; 
                        text-align: left;
                        font-weight: 600;
                    ">Estado</th>
                    <th style="
                        padding: 0.5rem 0.6rem; 
                        text-align: right;
                        font-weight: 600;
                    ">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($vehiculos as $vehiculo)
                    <tr data-tipo="{{ $vehiculo->tipo }}" 
                        data-patente="{{ $vehiculo->patente }}" 
                        data-marca="{{ $vehiculo->marca }}"
                        style="
                            border-bottom: 1px solid #333;
                            transition: background-color 0.2s;
                        "
                        onmouseover="this.style.backgroundColor='#2a2a2a'"
                        onmouseout="this.style.backgroundColor='transparent'">
                        <td style="
                            padding: 0.4rem 0.6rem; 
                            color: #fff;
                            font-weight: 500;
                        ">
                            {{ $vehiculo->id }}
                        </td>
                        <td style="
                            padding: 0.4rem 0.6rem; 
                            color: #fff;
                            font-weight: 500;
                            white-space: nowrap;
                        ">
                            {{ $vehiculo->patente }}
                        </td>
                        <td style="
                            padding: 0.4rem 0.6rem; 
                            color: #ddd;
                        ">
                            <div style="font-weight: 500;">{{ $vehiculo->marca }}</div>
                            <div style="font-size: 0.8rem; color: #aaa;">{{ $vehiculo->modelo }}</div>
                        </td>
                        <td style="padding: 0.4rem 0.6rem;">
                            <span style="
                                font-size: 0.8rem;
                                padding: 0.2em 0.5em;
                                border-radius: 4px;
                                background-color: #0d6efd;
                                color: white;
                                white-space: nowrap;
                            ">
                                {{ ucfirst($vehiculo->tipo) }}
                            </span>
                        </td>
                        <td style="
                            padding: 0.4rem 0.6rem; 
                            color: #ccc;
                        ">
                            {{ number_format($vehiculo->kilometraje_actual ?? 0, 0, '', '.') }} km
                        </td>
                        <td style="padding: 0.4rem 0.6rem;">
                            @if($vehiculo->necesitaMantenimiento())
                                <span style="
                                    font-size: 0.7rem;
                                    padding: 0.2em 0.5em;
                                    border-radius: 4px;
                                    background-color: #dc3545;
                                    color: white;
                                    white-space: nowrap;
                                ">Mto!</span>
                            @else
                                <span style="
                                    font-size: 0.7rem;
                                    padding: 0.2em 0.5em;
                                    border-radius: 4px;
                                    background-color: #198754;
                                    color: white;
                                    white-space: nowrap;
                                ">OK</span>
                            @endif
                        </td>
                        <td class="text-end" style="
                            padding: 0.4rem 0.6rem; 
                            text-align: right;
                            white-space: nowrap;
                        ">
                            <a href="{{ route('vehiculos.show', $vehiculo->id) }}" 
                               style="
                                   display: inline-block;
                                   padding: 0.15rem 0.3rem;
                                   font-size: 0.8rem;
                                   color: #fff;
                                   background-color: #198754;
                                   border-radius: 4px;
                                   text-decoration: none;
                                   margin-right: 0.25rem;
                                   min-width: 50px;
                                   text-align: center;
                               ">
                                <i class="fas fa-eye" style="margin-right: 0.2rem;"></i>Ver
                            </a>
                            <a href="{{ route('vehiculos.edit', $vehiculo->id) }}" 
                               style="
                                   display: inline-block;
                                   padding: 0.15rem 0.3rem;
                                   font-size: 0.8rem;
                                   color: #fff;
                                   background-color: #0d6efd;
                                   border-radius: 4px;
                                   text-decoration: none;
                                   min-width: 50px;
                                   text-align: center;
                               ">
                                <i class="fas fa-edit" style="margin-right: 0.2rem;"></i>Editar
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="
                            padding: 1rem; 
                            text-align: center; 
                            color: #888; 
                            font-style: italic;
                            background-color: #1a1a1a;
                        ">
                            Sin vehículos registrados
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const table = document.getElementById('tabla-vehiculos');
    const searchInput = document.getElementById('search-vehiculos');
    const filterSelect = document.getElementById('filter-tipo');

    // Función para normalizar texto: sin acentos, minúsculas, limpio
    function normalize(str) {
        return str.toLowerCase()
                  .normalize('NFD')           // Descompone caracteres acentuados
                  .replace(/[\u0300-\u036f]/g, '') // Elimina tildes
                  .trim();
    }

    function filtrar() {
        const searchTerm = normalize(searchInput?.value || '');
        const filterValue = normalize(filterSelect?.value || '');

        Array.from(table.querySelectorAll('tbody tr')).forEach(row => {
            const patente = normalize(row.dataset.patente || '');
            const marca = normalize(row.dataset.marca || '');
            const modelo = normalize(row.dataset.modelo || '');
            const tipo = normalize(row.dataset.tipo || '');

            // Búsqueda en campos generales
            const matchesSearch = !searchTerm || 
                patente.includes(searchTerm) || 
                marca.includes(searchTerm) || 
                modelo.includes(searchTerm);

            // Filtro por tipo: comparación EXACTA (no parcial)
            let matchesFilter = true;
            if (filterValue) {
                // Normalizamos el tipo del vehículo
                const normalizedTipo = normalize(tipo);
                
                // Lista de tipos permitidos (puedes ajustarla)
                const tiposMap = {
                    'camion': ['camion'],
                    'camioneta': ['camioneta'],
                    'bascula': ['bascula'],
                    'acoplado': ['acoplado'],
                    'tolva': ['tolva'],
                    'semirremolque': ['semirremolque']
                };

                // Obtenemos los valores esperados para el filtro
                const valoresEsperados = tiposMap[filterValue] || [];

                // Compara exactamente
                matchesFilter = valoresEsperados.some(val => val === normalizedTipo);
            }

            row.style.display = matchesSearch && matchesFilter ? '' : 'none';
        });
    }

    if (searchInput) searchInput.addEventListener('input', filtrar);
    if (filterSelect) filterSelect.addEventListener('change', filtrar);
});
</script>
@endpush
@endsection