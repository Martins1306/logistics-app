<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\MovimientoInventario;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Listar todos los productos.
     */
    public function index()
    {
        $productos = Producto::all();
        return view('productos.index', compact('productos'));
    }

    /**
     * Mostrar formulario para crear.
     */
    public function create()
    {
        return view('productos.create');
    }

    /**
     * Guardar un nuevo producto.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'categoria' => 'required|in:construcción,frutas,hortalizas',
            'unidad' => 'required|in:kg,m³,unidad,bolsa,litro',
            'precio' => 'nullable|numeric|min:0',
            'stock_actual' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
        ]);

        Producto::create($request->all());

        return redirect()->route('productos.index')
            ->with('success', '✅ Producto agregado correctamente.');
    }

    /**
     * Mostrar detalles de un producto + movimientos
     */
    public function show($id)
    {
        // 🔁 Cargar producto FRESKO desde la base
        $producto = Producto::findOrFail($id);
    
        $movimientos = MovimientoInventario::where('producto_id', $id)
            ->with('referencia', 'usuario')
            ->latest()
            ->get();
    
        return view('productos.show', compact('producto', 'movimientos'));
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit($id)
    {
        $producto = Producto::findOrFail($id);
        return view('productos.edit', compact('producto'));
    }

    /**
     * Actualizar un producto.
     */
    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'categoria' => 'required|in:construcción,frutas,hortalizas',
            'unidad' => 'required|in:kg,m³,unidad,bolsa,litro',
            'precio' => 'nullable|numeric|min:0',
            'stock_actual' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
        ]);

        $producto->update($request->all());

        return redirect()->route('productos.index')
            ->with('success', '✅ Producto actualizado.');
    }

    /**
     * Eliminar un producto.
     */
    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();

        return redirect()->route('productos.index')
            ->with('success', '🗑️ Producto eliminado.');
    }
    /**
 * Ajustar manualmente el stock de un producto.
 */
public function ajustarStock(Request $request, $id)
{
    $producto = Producto::findOrFail($id);

    $request->validate([
        'tipo' => 'required|in:entrada,salida',
        'cantidad' => 'required|integer|min:1',
        'motivo' => 'required|string|max:255',
    ], [
        'tipo.required' => 'Debe seleccionar el tipo de ajuste.',
        'cantidad.required' => 'La cantidad es obligatoria.',
        'cantidad.min' => 'La cantidad debe ser al menos 1.',
        'motivo.required' => 'El motivo es obligatorio.',
    ]);

    $cantidad = $request->input('cantidad');

    if ($request->input('tipo') === 'entrada') {
        // Aumentar stock
        $producto->increment('stock_actual', $cantidad);
    } else {
        // Salida: verificar que haya suficiente stock
        if ($producto->stock_actual < $cantidad) {
            return back()->withErrors([
                'cantidad' => 'No hay suficiente stock disponible para realizar esta salida.'
            ])->withInput();
        }
        $producto->decrement('stock_actual', $cantidad);
    }

    // Registrar movimiento
    MovimientoInventario::create([
        'producto_id' => $producto->id,
        'tipo' => $request->input('tipo'),
        'cantidad' => $cantidad,
        'motivo' => $request->input('motivo'),
        'referencia_id' => null,
        'referencia_tipo' => null,
        'usuario_id' => auth()->check() ? auth()->id() : null,
    ]);

    return redirect()->route('productos.show', $producto->id)
        ->with('success', '✅ Stock ajustado correctamente.');
}
}