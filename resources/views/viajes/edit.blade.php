@extends('layouts.app')

@section('content')
<h1>✏️ Editar Viaje</h1>

<form action="{{ route('viajes.update', $viaje) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row g-3">
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Vehículo</label>
                <select name="vehiculo_id" class="form-control" required>
                    <option value="">Seleccionar vehículo</option>
                    @foreach($vehiculos as $v)
                        <option value="{{ $v->id }}" {{ old('vehiculo_id', $viaje->vehiculo_id) == $v->id ? 'selected' : '' }}>
                            {{ $v->patente }} - {{ $v->marca }} {{ $v->modelo }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Chofer</label>
                <select name="chofer_id" class="form-control" required>
                    <option value="">Seleccionar chofer</option>
                    @foreach($choferes as $c)
                        <option value="{{ $c->id }}" {{ old('chofer_id', $viaje->chofer_id) == $c->id ? 'selected' : '' }}>
                            {{ $c->nombre }} {{ $c->apellido }} ({{ $c->licencia_tipo }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Origen</label>
                <input type="text" name="origen" class="form-control" value="{{ old('origen', $viaje->origen) }}" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Destino</label>
                <input type="text" name="destino" class="form-control" value="{{ old('destino', $viaje->destino) }}" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Fecha de Salida</label>
                <input type="date" name="fecha_salida" class="form-control" value="{{ old('fecha_salida', $viaje->fecha_salida) }}" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Fecha de Llegada</label>
                <input type="date" name="fecha_llegada" class="form-control" value="{{ old('fecha_llegada', $viaje->fecha_llegada) }}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Kilómetros</label>
                <input type="number" name="kilometros" class="form-control" value="{{ old('kilometros', $viaje->kilometros) }}" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Estado</label>
                <select name="estado" class="form-control" required>
                    <option value="en curso" {{ old('estado', $viaje->estado) == 'en curso' ? 'selected' : '' }}>En curso</option>
                    <option value="completado" {{ old('estado', $viaje->estado) == 'completado' ? 'selected' : '' }}>Completado</option>
                    <option value="cancelado" {{ old('estado', $viaje->estado) == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                </select>
            </div>
        </div>
        <div class="col-12">
            <div class="mb-3">
                <label class="form-label">Descripción de la Carga</label>
                <textarea name="descripcion_carga" class="form-control" rows="3" required>{{ old('descripcion_carga', $viaje->descripcion_carga) }}</textarea>
            </div>
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

    <button type="submit" class="btn btn-success btn-sm">Actualizar Viaje</button>

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
                Has realizado cambios en el viaje. Si sales ahora, los cambios no se guardarán.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Seguir editando</button>
                <a href="{{ route('viajes.index') }}" class="btn btn-danger btn-sm">Salir sin guardar</a>
            </div>
        </div>
    </div>
</div>
@endsection