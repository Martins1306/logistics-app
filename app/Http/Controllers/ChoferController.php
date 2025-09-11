<?php

namespace App\Http\Controllers;

use App\Models\Chofer;
use Illuminate\Http\Request;

class ChoferController extends Controller
{
    /**
     * Listar todos los choferes.
     */
    public function index()
    {
        $choferes = Chofer::all();
        return view('choferes.index', compact('choferes'));
    }

    /**
     * Mostrar formulario para crear.
     */
    public function create()
    {
        return view('choferes.create');
    }

    /**
     * Guardar un nuevo chofer.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'dni' => 'required|string|max:20|unique:choferes,dni',
            'licencia_tipo' => 'required|string|max:10',
            'licencia_vencimiento' => 'required|date|after:today',
            'telefono' => 'required|string|max:20',
            'observaciones' => 'nullable|string',
        ]);

        Chofer::create($request->all());

        return redirect()->route('choferes.index')
            ->with('success', '✅ Chofer registrado correctamente.');
    }

    /**
     * Mostrar detalles.
     */
    public function show($id)
    {
        $chofer = Chofer::findOrFail($id);
        return view('choferes.show', compact('chofer'));
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit($id)
    {
        $chofer = Chofer::findOrFail($id);
        return view('choferes.edit', compact('chofer'));
    }

    /**
     * Actualizar chofer.
     */
    public function update(Request $request, $id)
    {
        $chofer = Chofer::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'dni' => 'required|string|max:20|unique:choferes,dni,' . $id,
            'licencia_tipo' => 'required|string|max:10',
            'licencia_vencimiento' => 'required|date|after:today',
            'telefono' => 'required|string|max:20',
            'observaciones' => 'nullable|string',
        ]);

        $chofer->update($request->all());

        return redirect()->route('choferes.index')
            ->with('success', '✅ Chofer actualizado correctamente.');
    }

    /**
     * Eliminar chofer.
     */
    public function destroy($id)
    {
        $chofer = Chofer::findOrFail($id);
        $chofer->delete();

        return redirect()->route('choferes.index')
            ->with('success', '🗑️ Chofer eliminado correctamente.');
    }
}