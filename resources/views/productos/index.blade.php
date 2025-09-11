@extends('layouts.app')

@section('content')
<h1>Productos</h1>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<a href="{{ route('productos.create') }}" class="btn btn-success mb-3">➕ Nuevo Producto</a>

<table class="table table-bordered table-striped">
    <thead class="table-light">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Categoría</th>
            <th>Unidad</th>
            <th>Precio</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($productos as $p)
        <tr>
            <td>{{ $p->id }}</td>
            <td><strong>{{ $p->nombre }}</strong></td>
            <td>
                <span class="badge 
                    @if($p->categoria == 'construcción') bg-secondary
                    @elseif($p->categoria == 'frutas') bg-success
                    @else bg-warning text-dark @endif">
                    {{ ucfirst($p->categoria) }}
                </span>
            </td>
            <td class="text-center">{{ $p->unidad }}</td>
            <td class="text-end">{{ $p->precio ? '$ ' . number_format($p->precio, 2, ',', '.') : '—' }}</td>
            <td>
                <a href="{{ route('productos.show', $p) }}" class="btn btn-sm btn-info text-white">Ver</a>
                <a href="{{ route('productos.edit', $p) }}" class="btn btn-sm btn-warning">Editar</a>
                <form action="{{ route('productos.destroy', $p) }}" method="POST" style="display: inline">
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
            <td colspan="6" class="text-center text-muted">No hay productos registrados.</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection