@extends('layouts.app')

@section('content')
<div class="p-3">
    <h2><i class="fas fa-building text-primary"></i> Editar Proveedor</h2>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="background-color: transparent; padding: 0; font-size: 0.9rem;">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" style="color: #198754;">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('proveedores.index') }}" style="color: #198754;">Proveedores</a></li>
            <li class="breadcrumb-item active" style="color: #ccc;">Editar</li>
        </ol>
    </nav>

    <!-- Mensaje de error -->
    @if ($errors->any())
        <div style="
            background-color: #dc3545;
            color: white;
            padding: 0.5rem;
            border-radius: 4px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        ">
            <strong>Errores:</strong>
            <ul style="margin: 0.3rem 0; padding-left: 1rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('proveedores.update', $proveedor->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nombre del Proveedor *</label>
            <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $proveedor->nombre) }}"
                   required style="background-color: #333; border: 1px solid #555; color: #eee;">
        </div>

        <div class="mb-3">
            <label class="form-label">Contacto (Persona de referencia)</label>
            <input type="text" name="contacto" class="form-control" value="{{ old('contacto', $proveedor->contacto) }}"
                   style="background-color: #333; border: 1px solid #555; color: #eee;">
        </div>

        <div class="mb-3">
            <label class="form-label">Teléfono</label>
            <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $proveedor->telefono) }}"
                   style="background-color: #333; border: 1px solid #555; color: #eee;">
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $proveedor->email) }}"
                   style="background-color: #333; border: 1px solid #555; color: #eee;">
        </div>

        <div class="mb-3">
            <label class="form-label">Dirección</label>
            <input type="text" name="direccion" class="form-control" value="{{ old('direccion', $proveedor->direccion) }}"
                   style="background-color: #333; border: 1px solid #555; color: #eee;">
        </div>

        <div class="mb-3">
            <label class="form-label">Notas / Observaciones</label>
            <textarea name="notas" class="form-control" rows="3"
                      style="background-color: #333; border: 1px solid #555; color: #eee;">{{ old('notas', $proveedor->notas) }}</textarea>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Actualizar Proveedor</button>
            <a href="{{ route('proveedores.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection