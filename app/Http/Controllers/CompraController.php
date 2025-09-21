<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\MovimientoInventario;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CompraController extends Controller
{
    /**
     * Listar todas las compras.
     */
    public function index()
    {
        $compras = Compra::with('proveedor')
            ->orderBy('fecha_compra', 'desc')
            ->get();

        return view('compras.index', compact('compras'));
    }

    /**
     * Mostrar formulario para crear una compra.
     */
    public function create()
    {
        $proveedores = Proveedor::orderBy('nombre')->get();
        $productos = Producto::orderBy('nombre')->get();

        return view('compras.create', compact('proveedores', 'productos'));
    }

    /**
     * Guardar una nueva compra.
     */
    public function store(Request $request)
    {
        $request->validate([
            'proveedor_id' => 'required|exists:proveedores,id',
            'fecha_compra' => 'required|date',
            'numero_factura' => 'nullable|string|max:50',
            'metodo_pago' => 'nullable|string|max:50',
            'producto_id' => 'required|array|min:1',
            'producto_id.*' => 'exists:productos,id',
            'cantidad' => 'required|array',
            'cantidad.*' => 'integer|min:1',
            'precio_unitario' => 'required|array',
            'precio_unitario.*' => 'numeric|min:0',
        ], [
            'proveedor_id.required' => 'Debe seleccionar un proveedor.',
            'producto_id.required' => 'Debe agregar al menos un producto.',
            'cantidad.*.min' => 'La cantidad debe ser al menos 1.',
            'precio_unitario.*.min' => 'El precio no puede ser negativo.',
        ]);

        // Calcular total
        $total = 0;
        $cantidades = $request->input('cantidad');
        $precios = $request->input('precio_unitario');

        foreach ($cantidades as $index => $cant) {
            $total += $cant * $precios[$index];
        }

        // Crear compra
        $compra = Compra::create([
            'proveedor_id' => $request->proveedor_id,
            'fecha_compra' => $request->fecha_compra,
            'numero_factura' => $request->numero_factura,
            'total' => $total,
            'metodo_pago' => $request->metodo_pago,
            'estado' => 'recibido',
        ]);

        // Detalles y ajuste de stock
        $productoIds = $request->input('producto_id');

        foreach ($productoIds as $index => $productoId) {
            $producto = Producto::find($productoId);
            $cantidad = $cantidades[$index];
            $precioUnitario = $precios[$index];

            if (!$producto || $cantidad <= 0) continue;

            // Aumentar stock
            $producto->increment('stock_actual', $cantidad);

            // Registrar movimiento
            MovimientoInventario::create([
                'producto_id' => $producto->id,
                'tipo' => 'entrada',
                'cantidad' => $cantidad,
                'motivo' => 'Compra #' . $compra->id . ' - Factura: ' . ($compra->numero_factura ?? 'Sin nro'),
                'referencia_id' => $compra->id,
                'referencia_tipo' => Compra::class,
                'usuario_id' => auth()->check() ? auth()->id() : null,
            ]);

            // Guardar detalle
            DetalleCompra::create([
                'compra_id' => $compra->id,
                'producto_id' => $producto->id,
                'cantidad' => $cantidad,
                'precio_unitario' => $precioUnitario,
            ]);
        }

        return redirect()->route('compras.show', $compra->id)
            ->with('success', '✅ Compra registrada correctamente.');
    }

    /**
     * Mostrar formulario para editar una compra.
     */
    public function edit($id)
    {
        $compra = Compra::with('detalles.producto', 'proveedor')->findOrFail($id);
        $proveedores = Proveedor::orderBy('nombre')->get();
        $productos = Producto::orderBy('nombre')->get();

        return view('compras.edit', compact('compra', 'proveedores', 'productos'));
    }

    /**
     * Actualizar una compra existente.
     */
    public function update(Request $request, $id)
    {
        $compra = Compra::with('detalles')->findOrFail($id);

        $request->validate([
            'proveedor_id' => 'required|exists:proveedores,id',
            'fecha_compra' => 'required|date',
            'numero_factura' => 'nullable|string|max:50',
            'metodo_pago' => 'nullable|string|max:50',
            'producto_id' => 'required|array|min:1',
            'producto_id.*' => 'exists:productos,id',
            'cantidad' => 'required|array',
            'cantidad.*' => 'integer|min:1',
            'precio_unitario' => 'required|array',
            'precio_unitario.*' => 'numeric|min:0',
        ], [
            'proveedor_id.required' => 'Debe seleccionar un proveedor.',
            'producto_id.required' => 'Debe agregar al menos un producto.',
        ]);

        // Calcular nuevo total
        $total = 0;
        $productoIds = $request->input('producto_id');
        $cantidades = $request->input('cantidad');
        $precios = $request->input('precio_unitario');

        foreach ($cantidades as $index => $cant) {
            $total += $cant * $precios[$index];
        }

        // Actualizar compra
        $compra->update([
            'proveedor_id' => $request->proveedor_id,
            'fecha_compra' => $request->fecha_compra,
            'numero_factura' => $request->numero_factura,
            'total' => $total,
            'metodo_pago' => $request->metodo_pago,
        ]);

        // === Ajustar stock por cambios ===
        $detallesActuales = $compra->detalles->keyBy('producto_id');

        foreach ($productoIds as $index => $productoId) {
            $producto = Producto::find($productoId);
            if (!$producto) continue;

            $nuevaCantidad = $cantidades[$index];
            $detalleAnterior = $detallesActuales->get($productoId);

            if ($detalleAnterior) {
                $diferencia = $nuevaCantidad - $detalleAnterior->cantidad;

                if ($diferencia != 0) {
                    // Ajustar stock
                    if ($diferencia > 0) {
                        $producto->increment('stock_actual', $diferencia);
                    } else {
                        $producto->decrement('stock_actual', abs($diferencia));
                    }

                    // Registrar movimiento
                    MovimientoInventario::create([
                        'producto_id' => $producto->id,
                        'tipo' => $diferencia > 0 ? 'entrada' : 'salida',
                        'cantidad' => abs($diferencia),
                        'motivo' => 'Ajuste en edición de compra #' . $compra->id,
                        'referencia_id' => $compra->id,
                        'referencia_tipo' => Compra::class,
                        'usuario_id' => auth()->check() ? auth()->id() : null,
                    ]);
                }

                // Actualizar detalle
                $detalleAnterior->update([
                    'cantidad' => $nuevaCantidad,
                    'precio_unitario' => $precios[$index],
                ]);
            } else {
                // Nuevo producto
                $producto->increment('stock_actual', $nuevaCantidad);

                MovimientoInventario::create([
                    'producto_id' => $producto->id,
                    'tipo' => 'entrada',
                    'cantidad' => $nuevaCantidad,
                    'motivo' => 'Agregado en edición de compra #' . $compra->id,
                    'referencia_id' => $compra->id,
                    'referencia_tipo' => Compra::class,
                    'usuario_id' => auth()->check() ? auth()->id() : null,
                ]);

                DetalleCompra::create([
                    'compra_id' => $compra->id,
                    'producto_id' => $producto->id,
                    'cantidad' => $nuevaCantidad,
                    'precio_unitario' => $precios[$index],
                ]);
            }
        }

        // Eliminar productos removidos
        $idsEliminados = $detallesActuales->keys()->diff($productoIds);
        foreach ($idsEliminados as $productoId) {
            $detalle = $detallesActuales[$productoId];
            $producto = Producto::find($productoId);

            if ($producto && $detalle) {
                $producto->decrement('stock_actual', $detalle->cantidad);

                MovimientoInventario::create([
                    'producto_id' => $producto->id,
                    'tipo' => 'salida',
                    'cantidad' => $detalle->cantidad,
                    'motivo' => 'Eliminado en edición de compra #' . $compra->id,
                    'referencia_id' => $compra->id,
                    'referencia_tipo' => Compra::class,
                    'usuario_id' => auth()->check() ? auth()->id() : null,
                ]);

                $detalle->delete();
            }
        }

        return redirect()->route('compras.show', $compra->id)
            ->with('info', '✅ Compra actualizada correctamente.');
    }

    /**
     * Mostrar detalles de una compra.
     */
    public function show($id)
    {
        $compra = Compra::with('proveedor', 'detalles.producto')->findOrFail($id);
        return view('compras.show', compact('compra'));
    }

    /**
     * Eliminar una compra.
     */
    public function destroy($id)
    {
        return back()->with('warning', 'Eliminación de compras no permitida.');
    }
}