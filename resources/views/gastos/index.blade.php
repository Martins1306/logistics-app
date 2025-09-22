@extends('layouts.app')

@section('content')
<div class="p-3">
    <h2><i class="fas fa-receipt text-primary"></i> Gastos</h2>

    <!-- Búsqueda, filtros y botón -->
    <div class="mb-3 d-flex flex-wrap gap-2 align-items-center">
        <!-- Búsqueda general -->
        <div style="flex-grow: 1; min-width: 200px;">
            <input type="text" id="search-gastos" class="form-control form-control-sm" placeholder="Buscar concepto, vehículo..."
                   style="background-color: #333; border: 1px solid #555; color: #eee;">
        </div>

        <!-- Filtro por chofer -->
        <select id="filter-chofer" class="form-select form-select-sm" style="width: auto; background-color: #333; border: 1px solid #555; color: #eee;">
            <option value="">Todos los choferes</option>
            @foreach($choferes as $c)
                <option value="{{ $c->id }}">{{ $c->nombre }}</option>
            @endforeach
        </select>

        <!-- Botón nuevo -->
        <a href="{{ route('gastos.create') }}" class="btn btn-sm btn-success">
            <i class="fas fa-plus-circle"></i> Nuevo
        </a>
    </div>

    <!-- Tabla -->
    <div style="
        max-height: 60vh;
        overflow-y: auto;
        border-radius: 0.375rem;
        border: 1px solid #444;
        background-color: #121212;
    ">
        <table style="
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
            color: #eee;
            background-color: #121212;
        ">
            <thead style="background-color: #000; color: #fff;">
                <tr>
                    <th style="padding: 0.5rem; text-align: left;">ID</th>
                    <th style="padding: 0.5rem; text-align: left;">Concepto</th>
                    <th style="padding: 0.5rem; text-align: left;">Vehículo</th>
                    <th style="padding: 0.5rem; text-align: left;">Chofer</th>
                    <th style="padding: 0.5rem; text-align: right;">Monto</th>
                    <th style="padding: 0.5rem; text-align: left;">Fecha</th>
                    <th style="padding: 0.5rem; text-align: left;">Tipo</th>
                    <th style="padding: 0.5rem; text-align: center;">Acciones</th>
                </tr>
            </thead>
            <tbody id="tabla-gastos-body">
                @forelse($gastos as $g)
                    <tr 
                        data-concepto="{{ strtolower($g->concepto) }}"
                        data-vehiculo="{{ strtolower($g->vehiculo ? $g->vehiculo->patente : '') }}"
                        data-chofer-id="{{ $g->chofer_id }}"
                        style="border-bottom: 1px solid #333;">
                        <td style="padding: 0.4rem 0.6rem; color: #fff; font-weight: 500;">
                            {{ $g->id }}
                        </td>
                        <td style="padding: 0.4rem 0.6rem; color: #ddd;">
                            {{ Str::limit($g->concepto, 20) }}
                        </td>
                        <td style="padding: 0.4rem 0.6rem; color: #ccc;">
                            {{ $g->vehiculo ? $g->vehiculo->patente : '–' }}
                        </td>
                        <td style="padding: 0.4rem 0.6rem; color: #ccc;">
                            {{ $g->chofer ? $g->chofer->nombre : '–' }}
                        </td>
                        <td style="padding: 0.4rem 0.6rem; color: #fff; text-align: right;">
                            ${{ number_format($g->monto, 2, ',', '.') }}
                        </td>
                        <td style="padding: 0.4rem 0.6rem; color: #ccc;">
                            {{ \Carbon\Carbon::parse($g->fecha)->format('d/m/Y') }}
                        </td>
                        <td style="padding: 0.4rem 0.6rem;">
                            @if($g->tipo)
                                @php
                                    $color = '#adb5bd';
                                    if (in_array($g->tipo, ['combustible', 'peaje'])) {
                                        $color = '#fd7e14';
                                    } elseif (in_array($g->tipo, ['mantenimiento', 'repuestos'])) {
                                        $color = '#dc3545';
                                    } elseif ($g->tipo === 'lavado') {
                                        $color = '#0dcaf0';
                                    } elseif ($g->tipo === 'seguro') {
                                        $color = '#6f42c1';
                                    } elseif (in_array($g->tipo, ['estadia', 'otros'])) {
                                        $color = '#6c757d';
                                    }
                                @endphp
                                <span style="
                                    font-size: 0.75rem;
                                    padding: 0.2em 0.5em;
                                    border-radius: 4px;
                                    background-color: {{ $color }};
                                    color: white;
                                ">
                                    {{ ucfirst($g->tipo) }}
                                </span>
                            @else
                                <span style="color: #777;">–</span>
                            @endif
                        </td>
                        <td style="padding: 0.4rem 0.6rem; text-align: center; white-space: nowrap;">
                            <!-- Botón VER -->
                            <a href="{{ route('gastos.show', $g->id) }}"
                               style="
                                   display: inline-block;
                                   align-items: center;
                                   gap: 0.3rem;
                                   background-color: #198754;
                                   color: white !important;
                                   text-decoration: none;
                                   padding: 0.15rem 0.3rem;
                                   border-radius: 4px;
                                   font-size: 0.8rem;
                                   margin-right: 0.25rem;
                                   box-shadow: 0 2px 4px rgba(0,0,0,0.2);
                              "
                               title="Ver detalle">
                                <i class="fas fa-eye"></i> Ver
                            </a>

                            <!-- Botón EDITAR -->
                            <a href="{{ route('gastos.edit', $g->id) }}"
                               style="
                                   display: inline-block;
                                   align-items: center;
                                   gap: 0.3rem;
                                   background-color: #0d6efd;
                                   color: white !important;
                                   text-decoration: none;
                                   padding: 0.15rem 0.3rem;
                                   border-radius: 4px;
                                   font-size: 0.8rem;
                                   box-shadow: 0 2px 4px rgba(0,0,0,0.2);
                              "
                               title="Editar">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="padding: 1rem; text-align: center; color: #888; background-color: #1a1a1a;">
                            No hay gastos registrados
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
        <!-- Total acumulado -->
    <div style="
        margin-top: 1rem;
        padding: 0.8rem;
        background-color: #1a1a1a;
        border-radius: 0.375rem;
        border: 1px solid #444;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.95rem;
    ">
    <strong>Total de gastos:</strong>
    <span style="font-size: 1.2rem; font-weight: bold; color: #fff;">
        ${{ number_format($gastos->sum('monto'), 2, ',', '.') }}
    </span>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.getElementById('tabla-gastos-body');
    const searchInput = document.getElementById('search-gastos');
    const filterChofer = document.getElementById('filter-chofer');

    function filtrar() {
        const searchTerm = (searchInput?.value || '').toLowerCase().trim();
        const choferId = (filterChofer?.value || '');

        Array.from(tableBody.querySelectorAll('tr')).forEach(row => {
            // Saltar fila de "No hay datos"
            if (row.querySelector('td[colspan]')) return;

            const concepto = row.getAttribute('data-concepto') || '';
            const vehiculo = row.getAttribute('data-vehiculo') || '';
            const rowChoferId = row.getAttribute('data-chofer-id') || '';

            const matchesSearch = !searchTerm || 
                concepto.includes(searchTerm) || 
                vehiculo.includes(searchTerm);

            const matchesChofer = !choferId || rowChoferId === choferId;

            row.style.display = matchesSearch && matchesChofer ? '' : 'none';
        });
    }

    if (searchInput) searchInput.addEventListener('input', filtrar);
    if (filterChofer) filterChofer.addEventListener('change', filtrar);
});
</script>
@endpush
@endsection