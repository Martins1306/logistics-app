@extends('layouts.app')

@section('content')
<h1>Editar Producto</h1>

<form action="{{ route('productos.update', $producto->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">Nombre</label>
        <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $producto->nombre) }}" required style="background-color: #333; border: 1px solid #555; color: #eee;">
        @error('nombre') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Categoría</label>
        <select name="categoria" class="form-control" required style="background-color: #333; border: 1px solid #555; color: #eee;">
            <option value="">Seleccionar categoría</option>
            <option value="hortalizas" {{ old('categoria', $producto->categoria) == 'hortalizas' ? 'selected' : '' }}>Hortalizas</option>
            <option value="frutas" {{ old('categoria', $producto->categoria) == 'frutas' ? 'selected' : '' }}>Frutas</option>
            <option value="construcción" {{ old('categoria', $producto->categoria) == 'construcción' ? 'selected' : '' }}>Construcción</option>
        </select>
        @error('categoria') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Unidad de Medida</label>
        <select name="unidad" class="form-control" required style="background-color: #333; border: 1px solid #555; color: #eee;">
            <option value="">Seleccionar unidad</option>
            <option value="kg" {{ old('unidad', $producto->unidad) == 'kg' ? 'selected' : '' }}>kg</option>
            <option value="m³" {{ old('unidad', $producto->unidad) == 'm³' ? 'selected' : '' }}>m³</option>
            <option value="unidad" {{ old('unidad', $producto->unidad) == 'unidad' ? 'selected' : '' }}>unidad</option>
            <option value="bolsa" {{ old('unidad', $producto->unidad) == 'bolsa' ? 'selected' : '' }}>bolsa</option>
            <option value="litro" {{ old('unidad', $producto->unidad) == 'litro' ? 'selected' : '' }}>litro</option>
        </select>
        @error('unidad') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Precio ($)</label>
        <input type="number" step="0.01" name="precio" class="form-control" value="{{ old('precio', $producto->precio) }}" style="background-color: #333; border: 1px solid #555; color: #eee;">
        @error('precio') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <!-- Campos de inventario -->
    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">Stock Actual</label>
            <input type="number" name="stock_actual" class="form-control" value="{{ old('stock_actual', $producto->stock_actual) }}" min="0" required style="background-color: #333; border: 1px solid #555; color: #eee;">
            @error('stock_actual') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label">Stock Mínimo</label>
            <input type="number" name="stock_minimo" class="form-control" value="{{ old('stock_minimo', $producto->stock_minimo) }}" min="0" required style="background-color: #333; border: 1px solid #555; color: #eee;">
            @error('stock_minimo') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
    </div>

    <button type="submit" class="btn btn-primary btn-sm">Actualizar Producto</button>

    <!-- Botón que abre el modal de confirmación al salir -->
    <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#confirmExitModal">
        Volver
    </button>
</form>

<!-- Modal de Confirmación al Salir -->
<div class="modal fade" id="confirmExitModal" tabindex="-1" aria-labelledby="confirmExitModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-light">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmExitModalLabel">¿Salir sin guardar?</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Has realizado cambios en el formulario. Si sales ahora, los cambios no se guardarán.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Seguir editando</button>
                <a href="{{ route('productos.index') }}" class="btn btn-danger btn-sm">Salir sin guardar</a>
            </div>
        </div>
    </div>
</div>
@endsection