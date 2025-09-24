<?php

namespace App\Http\Controllers;

use App\Models\Vehiculo;
use App\Models\Mantenimiento;
use Illuminate\Http\Request;

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
     * Guarda un nuevo mantenimiento y actualiza el kilometraje del vehículo si está completado.
     */
    public function store(Request $request)
    {
        // Validación básica
        $request->validate([
            'vehiculo_id' => 'required|exists:vehiculos,id',
            'tipo' => 'required|string|max:50',
            'descripcion' => 'nullable|string',
            'fecha' => 'required|date',
            'costo' => 'nullable|numeric|min:0',
            'proveedor' => 'nullable|string|max:255',
            'estado' => 'required|in:pendiente,en_proceso,completado,cancelado',
            'observaciones' => 'nullable|string',
        ]);

        $vehiculo = Vehiculo::find($request->vehiculo_id);
        if (!$vehiculo) {
            return redirect()->back()
                ->withErrors(['vehiculo_id' => 'El vehículo seleccionado no existe.'])
                ->withInput();
        }

        // Validación: evitar fechas futuras
        if ($request->fecha > now()->format('Y-m-d')) {
            return redirect()->back()
                ->withErrors(['fecha' => 'La fecha del mantenimiento no puede ser futura.'])
                ->withInput();
        }

        // Validación: no permitir km actual menor al último mantenimiento registrado
        if ($vehiculo->ultimo_mantenimiento_km !== null && 
            $vehiculo->kilometraje_actual < $vehiculo->ultimo_mantenimiento_km) {
            return redirect()->back()
                ->withErrors([
                    'kilometraje_actual' => "El kilometraje actual ({$vehiculo->kilometraje_actual} km) no puede ser menor al último mantenimiento ({$vehiculo->ultimo_mantenimiento_km} km)."
                ])
                ->withInput();
        }

        // Crear mantenimiento usando el kilometraje actual del vehículo
        $mantenimiento = Mantenimiento::create([
            'vehiculo_id' => $vehiculo->id,
            'tipo' => $request->tipo,
            'descripcion' => $request->descripcion,
            'fecha' => $request->fecha,
            'kilometraje' => $vehiculo->kilometraje_actual,
            'costo_real' => $request->costo ?? 0.0,
            'proveedor' => $request->proveedor,
            'estado' => $request->estado,
            'observaciones' => $request->observaciones,
        ]);

        // ✅ Solo si el mantenimiento está COMPLETADO, actualizamos el vehículo
        if ($mantenimiento->estado === 'completado') {
            $vehiculo->kilometraje_actual = $mantenimiento->kilometraje;
            $vehiculo->ultimo_mantenimiento_km = $mantenimiento->kilometraje;
            $vehiculo->save();
        }

        return redirect()
            ->route('vehiculos.show', $vehiculo->id)
            ->with('success', '✅ Mantenimiento registrado correctamente.');
    }

    /**
     * Muestra el formulario para editar un mantenimiento.
     */
    public function edit($id)
    {
        $mantenimiento = Mantenimiento::with('vehiculo')->findOrFail($id);
        return view('mantenimientos.edit', compact('mantenimiento'));
    }

    /**
     * Actualiza un mantenimiento existente.
     */
    public function update(Request $request, $id)
    {
        $mantenimiento = Mantenimiento::findOrFail($id);

        $request->validate([
            'tipo' => 'required|string|max:50',
            'descripcion' => 'nullable|string',
            'fecha' => 'required|date',
            'costo' => 'nullable|numeric|min:0',
            'proveedor' => 'nullable|string|max:255',
            'estado' => 'required|in:pendiente,en_proceso,completado,cancelado',
            'observaciones' => 'nullable|string',
        ]);

        $vehiculo = $mantenimiento->vehiculo;
        if (!$vehiculo) {
            return redirect()->back()
                ->withErrors(['vehiculo' => 'Vehículo no encontrado.'])
                ->withInput();
        }

        // Guardar valores anteriores antes de actualizar
        $anteriorEstado = $mantenimiento->estado;
        $anteriorKm = $mantenimiento->kilometraje;

        // Actualizar datos del mantenimiento
        $mantenimiento->update([
            'tipo' => $request->tipo,
            'descripcion' => $request->descripcion,
            'fecha' => $request->fecha,
            'costo_real' => $request->costo ?? 0.0,
            'proveedor' => $request->proveedor,
            'estado' => $request->estado,
            'observaciones' => $request->observaciones,
        ]);

        // ✅ Si pasa a estado "completado", o cambia el km siendo ya completado
        if ($mantenimiento->estado === 'completado') {
            // Validar que el km no sea menor al actual del vehículo
            if ($mantenimiento->kilometraje < $vehiculo->kilometraje_actual && $mantenimiento->kilometraje != $anteriorKm) {
                return redirect()->back()
                    ->withErrors([
                        'kilometraje' => 'El kilometraje del mantenimiento (' . $mantenimiento->kilometraje . ' km) no puede ser menor al actual del vehículo (' . $vehiculo->kilometraje_actual . ' km).'
                    ])
                    ->withInput();
            }

            // Actualizar el vehículo
            $vehiculo->kilometraje_actual = $mantenimiento->kilometraje;
            $vehiculo->ultimo_mantenimiento_km = $mantenimiento->kilometraje;
            $vehiculo->save();
        }

        return redirect()
            ->route('vehiculos.show', $mantenimiento->vehiculo_id)
            ->with('info', '✅ Mantenimiento actualizado correctamente.');
    }

    /**
     * Elimina un mantenimiento.
     */
    public function destroy($id)
    {
        $mantenimiento = Mantenimiento::findOrFail($id);
        $vehiculo_id = $mantenimiento->vehiculo_id;

        $vehiculo = $mantenimiento->vehiculo;

        // Opcional: si se elimina un mantenimiento "completado", podrías querer revertir
        // Pero por ahora, solo eliminamos
        $mantenimiento->delete();

        return redirect()
            ->route('vehiculos.show', $vehiculo_id)
            ->with('warning', '🗑️ Mantenimiento eliminado.');
    }
}