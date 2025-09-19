@extends('layouts.app')

@section('content')
<div class="p-3">
    <h2><i class="fas fa-route text-primary"></i> Viajes</h2>

   <!-- Estadísticas por tipo de viaje -->
<div style="
    display: flex;
    gap: 1rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    background-color: #1a1a1a;
    padding: 1rem;
    border-radius: 0.375rem;
    border: 1px solid #444;
">
    @php
        $totalAgricola = 0;
        $totalConstruccion = 0;
        $sinTipo = 0;

        foreach ($viajes as $v) {
            $tipo = strtolower(trim($v->tipo ?? ''));

            if (
                str_contains($tipo, 'agricol') ||
                str_contains($tipo, 'hortaliza') ||
                str_contains($tipo, 'fruta') ||
                str_contains($tipo, 'cereal') ||
                str_contains($tipo, 'insumo') ||
                str_contains($tipo, 'semilla') ||
                str_contains($tipo, 'fertilizante')
            ) {
                $totalAgricola++;
            } elseif (
                str_contains($tipo, 'construccion') ||
                str_contains($tipo, 'materiales') ||
                str_contains($tipo, 'herramienta') ||
                str_contains($tipo, 'cemento') ||
                str_contains($tipo, 'acero') ||
                str_contains($tipo, 'ladrillo') ||
                str_contains($tipo, 'madera')
            ) {
                $totalConstruccion++;
            } elseif (empty($tipo)) {
                $sinTipo++;
            }
        }

        $total = $viajes->count();
    @endphp

    <!-- Viajes Agrícolas -->
    <div style="
        flex: 1;
        min-width: 180px;
        background-color: #28a745;
        color: white;
        padding: 0.8rem;
        border-radius: 0.375rem;
        text-align: center;
    ">
        <strong style="font-size: 1.2rem;">{{ $totalAgricola }}</strong><br>
        <small>Viajes Agrícolas</small>
    </div>

    <!-- Materiales de Construcción -->
    <div style="
        flex: 1;
        min-width: 180px;
        background-color: #fd7e14;
        color: white;
        padding: 0.8rem;
        border-radius: 0.375rem;
        text-align: center;
    ">
        <strong style="font-size: 1.2rem;">{{ $totalConstruccion }}</strong><br>
        <small>Materiales Construcción</small>
    </div>

    <!-- Sin Tipo -->
    <div style="
        flex: 1;
        min-width: 180px;
        background-color: #6c757d;
        color: white;
        padding: 0.8rem;
        border-radius: 0.375rem;
        text-align: center;
    ">
        <strong style="font-size: 1.2rem;">{{ $sinTipo }}</strong><br>
        <small>Sin Tipo</small>
    </div>

    <!-- Total Viajes -->
    <div style="
        flex: 1;
        min-width: 180px;
        background-color: #0d6efd;
        color: white;
        padding: 0.8rem;
        border-radius: 0.375rem;
        text-align: center;
    ">
        <strong style="font-size: 1.2rem;">{{ $total }}</strong><br>
        <small>Total Viajes</small>
    </div>
</div>
    <!-- Búsqueda y filtros -->
    <div class="mb-3 d-flex flex-wrap gap-2 align-items-center">
        <div class="flex-grow-1" style="min-width: 200px;">
            <input type="text" id="search-viajes" class="form-control form-control-sm" placeholder="Buscar origen, destino, cliente..."
                   style="background-color: #333; border: 1px solid #555; color: #eee;">
        </div>
        
        <!-- Filtro por tipo de viaje -->
        <select id="filter-tipo" class="form-select form-select-sm" style="width: auto; background-color: #333; border: 1px solid #555; color: #eee;">
            <option value="">Todos los tipos</option>
            <option value="agrícola">Agrícola</option>
            <option value="construccion">Construcción</option>
        </select>

        <a href="{{ route('viajes.create') }}" class="btn btn-sm btn-success">
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
                    <th style="padding: 0.5rem 0.6rem; text-align: left; font-weight: 600;">ID</th>
                    <th style="padding: 0.5rem 0.6rem; text-align: left; font-weight: 600;">Trayecto</th>
                    <th style="padding: 0.5rem 0.6rem; text-align: left; font-weight: 600;">Cliente</th>
                    <th style="padding: 0.5rem 0.6rem; text-align: left; font-weight: 600;">Vehículo</th>
                    <th style="padding: 0.5rem 0.6rem; text-align: left; font-weight: 600;">Chofer</th>
                    <th style="padding: 0.5rem 0.6rem; text-align: left; font-weight: 600;">F. Salida</th>
                    <th style="padding: 0.5rem 0.6rem; text-align: left; font-weight: 600;">Tipo</th>
                    <th style="padding: 0.5rem 0.6rem; text-align: left; font-weight: 600;">Estado</th>
                    <th style="padding: 0.5rem 0.6rem; text-align: right; font-weight: 600;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($viajes as $viaje)
                    @php
                        // Normalizar tipo para filtro
                        $tipo = strtolower(trim($viaje->tipo ?? ''));
                        if ($tipo === 'agricola') $tipo = 'agrícola';
                    @endphp
                    <tr data-origen="{{ $viaje->origen }}" 
                        data-destino="{{ $viaje->destino }}"
                        data-cliente="{{ $viaje->cliente ? $viaje->cliente->nombre : '' }}"
                        data-tipo="{{ $tipo }}"
                        style="border-bottom: 1px solid #333;"
                        onmouseover="this.style.backgroundColor='#2a2a2a'"
                        onmouseout="this.style.backgroundColor='transparent'">
                        <td style="padding: 0.4rem 0.6rem; color: #fff; font-weight: 500;">
                            {{ $viaje->id }}
                        </td>
                        <td style="padding: 0.4rem 0.6rem; color: #ddd;">
                            <div>{{ Str::limit($viaje->origen, 15) }}</div>
                            <div style="color: #aaa;">→ {{ Str::limit($viaje->destino, 15) }}</div>
                        </td>
                        <td style="padding: 0.4rem 0.6rem; color: #ccc;">
                            {{ $viaje->cliente ? Str::limit($viaje->cliente->nombre, 18) : '–' }}
                        </td>
                        <td style="padding: 0.4rem 0.6rem; color: #ccc;">
                            {{ $viaje->vehiculo ? $viaje->vehiculo->patente : '–' }}
                        </td>
                        <td style="padding: 0.4rem 0.6rem; color: #ccc;">
                            {{ $viaje->chofer ? $viaje->chofer->nombre : '–' }}
                        </td>
                        <td style="padding: 0.4rem 0.6rem; color: #ccc;">
                            {{ \Carbon\Carbon::parse($viaje->fecha_salida)->format('d/m') }}
                        </td>
                        <td style="padding: 0.4rem 0.6rem;">
                            @if($viaje->tipo)
                                <span style="
                                    font-size: 0.7rem;
                                    padding: 0.2em 0.5em;
                                    border-radius: 4px;
                                    background-color: {{ $viaje->tipo == 'agrícola' || $viaje->tipo == 'agricola' ? '#28a745' : '#fd7e14' }};
                                    color: white;
                                ">
                                    {{ $viaje->tipo == 'agrícola' || $viaje->tipo == 'agricola' ? 'Agrícola' : 'Constr.' }}
                                </span>
                            @else
                                <span style="color: #777;">–</span>
                            @endif
                        </td>
                        <td style="padding: 0.4rem 0.6rem;">
                            @php $estado = $viaje->estado; @endphp
                            <span style="
                                font-size: 0.7rem;
                                padding: 0.2em 0.5em;
                                border-radius: 4px;
                                background-color:
                                    {{ $estado == 'en curso' ? '#ffc107' : ($estado == 'completado' ? '#28a745' : '#dc3545') }};
                                color: {{ $estado == 'en curso' ? '#000' : '#fff' }};
                            ">
                                {{ ucfirst(substr($estado, 0, 1)) }}
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
                @empty
                    <tr>
                        <td colspan="9" style="padding: 1rem; text-align: center; color: #888; background-color: #1a1a1a;">
                            Sin viajes registrados
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
    const table = document.getElementById('tabla-viajes');
    const searchInput = document.getElementById('search-viajes');
    const filterTipo = document.getElementById('filter-tipo');

    function filterTable() {
        const searchTerm = (searchInput.value || '').toLowerCase().trim();
        const tipoValue = (filterTipo.value || '').toLowerCase().trim();

        Array.from(table.querySelectorAll('tbody tr')).forEach(row => {
            const origen = (row.getAttribute('data-origen') || '').toLowerCase();
            const destino = (row.getAttribute('data-destino') || '').toLowerCase();
            const cliente = (row.getAttribute('data-cliente') || '').toLowerCase();
            const tipo = (row.getAttribute('data-tipo') || '').toLowerCase();

            const matchesSearch = !searchTerm || 
                origen.includes(searchTerm) || 
                destino.includes(searchTerm) || 
                cliente.includes(searchTerm);

            const matchesTipo = !tipoValue || tipo === tipoValue;

            row.style.display = matchesSearch && matchesTipo ? '' : 'none';
        });
    }

    if (searchInput) searchInput.addEventListener('input', filterTable);
    if (filterTipo) filterTipo.addEventListener('change', filterTable);
});
</script>
@endpush
@endsection