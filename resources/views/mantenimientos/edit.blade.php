@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4 text-light">
        <i class="fas fa-edit me-2"></i>Editar Mantenimiento - 
        <strong>{{ $mantenimiento->vehiculo->patente }}</strong>
    </h1>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('vehiculos.index') }}" class="text-secondary">Vehículos</a></li>
            <li class="breadcrumb-item"><a href="{{ route('vehiculos.show', $mantenimiento->vehiculo_id) }}" class="text-secondary">{{ $mantenimiento->vehiculo->patente }}</a></li>
            <li class="breadcrumb-item active text-white">Editar Mantenimiento</li>
        </ol>
    </nav>

    <form action="{{ route('mantenimientos.update', $mantenimiento) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-3">

            <!-- Tipo -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label text-light"><strong>Tipo de Mantenimiento</strong></label>
                    <select name="tipo" class="form-control bg-secondary text-light" required>
                        <option value="">Seleccionar tipo</option>
                        <option value="Cambio de aceite" {{ old('tipo', $mantenimiento->tipo) == 'Cambio de aceite' ? 'selected' : '' }}>Cambio de aceite</option>
                        <option value="Correas" {{ old('tipo', $mantenimiento->tipo) == 'Correas' ? 'selected' : '' }}>Correas</option>
                        <option value="Caja" {{ old('tipo', $mantenimiento->tipo) == 'Caja' ? 'selected' : '' }}>Caja</option>
                        <option value="Cubiertas" {{ old('tipo', $mantenimiento->tipo) == 'Cubiertas' ? 'selected' : '' }}>Cubiertas</option>
                        <option value="Frenos" {{ old('tipo', $mantenimiento->tipo) == 'Frenos' ? 'selected' : '' }}>Frenos</option>
                        <option value="Filtro" {{ old('tipo', $mantenimiento->tipo) == 'Filtro' ? 'selected' : '' }}>Filtro</option>
                    </select>
                    @error('tipo')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Fecha -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label text-light"><strong>Fecha</strong></label>
                    <input type="date" name="fecha" class="form-control bg-secondary text-light"
                           value="{{ old('fecha', $mantenimiento->fecha ? $mantenimiento->fecha->format('Y-m-d') : '') }}"
                           required>
                    @error('fecha')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Costo -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label text-light"><strong>Costo ($)</strong></label>
                    <input type="number" name="costo" step="0.01" class="form-control bg-secondary text-light"
                           value="{{ old('costo', $mantenimiento->costo_real) }}"
                           min="0" placeholder="0.00">
                    @error('costo')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Proveedor -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label text-light"><strong>Proveedor</strong></label>
                    <input type="text" name="proveedor" class="form-control bg-secondary text-light"
                           value="{{ old('proveedor', $mantenimiento->proveedor) }}"
                           placeholder="Taller mecánico...">
                    @error('proveedor')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Estado -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label text-light"><strong>Estado</strong></label>
                    <select name="estado" class="form-control bg-secondary text-light" required>
                        <option value="">Seleccionar estado...</option>
                        <option value="pendiente" {{ old('estado', $mantenimiento->estado) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="en_proceso" {{ old('estado', $mantenimiento->estado) == 'en_proceso' ? 'selected' : '' }}>En Proceso</option>
                        <option value="completado" {{ old('estado', $mantenimiento->estado) == 'completado' ? 'selected' : '' }}>Completado</option>
                        <option value="cancelado" {{ old('estado', $mantenimiento->estado) == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                    @error('estado')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Descripción -->
            <div class="col-12">
                <div class="mb-3">
                    <label class="form-label text-light"><strong>Descripción</strong></label>
                    <textarea name="descripcion" class="form-control bg-secondary text-light" rows="3"
                              placeholder="Detalles del mantenimiento...">{{ old('descripcion', $mantenimiento->descripcion) }}</textarea>
                    @error('descripcion')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Observaciones -->
            <div class="col-12">
                <div class="mb-3">
                    <label class="form-label text-light">Observaciones</label>
                    <textarea name="observaciones" class="form-control bg-secondary text-light" rows="3"
                              placeholder="Notas adicionales...">{{ old('observaciones', $mantenimiento->observaciones) }}</textarea>
                    @error('observaciones')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
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
                <i class="fas fa-save me-1"></i>Actualizar Mantenimiento
            </button>

            <!-- Botón con Modal de Confirmación -->
            <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#confirmExitModal">
                <i class="fas fa-arrow-left me-1"></i>Volver
            </button>
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
                    Has realizado cambios en el mantenimiento. Si sales ahora, los cambios no se guardarán.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                        <i class="fas fa-edit me-1"></i>Seguir editando
                    </button>
                    <a href="{{ route('vehiculos.show', $mantenimiento->vehiculo_id) }}" class="btn btn-danger btn-sm">
                        <i class="fas fa-door-open me-1"></i>Salir sin guardar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://kit.fontawesome.com/your-kit.js" crossorigin="anonymous"></script>
@endpush