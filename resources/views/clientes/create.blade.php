@extends('layouts.app')

@section('content')
<div class="p-3">
    <h2><i class="fas fa-user-plus text-primary"></i> Nuevo Cliente</h2>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="background-color: transparent; padding: 0; font-size: 0.9rem;">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" style="color: #198754;">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('clientes.index') }}" style="color: #198754;">Clientes</a></li>
            <li class="breadcrumb-item active" style="color: #ccc;">Nuevo</li>
        </ol>
    </nav>

    <form action="{{ route('clientes.store') }}" method="POST">
        @csrf

        <!-- Datos del cliente -->
        <div style="
            background-color: #1a1a1a;
            border-radius: 0.375rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border: 1px solid #444;
        ">
            <h5 style="color: #fff; margin-top: 0; border-bottom: 1px solid #444; padding-bottom: 0.5rem;">
                <i class="fas fa-info-circle me-1"></i> Datos del Cliente
            </h5>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" style="color: #eee;">Nombre *</label>
                    <input type="text" name="nombre" class="form-control"
                           value="{{ old('nombre') }}" required
                           style="background-color: #333; border: 1px solid #555; color: #eee;">
                    @error('nombre')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" style="color: #eee;">Razón Social</label>
                    <input type="text" name="razon_social" class="form-control"
                           value="{{ old('razon_social') }}"
                           style="background-color: #333; border: 1px solid #555; color: #eee;">
                </div>

                <div class="col-md-6">
                    <label class="form-label" style="color: #eee;">Teléfono</label>
                    <input type="text" name="telefono" class="form-control"
                           value="{{ old('telefono') }}"
                           style="background-color: #333; border: 1px solid #555; color: #eee;">
                </div>

                <div class="col-md-6">
                    <label class="form-label" style="color: #eee;">Email</label>
                    <input type="email" name="email" class="form-control"
                           value="{{ old('email') }}"
                           style="background-color: #333; border: 1px solid #555; color: #eee;">
                    @error('email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" style="color: #eee;">CUIT / DNI</label>
                    <input type="text" name="cuit" class="form-control"
                           value="{{ old('cuit') }}"
                           placeholder="20-12345678-9"
                           style="background-color: #333; border: 1px solid #555; color: #eee;">
                </div>

                <div class="col-md-6">
                    <label class="form-label" style="color: #eee;">Tipo de Cliente</label>
                    <select name="tipo" class="form-control" style="background-color: #333; border: 1px solid #555; color: #eee;">
                        <option value="">Seleccionar tipo</option>
                        <option value="agricola" {{ old('tipo') == 'agricola' ? 'selected' : '' }}>Agrícola</option>
                        <option value="construccion" {{ old('tipo') == 'construccion' ? 'selected' : '' }}>Construcción</option>
                        <option value="industrial" {{ old('tipo') == 'industrial' ? 'selected' : '' }}>Industrial</option>
                        <option value="transporte" {{ old('tipo') == 'transporte' ? 'selected' : '' }}>Transporte</option>
                        <option value="otros" {{ old('tipo') == 'otros' ? 'selected' : '' }}>Otros</option>
                    </select>
                    @error('tipo')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label" style="color: #eee;">Notas</label>
                    <textarea name="notas" class="form-control" rows="2"
                              style="background-color: #333; border: 1px solid #555; color: #eee;">{{ old('notas') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Dirección Detallada -->
        <div style="
            background-color: #1a1a1a;
            border-radius: 0.375rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border: 1px solid #444;
        ">
            <h5 style="color: #fff; margin-top: 0; border-bottom: 1px solid #444; padding-bottom: 0.5rem;">
                <i class="fas fa-map-marker-alt me-1"></i> Dirección Completa
            </h5>

            <div class="row g-2">
                <div class="col-md-6">
                    <input type="text" name="calle" class="form-control" placeholder="Calle"
                           value="{{ old('calle') }}" style="background-color: #333; border: 1px solid #555; color: #eee;">
                </div>
                <div class="col-md-2">
                    <input type="text" name="numero" class="form-control" placeholder="Número"
                           value="{{ old('numero') }}" style="background-color: #333; border: 1px solid #555; color: #eee;">
                </div>
                <div class="col-md-4">
                    <input type="text" name="codigo_postal" class="form-control" placeholder="Código Postal"
                           value="{{ old('codigo_postal') }}" style="background-color: #333; border: 1px solid #555; color: #eee;">
                </div>
            </div>
            <div class="row g-2 mt-2">
                <div class="col-md-4">
                    <input type="text" name="localidad" class="form-control" placeholder="Localidad"
                           value="{{ old('localidad') }}" style="background-color: #333; border: 1px solid #555; color: #eee;">
                </div>
                <div class="col-md-4">
                    <input type="text" name="partido" class="form-control" placeholder="Partido"
                           value="{{ old('partido') }}" style="background-color: #333; border: 1px solid #555; color: #eee;">
                </div>
                <div class="col-md-4">
                    <input type="text" name="provincia" class="form-control" placeholder="Provincia"
                           value="{{ old('provincia') }}" style="background-color: #333; border: 1px solid #555; color: #eee;">
                </div>
            </div>
        </div>

        <!-- Errores generales -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert" style="background-color: #dc3545; color: #fff; border: none; border-radius: 0.375rem;">
                <strong>Errores:</strong>
                <ul class="mb-0" style="margin-left: 1rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Botones -->
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success px-4">
                <i class="fas fa-save me-1"></i>Guardar Cliente
            </button>
            <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Volver
            </a>
        </div>
    </form>
</div>
@endsection