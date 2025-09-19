@extends('layouts.app')

@section('content')
<div class="p-3">
    <h2><i class="fas fa-users text-primary"></i> Clientes</h2>

    <!-- Búsqueda -->
    <div class="mb-3 d-flex flex-wrap gap-2 align-items-center">
        <div class="flex-grow-1" style="min-width: 200px;">
            <input type="text" id="search-clientes" class="form-control form-control-sm" placeholder="Buscar nombre, cuit..."
                   style="background-color: #333; border: 1px solid #555; color: #eee;">
        </div>
        <select id="filter-tipo" class="form-select form-select-sm" style="width: auto; background-color: #333; border: 1px solid #555; color: #eee;">
            <option value="">Todos los tipos</option>
            <option value="agrícola">Agrícola</option>
            <option value="construccion">Construcción</option>
            <option value="industrial">Industrial</option>
            <option value="transporte">Transporte</option>
            <option value="otros">Otros</option>
        </select>
        <a href="{{ route('clientes.create') }}" class="btn btn-sm btn-success">
            <i class="fas fa-plus-circle"></i> Nuevo
        </a>
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
        <table id="tabla-clientes" style="
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
                    <th style="padding: 0.5rem 0.6rem; text-align: left;">Nombre</th>
                    <th style="padding: 0.5rem 0.6rem; text-align: left;">CUIT</th>
                    <th style="padding: 0.5rem 0.6rem; text-align: left;">Tipo</th>
                    <th style="padding: 0.5rem 0.6rem; text-align: left;">Contacto</th>
                    <th style="padding: 0.5rem 0.6rem; text-align: right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($clientes as $cliente)
                    <tr data-nombre="{{ $cliente->nombre }}" 
                        data-cuit="{{ $cliente->cuit }}" 
                        data-tipo="{{ $cliente->tipo }}"
                        style="border-bottom: 1px solid #333;">
                        <td style="padding: 0.4rem 0.6rem; color: #fff; font-weight: 500;">{{ $cliente->id }}</td>
                        <td style="padding: 0.4rem 0.6rem; color: #ddd;">{{ $cliente->nombre_corto }}</td>
                        <td style="padding: 0.4rem 0.6rem; color: #ccc;">{{ $cliente->cuit }}</td>
                        <td style="padding: 0.4rem 0.6rem;">
                            <span style="
                                font-size: 0.75rem;
                                padding: 0.2em 0.5em;
                                border-radius: 4px;
                                background-color:
                                    {{ $cliente->tipo == 'agrícola' ? '#28a745' :
                                       ($cliente->tipo == 'construccion' ? '#fd7e14' :
                                       ($cliente->tipo == 'industrial' ? '#0d6efd' :
                                       ($cliente->tipo == 'transporte' ? '#6f42c1' : '#6c757d'))) }};
                                color: white;
                            ">
                                {{ ucfirst($cliente->tipo) }}
                            </span>
                        </td>
                        <td style="padding: 0.4rem 0.6rem; color: #ccc;">
                            {{ $cliente->telefono ?? '-' }}<br>
                            <small>{{ $cliente->email ?? '-' }}</small>
                        </td>
                        <td class="text-end" style="padding: 0.4rem 0.6rem; text-align: right;">
                            <a href="{{ route('clientes.show', $cliente->id) }}" 
                               style="display: inline-block; padding: 0.15rem 0.3rem; font-size: 0.8rem; color: #fff; background-color: #198754; border-radius: 4px; text-decoration: none; margin-right: 0.25rem;">
                                <i class="fas fa-eye" style="margin-right: 0.2rem;"></i>Ver
                            </a>
                            <a href="{{ route('clientes.edit', $cliente->id) }}" 
                               style="display: inline-block; padding: 0.15rem 0.3rem; font-size: 0.8rem; color: #fff; background-color: #0d6efd; border-radius: 4px; text-decoration: none;">
                                <i class="fas fa-edit" style="margin-right: 0.2rem;"></i>Editar
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 1rem; text-align: center; color: #888; background-color: #1a1a1a;">
                            Sin clientes registrados
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
    const table = document.getElementById('tabla-clientes');
    const searchInput = document.getElementById('search-clientes');
    const filterSelect = document.getElementById('filter-tipo');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const filterValue = filterSelect.value.toLowerCase().trim();

        Array.from(table.querySelectorAll('tbody tr')).forEach(row => {
            const nombre = row.dataset.nombre.toLowerCase();
            const cuit = row.dataset.cuit.toLowerCase();
            const tipo = row.dataset.tipo.toLowerCase();

            const matchesSearch = !searchTerm || 
                nombre.includes(searchTerm) || 
                cuit.includes(searchTerm);
            
            const matchesFilter = !filterValue || tipo === filterValue;

            row.style.display = matchesSearch && matchesFilter ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', filterTable);
    filterSelect.addEventListener('change', filterTable);
});
</script>
@endpush
@endsection