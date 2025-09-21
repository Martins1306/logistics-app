@extends('layouts.app')

@section('content')
<div class="p-3">
    <h2><i class="fas fa-shopping-cart text-success"></i> Detalles de la Compra #{{ $compra->id }}</h2>

    <div style="background: #1a1a1a; padding: 1.5rem; border-radius: 0.375rem; border: 1px solid #444; margin-bottom: 1.5rem;">
        <p><strong>Proveedor:</strong> {{ $compra->proveedor->nombre }}</p>
        <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($compra->fecha_compra)->format('d/m/Y') }}</p>
        <p><strong>Factura:</strong> {{ $compra->numero_factura ?? '–' }}</p>
        <p><strong>Método de Pago:</strong> {{ $compra->metodo_pago ?? '–' }}</p>
        <p><strong>Total:</strong> <strong style="color: #4CAF50;">${{ number_format($compra->total, 2, ',', '.') }}</strong></p>
    </div>

    <h5>Productos Comprados</h5>
    <table class="table table-dark table-striped">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unit.</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($compra->detalles as $detalle)
                <tr>
                    <td>{{ $detalle->producto->nombre }}</td>
                    <td>{{ $detalle->cantidad }} {{ $detalle->producto->unidad }}</td>
                    <td>${{ number_format($detalle->precio_unitario, 2, ',', '.') }}</td>
                    <td>${{ number_format($detalle->cantidad * $detalle->precio_unitario, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('compras.edit', $compra->id) }}" class="btn btn-primary">Editar</a>
    <a href="{{ route('compras.index') }}" class="btn btn-secondary">Volver</a>

   </div>
@endsection