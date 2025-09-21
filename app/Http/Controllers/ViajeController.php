<?php

namespace App\Http\Controllers;

use App\Models\Viaje;
use App\Models\Vehiculo;
use App\Models\Producto;
use App\Models\Chofer;
use App\Models\Cliente;
use App\Models\MovimientoInventario; // ← Nuevo: para registrar salidas
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ViajeController extends Controller
{
    /**
     * Listar todos los viajes con sus relaciones.
     */
    public function index()
    {
        $viajes = Viaje::with('vehiculo', 'chofer', 'cliente', 'productos')
                       ->orderBy('fecha_salida', 'desc')
                       ->get();
        return view('viajes.index', compact('viajes'));
    }

    /**
     * Mostrar formulario para crear un nuevo viaje.
     */
    public function create()
    {
        $vehiculos = Vehiculo::orderBy('patente')->get();
        $choferes = Chofer::orderBy('nombre')->get();
        $productos = Producto::orderBy('nombre')->get();
        $clientes = Cliente::orderBy('nombre')->get();

        return view('viajes.create', compact('vehiculos', 'choferes', 'productos', 'clientes'));
    }

    /**
     * Guardar un nuevo viaje.
     */
    public function store(Request $request)
    {
        // Validación principal
        $validator = Validator::make($request->all(), [
            'vehiculo_id' => 'required|exists:vehiculos,id',
            'chofer_id' => 'required|exists:choferes,id',
            'cliente_id' => 'nullable|exists:clientes,id',
            'origen' => 'required|string|max:255',
            'destino' => 'required|string|max:255',
            'fecha_salida' => 'required|date',
            'fecha_llegada' => 'nullable|date|after_or_equal:fecha_salida',
            'kilometros' => 'required|integer|min:1',
            'descripcion_carga' => 'required|string|max:1000',
            'estado' => 'required|in:en curso,completado,cancelado',
            'tipo' => 'nullable|string|max:255',

            'producto_id' => 'required|array|min:1',
            'producto_id.*' => 'exists:productos,id',
            'cantidad' => 'required|array',
            'cantidad.*' => 'nullable|integer|min:0',
            'notas' => 'nullable|array',
            'notas.*' => 'nullable|string|max:255',
        ], [
            'producto_id.required' => 'Debe seleccionar al menos un producto.',
            'producto_id.*.exists' => 'Uno de los productos seleccionados no es válido.',
            'vehiculo_id.required' => 'Debe seleccionar un vehículo.',
            'chofer_id.required' => 'Debe seleccionar un chofer.',
            'origen.required' => 'El origen es obligatorio.',
            'destino.required' => 'El destino es obligatorio.',
            'kilometros.required' => 'Los kilómetros son obligatorios.',
            'descripcion_carga.required' => 'La descripción de carga es obligatoria.',
            'estado.required' => 'El estado del viaje es obligatorio.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Verificar licencia del chofer
        $chofer = Chofer::findOrFail($request->chofer_id);
        if ($chofer->licencia_vencimiento && $chofer->licencia_vencimiento < now()) {
            return back()->withErrors([
                'chofer_id' => 'No se puede asignar un chofer con licencia vencida.'
            ])->withInput();
        }

        // 🔧 Normalizar tipo de viaje
        $tipoNormalizado = $this->normalizarTipoViaje($request->input('tipo'));

        // Crear el viaje con tipo estandarizado
        $viaje = Viaje::create(array_merge(
            $request->except('tipo'),
            ['tipo' => $tipoNormalizado]
        ));

        // Sincronizar productos
        $this->syncProductosConViaje($viaje, $request);

        // ✅ REGISTRAR SALIDA DE PRODUCTOS DEL INVENTARIO
        foreach ($viaje->productos as $producto) {
            $cantidad = $producto->pivot->cantidad ?? 0;
            if ($cantidad > 0) {
                // Solo si hay stock suficiente
                if ($producto->stock_actual >= $cantidad) {
                    // Restar del stock
                    $producto->decrement('stock_actual', $cantidad);

                    // Registrar movimiento
                    MovimientoInventario::create([
                        'producto_id' => $producto->id,
                        'tipo' => 'salida',
                        'cantidad' => $cantidad,
                        'motivo' => 'Asignado a viaje #' . $viaje->id,
                        'referencia_id' => $viaje->id,
                        'referencia_tipo' => Viaje::class,
                        'usuario_id' => auth()->check() ? auth()->id() : null,
                    ]);
                }
                // Si no hay stock, podrías agregar una alerta opcional
            }
        }

        // Actualizar kilometraje del vehículo
        $vehiculo = $viaje->vehiculo;
        $vehiculo->kilometraje_actual = ($vehiculo->kilometraje_actual ?? 0) + $viaje->kilometros;
        $vehiculo->save();

        return redirect()->route('viajes.show', $viaje->id)
            ->with('success', '✅ Viaje registrado correctamente.');
    }

    /**
     * Mostrar detalles de un viaje.
     */
    public function show($id)
    {
        $viaje = Viaje::with('vehiculo', 'chofer', 'cliente', 'productos')->findOrFail($id);
        return view('viajes.show', compact('viaje'));
    }

    /**
     * Mostrar formulario para editar un viaje.
     */
    public function edit($id)
    {
        $viaje = Viaje::with('productos')->findOrFail($id);
        $vehiculos = Vehiculo::orderBy('patente')->get();
        $choferes = Chofer::orderBy('nombre')->get();
        $productos = Producto::orderBy('nombre')->get();
        $clientes = Cliente::orderBy('nombre')->get();

        return view('viajes.edit', compact('viaje', 'vehiculos', 'choferes', 'productos', 'clientes'));
    }

    /**
     * Actualizar un viaje.
     */
    public function update(Request $request, $id)
    {
        $viaje = Viaje::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'vehiculo_id' => 'required|exists:vehiculos,id',
            'chofer_id' => 'required|exists:choferes,id',
            'cliente_id' => 'nullable|exists:clientes,id',
            'origen' => 'required|string|max:255',
            'destino' => 'required|string|max:255',
            'fecha_salida' => 'required|date',
            'fecha_llegada' => 'nullable|date|after_or_equal:fecha_salida',
            'kilometros' => 'required|integer|min:1',
            'descripcion_carga' => 'required|string|max:1000',
            'estado' => 'required|in:en curso,completado,cancelado',
            'tipo' => 'nullable|string|max:255',

            'producto_id' => 'required|array|min:1',
            'producto_id.*' => 'exists:productos,id',
            'cantidad' => 'required|array',
            'cantidad.*' => 'nullable|integer|min:0',
            'notas' => 'nullable|array',
            'notas.*' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Validar chofer
        $chofer = Chofer::findOrFail($request->chofer_id);
        if ($chofer->licencia_vencimiento && $chofer->licencia_vencimiento < now()) {
            return back()->withErrors([
                'chofer_id' => 'No se puede asignar un chofer con licencia vencida.'
            ])->withInput();
        }

        // Guardar kilometraje anterior
        $kmAnterior = $viaje->kilometros;

        // 🔧 Normalizar tipo de viaje
        $tipoNormalizado = $this->normalizarTipoViaje($request->input('tipo'));

        // Actualizar viaje con tipo estandarizado
        $viaje->update(array_merge(
            $request->except('tipo'),
            ['tipo' => $tipoNormalizado]
        ));

        // Obtener productos anteriores antes de sincronizar
        $productosAnteriores = $viaje->productos->keyBy('id');

        // Sincronizar productos
        $this->syncProductosConViaje($viaje, $request);

        // ✅ AJUSTAR STOCK POR CAMBIOS EN PRODUCTOS
        $productosActuales = $viaje->fresh()->productos->keyBy('id');

        foreach ($productosAnteriores as $id => $producto) {
            $cantidadAnterior = $producto->pivot->cantidad ?? 0;
            $cantidadActual = $productosActuales->has($id) ? $productosActuales[$id]->pivot->cantidad : 0;

            $diferencia = $cantidadAnterior - $cantidadActual;

            if ($diferencia > 0) {
                // Se quitó cantidad → devolver al stock
                $producto->increment('stock_actual', $diferencia);

                MovimientoInventario::create([
                    'producto_id' => $producto->id,
                    'tipo' => 'entrada',
                    'cantidad' => $diferencia,
                    'motivo' => 'Devolución por edición de viaje #' . $viaje->id,
                    'referencia_id' => $viaje->id,
                    'referencia_tipo' => Viaje::class,
                    'usuario_id' => auth()->check() ? auth()->id() : null,
                ]);
            } elseif ($diferencia < 0) {
                // Se agregó más cantidad → restar del stock
                $nuevaCantidad = abs($diferencia);
                if ($producto->stock_actual >= $nuevaCantidad) {
                    $producto->decrement('stock_actual', $nuevaCantidad);

                    MovimientoInventario::create([
                        'producto_id' => $producto->id,
                        'tipo' => 'salida',
                        'cantidad' => $nuevaCantidad,
                        'motivo' => 'Ajuste en viaje #' . $viaje->id,
                        'referencia_id' => $viaje->id,
                        'referencia_tipo' => Viaje::class,
                        'usuario_id' => auth()->check() ? auth()->id() : null,
                    ]);
                }
            }
        }

        // Productos nuevos (que no estaban antes)
        foreach ($productosActuales as $id => $producto) {
            if (!$productosAnteriores->has($id)) {
                $cantidad = $producto->pivot->cantidad ?? 0;
                if ($cantidad > 0 && $producto->stock_actual >= $cantidad) {
                    $producto->decrement('stock_actual', $cantidad);

                    MovimientoInventario::create([
                        'producto_id' => $producto->id,
                        'tipo' => 'salida',
                        'cantidad' => $cantidad,
                        'motivo' => 'Agregado a viaje #' . $viaje->id,
                        'referencia_id' => $viaje->id,
                        'referencia_tipo' => Viaje::class,
                        'usuario_id' => auth()->check() ? auth()->id() : null,
                    ]);
                }
            }
        }

        // Ajustar kilometraje del vehículo
        $vehiculo = $viaje->vehiculo;
        $diferencia = $viaje->kilometros - $kmAnterior;
        $vehiculo->kilometraje_actual = max(0, ($vehiculo->kilometraje_actual ?? 0) + $diferencia);
        $vehiculo->save();

        return redirect()->route('viajes.show', $viaje->id)
            ->with('info', '✅ Viaje actualizado correctamente.');
    }

    /**
     * Eliminar un viaje.
     */
    public function destroy($id)
    {
        $viaje = Viaje::findOrFail($id);
        $vehiculo = $viaje->vehiculo;

        // ✅ DEVOLVER PRODUCTOS AL STOCK
        foreach ($viaje->productos as $producto) {
            $cantidad = $producto->pivot->cantidad ?? 0;
            if ($cantidad > 0) {
                $producto->increment('stock_actual', $cantidad);

                MovimientoInventario::create([
                    'producto_id' => $producto->id,
                    'tipo' => 'entrada',
                    'cantidad' => $cantidad,
                    'motivo' => 'Devolución por eliminación de viaje #' . $viaje->id,
                    'referencia_id' => $viaje->id,
                    'referencia_tipo' => Viaje::class,
                    'usuario_id' => auth()->check() ? auth()->id() : null,
                ]);
            }
        }

        // Restar kilometraje
        if ($vehiculo && $vehiculo->kilometraje_actual) {
            $vehiculo->kilometraje_actual = max(0, $vehiculo->kilometraje_actual - $viaje->kilometros);
            $vehiculo->save();
        }

        $viaje->delete();

        return redirect()->route('viajes.index')
            ->with('warning', '🗑️ Viaje eliminado correctamente.');
    }

    /**
     * Sincroniza productos con cantidades y notas.
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
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        $viaje->productos()->sync($syncData);
    }

    /**
     * Normaliza el tipo de viaje a valores estandarizados.
     *
     * @param string|null $tipo
     * @return string|null
     */
    private function normalizarTipoViaje($tipo)
    {
        if (!$tipo) {
            return null;
        }

        $lower = strtolower(trim($tipo));

        // Detectar agrícola
        if (
            str_contains($lower, 'agricol') ||
            str_contains($lower, 'hortaliza') ||
            str_contains($lower, 'fruta') ||
            str_contains($lower, 'cereal') ||
            str_contains($lower, 'insumo') ||
            str_contains($lower, 'semilla') ||
            str_contains($lower, 'fertilizante')
        ) {
            return 'agrícola';
        }

        // Detectar construcción
        if (
            str_contains($lower, 'construccion') ||
            str_contains($lower, 'construcción') ||
            str_contains($lower, 'materiales') ||
            str_contains($lower, 'herramienta') ||
            str_contains($lower, 'cemento') ||
            str_contains($lower, 'acero') ||
            str_contains($lower, 'ladrillo') ||
            str_contains($lower, 'madera')
        ) {
            return 'construccion';
        }

        return null;
    }
}