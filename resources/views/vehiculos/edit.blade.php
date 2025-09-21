@extends('layouts.app')

@section('content')
<div class="p-3">
    <h2><i class="fas fa-truck text-primary"></i> Editar Vehículo</h2>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="background-color: transparent; padding: 0; font-size: 0.9rem;">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" style="color: #198754;">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('vehiculos.index') }}" style="color: #198754;">Vehículos</a></li>
            <li class="breadcrumb-item active" style="color: #ccc;">Editar</li>
        </ol>
    </nav>

    <!-- Mensajes de error -->
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

    <form action="{{ route('vehiculos.update', $vehiculo->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Patente *</label>
                <input type="text" name="patente" class="form-control"
                       value="{{ old('patente', $vehiculo->patente) }}"
                       required
                       style="background-color: #333; border: 1px solid #555; color: #eee;">
            </div>
            <div class="col-md-6">
                <label class="form-label">Tipo *</label>
                <select name="tipo" class="form-control" required style="background-color: #333; border: 1px solid #555; color: #eee;">
                    <option value="">Seleccionar tipo</option>
                    <option value="camion" {{ old('tipo', $vehiculo->tipo) == 'camion' ? 'selected' : '' }}>Camión</option>
                    <option value="camioneta" {{ old('tipo', $vehiculo->tipo) == 'camioneta' ? 'selected' : '' }}>Camioneta</option>
                    <option value="semirremolque" {{ old('tipo', $vehiculo->tipo) == 'semirremolque' ? 'selected' : '' }}>Semirremolque</option>
                    <option value="acoplado" {{ old('tipo', $vehiculo->tipo) == 'acoplado' ? 'selected' : '' }}>Acoplado</option>
                    <option value="tractocamion" {{ old('tipo', $vehiculo->tipo) == 'tractocamion' ? 'selected' : '' }}>Tractocamión</option>
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Marca *</label>
                <input type="text" name="marca" class="form-control"
                       value="{{ old('marca', $vehiculo->marca) }}"
                       required
                       style="background-color: #333; border: 1px solid #555; color: #eee;">
            </div>
            <div class="col-md-6">
                <label class="form-label">Modelo *</label>
                <input type="text" name="modelo" class="form-control"
                       value="{{ old('modelo', $vehiculo->modelo) }}"
                       required
                       style="background-color: #333; border: 1px solid #555; color: #eee;">
            </div>
        </div>
        <div class="row mb-3">
    <div class="col-md-6">
        <label class="form-label">Último Mantenimiento (km)</label>
        <input type="number" name="ultimo_mantenimiento_km" class="form-control"
               value="{{ old('ultimo_mantenimiento_km', $vehiculo->ultimo_mantenimiento_km ?? '') }}"
               min="0"
               style="background-color: #333; border: 1px solid #555; color: #eee;">
    </div>
    <div class="col-md-6">
        <label class="form-label">Intervalo de Mantenimiento (km)</label>
        <input type="number" name="intervalo_mantenimiento" class="form-control"
               value="{{ old('intervalo_mantenimiento', $vehiculo->intervalo_mantenimiento ?? 10000) }}"
               min="1000" step="100"
               style="background-color: #333; border: 1px solid #555; color: #eee;"
               placeholder="Ej: 10000">
    </div>
</div>
            <!-- Mostrar próximo mantenimiento (solo lectura) -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Próximo Mantenimiento (estimado)</label>
                    <input type="text" class="form-control" value="{{ number_format($vehiculo->proximo_mantenimiento, 0, ',', '.') }} km" readonly
                        style="background-color: #444; border: 1px solid #555; color: #fff; font-weight: 500;">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Estado</label>
                    @if($vehiculo->necesitaMantenimiento())
                        <div style="color: #dc3545; font-weight: 500; margin-top: 0.5rem;">⚠️ REQUIERE MANTENIMIENTO</div>
                    @elseif($vehiculo->proximoAMantenimiento())
                        <div style="color: #fd7e14; font-weight: 500; margin-top: 0.5rem;">🟡 Próximo a mantenimiento</div>
                    @else
                        <div style="color: #28a745; font-weight: 500; margin-top: 0.5rem;">✅ En buen estado</div>
                    @endif
                </div>
            </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Fecha de Compra</label>
                <input type="date" name="fecha_compra" class="form-control"
                       value="{{ old('fecha_compra', $vehiculo->fecha_compra ? \Carbon\Carbon::parse($vehiculo->fecha_compra)->format('Y-m-d') : '') }}"
                       style="background-color: #333; border: 1px solid #555; color: #eee;">
            </div>
            <div class="col-md-6">
                <label class="form-label">Estado</label>
                <select name="estado" class="form-control" style="background-color: #333; border: 1px solid #555; color: #eee;">
                    <option value="activo" {{ old('estado', $vehiculo->estado) == 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="inactivo" {{ old('estado', $vehiculo->estado) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                    <option value="en mantenimiento" {{ old('estado', $vehiculo->estado) == 'en mantenimiento' ? 'selected' : '' }}>En mantenimiento</option>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Notas</label>
            <textarea name="notas" class="form-control" rows="3"
                      style="background-color: #333; border: 1px solid #555; color: #eee;">{{ old('notas', $vehiculo->notas ?? '') }}</textarea>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Actualizar Vehículo</button>
            <a href="{{ route('vehiculos.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection