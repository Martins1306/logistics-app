@extends('layouts.app')

@section('title', 'Reportes')

@section('content')
<div class="card bg-dark text-light shadow-sm">
    <div class="card-header">
        <h4><i class="fas fa-chart-line"></i> Generar Reporte</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('reportes.generar') }}" method="POST">
            @csrf
            <div class="row g-3">
                <!-- Tipo de reporte -->
                <div class="col-md-6">
                    <label class="form-label">Tipo de Reporte</label>
                    <select name="tipo" class="form-control bg-secondary text-light" required onchange="toggleFiltros()">
                        <option value="">Seleccionar...</option>
                        <option value="viajes" {{ old('tipo', session('ultimo_reporte_tipo')) == 'viajes' ? 'selected' : '' }}>Viajes por Periodo</option>
                        <option value="stock" {{ old('tipo', session('ultimo_reporte_tipo')) == 'stock' ? 'selected' : '' }}>Movimientos de Stock</option>
                        <option value="choferes" {{ old('tipo', session('ultimo_reporte_tipo')) == 'choferes' ? 'selected' : '' }}>Rendimiento de Choferes</option>
                        <option value="mantenimientos" {{ old('tipo', session('ultimo_reporte_tipo')) == 'mantenimientos' ? 'selected' : '' }}>Mantenimientos Programados</option>
                    </select>
                </div>

                <!-- Fechas -->
                <div class="col-md-3">
                    <label class="form-label">Desde</label>
                    <input type="date" name="fecha_desde" class="form-control bg-secondary text-light"
                           value="{{ old('fecha_desde', \Carbon\Carbon::now()->subDays(30)->format('Y-m-d')) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Hasta</label>
                    <input type="date" name="fecha_hasta" class="form-control bg-secondary text-light"
                           value="{{ old('fecha_hasta', \Carbon\Carbon::now()->format('Y-m-d')) }}">
                </div>

                <!-- Filtros condicionales -->
                <div id="filtros-adicionales" class="w-100 mt-3 d-none">
                    <!-- Se llenará con JS si es necesario -->
                </div>

                <!-- Botones -->
                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-cogs"></i> Generar Reporte
                    </button>
                    <button type="button" class="btn btn-outline-success ms-2" onclick="descargarCSV()">
                        <i class="fas fa-download"></i> Descargar CSV
                    </button>
                    <button type="button" class="btn btn-outline-light ms-2" onclick="descargarPDF()">
                        <i class="fas fa-file-pdf"></i> Descargar PDF
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
                        @if(isset(session('reporte')[0]))
                            @foreach(array_keys(session('reporte')[0]) as $header)
                                <th>{{ ucfirst(str_replace('_', ' ', $header)) }}</th>
                            @endforeach
                        @endif
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
// Filtros dinámicos (si los necesitás en el futuro)
function toggleFiltros() {
    const tipo = document.querySelector('[name="tipo"]').value;
    const contenedor = document.getElementById('filtros-adicionales');
    contenedor.innerHTML = '';
    contenedor.classList.add('d-none');

    if (!tipo) return;

    contenedor.classList.remove('d-none');

    // Aquí puedes agregar lógica para filtros adicionales si los usas
}

// Exportar como CSV
function descargarCSV() {
    // Usamos el valor del select o el tipo guardado en sesión
    const tipoSelect = document.querySelector('[name="tipo"]');
    const tipo = tipoSelect?.value || "{{ session('ultimo_reporte_tipo') }}";

    if (!tipo) {
        alert('Primero selecciona un tipo de reporte.');
        return;
    }

    const resultados = document.getElementById('resultados-reporte');
    if (!resultados || !resultados.querySelector('table')) {
        alert('Primero genera un reporte.');
        return;
    }

    const url = "{{ route('reportes.descargar') }}?tipo=" + encodeURIComponent(tipo) + "&formato=csv";
    console.log("🚀 Descargando CSV:", url);
    window.open(url, '_blank');
}

// Exportar como PDF
function descargarPDF() {
    // Usamos el valor del select o el tipo guardado en sesión
    const tipoSelect = document.querySelector('[name="tipo"]');
    const tipo = tipoSelect?.value || "{{ session('ultimo_reporte_tipo') }}";

    if (!tipo) {
        alert('Primero selecciona un tipo de reporte.');
        return;
    }

    const resultados = document.getElementById('resultados-reporte');
    if (!resultados || !resultados.querySelector('table')) {
        alert('Primero genera un reporte.');
        return;
    }

    const url = "{{ route('reportes.descargar') }}?tipo=" + encodeURIComponent(tipo) + "&formato=pdf";
    console.log("🚀 Descargando PDF:", url);
    window.open(url, '_blank');
}
</script>
@endsection