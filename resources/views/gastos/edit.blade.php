@extends('layouts.app')

@section('content')
<h1>Editar Gasto</h1>

<form action="{{ route('gastos.update', $gasto) }}" method="POST"> 
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">Vehículo</label>
        <select name="vehiculo_id" class="form-control" required>
            <option value="">Seleccionar vehículo</option>
            @foreach($vehiculos as $v)
                <option value="{{ $v->id }}" {{ old('vehiculo_id', $gasto->vehiculo_id) == $v->id ? 'selected' : '' }}>
                    {{ $v->patente }} - {{ $v->marca }} {{ $v->modelo }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Tipo de Gasto</label>
        <select name="tipo" class="form-control" required>
            <option value="">Seleccionar</option>
            <option value="combustible" {{ old('tipo', $gasto->tipo) == 'combustible' ? 'selected' : '' }}>Combustible</option>
            <option value="mantenimiento" {{ old('tipo', $gasto->tipo) == 'mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
            <option value="repuestos" {{ old('tipo', $gasto->tipo) == 'repuestos' ? 'selected' : '' }}>Repuestos</option>
            <option value="lavado" {{ old('tipo', $gasto->tipo) == 'lavado' ? 'selected' : '' }}>Lavado</option>
            <option value="seguro" {{ old('tipo', $gasto->tipo) == 'seguro' ? 'selected' : '' }}>Seguro</option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Monto ($)</label>
        <input type="number" name="monto" step="0.01" class="form-control" value="{{ old('monto', $gasto->monto) }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Fecha</label>
        <input type="date" name="fecha" class="form-control" value="{{ old('fecha', $gasto->fecha) }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Descripción</label>
        <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion', $gasto->descripcion) }}</textarea>
    </div>

    <button type="submit" class="btn btn-primary btn-sm">Actualizar Gasto</button>

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
                <a href="{{ route('gastos.index') }}" class="btn btn-danger btn-sm">Salir sin guardar</a>
            </div>
        </div>
    </div>
</div>
@endsection