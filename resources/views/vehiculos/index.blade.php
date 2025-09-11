@extends('layouts.app')

@section('content')
<h1>Listado de Vehículos</h1>

<!-- Mensaje de éxito -->
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<a href="{{ route('vehiculos.create') }}" class="btn btn-success mb-3">➕ Nuevo Vehículo</a>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Patente</th>
            <th>Marca</th>
            <th>Modelo</th>
            <th>Tipo</th>
            <th>Capacidad</th>
            <th>Fecha Compra</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($vehiculos as $v)
        <tr>
            <td>{{ $v->id }}</td>
            <td><strong>{{ $v->patente }}</strong></td>
            <td>{{ $v->marca }}</td>
            <td>{{ $v->modelo }}</td>
            <td>{{ ucfirst($v->tipo) }}</td>
            <td class="text-end">{{ number_format($v->capacidad_kg, 0, ',', '.') }} kg</td>
            <td>{{ \Carbon\Carbon::parse($v->fecha_compra)->format('d/m/Y') }}</td>
            <td>
                <!-- Ver -->
                <a href="{{ route('vehiculos.show', $v) }}" class="btn btn-sm btn-info text-white">Ver</a>

                <!-- Editar -->
                <a href="{{ route('vehiculos.edit', $v) }}" class="btn btn-sm btn-warning text-dark">Editar</a>

                <!-- Eliminar -->
                <form action="{{ route('vehiculos.destroy', $v) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger"
                            onclick="return confirm('¿Eliminar el vehículo {{ $v->patente }}?')">
                        Eliminar
                    </button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" class="text-center text-muted">
                No hay vehículos registrados. <a href="{{ route('vehiculos.create') }}">Registra uno</a>.
            </td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection