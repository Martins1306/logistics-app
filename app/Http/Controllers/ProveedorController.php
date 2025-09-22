<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    /**
     * Listar todos los proveedores.
     */
    public function index()
    {
        $proveedores = Proveedor::orderBy('nombre')->get();
        return view('proveedores.index', compact('proveedores'));
    }

    /**
     * Mostrar formulario para crear.
     */
    public function create()
    {
        return view('proveedores.create');
    }

    /**
     * Guardar un nuevo proveedor.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'contacto' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'calle' => 'nullable|string|max:255',
            'numero' => 'nullable|string|max:10',
            'codigo_postal' => 'nullable|string|max:10',
            'localidad' => 'nullable|string|max:100',
            'partido' => 'nullable|string|max:100',
            'provincia' => 'nullable|string|max:100',
            'notas' => 'nullable|string',
        ]);

        Proveedor::create($request->all());

        return redirect()->route('proveedores.index')
            ->with('success', '✅ Proveedor agregado correctamente.');
    }

    /**
     * Mostrar detalles.
     */
    public function show($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        return view('proveedores.show', compact('proveedor'));
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        return view('proveedores.edit', compact('proveedor'));
    }

    /**
     * Actualizar proveedor.
     */
    public function update(Request $request, $id)
    {
        $proveedor = Proveedor::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'contacto' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'calle' => 'nullable|string|max:255',
            'numero' => 'nullable|string|max:10',
            'codigo_postal' => 'nullable|string|max:10',
            'localidad' => 'nullable|string|max:100',
            'partido' => 'nullable|string|max:100',
            'provincia' => 'nullable|string|max:100',
            'notas' => 'nullable|string',
        ]);

        $proveedor->update($request->all());

        return redirect()->route('proveedores.index')
            ->with('info', '✅ Proveedor actualizado correctamente.');
    }

    /**
     * Eliminar proveedor.
     */
    public function destroy($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        
        if ($proveedor->compras()->count() > 0) {
            return redirect()->route('proveedores.index')
                ->withErrors('No se puede eliminar un proveedor con compras registradas.');
        }

        $proveedor->delete();

        return redirect()->route('proveedores.index')
            ->with('warning', '🗑️ Proveedor eliminado correctamente.');
    }
}