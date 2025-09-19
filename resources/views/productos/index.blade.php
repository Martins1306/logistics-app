@extends('layouts.app')

@section('content')
<div class="p-3">
    <h2><i class="fas fa-box text-primary"></i> Productos</h2>

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
            <input type="text" id="search-productos" class="form-control form-control-sm" placeholder="Buscar nombre..."
                   style="background-color: #333; border: 1px solid #555; color: #eee;">
        </div>
        <select id="filter-categoria" class="form-select form-select-sm" style="width: auto; background-color: #333; border: 1px solid #555; color: #eee;">
            <option value="">Todas las categorías</option>
            <option value="agrícola">Hortalizas / Agrícolas</option>
            <option value="construccion">Construcción</option>
        </select>
        <a href="{{ route('productos.create') }}" class="btn btn-sm btn-success">
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
        <table id="tabla-productos" style="
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
                    <th style="padding: 0.5rem 0.6rem; text-align: left;">Categoría</th>
                    <th style="padding: 0.5rem 0.6rem; text-align: left;">Unidad</th>
                    <th style="padding: 0.5rem 0.6rem; text-align: right;">Precio</th>
                    <th style="padding: 0.5rem 0.6rem; text-align: center;">Stock</th>
                    <th style="padding: 0.5rem 0.6rem; text-align: center;">Estado</th>
                    <th style="padding: 0.5rem 0.6rem; text-align: right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productos as $producto)
                    @php
                        // Normalizar categoría para filtro
                        $categoria = strtolower(trim($producto->categoria));
                        $categoria = str_replace(['á','é','í','ó','ú'], ['a','e','i','o','u'], $categoria);
                        $categoriaKey = ($categoria === 'hortalizas' || $categoria === 'frutas') ? 'agrícola' : ($categoria === 'construcción' ? 'construccion' : $categoria);

                        // Calcular estado de stock
                        if ($producto->stock_actual == 0) {
                            $estadoColor = '#dc3545';
                            $estadoTexto = 'Agotado';
                        } elseif ($producto->stock_actual <= $producto->stock_minimo) {
                            $estadoColor = '#fd7e14';
                            $estadoTexto = 'Bajo';
                        } else {
                            $estadoColor = '#28a745';
                            $estadoTexto = 'Normal';
                        }
                    @endphp
                    <tr 
                        data-nombre="{{ strtolower($producto->nombre) }}" 
                        data-categoria="{{ $categoriaKey }}"
                        style="border-bottom: 1px solid #333;">
                        <td style="padding: 0.4rem 0.6rem; color: #fff; font-weight: 500;">
                            {{ $producto->id }}
                        </td>
                        <td style="padding: 0.4rem 0.6rem; color: #ddd;">
                            {{ $producto->nombre }}
                        </td>
                        <td style="padding: 0.4rem 0.6rem;">
                            <span style="
                                font-size: 0.75rem;
                                padding: 0.2em 0.5em;
                                border-radius: 4px;
                                background-color: {{ $categoriaKey == 'agrícola' ? '#28a745' : '#fd7e14' }};
                                color: white;
                            ">
                                {{ ucfirst($producto->categoria) }}
                            </span>
                        </td>
                        <td style="padding: 0.4rem 0.6rem; color: #ccc;">
                            {{ $producto->unidad }}
                        </td>
                        <td style="padding: 0.4rem 0.6rem; color: #fff; font-weight: 500; text-align: right;">
                            ${{ number_format($producto->precio, 2, ',', '.') }}
                        </td>
                        <td style="padding: 0.4rem 0.6rem; text-align: center; font-weight: 500;">
                            <span style="color: {{ $estadoColor }};">
                                {{ $producto->stock_actual }}
                            </span>
                        </td>
                        <td style="padding: 0.4rem 0.6rem; text-align: center;">
                            <span style="
                                font-size: 0.75rem;
                                padding: 0.2em 0.5em;
                                border-radius: 4px;
                                background-color: {{ $estadoColor }};
                                color: white;
                            ">
                                {{ $estadoTexto }}
                            </span>
                        </td>
                        <td class="text-end" style="padding: 0.4rem 0.6rem; text-align: right;">
                            <a href="{{ route('productos.show', $producto->id) }}" 
                               style="display: inline-block; padding: 0.15rem 0.3rem; font-size: 0.8rem; color: #fff; background-color: #198754; border-radius: 4px; text-decoration: none; margin-right: 0.25rem;">
                                <i class="fas fa-eye" style="margin-right: 0.2rem;"></i>Ver
                            </a>
                            <a href="{{ route('productos.edit', $producto->id) }}" 
                               style="display: inline-block; padding: 0.15rem 0.3rem; font-size: 0.8rem; color: #fff; background-color: #0d6efd; border-radius: 4px; text-decoration: none; margin-right: 0.25rem;">
                                <i class="fas fa-edit" style="margin-right: 0.2rem;"></i>Editar
                            </a>
                            <!-- Botón Ajustar Stock -->
                            <a href="#" 
                               class="btn-ajustar-stock"
                               data-id="{{ $producto->id }}"
                               data-nombre="{{ $producto->nombre }}"
                               data-stock="{{ $producto->stock_actual }}"
                               style="
                                   display: inline-block;
                                   padding: 0.15rem 0.3rem;
                                   font-size: 0.8rem;
                                   color: #fff;
                                   background-color: #ffc107;
                                   border-radius: 4px;
                                   text-decoration: none;
                                   margin-left: 0.25rem;
                                   border: none;
                                   cursor: pointer;
                               "
                               title="Ajustar Stock">
                                <i class="fas fa-sliders-h" style="margin-right: 0.2rem;"></i>Ajustar
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- ✅ Modal Único para Ajustar Stock -->
<div class="modal fade" id="ajustarStockModal" tabindex="-1" aria-labelledby="ajustarStockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-light">
            <form action="" method="POST" id="formAjustarStock">
                @csrf
                @method('POST')
                
                <div class="modal-header">
                    <h5 class="modal-title" id="ajustarStockModalLabel">Ajustar Stock</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Producto:</strong> <span id="nombreProducto"></span></p>
                    <p><strong>Stock actual:</strong> <span id="stockActual"></span></p>

                    <div class="mb-3">
                        <label class="form-label">Tipo de Ajuste</label>
                        <select name="tipo" class="form-control" required style="background-color: #333; border: 1px solid #555; color: #eee;">
                            <option value="">Seleccionar</option>
                            <option value="entrada">Entrada (sumar)</option>
                            <option value="salida">Salida (restar)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Cantidad</label>
                        <input type="number" name="cantidad" class="form-control" min="1" required style="background-color: #333; border: 1px solid #555; color: #eee;">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Motivo</label>
                        <input type="text" name="motivo" class="form-control" placeholder="Ej: merma, donación, error de carga..." required style="background-color: #333; border: 1px solid #555; color: #eee;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Ajustar Stock</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Función para preparar el modal
function prepararAjuste(id, nombre, stock) {
    document.getElementById('nombreProducto').textContent = nombre;
    document.getElementById('stockActual').textContent = stock;
    document.getElementById('formAjustarStock').action = `/productos/${id}/ajustar-stock`;
}

// Asociar evento a todos los botones "Ajustar Stock"
document.querySelectorAll('.btn-ajustar-stock').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const id = this.getAttribute('data-id');
        const nombre = this.getAttribute('data-nombre');
        const stock = this.getAttribute('data-stock');
        prepararAjuste(id, nombre, stock);
        new bootstrap.Modal(document.getElementById('ajustarStockModal')).show();
    });
});

// Filtro de búsqueda
document.addEventListener('DOMContentLoaded', function () {
    var tabla = document.getElementById('tabla-productos');
    var searchInput = document.getElementById('search-productos');
    var filterSelect = document.getElementById('filter-categoria');

    var categoriaMap = {
        'hortalizas': 'agrícola',
        'frutas': 'agrícola',
        'cereales': 'agrícola',
        'insumos': 'agrícola',
        'semillas': 'agrícola',
        'fertilizantes': 'agrícola',
        'materiales de construccion': 'construccion',
        'herramientas': 'construccion',
        'acero': 'construccion',
        'cemento': 'construccion',
        'construcción': 'construccion'
    };

    function filtrar() {
        var searchTerm = (searchInput?.value || '').toLowerCase().trim();
        var filterValue = (filterSelect?.value || '').toLowerCase().trim();

        var filas = tabla.querySelectorAll('tbody tr');

        for (var i = 0; i < filas.length; i++) {
            var row = filas[i];
            var nombre = (row.getAttribute('data-nombre') || '').toLowerCase();
            var categoriaRaw = (row.getAttribute('data-categoria') || '').toLowerCase();
            
            var categoriaNormalizada = categoriaMap[categoriaRaw] || categoriaRaw;

            var matchSearch = !searchTerm || nombre.includes(searchTerm);
            var matchFilter = !filterValue || categoriaNormalizada === filterValue;

            row.style.display = matchSearch && matchFilter ? '' : 'none';
        }
    }

    if (searchInput) searchInput.addEventListener('input', filtrar);
    if (filterSelect) filterSelect.addEventListener('change', filtrar);
});
</script>
@endpush
@endsection