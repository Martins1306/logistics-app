@extends('layouts.app')

@section('content')
<div class="p-3">
    <h2><i class="fas fa-user-plus text-primary"></i> Nuevo Cliente</h2>

    <form action="{{ route('clientes.store') }}" method="POST">
        @csrf

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
                    <input type="text" name="nombre" class="form-control" required style="background-color: #333; border: 1px solid #555; color: #eee;">
                </div>

                <div class="col-md-6">
                    <label class="form-label" style="color: #eee;">Razón Social</label>
                    <input type="text" name="razon_social" class="form-control" style="background-color: #333; border: 1px solid #555; color: #eee;">
                </div>

                <div class="col-md-6">
                    <label class="form-label" style="color: #eee;">CUIT</label>
                    <input type="text" name="cuit" class="form-control" required style="background-color: #333; border: 1px solid #555; color: #eee;">
                </div>

                <div class="col-md-6">
                    <label class="form-label" style="color: #eee;">Teléfono</label>
                    <input type="text" name="telefono" class="form-control" style="background-color: #333; border: 1px solid #555; color: #eee;">
                </div>

                <div class="col-md-6">
                    <label class="form-label" style="color: #eee;">Email</label>
                    <input type="email" name="email" class="form-control" style="background-color: #333; border: 1px solid #555; color: #eee;">
                </div>

                <div class="col-md-6">
                    <label class="form-label" style="color: #eee;">Tipo de Cliente</label>
                    <select name="tipo" class="form-control" required style="background-color: #333; border: 1px solid #555; color: #eee;">
                        <option value="agrícola">Agrícola</option>
                        <option value="construccion">Construcción</option>
                        <option value="industrial">Industrial</option>
                        <option value="transporte">Transporte</option>
                        <option value="otros">Otros</option>
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label" style="color: #eee;">Dirección</label>
                    <input type="text" name="direccion" class="form-control" style="background-color: #333; border: 1px solid #555; color: #eee;">
                </div>

                <div class="col-md-6">
                    <label class="form-label" style="color: #eee;">Localidad</label>
                    <input type="text" name="localidad" class="form-control" style="background-color: #333; border: 1px solid #555; color: #eee;">
                </div>

                <div class="col-md-6">
                    <label class="form-label" style="color: #eee;">Provincia</label>
                    <input type="text" name="provincia" class="form-control" style="background-color: #333; border: 1px solid #555; color: #eee;">
                </div>

                <div class="col-12">
                    <label class="form-label" style="color: #eee;">Notas</label>
                    <textarea name="notas" class="form-control" rows="2" style="background-color: #333; border: 1px solid #555; color: #eee;"></textarea>
                </div>
            </div>
        </div>

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