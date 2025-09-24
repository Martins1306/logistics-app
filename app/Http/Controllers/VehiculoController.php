<?php

namespace App\Http\Controllers;

use App\Models\Vehiculo;
use Illuminate\Http\Request;

class VehiculoController extends Controller
{
    /**
     * Listar todos los vehículos.
     */
    public function index()
    {
        $vehiculos = Vehiculo::orderBy('marca')->orderBy('modelo')->get();
        return view('vehiculos.index', compact('vehiculos'));
    }

    /**
     * Mostrar el formulario para crear un nuevo vehículo.
     */
    public function create()
    {
        return view('vehiculos.create');
    }

    /**
     * Guardar un nuevo vehículo en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'patente' => 'required|string|max:10|unique:vehiculos,patente',
            'marca' => 'required|string|max:50',
            'modelo' => 'required|string|max:50',
            'tipo' => 'required|in:camion,camioneta,semirremolque,acoplado,tractocamion',
            'capacidad_kg' => 'nullable|integer|min:100',
            'fecha_compra' => 'nullable|date',
            'ultimo_mantenimiento_km' => 'nullable|integer|min:0',
            'kilometraje_actual' => 'nullable|integer|min:0',
            'intervalo_mantenimiento' => 'nullable|integer|min:1000',
            'estado' => 'required|in:activo,inactivo,en mantenimiento',
            'notas' => 'nullable|string|max:1000',
        ], [
            'patente.unique' => 'Ya existe un vehículo con esa patente.',
            'tipo.in' => 'El tipo de vehículo seleccionado no es válido.',
        ]);

        $vehiculo = Vehiculo::create($request->all());

        return redirect()->route('vehiculos.show', $vehiculo->id)
            ->with('success', '✅ Vehículo agregado correctamente.');
    }

    /**
     * Mostrar los detalles de un vehículo.
     */
    public function show($id)
    {
        $vehiculo = Vehiculo::with(['viajes.chofer', 'mantenimientos'])->findOrFail($id);
        return view('vehiculos.show', compact('vehiculo'));
        
        $vehiculo = Vehiculo::with(['viajes', 'mantenimientos'])->findOrFail($id);
    }

    
    /**
     * Mostrar el formulario para editar un vehículo.
     */
    public function edit($id)
    {
        $vehiculo = Vehiculo::findOrFail($id);
        return view('vehiculos.edit', compact('vehiculo'));
    }

    /**
     * Actualizar un vehículo en la base de datos.
     */
    public function update(Request $request, $id)
    {
        $vehiculo = Vehiculo::findOrFail($id);

        $request->validate([
            'patente' => 'required|string|max:10|unique:vehiculos,patente,' . $id,
            'marca' => 'required|string|max:50',
            'modelo' => 'required|string|max:50',
            'tipo' => 'required|in:camion,camioneta,semirremolque,acoplado,tractocamion',
            'capacidad_kg' => 'nullable|integer|min:100',
            'fecha_compra' => 'nullable|date',
            'ultimo_mantenimiento_km' => 'nullable|integer|min:0',
            'kilometraje_actual' => 'nullable|integer|min:0',
            'intervalo_mantenimiento' => 'nullable|integer|min:1000',
            'estado' => 'required|in:activo,inactivo,en mantenimiento',
            'notas' => 'nullable|string|max:1000',
        ], [
            'patente.unique' => 'Ya existe un vehículo con esa patente.',
            'tipo.in' => 'El tipo de vehículo seleccionado no es válido.',
        ]);

        $vehiculo->update($request->all());

        return redirect()->route('vehiculos.show', $vehiculo->id)
            ->with('info', '✅ Vehículo actualizado correctamente.');
    }

    /**
     * Eliminar un vehículo.
     */
    public function destroy($id)
    {
        $vehiculo = Vehiculo::findOrFail($id);

        // Prevenir eliminación si tiene viajes asociados
        if ($vehiculo->viajes()->count() > 0) {
            return redirect()->route('vehiculos.index')
                ->withErrors('No se puede eliminar un vehículo con viajes registrados.');
        }

        $vehiculo->delete();

        return redirect()->route('vehiculos.index')
            ->with('warning', '🗑️ Vehículo eliminado correctamente.');
    }
}