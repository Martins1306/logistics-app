<?php

namespace App\Http\Controllers;

use App\Models\Viaje;
use App\Models\Vehiculo;
use App\Models\Producto;
use App\Models\Chofer;
use Illuminate\Http\Request;

class ViajeController extends Controller
{
    /**
     * Listar todos los viajes con sus vehículos, productos y chofer.
     */
    public function index()
    {
        $viajes = Viaje::with('vehiculo', 'productos', 'chofer')->get();
        return view('viajes.index', compact('viajes'));
    }

    /**
     * Mostrar el formulario para crear un nuevo viaje.
     */
    public function create()
    {
        $vehiculos = Vehiculo::all();
        $productos = Producto::all();
        $choferes = Chofer::all();
        return view('viajes.create', compact('vehiculos', 'productos', 'choferes'));
    }

    /**
     * Guardar un nuevo viaje en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'vehiculo_id' => 'required|exists:vehiculos,id',
            'chofer_id' => 'required|exists:choferes,id',
            'origen' => 'required|string|max:255',
            'destino' => 'required|string|max:255',
            'fecha_salida' => 'required|date',
            'fecha_llegada' => 'nullable|date|after_or_equal:fecha_salida',
            'kilometros' => 'required|integer|min:1',
            'descripcion_carga' => 'required|string',
            'estado' => 'required|in:en curso,completado,cancelado',
        ]);

        // Validar que la licencia del chofer no esté vencida
        $chofer = Chofer::findOrFail($request->chofer_id);
        if ($chofer->licencia_vencimiento < now()) {
            return back()->withErrors([
                'chofer_id' => 'No se puede asignar un chofer con licencia vencida.'
            ])->withInput();
        }

        // Crear el viaje
        $viaje = Viaje::create($request->except(['producto_id', 'cantidad', 'notas']));

        // Sincronizar productos
        $this->syncProductosConViaje($viaje, $request);

        // Actualizar el kilometraje actual del vehículo
        $vehiculo = $viaje->vehiculo;
        $vehiculo->kilometraje_actual = ($vehiculo->kilometraje_actual ?? 0) + $viaje->kilometros;
        $vehiculo->save();

        return redirect()->route('viajes.index')
            ->with('success', '✅ Viaje registrado correctamente.');
    }

    /**
     * Mostrar los detalles de un viaje.
     */
    public function show($id)
    {
        $viaje = Viaje::with('vehiculo', 'productos', 'chofer')->findOrFail($id);
        return view('viajes.show', compact('viaje'));
    }

    /**
     * Mostrar el formulario para editar un viaje.
     */
    public function edit($id)
    {
        $viaje = Viaje::with('productos')->findOrFail($id);
        $vehiculos = Vehiculo::all();
        $productos = Producto::all();
        $choferes = Chofer::all();
        return view('viajes.edit', compact('viaje', 'vehiculos', 'productos', 'choferes'));
    }

    /**
     * Actualizar un viaje en la base de datos.
     */
    public function update(Request $request, $id)
    {
        $viaje = Viaje::findOrFail($id);

        $request->validate([
            'vehiculo_id' => 'required|exists:vehiculos,id',
            'chofer_id' => 'required|exists:choferes,id',
            'origen' => 'required|string|max:255',
            'destino' => 'required|string|max:255',
            'fecha_salida' => 'required|date',
            'fecha_llegada' => 'nullable|date|after_or_equal:fecha_salida',
            'kilometros' => 'required|integer|min:1',
            'descripcion_carga' => 'required|string',
            'estado' => 'required|in:en curso,completado,cancelado',
        ]);

        // Validar licencia del chofer
        $chofer = Chofer::findOrFail($request->chofer_id);
        if ($chofer->licencia_vencimiento < now()) {
            return back()->withErrors([
                'chofer_id' => 'No se puede asignar un chofer con licencia vencida.'
            ])->withInput();
        }

        // Guardar el kilometraje anterior del viaje
        $kilometrosAnteriores = $viaje->kilometros;

        // Actualizar el viaje
        $viaje->update($request->except(['producto_id', 'cantidad', 'notas']));

        // Sincronizar productos
        $this->syncProductosConViaje($viaje, $request);

        // Actualizar el kilometraje del vehículo
        $vehiculo = $viaje->vehiculo;
        $diferencia = $viaje->kilometros - $kilometrosAnteriores;
        $vehiculo->kilometraje_actual = ($vehiculo->kilometraje_actual ?? 0) + $diferencia;
        $vehiculo->save();

        return redirect()->route('viajes.index')
            ->with('success', '✅ Viaje actualizado correctamente.');
    }

    /**
     * Eliminar un viaje.
     */
    public function destroy($id)
    {
        $viaje = Viaje::findOrFail($id);
        $vehiculo = $viaje->vehiculo;

        // Restar el kilometraje del viaje eliminado
        if ($vehiculo->kilometraje_actual) {
            $vehiculo->kilometraje_actual = max(0, $vehiculo->kilometraje_actual - $viaje->kilometros);
            $vehiculo->save();
        }

        $viaje->delete();

        return redirect()->route('viajes.index')
            ->with('success', '🗑️ Viaje eliminado correctamente.');
    }

    /**
     * Sincronizar productos con el viaje
     */
    private function syncProductosConViaje($viaje, $request)
    {
        $productoIds = $request->input('producto_id', []);
        $cantidades = $request->input('cantidad', []);
        $notas = $request->input('notas', []);

        $syncData = [];
        foreach ($productoIds as $index => $productoId) {
            if ($productoId) {
                $syncData[$productoId] = [
                    'cantidad' => $cantidades[$index] ?? 0,
                    'notas' => $notas[$index] ?? null,
                    'updated_at' => now(),
                ];
            }
        }

        $viaje->productos()->sync($syncData);
    }
}