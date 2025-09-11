@extends('layouts.app')

@section('content')
<h1>Gastos</h1>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<a href="{{ route('gastos.create') }}" class="btn btn-success mb-3">➕ Nuevo Gasto</a>

<table class="table table-bordered table-striped">
    <thead class="table-light">
        <tr>
            <th>ID</th>
            <th>Vehículo</th>
            <th>Tipo</th>
            <th>Monto</th>
            <th>Fecha</th>
            <th>Descripción</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($gastos as $g)
        <tr>
            <td>{{ $g->id }}</td>
            <td><strong>{{ $g->vehiculo->patente }}</strong> ({{ $g->vehiculo->marca }})</td>
            <td><span class="badge bg-primary">{{ ucfirst($g->tipo) }}</span></td>
            <td class="text-end"><strong>$ {{ number_format($g->monto, 2, ',', '.') }}</strong></td>
            <td>{{ \Carbon\Carbon::parse($g->fecha)->format('d/m/Y') }}</td>
            <td>{{ Str::limit($g->descripcion, 50) }}</td>
            <td>
                <a href="{{ route('gastos.show', $g) }}" class="btn btn-sm btn-info text-white">Ver</a>
                 <a href="{{ route('gastos.edit', $g) }}" class="btn btn-sm btn-warning">Editar</a>
                 <form action="{{ route('gastos.destroy', $g) }}" method="POST" style="display: inline">
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
            <td colspan="7" class="text-center text-muted">No hay gastos registrados.</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection