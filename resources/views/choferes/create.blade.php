@extends('layouts.app')

@section('content')
<h1>Registrar Nuevo Chofer</h1>

<form action="{{ route('choferes.store') }}" method="POST">
    @csrf

    <div class="row g-3">
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Apellido</label>
                <input type="text" name="apellido" class="form-control" value="{{ old('apellido') }}" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">DNI</label>
                <input type="text" name="dni" class="form-control" value="{{ old('dni') }}" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Teléfono</label>
                <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Tipo de Licencia</label>
                <select name="licencia_tipo" class="form-control" required>
                    <option value="">Seleccionar</option>
                    <option value="A1" {{ old('licencia_tipo') == 'A1' ? 'selected' : '' }}>A1</option>
                    <option value="A2" {{ old('licencia_tipo') == 'A2' ? 'selected' : '' }}>A2</option>
                    <option value="B" {{ old('licencia_tipo') == 'B' ? 'selected' : '' }}>B</option>
                    <option value="C" {{ old('licencia_tipo') == 'C' ? 'selected' : '' }}>C</option>
                    <option value="D" {{ old('licencia_tipo') == 'D' ? 'selected' : '' }}>D</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Vencimiento de Licencia</label>
                <input type="date" name="licencia_vencimiento" class="form-control" value="{{ old('licencia_vencimiento') }}" required>
            </div>
        </div>
         <!-- Dirección Detallada -->
         <div class="mb-3">
            <label class="form-label">Dirección Completa</label>
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

        <div class="col-12">
            <div class="mb-3">
                <label class="form-label">Observaciones</label>
                <textarea name="observaciones" class="form-control" rows="3">{{ old('observaciones') }}</textarea>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-success btn-sm">Registrar Chofer</button>

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
                Has comenzado a registrar un nuevo chofer. Si sales ahora, los datos ingresados se perderán.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Seguir registrando</button>
                <a href="{{ route('choferes.index') }}" class="btn btn-danger btn-sm">Salir sin guardar</a>
            </div>
        </div>
    </div>
</div>
@endsection