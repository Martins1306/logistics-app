@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Detalles del Producto</h3>
    </div>
    <div class="card-body">
        <p><strong>Nombre:</strong> {{ $producto->nombre }}</p>

        <p><strong>Categoría:</strong>
            @if($producto->categoria == 'construcción')
                <span class="badge bg-secondary">Construcción</span>
            @elseif($producto->categoria == 'frutas')
                <span class="badge bg-success">Frutas</span>
            @else
                <span class="badge bg-warning text-dark">Hortalizas</span>
            @endif
        </p>

        <p><strong>Unidad de medida:</strong> 
            <strong>{{ $producto->unidad }}</strong>
        </p>

        <p><strong>Precio:</strong> 
            @if($producto->precio)
                <strong>$ {{ number_format($producto->precio, 2, ',', '.') }}</strong>
            @else
                <em class="text-muted">No definido</em>
            @endif
        </p>

        <p><strong>Registrado el:</strong> 
            {{ \Carbon\Carbon::parse($producto->created_at)->format('d/m/Y H:i') }}
        </p>
    </div>
    <div class="card-footer">
        <a href="{{ route('productos.edit', $producto) }}" class="btn btn-warning">Editar</a>
        <a href="{{ route('productos.index') }}" class="btn btn-secondary">Volver al listado</a>
    </div>
</div>
@endsection