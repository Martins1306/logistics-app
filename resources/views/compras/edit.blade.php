@extends('layouts.app')

@section('content')
<div class="p-3">
    <h2><i class="fas fa-shopping-cart text-success"></i> Editar Compra #{{ $compra->id }}</h2>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="background-color: transparent; padding: 0; font-size: 0.9rem;">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" style="color: #198754;">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('compras.index') }}" style="color: #198754;">Compras</a></li>
            <li class="breadcrumb-item active" style="color: #ccc;">Editar</li>
        </ol>
    </nav>

    <!-- Mensajes de error -->
    @if ($errors->any())
        <div style="background: #dc3545; color: white; padding: 0.5rem; border-radius: 4px; margin-bottom: 1rem;">
            <strong>Errores:</strong>
            <ul style="margin: 0.3rem 0; padding-left: 1rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('compras.update', $compra->id) }}" method="POST" class="needs-confirmation" data-message="¿Confirmar edición? Esto ajustará el stock.">
        @csrf
        @method('PUT')

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Proveedor *</label>
                <select name="proveedor_id" class="form-control" required style="background-color: #333; border: 1px solid #555; color: #eee;">
                    <option value="">Seleccionar proveedor</option>
                    @foreach($proveedores as $p)
                        <option value="{{ $p->id }}" {{ $p->id == $compra->proveedor_id ? 'selected' : '' }}>{{ $p->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Fecha de Compra *</label>
                <input type="date" name="fecha_compra" class="form-control" value="{{ old('fecha_compra', $compra->fecha_compra) }}" required style="background-color: #333; border: 1px solid #555; color: #eee;">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Número de Factura</label>
                <input type="text" name="numero_factura" class="form-control" value="{{ old('numero_factura', $compra->numero_factura) }}" style="background-color: #333; border: 1px solid #555; color: #eee;">
            </div>
            <div class="col-md-6">
                <label class="form-label">Método de Pago</label>
                <input type="text" name="metodo_pago" class="form-control" value="{{ old('metodo_pago', $compra->metodo_pago) }}" style="background-color: #333; border: 1px solid #555; color: #eee;">
            </div>
        </div>

        <!-- Productos -->
        <h5 class="mt-4">Productos Comprados</h5>
        <div id="productos-container">
            @foreach($compra->detalles as $detalle)
                <div class="row producto-row mb-2 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label">Producto</label>
                        <select name="producto_id[]" class="form-control" required style="background-color: #333; border: 1px solid #555; color: #eee;">
                            <option value="">Seleccionar producto</option>
                            @foreach($productos as $p)
                                <option value="{{ $p->id }}" {{ $p->id == $detalle->producto_id ? 'selected' : '' }}>
                                    {{ $p->nombre }} ({{ $p->unidad }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Cantidad</label>
                        <input type="number" name="cantidad[]" class="form-control" min="1" value="{{ $detalle->cantidad }}" required style="background-color: #333; border: 1px solid #555; color: #eee;">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Precio Unitario</label>
                        <input type="number" step="0.01" name="precio_unitario[]" class="form-control" min="0" value="{{ $detalle->precio_unitario }}" required style="background-color: #333; border: 1px solid #555; color: #eee;">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger btn-sm w-100" onclick="this.closest('.producto-row').remove()">Eliminar</button>
                    </div>
                </div>
            @endforeach
        </div>

        <button type="button" class="btn btn-secondary btn-sm" onclick="agregarFilaProducto()">+ Agregar Producto</button>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary">Actualizar Compra</button>
            <a href="{{ route('compras.show', $compra->id) }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<script>
function agregarFilaProducto() {
    const container = document.getElementById('productos-container');
    const row = document.createElement('div');
    row.className = 'row producto-row mb-2 align-items-end';
    row.innerHTML = `
        <div class="col-md-5">
            <label class="form-label">Producto</label>
            <select name="producto_id[]" class="form-control" required style="background-color: #333; border: 1px solid #555; color: #eee;">
                <option value="">Seleccionar producto</option>
                @foreach($productos as $p)
                    <option value="{{ $p->id }}">{{ $p->nombre }} ({{ $p->unidad }})</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Cantidad</label>
            <input type="number" name="cantidad[]" class="form-control" min="1" required style="background-color: #333; border: 1px solid #555; color: #eee;">
        </div>
        <div class="col-md-3">
            <label class="form-label">Precio Unitario</label>
            <input type="number" step="0.01" name="precio_unitario[]" class="form-control" min="0" required style="background-color: #333; border: 1px solid #555; color: #eee;">
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-danger btn-sm w-100" onclick="this.closest('.producto-row').remove()">Eliminar</button>
        </div>
    `;
    container.appendChild(row);
}
</script>
@endsection