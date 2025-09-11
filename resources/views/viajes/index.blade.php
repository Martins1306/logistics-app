@extends('layouts.app')

@section('content')
<h1>Viajes</h1>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<a href="{{ route('viajes.create') }}" class="btn btn-success mb-3">➕ Nuevo Viaje</a>

<table class="table table-bordered table-striped">
    <thead class="table-light">
        <tr>
            <th>ID</th>
            <th>Vehículo</th>
            <th>Chofer</th>
            <th>Origen → Destino</th>
            <th>Salida</th>
            <th>Llegada</th>
            <th>Km</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($viajes as $v)
        <tr>
        <td>
            @if($v->chofer)
            <strong>{{ $v->chofer->nombre }} {{ $v->chofer->apellido }}</strong><br>
            <small class="text-muted">
            DNI: {{ $v->chofer->dni }} | Lic: {{ $v->chofer->licencia_tipo }}
            </small>
            @if($v->chofer->licencia_vencimiento < now())
            <br><span class="badge bg-danger">Licencia vencida</span>
            @endif
        @else
            <em class="text-muted">—</em>
        @endif
    </td>
            <td>{{ $v->id }}</td>
            <td><strong>{{ $v->vehiculo->patente }}</strong></td>
            <td>{{ $v->origen }} → {{ $v->destino }}</td>
            <td>{{ \Carbon\Carbon::parse($v->fecha_salida)->format('d/m') }}</td>
            <td>{{ $v->fecha_llegada ? \Carbon\Carbon::parse($v->fecha_llegada)->format('d/m') : '—' }}</td>
            <td class="text-end">{{ number_format($v->kilometros, 0, '.', '.') }} km</td>
            <td>
                @if($v->estado == 'en curso')
                    <span class="badge bg-warning text-dark">En curso</span>
                @elseif($v->estado == 'completado')
                    <span class="badge bg-success">Completado</span>
                @else
                    <span class="badge bg-danger">Cancelado</span>
                @endif
            </td>
            <td>
                <a href="{{ route('viajes.show', $v) }}" class="btn btn-sm btn-info text-white">Ver</a>
                <a href="{{ route('viajes.edit', $v) }}" class="btn btn-sm btn-warning">Editar</a>
                <form action="{{ route('viajes.destroy', $v) }}" method="POST" style="display: inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar?')">
                        Eliminar
                    </button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" class="text-center text-muted">No hay viajes registrados.</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection