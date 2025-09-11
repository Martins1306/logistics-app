@extends('layouts.app')

@section('content')
<h1>Choferes</h1>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<a href="{{ route('choferes.create') }}" class="btn btn-success btn-sm mb-3">➕ Nuevo Chofer</a>

<table class="table table-dark table-striped table-hover">
    <thead>
        <tr>
            <th>ID</th>
            <th>Chofer</th>
            <th>DNI</th>
            <th>Licencia</th>
            <th>Vencimiento</th>
            <th>Teléfono</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($choferes as $c)
        <tr>
            <td>{{ $c->id }}</td>
            <td>
                <strong>{{ $c->nombre }} {{ $c->apellido }}</strong>
                @if($c->licencia_vencimiento < now())
                    <br><span class="badge bg-danger">Licencia vencida</span>
                @endif
            </td>
            <td>{{ $c->dni }}</td>
            <td>{{ $c->licencia_tipo }}</td>
            <td>{{ \Carbon\Carbon::parse($c->licencia_vencimiento)->format('d/m/Y') }}</td>
            <td>{{ $c->telefono }}</td>
            <td>
                <a href="{{ route('choferes.show', $c) }}" class="btn btn-sm btn-info text-white">Ver</a>
                <a href="{{ route('choferes.edit', $c) }}" class="btn btn-sm btn-warning">Editar</a>
                <form action="{{ route('choferes.destroy', $c) }}" method="POST" style="display: inline">
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
            <td colspan="7" class="text-center text-muted">No hay choferes registrados.</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection