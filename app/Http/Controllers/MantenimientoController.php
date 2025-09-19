<?php

namespace App\Http\Controllers;

use App\Models\Vehiculo;
use App\Models\Mantenimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MantenimientoController extends Controller
{
    /**
     * Muestra el formulario para crear un nuevo mantenimiento.
     */
    public function create(Request $request)
    {
        $vehiculo_id = $request->route('id');
        $vehiculo = Vehiculo::findOrFail($vehiculo_id);

        return view('mantenimientos.create', compact('vehiculo'));
    }

    /**
     * Guarda un nuevo mantenimiento y actualiza el vehículo.
     */
    public function store(Request $request)
    {
        // Validación básica
        $request->validate([
            'vehiculo_id' => 'required|exists:vehiculos,id',
            'tipo' => 'required|string|max:255',
            'kilometraje' => 'required|integer|min:0',
            'fecha' => 'required|date',
            'costo' => 'nullable|numeric|min:0',
            'proveedor' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        // Validación avanzada: evitar km menor al último mantenimiento
        $vehiculo = Vehiculo::find($request->vehiculo_id);
        if ($vehiculo && $request->kilometraje < $vehiculo->ultimo_mantenimiento_km) {
            return redirect()->back()
                ->withErrors(['kilometraje' => "El kilometraje no puede ser menor al último mantenimiento ({$vehiculo->ultimo_mantenimiento_km} km)."])
                ->withInput();
        }

        // Crear el mantenimiento
        $mantenimiento = Mantenimiento::create($request->all());

        // Actualizar el vehículo
        $vehiculo->kilometraje_actual = $request->kilometraje;
        $vehiculo->ultimo_mantenimiento_km = $request->kilometraje; // ← ¡Se actualiza aquí!
        $vehiculo->save();

        return redirect()
            ->route('vehiculos.show', $vehiculo->id)
            ->with('success', '✅ Mantenimiento registrado correctamente. Kilometraje y referencia de mantenimiento actualizados.');
    }

    /**
     * Muestra el formulario para editar un mantenimiento.
     */
    public function edit($id)
    {
        $mantenimiento = Mantenimiento::findOrFail($id);
        return view('mantenimientos.edit', compact('mantenimiento'));
    }

    /**
     * Actualiza un mantenimiento existente.
     */
    public function update(Request $request, $id)
    {
        $mantenimiento = Mantenimiento::findOrFail($id);

        $request->validate([
            'tipo' => 'required|string|max:255',
            'kilometraje' => 'required|integer|min:0',
            'fecha' => 'required|date',
            'costo' => 'nullable|numeric|min:0',
            'proveedor' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        // Opcional: si permites editar km, podrías querer actualizar el vehículo
        // Pero normalmente no se cambia el km de un mantenimiento pasado
        $mantenimiento->update($request->all());

        return redirect()
            ->route('vehiculos.show', $mantenimiento->vehiculo_id)
            ->with('info', '✅ Mantenimiento actualizado.');
    }

    /**
     * Elimina un mantenimiento.
     */
    public function destroy($id)
    {
        $mantenimiento = Mantenimiento::findOrFail($id);
        $vehiculo_id = $mantenimiento->vehiculo_id;
        $mantenimiento->delete();

        return redirect()
            ->route('vehiculos.show', $vehiculo_id)
            ->with('warning', '🗑️ Mantenimiento eliminado.');
    }
}