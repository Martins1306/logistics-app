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
        // Validar los datos del formulario
        $request->validate([
            'patente' => 'required|unique:vehiculos',
            'marca' => 'required|string|max:100',
            'modelo' => 'required|string|max:100',
            'tipo' => 'required|in:camión,camioneta,bascula,acoplado,semirremolque,tolva', // ✅ Incluye "bascula"
            'capacidad_kg' => 'required|integer|min:1',
            'fecha_compra' => 'required|date',
        ]);

        // Crear el vehículo
        Vehiculo::create($request->all());

        // Redirigir con mensaje de éxito
        return redirect()->route('vehiculos.index')
            ->with('success', '✅ Vehículo agregado correctamente.');
    }

    /**
     * Mostrar los detalles de un vehículo.
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
            'patente' => 'required|unique:vehiculos,patente,' . $id,
            'marca' => 'required|string|max:100',
            'modelo' => 'required|string|max:100',
            'tipo' => 'required|in:camión,camioneta,bascula,acoplado,semirremolque,tolva', // ✅ Mismo que en store
            'capacidad_kg' => 'required|integer|min:1',
            'fecha_compra' => 'required|date',
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