@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">
        <i class="fas fa-truck me-2"></i>Crear Nuevo Vehículo
    </h1>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('vehiculos.index') }}">Vehículos</a></li>
            <li class="breadcrumb-item active">Nuevo</li>
        </ol>
    </nav>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('vehiculos.store') }}" method="POST">
                @csrf

                <!-- Sección: Datos Principales -->
                <h5 class="mb-3 text-primary border-bottom pb-2">
                    <i class="fas fa-info-circle me-1"></i>Datos del Vehículo
                </h5>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Patente</label>
                        <input type="text" name="patente" class="form-control" value="{{ old('patente') }}" required maxlength="10" placeholder="Ej: ABC123">
                        @error('patente')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Marca</label>
                        <input type="text" name="marca" class="form-control" value="{{ old('marca') }}" required placeholder="Ej: Mercedes-Benz">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Modelo</label>
                        <input type="text" name="modelo" class="form-control" value="{{ old('modelo') }}" required placeholder="Ej: Actros">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Tipo de Vehículo</label>
                        <select name="tipo" class="form-control" required>
                            <option value="">Seleccionar tipo</option>
                            <option value="camion" {{ old('tipo') == 'camion' ? 'selected' : '' }}>Camión</option>
                            <option value="camioneta" {{ old('tipo') == 'camioneta' ? 'selected' : '' }}>Camioneta</option>
                            <option value="bascula" {{ old('tipo') == 'bascula' ? 'selected' : '' }}>Báscula</option>
                            <option value="acoplado" {{ old('tipo') == 'acoplado' ? 'selected' : '' }}>Acoplado</option>
                            <option value="semirremolque" {{ old('tipo') == 'semirremolque' ? 'selected' : '' }}>Semirremolque</option>
                            <option value="tolva" {{ old('tipo') == 'tolva' ? 'selected' : '' }}>Tolva</option>
                        </select>
                        @error('tipo')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Capacidad (kg)</label>
                        <input type="number" name="capacidad_kg" class="form-control" value="{{ old('capacidad_kg') }}" required min="100" placeholder="Ej: 8000">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Fecha de Compra</label>
                        <input type="date" name="fecha_compra" class="form-control" value="{{ old('fecha_compra') }}" required>
                    </div>
                </div>

                <!-- Sección: Mantenimiento -->
                <h5 class="mb-3 text-success border-bottom pb-2">
                    <i class="fas fa-wrench me-1"></i>Mantenimiento Preventivo
                </h5>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Kilometraje Actual (km)</label>
                        <input type="number" name="kilometraje_actual" class="form-control" value="{{ old('kilometraje_actual', 0) }}" min="0" placeholder="0">
                        <small class="text-muted">Deja en 0 si es vehículo nuevo.</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Último Mantenimiento (km)</label>
                        <input type="number" name="ultimo_mantenimiento_km" class="form-control" value="{{ old('ultimo_mantenimiento_km', 0) }}" min="0" placeholder="0">
                        <small class="text-muted">Para mantenimientos futuros.</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Intervalo de Mantenimiento (km)</label>
                        <input type="number" name="intervalo_mantenimiento" class="form-control" value="{{ old('intervalo_mantenimiento', 10000) }}" min="1000" step="1000" placeholder="10000">
                        <small class="text-muted">Cada cuántos km debe hacerse (ej: 10000).</small>
                    </div>
                </div>

                <!-- Errores generales -->
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <strong>Errores:</strong>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Botones -->
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success px-4">
                        <i class="fas fa-save me-1"></i>Guardar Vehículo
                    </button>

                    <a href="{{ route('vehiculos.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Volver
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<!-- Si usas Font Awesome -->
<script src="https://kit.fontawesome.com/YOUR-KIT-ID.js" crossorigin="anonymous"></script>
@endpush
@endsection