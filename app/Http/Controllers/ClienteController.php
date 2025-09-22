<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
        // Limpiar CUIT: solo dígitos
        $cuit = preg_replace('/[^0-9]/', '', $request->input('cuit', ''));
        $request->merge(['cuit' => $cuit]);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'nullable|in:agricola,construccion,industrial,transporte,otros',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'cuit' => [
                'nullable',
                'string',
                'size:11', // Exactamente 11 dígitos
                Rule::unique('clientes', 'cuit') // ✅ Sin whereNull('deleted_at')
            ],
            'calle' => 'nullable|string|max:255',
            'numero' => 'nullable|string|max:10',
            'codigo_postal' => 'nullable|string|max:10',
            'localidad' => 'nullable|string|max:100',
            'partido' => 'nullable|string|max:100',
            'provincia' => 'nullable|string|max:100',
            'notas' => 'nullable|string',
        ], [
            'tipo.in' => 'El tipo seleccionado no es válido.',
            'cuit.size' => 'El CUIT debe tener 11 dígitos numéricos.',
            'cuit.unique' => 'Ya existe un cliente con ese CUIT.',
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

        // Limpiar CUIT: solo dígitos
        $cuit = preg_replace('/[^0-9]/', '', $request->input('cuit', ''));
        $request->merge(['cuit' => $cuit]);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'nullable|in:agricola,construccion,industrial,transporte,otros',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'cuit' => [
                'nullable',
                'string',
                'size:11',
                Rule::unique('clientes', 'cuit')
                    ->ignore($cliente->id) // ✅ Ignora el cliente actual
                // ❌ No usar ->whereNull('deleted_at') si no tenés SoftDeletes
            ],
            'calle' => 'nullable|string|max:255',
            'numero' => 'nullable|string|max:10',
            'codigo_postal' => 'nullable|string|max:10',
            'localidad' => 'nullable|string|max:100',
            'partido' => 'nullable|string|max:100',
            'provincia' => 'nullable|string|max:100',
            'notas' => 'nullable|string',
        ], [
            'tipo.in' => 'El tipo seleccionado no es válido.',
            'cuit.size' => 'El CUIT debe tener 11 dígitos numéricos.',
            'cuit.unique' => 'Ya existe otro cliente con ese CUIT.',
        ]);

        $cliente->update($request->all());

        return redirect()->route('clientes.index')
            ->with('info', '✅ Cliente actualizado correctamente.');
    }

    /**
     * Eliminar cliente.
     */
    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();

        return redirect()->route('clientes.index')
            ->with('warning', '🗑️ Cliente eliminado correctamente.');
    }
}