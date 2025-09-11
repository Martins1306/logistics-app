<?php

namespace App\Http\Controllers;

use App\Models\Producto;
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
        ]);

        Producto::create($request->all());

        return redirect()->route('productos.index')
            ->with('success', '✅ Producto agregado correctamente.');
    }

    /**
     * Mostrar detalles de un producto.
     */
    public function show($id)
    {
        $producto = Producto::findOrFail($id);
        return view('productos.show', compact('producto'));
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
}