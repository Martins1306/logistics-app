@extends('layouts.app')

@section('content')
<div class="p-3">
    <h2><i class="fas fa-box text-primary"></i> Detalles del Producto</h2>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="background-color: transparent; padding: 0; font-size: 0.9rem;">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" style="color: #198754;">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('productos.index') }}" style="color: #198754;">Productos</a></li>
            <li class="breadcrumb-item active" style="color: #ccc;">Detalle</li>
        </ol>
    </nav>

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
                <p style="margin: 0.4rem 0;"><strong>Nombre:</strong> {{ $producto->nombre }}</p>
                <p style="margin: 0.4rem 0;"><strong>Categoría:</strong> 
                    <span style="
                        font-size: 0.8rem;
                        padding: 0.2em 0.5em;
                        border-radius: 4px;
                        background-color: {{ $producto->categoria == 'construcción' ? '#fd7e14' : '#28a745' }};
                        color: white;
                    ">{{ ucfirst($producto->categoria) }}</span>
                </p>
                <p style="margin: 0.4rem 0;"><strong>Unidad:</strong> {{ $producto->unidad }}</p>
                <p style="margin: 0.4rem 0;"><strong>Precio:</strong> 
                    <strong style="color: #fff;">${{ number_format($producto->precio, 2, ',', '.') }}</strong>
                </p>
            </div>
            <div class="col-md-6">
                <!-- Mostrar Stock -->
                <p style="margin: 0.4rem 0;"><strong>Stock Actual:</strong> 
                    <span style="color: 
                        @if($producto->stock_actual == 0) #dc3545;
                        @elseif($producto->stock_actual <= $producto->stock_minimo) #fd7e14;
                        @else #28a745; @endif
                        font-weight: 500;">
                        {{ $producto->stock_actual }}
                    </span>
                </p>
                <p style="margin: 0.4rem 0;"><strong>Stock Mínimo:</strong> {{ $producto->stock_minimo }}</p>
                <p style="margin: 0.4rem 0;"><strong>Estado:</strong> 
                    @if($producto->stock_actual == 0)
                        <span style="color: #dc3545; font-weight: 500;">Agotado</span>
                    @elseif($producto->stock_actual <= $producto->stock_minimo)
                        <span style="color: #fd7e14; font-weight: 500;">Bajo</span>
                    @else
                        <span style="color: #28a745; font-weight: 500;">Normal</span>
                    @endif
                </p>
            </div>
        </div>

        <div style="margin-top: 1rem; padding: 0.8rem; background-color: #2a2a2a; border-radius: 0.375rem;">
            <strong style="color: #fff;">Registrado el:</strong>
            <span style="color: #ddd;">
                {{ \Carbon\Carbon::parse($producto->created_at)->format('d/m/Y H:i') }}
            </span>
        </div>
    </div>

    <!-- Historial de movimientos -->
    @if(isset($movimientos) && $movimientos->isNotEmpty())
        <h5><i class="fas fa-history text-info"></i> Historial de Inventario</h5>
        <div style="max-height: 300px; overflow-y: auto; border: 1px solid #444; border-radius: 0.375rem; margin-bottom: 1.5rem;">
            <table class="table table-dark table-striped table-sm" style="font-size: 0.9rem; margin: 0;">
                <thead style="background-color: #000;">
                    <tr>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Cantidad</th>
                        <th>Motivo</th>
                        <th>Referencia</th>
                        <th>Usuario</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($movimientos as $m)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($m->created_at)->format('d/m H:i') }}</td>
                            <td>
                                <span style="
                                    padding: 0.2em 0.5em;
                                    border-radius: 4px;
                                    background-color: {{ $m->tipo === 'entrada' ? '#28a745' : '#dc3545' }};
                                    color: white;
                                    font-size: 0.8rem;
                                ">{{ ucfirst($m->tipo) }}</span>
                            </td>
                            <td>{{ $m->cantidad }}</td>
                            <td>{{ $m->motivo }}</td>
                            <td>
                                @if($m->referencia)
                                    {{ class_basename($m->referencia) }} #{{ $m->referencia->id }}
                                @else
                                    –
                                @endif
                            </td>
                            <td>
                                @if($m->usuario && $m->usuario->name)
                                    {{ $m->usuario->name }}
                                @else
                                    –
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div style="padding: 1rem; background-color: #1a1a1a; border-radius: 0.375rem; border: 1px solid #444; color: #888; text-align: center;">
            No hay movimientos registrados para este producto.
        </div>
    @endif

    <!-- Acciones -->
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('productos.edit', $producto->id) }}" class="btn btn-primary">
            <i class="fas fa-edit me-1"></i>Editar Producto
        </a>

        <!-- Botón Ajustar Stock -->
        <button type="button" 
                class="btn btn-warning"
                data-bs-toggle="modal" 
                data-bs-target="#ajustarStockModal"
                onclick="prepararAjuste(
                    {{ $producto->id }},
                    '{{ addslashes($producto->nombre) }}',
                    {{ $producto->stock_actual }}
                )">
            <i class="fas fa-sliders-h me-1"></i>Ajustar Stock
        </button>
<!-- Historial de Compras -->
@if(isset($compras) && $compras->isNotEmpty())
    <h5 class="mt-4"><i class="fas fa-shopping-cart text-success"></i> Historial de Compras</h5>
    <div style="max-height: 300px; overflow-y: auto; border: 1px solid #444; border-radius: 0.375rem; margin-bottom: 1.5rem;">
        <table class="table table-dark table-striped table-sm" style="font-size: 0.9rem; margin: 0;">
            <thead style="background-color: #000;">
                <tr>
                    <th>Fecha</th>
                    <th>Proveedor</th>
                    <th>Cantidad</th>
                    <th>Precio Unit.</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($compras as $c)
                    @php
                        $detalle = $c->detalles->firstWhere('producto_id', $producto->id);
                    @endphp
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($c->fecha_compra)->format('d/m') }}</td>
                        <td>{{ $c->proveedor->nombre }}</td>
                        <td>{{ $detalle->cantidad }} {{ $producto->unidad }}</td>
                        <td>${{ number_format($detalle->precio_unitario, 2, ',', '.') }}</td>
                        <td>${{ number_format($detalle->cantidad * $detalle->precio_unitario, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
        <a href="{{ route('productos.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Volver al listado
        </a>
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

// Filtro de búsqueda (opcional si necesitas en otras partes)
document.addEventListener('DOMContentLoaded', function () {
    // Puedes agregar funciones adicionales aquí si es necesario
});
</script>
@endpush
@endsection