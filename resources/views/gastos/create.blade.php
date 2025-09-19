@extends('layouts.app')

@section('content')
<h1>Agregar Gasto</h1>

<form action="{{ route('gastos.store') }}" method="POST"> 
    @csrf

    <div class="mb-3">
        <label class="form-label">Vehículo</label>
        <select name="vehiculo_id" class="form-control" required>
            <option value="">Seleccionar vehículo</option>
            @foreach($vehiculos as $v)
                <option value="{{ $v->id }}" {{ old('vehiculo_id') == $v->id ? 'selected' : '' }}>
                    {{ $v->patente }} - {{ $v->marca }} {{ $v->modelo }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Nuevo: Chofer -->
    <div class="mb-3">
        <label class="form-label">Chofer (opcional)</label>
        <select name="chofer_id" class="form-control">
            <option value="">Sin chofer</option>
            @foreach($choferes as $c)
                <option value="{{ $c->id }}" {{ old('chofer_id') == $c->id ? 'selected' : '' }}>
                    {{ $c->nombre }} (Lic: {{ $c->licencia_numero }})
                </option>
            @endforeach
        </select>
        <small class="text-muted">Puede usarse para seguros personales, multas, etc.</small>
    </div>

    <div class="mb-3">
        <label class="form-label">Tipo de Gasto</label>
        <select name="tipo" class="form-control" required>
            <option value="">Seleccionar</option>
            <option value="combustible" {{ old('tipo') == 'combustible' ? 'selected' : '' }}>Combustible</option>
            <option value="mantenimiento" {{ old('tipo') == 'mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
            <option value="repuestos" {{ old('tipo') == 'repuestos' ? 'selected' : '' }}>Repuestos</option>
            <option value="lavado" {{ old('tipo') == 'lavado' ? 'selected' : '' }}>Lavado</option>
            <option value="seguro" {{ old('tipo') == 'seguro' ? 'selected' : '' }}>Seguro</option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Monto ($)</label>
        <input type="number" name="monto" step="0.01" class="form-control" value="{{ old('monto') }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Fecha</label>
        <input type="date" name="fecha" class="form-control" value="{{ old('fecha') }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Descripción (opcional)</label>
        <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion') }}</textarea>
    </div>

    <button type="submit" class="btn btn-success btn-sm">Guardar Gasto</button>

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
                Has comenzado a registrar un gasto. Si sales ahora, los datos ingresados se perderán.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Seguir registrando</button>
                <a href="{{ route('gastos.index') }}" class="btn btn-danger btn-sm">Salir sin guardar</a>
            </div>
        </div>
    </div>
</div>
@endsection