<?php

namespace App\Http\Controllers;

use App\Models\Vehiculo;
use App\Models\Viaje;
use App\Models\Mantenimiento;
use Illuminate\Http\Request;

class VehiculoController extends Controller
{
    /**
     * Listar todos los vehículos.
     */
    public function index()
    {
        $vehiculos = Vehiculo::all();
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
            'tipo' => 'required|in:camion,camioneta,bascula,acoplado,semiremolque,tolva',
            'capacidad_kg' => 'required|numeric|min:100',
            'fecha_compra' => 'required|date',
            'kilometraje_actual' => 'nullable|integer|min:0',
            'ultimo_mantenimiento_km' => 'nullable|integer|min:0',
            'intervalo_mantenimiento' => 'nullable|integer|min:1000',
        
        ]);

        Vehiculo::create($request->all());

        return redirect()->route('vehiculos.index')
            ->with('success', '✅ Vehículo agregado correctamente.');
    }

    /**
     * Mostrar los detalles de un vehículo, incluyendo viajes y mantenimientos.
     */
    public function show($id)
    {
        $vehiculo = Vehiculo::with(['viajes.chofer', 'mantenimientos'])->findOrFail($id);
        return view('vehiculos.show', compact('vehiculo'));
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
            'patente' => 'required|string|max:10|unique:vehiculos,patente',
            'marca' => 'required|string|max:50',
            'modelo' => 'required|string|max:50',
            'tipo' => 'required|in:camion,camioneta,bascula,acoplado,semiremolque,tolva',
            'capacidad_kg' => 'required|numeric|min:100',
            'fecha_compra' => 'required|date',
            'kilometraje_actual' => 'nullable|integer|min:0',
            'ultimo_mantenimiento_km' => 'nullable|integer|min:0',
            'intervalo_mantenimiento' => 'nullable|integer|min:1000',
        ]);
        $vehiculo->update($request->all());

        return redirect()->route('vehiculos.index')
            ->with('success', '✅ Vehículo actualizado correctamente.');
    }

    /**
     * Eliminar un vehículo.
     */
    public function destroy($id)
    {
        $vehiculo = Vehiculo::findOrFail($id);
        $vehiculo->delete();

        return redirect()->route('vehiculos.index')
            ->with('success', '🗑️ Vehículo eliminado correctamente.');
    }
}