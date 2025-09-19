@extends('layouts.app')

@section('content')
<div class="p-3">
    <h2><i class="fas fa-user-edit text-primary"></i> Editar Cliente #{{ $cliente->id }}</h2>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb" style="background-color: transparent; padding: 0; font-size: 0.9rem;">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" style="color: #198754;">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('clientes.index') }}" style="color: #198754;">Clientes</a></li>
            <li class="breadcrumb-item active" style="color: #ccc;">Editar</li>
        </ol>
    </nav>

    <form action="{{ route('clientes.update', $cliente->id) }}" method="POST">
        @csrf
        @method('PUT')

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
                    <label class="form-label" style="color: #eee;">Nombre</label>
                    <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $cliente->nombre) }}" required style="background-color: #333; border: 1px solid #555; color: #eee;">
                </div>

                <div class="col-md-6">
                    <label class="form-label" style="color: #eee;">Razón Social</label>
                    <input type="text" name="razon_social" class="form-control" value="{{ old('razon_social', $cliente->razon_social) }}" style="background-color: #333; border: 1px solid #555; color: #eee;">
                </div>

                <div class="col-md-6">
                    <label class="form-label" style="color: #eee;">CUIT</label>
                    <input type="text" name="cuit" class="form-control" value="{{ old('cuit', $cliente->cuit) }}" required style="background-color: #333; border: 1px solid #555; color: #eee;">
                </div>

                <div class="col-md-6">
                    <label class="form-label" style="color: #eee;">Teléfono</label>
                    <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $cliente->telefono) }}" style="background-color: #333; border: 1px solid #555; color: #eee;">
                </div>

                <div class="col-md-6">
                    <label class="form-label" style="color: #eee;">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $cliente->email) }}" style="background-color: #333; border: 1px solid #555; color: #eee;">
                </div>

                <div class="col-md-6">
                    <label class="form-label" style="color: #eee;">Tipo de Cliente</label>
                    <select name="tipo" class="form-control" required style="background-color: #333; border: 1px solid #555; color: #eee;">
                        <option value="agrícola" {{ old('tipo', $cliente->tipo) == 'agrícola' ? 'selected' : '' }}>Agrícola</option>
                        <option value="construccion" {{ old('tipo', $cliente->tipo) == 'construccion' ? 'selected' : '' }}>Construcción</option>
                        <option value="industrial" {{ old('tipo', $cliente->tipo) == 'industrial' ? 'selected' : '' }}>Industrial</option>
                        <option value="transporte" {{ old('tipo', $cliente->tipo) == 'transporte' ? 'selected' : '' }}>Transporte</option>
                        <option value="otros" {{ old('tipo', $cliente->tipo) == 'otros' ? 'selected' : '' }}>Otros</option>
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label" style="color: #eee;">Dirección</label>
                    <input type="text" name="direccion" class="form-control" value="{{ old('direccion', $cliente->direccion) }}" style="background-color: #333; border: 1px solid #555; color: #eee;">
                </div>

                <div class="col-md-6">
                    <label class="form-label" style="color: #eee;">Localidad</label>
                    <input type="text" name="localidad" class="form-control" value="{{ old('localidad', $cliente->localidad) }}" style="background-color: #333; border: 1px solid #555; color: #eee;">
                </div>

                <div class="col-md-6">
                    <label class="form-label" style="color: #eee;">Provincia</label>
                    <input type="text" name="provincia" class="form-control" value="{{ old('provincia', $cliente->provincia) }}" style="background-color: #333; border: 1px solid #555; color: #eee;">
                </div>

                <div class="col-12">
                    <label class="form-label" style="color: #eee;">Notas</label>
                    <textarea name="notas" class="form-control" rows="2" style="background-color: #333; border: 1px solid #555; color: #eee;">{{ old('notas', $cliente->notas) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Errores -->
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
                <i class="fas fa-save me-1"></i>Actualizar Cliente
            </button>
            <a href="{{ route('clientes.show', $cliente->id) }}" class="btn btn-secondary">
                <i class="fas fa-times me-1"></i>Cancelar
            </a>
        </div>
    </form>
</div>
@endsection