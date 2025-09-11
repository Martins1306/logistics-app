@extends('layouts.app')

@section('content')
<h1>🔧 Registrar Mantenimiento - {{ $vehiculo->patente }}</h1>

<form action="{{ route('mantenimientos.store') }}" method="POST">    
    @csrf
    <input type="hidden" name="vehiculo_id" value="{{ $vehiculo->id }}">

    <div class="row g-3">
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Tipo de Mantenimiento</label>
                <select name="tipo" class="form-control" required>
                    <option value="">Seleccionar</option>
                    <option value="Cambio de aceite" {{ old('tipo') == 'Cambio de aceite' ? 'selected' : '' }}>Cambio de aceite</option>
                    <option value="Correas" {{ old('tipo') == 'Correas' ? 'selected' : '' }}>Correas</option>
                    <option value="Caja" {{ old('tipo') == 'Caja' ? 'selected' : '' }}>Caja</option>
                    <option value="Cubiertas" {{ old('tipo') == 'Cubiertas' ? 'selected' : '' }}>Cubiertas</option>
                    <option value="Frenos" {{ old('tipo') == 'Frenos' ? 'selected' : '' }}>Frenos</option>
                    <option value="Filtro" {{ old('tipo') == 'Filtro' ? 'selected' : '' }}>Filtro</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Kilometraje</label>
                <input type="number" name="kilometraje" class="form-control" value="{{ old('kilometraje') }}" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Fecha</label>
                <input type="date" name="fecha" class="form-control" value="{{ old('fecha') }}" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Costo ($)</label>
                <input type="number" name="costo" step="0.01" class="form-control" value="{{ old('costo') }}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Proveedor</label>
                <input type="text" name="proveedor" class="form-control" value="{{ old('proveedor') }}">
            </div>
        </div>
        <div class="col-12">
            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion') }}</textarea>
            </div>
        </div>
    </div>

    <!-- Mostrar errores de validación -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <button type="submit" class="btn btn-success btn-sm">Guardar Mantenimiento</button>

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
                Has comenzado a registrar un mantenimiento. Si sales ahora, los datos ingresados se perderán.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Seguir registrando</button>
                <a href="{{ route('vehiculos.show', $vehiculo) }}" class="btn btn-danger btn-sm">Salir sin guardar</a>
            </div>
        </div>
    </div>
</div>
@endsection