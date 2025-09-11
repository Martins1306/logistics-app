@extends('layouts.app')

@section('content')
<h1>Editar Producto</h1>

<form action="{{ route('productos.update', $producto) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">Nombre</label>
        <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $producto->nombre) }}" required>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Categoría</label>
                <select name="categoria" class="form-control" required>
                    <option value="">Seleccionar</option>
                    <option value="construcción" {{ old('categoria', $producto->categoria) == 'construcción' ? 'selected' : '' }}>Construcción</option>
                    <option value="frutas" {{ old('categoria', $producto->categoria) == 'frutas' ? 'selected' : '' }}>Frutas</option>
                    <option value="hortalizas" {{ old('categoria', $producto->categoria) == 'hortalizas' ? 'selected' : '' }}>Hortalizas</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Unidad</label>
                <select name="unidad" class="form-control" required>
                    <option value="">Seleccionar</option>
                    <option value="kg" {{ old('unidad', $producto->unidad) == 'kg' ? 'selected' : '' }}>Kilogramo (kg)</option>
                    <option value="m³" {{ old('unidad', $producto->unidad) == 'm³' ? 'selected' : '' }}>Metro cúbico (m³)</option>
                    <option value="unidad" {{ old('unidad', $producto->unidad) == 'unidad' ? 'selected' : '' }}>Unidad</option>
                    <option value="bolsa" {{ old('unidad', $producto->unidad) == 'bolsa' ? 'selected' : '' }}>Bolsa</option>
                    <option value="litro" {{ old('unidad', $producto->unidad) == 'litro' ? 'selected' : '' }}>Litro</option>
                </select>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Precio (opcional)</label>
        <input type="number" name="precio" step="0.01" class="form-control" value="{{ old('precio', $producto->precio) }}" placeholder="0.00">
    </div>

    <!-- Mostrar errores de validación -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <button type="submit" class="btn btn-success btn-sm">Actualizar Producto</button>

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
                Has realizado cambios en el producto. Si sales ahora, los cambios no se guardarán.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Seguir editando</button>
                <a href="{{ route('productos.index') }}" class="btn btn-danger btn-sm">Salir sin guardar</a>
            </div>
        </div>
    </div>
</div>
@endsection