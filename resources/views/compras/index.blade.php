@extends('layouts.app')

@section('content')
<div class="p-3">
    <h2><i class="fas fa-shopping-cart text-success"></i> Compras</h2>

    <!-- Búsqueda -->
    <div class="mb-3 d-flex flex-wrap gap-2 align-items-center">
        <a href="{{ route('compras.create') }}" class="btn btn-sm btn-success">
            <i class="fas fa-plus-circle"></i> Nueva Compra
        </a>
    </div>

    <!-- Tabla -->
    <div style="overflow-y: auto; max-height: 60vh; border-radius: 0.375rem; border: 1px solid #444;">
        <table class="table table-dark table-striped table-sm" style="margin: 0;">
            <thead style="background-color: #000;">
                <tr>
                    <th>ID</th>
                    <th>Proveedor</th>
                    <th>Fecha</th>
                    <th>Factura</th>
                    <th>Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($compras as $c)
                    <tr>
                        <td>{{ $c->id }}</td>
                        <td>{{ $c->proveedor->nombre }}</td>
                        <td>{{ \Carbon\Carbon::parse($c->fecha_compra)->format('d/m/Y') }}</td>
                        <td>{{ $c->numero_factura ?? '–' }}</td>
                        <td>${{ number_format($c->total, 2, ',', '.') }}</td>
                        <td>
                            <a href="{{ route('compras.show', $c->id) }}" class="btn btn-sm btn-info">Ver</a>
                            <a href="{{ route('compras.edit', $c->id) }}" 
                               class="btn btn-sm btn-warning">Editar</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection