<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Listar todos los clientes.
     */
    public function index()
    {
        $clientes = Cliente::orderBy('nombre')->get();
        return view('clientes.index', compact('clientes'));
    }

    /**
     * Mostrar formulario para crear cliente.
     */
    public function create()
    {
        return view('clientes.create');
    }

    /**
     * Guardar nuevo cliente.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'razon_social' => 'nullable|string|max:100',
            'cuit' => 'required|string|max:20|unique:clientes,cuit',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'direccion' => 'nullable|string|max:200',
            'localidad' => 'nullable|string|max:100',
            'provincia' => 'nullable|string|max:100',
            'tipo' => 'required|in:agrícola,construccion,industrial,transporte,otros',
            'notas' => 'nullable|string',
        ]);

        Cliente::create($request->all());

        return redirect()->route('clientes.index')
            ->with('success', '✅ Cliente creado correctamente.');
    }

    /**
     * Mostrar detalles de un cliente.
     */
    public function show($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('clientes.show', compact('cliente'));
    }

    /**
     * Mostrar formulario para editar cliente.
     */
    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('clientes.edit', compact('cliente'));
    }

    /**
     * Actualizar cliente.
     */
    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:100',
            'razon_social' => 'nullable|string|max:100',
            'cuit' => 'required|string|max:20|unique:clientes,cuit,' . $cliente->id,
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'direccion' => 'nullable|string|max:200',
            'localidad' => 'nullable|string|max:100',
            'provincia' => 'nullable|string|max:100',
            'tipo' => 'required|in:agrícola,construccion,industrial,transporte,otros',
            'notas' => 'nullable|string',
        ]);

        $cliente->update($request->all());

        return redirect()->route('clientes.index')
            ->with('info', '✅ Cliente actualizado.');
    }

    /**
     * Eliminar cliente.
     */
    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();

        return redirect()->route('clientes.index')
            ->with('warning', '🗑️ Cliente eliminado.');
    }
}