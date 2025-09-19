@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">
        <i class="fas fa-wrench me-2"></i>Registrar Mantenimiento - 
        <strong>{{ $vehiculo->patente }}</strong>
    </h1>

    <!-- Breadcrumb opcional -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('vehiculos.index') }}">Vehículos</a></li>
            <li class="breadcrumb-item"><a href="{{ route('vehiculos.show', $vehiculo->id) }}">{{ $vehiculo->patente }}</a></li>
            <li class="breadcrumb-item active">Nuevo Mantenimiento</li>
        </ol>
    </nav>

    <form action="{{ route('mantenimientos.store') }}" method="POST">
        @csrf
        <input type="hidden" name="vehiculo_id" value="{{ $vehiculo->id }}">

        <div class="row g-3">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label"><strong>Tipo de Mantenimiento</strong></label>
                    <select name="tipo" class="form-control" required>
                        <option value="">Seleccionar tipo</option>
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
                    <label class="form-label"><strong>Kilometraje actual (km)</strong></label>
                    <input type="number" name="kilometraje" class="form-control" value="{{ old('kilometraje') }}" required min="0">
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label"><strong>Fecha del mantenimiento</strong></label>
                    <input type="date" name="fecha" class="form-control" value="{{ old('fecha') }}" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label"><strong>Costo ($)</strong></label>
                    <input type="number" name="costo" step="0.01" class="form-control" value="{{ old('costo') }}" min="0" placeholder="0.00">
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label"><strong>Proveedor</strong></label>
                    <input type="text" name="proveedor" class="form-control" value="{{ old('proveedor') }}" placeholder="Taller mecánico...">
                </div>
            </div>

            <div class="col-12">
                <div class="mb-3">
                    <label class="form-label"><strong>Descripción adicional</strong></label>
                    <textarea name="descripcion" class="form-control" rows="3" placeholder="Detalles del trabajo realizado...">{{ old('descripcion') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Errores de validación -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mt-4" role="alert">
                <h5><i class="fas fa-exclamation-triangle me-2"></i>Errores:</h5>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="mt-4 d-flex gap-2">
            <!-- Botón Guardar -->
            <button type="submit" class="btn btn-success px-4">
                <i class="fas fa-save me-1"></i>Guardar Mantenimiento
            </button>

            <!-- Botón con Modal de Confirmación -->
            <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#confirmExitModal">
                <i class="fas fa-arrow-left me-1"></i>Volver
            </button>

            <!-- Botón directo a vehículo (opcional, más rápido) -->
            <!-- <a href="{{ route('vehiculos.show', $vehiculo->id) }}" class="btn btn-outline-secondary">Cancelar</a> -->
        </div>
    </form>

    <!-- Modal de Confirmación al Salir -->
    <div class="modal fade" id="confirmExitModal" tabindex="-1" aria-labelledby="confirmExitModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-light">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmExitModalLabel">
                        <i class="fas fa-question-circle me-2"></i>¿Salir sin guardar?
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Has comenzado a registrar un mantenimiento. Si sales ahora, todos los datos ingresados se perderán.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                        <i class="fas fa-edit me-1"></i>Seguir editando
                    </button>
                    <a href="{{ route('vehiculos.show', $vehiculo->id) }}" class="btn btn-danger btn-sm">
                        <i class="fas fa-door-open me-1"></i>Salir sin guardar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Si usas Font Awesome para iconos -->
<script src="https://kit.fontawesome.com/your-kit.js" crossorigin="anonymous"></script>
@endpush