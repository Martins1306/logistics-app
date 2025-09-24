@extends('layouts.app')

@section('title', 'Reportes')

@section('content')
<div class="card bg-dark text-light shadow-sm">
    <div class="card-header">
        <h4><i class="fas fa-chart-line"></i> Generar Reporte</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('reportes.generar') }}" method="POST" id="form-reporte">
            @csrf
            <div class="row g-3">
                <!-- Tipo de reporte -->
                <div class="col-md-6">
                    <label class="form-label">Tipo de Reporte</label>
                    <select name="tipo" class="form-control bg-secondary text-light" required onchange="toggleFiltros()">
                        <option value="">Seleccionar...</option>
                        <option value="viajes">Viajes por Periodo</option>
                        <option value="stock">Movimientos de Stock</option>
                        <option value="choferes">Rendimiento de Choferes</option>
                        <option value="mantenimientos">Mantenimientos Programados</option>
                    </select>
                </div>

                <!-- Fechas -->
                <div class="col-md-3">
                    <label class="form-label">Desde</label>
                    <input type="date" name="fecha_desde" class="form-control bg-secondary text-light"
                           value="{{ old('fecha_desde', now()->subDays(30)->format('Y-m-d')) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Hasta</label>
                    <input type="date" name="fecha_hasta" class="form-control bg-secondary text-light"
                           value="{{ old('fecha_hasta', now()->format('Y-m-d')) }}">
                </div>

                <!-- Filtros condicionales (se mostrarán según tipo) -->
                <div id="filtros-adicionales" class="w-100 mt-3 d-none">
                    <!-- Aquí se cargarán dinámicamente con JS -->
                </div>

                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-cogs"></i> Generar Reporte
                    </button>
                    <button type="button" class="btn btn-outline-light ms-2" onclick="exportarReporte()">
                        <i class="fas fa-file-export"></i> Exportar Vista
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Resultados dinámicos -->
<div id="resultados-reporte" class="mt-4">
    @if(session('reporte'))
        <div class="alert alert-info">
            Se encontraron {{ count(session('reporte')) }} registros.
        </div>
        <div class="table-responsive">
            <table class="table table-dark table-striped align-middle">
                <thead>
                    <tr>
                        @foreach(array_keys(session('reporte')[0] ?? []) as $header)
                            <th>{{ ucfirst(str_replace('_', ' ', $header)) }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach(session('reporte') as $fila)
                        <tr>
                            @foreach($fila as $valor)
                                <td>{{ $valor }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endsection

@section('scripts')
<script>
function toggleFiltros() {
    const tipo = document.querySelector('[name="tipo"]').value;
    const contenedor = document.getElementById('filtros-adicionales');
    contenedor.innerHTML = '';
    contenedor.classList.add('d-none');

    if (!tipo) return;

    contenedor.classList.remove('d-none');

    if (tipo === 'viajes') {
        contenedor.innerHTML = `
            <div class="row g-3">
                <div class="col-md-6">
                    <label>Cliente</label>
                    <select name="cliente_id" class="form-control bg-secondary text-light">
                        <option value="">Todos</option>
                        @foreach($clientes as $c)
                            <option value="{{ $c->id }}" {{ old('cliente_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label>Tipo de Viaje</label>
                    <select name="tipo_viaje" class="form-control bg-secondary text-light">
                        <option value="">Todos</option>
                        <option value="agricola" {{ old('tipo_viaje') == 'agricola' ? 'selected' : '' }}>Agrícola</option>
                        <option value="construccion" {{ old('tipo_viaje') == 'construccion' ? 'selected' : '' }}>Construcción</option>
                    </select>
                </div>
            </div>`;
    }

    if (tipo === 'stock') {
        contenedor.innerHTML = `
            <div class="row g-3">
                <div class="col-md-6">
                    <label>Producto</label>
                    <select name="producto_id" class="form-control bg-secondary text-light">
                        <option value="">Todos</option>
                        @foreach($productos as $p)
                            <option value="{{ $p->id }}" {{ old('producto_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>`;
    }

    if (tipo === 'choferes') {
        contenedor.innerHTML = `
            <div class="row g-3">
                <div class="col-md-6">
                    <label>Chofer</label>
                    <select name="chofer_id" class="form-control bg-secondary text-light">
                        <option value="">Todos</option>
                        @foreach($choferes as $ch)
                            <option value="{{ $ch->id }}" {{ old('chofer_id') == $ch->id ? 'selected' : '' }}>
                                {{ $ch->nombre }} {{ $ch->apellido }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>`;
    }
}

function exportarReporte() {
    const form = document.getElementById('form-reporte');
    const tipo = document.querySelector('[name="tipo"]').value;
    if (!tipo) {
        alert('Primero genera un reporte.');
        return;
    }
    // Aquí podrías redirigir a descarga o usar una librería ligera como html-table-export-js
    alert("Próximamente: Exportación a PDF/Excel");
}
</script>
@endsection