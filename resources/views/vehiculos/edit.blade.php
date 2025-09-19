@extends('layouts.app')

@section('content')
<h1>✏️ Editar Vehículo</h1>

<form action="{{ route('vehiculos.update', $vehiculo) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row g-3">
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Patente</label>
                <input type="text" name="patente" class="form-control" value="{{ old('patente', $vehiculo->patente) }}" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Marca</label>
                <input type="text" name="marca" class="form-control" value="{{ old('marca', $vehiculo->marca) }}" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Modelo</label>
                <input type="text" name="modelo" class="form-control" value="{{ old('modelo', $vehiculo->modelo) }}" required>
            </div>
        </div>
        <div class="col-md-6">
         <div class="mb-3">
             <label class="form-label"><strong>Tipo de Vehículo</strong></label>
             <select name="tipo" class="form-control" required>
               <option value="">Seleccionar tipo</option>
               <option value="camion" {{ old('tipo') == 'camion' ? 'selected' : '' }}>Camión</option>
               <option value="camioneta" {{ old('tipo') == 'camioneta' ? 'selected' : '' }}>Camioneta</option>
              <option value="bascula" {{ old('tipo') == 'bascula' ? 'selected' : '' }}>Báscula</option>
              <option value="acoplado" {{ old('tipo') == 'acoplado' ? 'selected' : '' }}>Acoplado</option>
              <option value="semiremolque" {{ old('tipo') == 'semiremolque' ? 'selected' : '' }}>Semiremolque</option>
              <option value="tolva" {{ old('tipo') == 'tolva' ? 'selected' : '' }}>Tolva</option>
            </select>
     </div>
    </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Capacidad (kg)</label>
                <input type="number" name="capacidad_kg" class="form-control" value="{{ old('capacidad_kg', $vehiculo->capacidad_kg) }}" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Fecha de Compra</label>
                <input type="date" name="fecha_compra" class="form-control" value="{{ old('fecha_compra', $vehiculo->fecha_compra) }}" required>
            </div>
        </div>
    </div>
    <!-- Kilometraje Actual -->
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label"><strong>Kilometraje Actual (km)</strong></label>
            <input type="number" name="kilometraje_actual" 
               class="form-control" 
               value="{{ old('kilometraje_actual', $vehiculo->kilometraje_actual ?? '') }}" 
               min="0"
               placeholder="Ej: 85000">
            <small class="text-muted">Kilómetros recorridos hasta hoy.</small>
     </div>
    </div>

    <!-- Último Mantenimiento (km) -->
    <div class="col-md-6">
      <div class="mb-3">
        <label class="form-label"><strong>Último Mantenimiento (km)</strong></label>
        <input type="number" name="ultimo_mantenimiento_km" 
               class="form-control" 
               value="{{ old('ultimo_mantenimiento_km', $vehiculo->ultimo_mantenimiento_km ?? '') }}" 
               min="0"
               placeholder="Ej: 82000">
        <small class="text-muted">Kilometraje en el que se realizó el último mantenimiento.</small>
    </div>
    </div>

    <!-- Intervalo de Mantenimiento -->
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label"><strong>Intervalo de Mantenimiento (km)</strong></label>
            <input type="number" name="intervalo_mantenimiento" 
               class="form-control" 
               value="{{ old('intervalo_mantenimiento', $vehiculo->intervalo_mantenimiento ?? '10000') }}" 
               min="1000"
               step="1000"
               placeholder="Ej: 10000">
            <small class="text-muted">Cada cuántos km debe hacerse mantenimiento (ej: 10000).</small>
        </div>
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

    <button type="submit" class="btn btn-success btn-sm">Actualizar Vehículo</button>

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
                Has realizado cambios en el vehículo. Si sales ahora, los cambios no se guardarán.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Seguir editando</button>
                <a href="{{ route('vehiculos.index') }}" class="btn btn-danger btn-sm">Salir sin guardar</a>
            </div>
        </div>
    </div>
</div>
@endsection