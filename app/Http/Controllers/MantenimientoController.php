<?php

namespace App\Http\Controllers;

use App\Models\Mantenimiento;
use App\Models\Vehiculo;
use Illuminate\Http\Request;

class MantenimientoController extends Controller
{
    public function create($vehiculoId)
    {
        $vehiculo = Vehiculo::findOrFail($vehiculoId);
        return view('mantenimientos.create', compact('vehiculo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vehiculo_id' => 'required|exists:vehiculos,id',
            'tipo' => 'required|string|max:50',
            'kilometraje' => 'required|integer|min:0',
            'fecha' => 'required|date',
            'costo' => 'nullable|numeric|min:0',
            'descripcion' => 'nullable|string',
            'proveedor' => 'nullable|string|max:255',
        ]);

        Mantenimiento::create($request->all());

        return redirect()->route('vehiculos.show', $request->vehiculo_id)
            ->with('success', '✅ Mantenimiento registrado correctamente.');
    }

    public function edit($id)
    {
        $mantenimiento = Mantenimiento::findOrFail($id);
        return view('mantenimientos.edit', compact('mantenimiento'));
    }

    public function update(Request $request, $id)
    {
        $mantenimiento = Mantenimiento::findOrFail($id);

        $request->validate([
            'tipo' => 'required|string|max:50',
            'kilometraje' => 'required|integer|min:0',
            'fecha' => 'required|date',
            'costo' => 'nullable|numeric|min:0',
            'descripcion' => 'nullable|string',
            'proveedor' => 'nullable|string|max:255',
        ]);

        $mantenimiento->update($request->all());

        return redirect()->route('vehiculos.show', $mantenimiento->vehiculo_id)
            ->with('success', '✅ Mantenimiento actualizado correctamente.');
    }

    public function destroy($id)
    {
        $mantenimiento = Mantenimiento::findOrFail($id);
        $vehiculoId = $mantenimiento->vehiculo_id;
        $mantenimiento->delete();

        return redirect()->route('vehiculos.show', $vehiculoId)
            ->with('success', '🗑️ Mantenimiento eliminado correctamente.');
    }
}