<?php

namespace App\Http\Controllers;

use App\Models\Viaje;
use App\Models\Vehiculo;
use App\Models\Producto;
use App\Models\Chofer;
use App\Models\Cliente;
use App\Models\MovimientoInventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;

class ViajeController extends Controller
{
    /**
     * Listar todos los viajes con sus relaciones.
     */
    public function index()
    {
        $viajes = Viaje::with('vehiculo', 'chofer', 'cliente')
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

        // Crear el viaje
        $data = $request->except(['_token', 'producto_id', 'cantidad', 'notas']);
        $data['tipo'] = $tipoNormalizado;

        $viaje = Viaje::create($data);

        // Sincronizar productos
        $this->syncProductosConViaje($viaje, $request);

        // ✅ REGISTRAR SALIDA DE PRODUCTOS DEL INVENTARIO
        foreach ($viaje->productos as $producto) {
            $cantidad = $producto->pivot->cantidad ?? 0;
            if ($cantidad > 0 && $producto->stock_actual >= $cantidad) {
                $producto->decrement('stock_actual', $cantidad);

                MovimientoInventario::create([
                    'producto_id' => $producto->id,
                    'tipo' => 'salida',
                    'cantidad' => $cantidad,
                    'motivo' => 'Asignado a viaje #' . $viaje->id,
                    'referencia_id' => $viaje->id,
                    'referencia_tipo' => get_class($viaje),
                    'usuario_id' => auth()->check() ? auth()->id() : null,
                ]);
            }
        }

        // Actualizar kilometraje del vehículo
        $vehiculo = $viaje->vehiculo;
        if ($vehiculo) {
            $vehiculo->kilometraje_actual = ($vehiculo->kilometraje_actual ?? 0) + $viaje->kilometros;
            $vehiculo->save();
        }

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

        // Actualizar viaje
        $data = $request->except(['_token', '_method', 'producto_id', 'cantidad', 'notas']);
        $data['tipo'] = $tipoNormalizado;

        $viaje->update($data);

        // Obtener productos anteriores antes de sincronizar
        $productosAnteriores = $viaje->productos->keyBy('id');

        // Sincronizar productos
        $this->syncProductosConViaje($viaje, $request);

        // Refrescar productos actuales
        $viaje->load('productos');
        $productosActuales = $viaje->productos->keyBy('id');

        // ✅ AJUSTAR STOCK POR CAMBIOS EN PRODUCTOS
        foreach ($productosAnteriores as $id => $producto) {
            $cantAnterior = $producto->pivot->cantidad ?? 0;
            $cantActual = $productosActuales->has($id) ? $productosActuales[$id]->pivot->cantidad : 0;

            $diferencia = $cantAnterior - $cantActual;

            if ($diferencia > 0) {
                // Devolver stock
                $producto->increment('stock_actual', $diferencia);

                MovimientoInventario::create([
                    'producto_id' => $producto->id,
                    'tipo' => 'entrada',
                    'cantidad' => $diferencia,
                    'motivo' => 'Devolución por edición de viaje #' . $viaje->id,
                    'referencia_id' => $viaje->id,
                    'referencia_tipo' => get_class($viaje),
                    'usuario_id' => auth()->check() ? auth()->id() : null,
                ]);
            } elseif ($diferencia < 0) {
                // Quitar más stock
                $nuevaCantidad = abs($diferencia);
                if ($producto->stock_actual >= $nuevaCantidad) {
                    $producto->decrement('stock_actual', $nuevaCantidad);

                    MovimientoInventario::create([
                        'producto_id' => $producto->id,
                        'tipo' => 'salida',
                        'cantidad' => $nuevaCantidad,
                        'motivo' => 'Ajuste en viaje #' . $viaje->id,
                        'referencia_id' => $viaje->id,
                        'referencia_tipo' => get_class($viaje),
                        'usuario_id' => auth()->check() ? auth()->id() : null,
                    ]);
                }
            }
        }

        // Productos nuevos
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
                        'referencia_tipo' => get_class($viaje),
                        'usuario_id' => auth()->check() ? auth()->id() : null,
                    ]);
                }
            }
        }

        // Ajustar kilometraje del vehículo
        $vehiculo = $viaje->vehiculo;
        if ($vehiculo) {
            $diferencia = $viaje->kilometros - $kmAnterior;
            $vehiculo->kilometraje_actual = max(0, ($vehiculo->kilometraje_actual ?? 0) + $diferencia);
            $vehiculo->save();
        }

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
                    'referencia_tipo' => get_class($viaje),
                    'usuario_id' => auth()->check() ? auth()->id() : null,
                ]);
            }
        }

        // Restar kilometraje
        if ($vehiculo) {
            $vehiculo->kilometraje_actual = max(0, ($vehiculo->kilometraje_actual ?? 0) - $viaje->kilometros);
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
                $cantidad = isset($cantidades[$index]) ? (int)$cantidades[$index] : 0;
                $nota = isset($notas[$index]) ? $notas[$index] : null;

                $syncData[$productoId] = [
                    'cantidad' => $cantidad,
                    'notas' => $nota,
                    'updated_at' => \Carbon\Carbon::now(),
                ];
            }
        }

        $viaje->productos()->sync($syncData);
    }

    /**
     * Normaliza el tipo de viaje a valores estandarizados (compatible PHP 7.4)
     */
    private function normalizarTipoViaje($tipo)
    {
        if (!$tipo || !is_string($tipo)) {
            return null;
        }

        $lower = strtolower(trim($tipo));

        // Detectar agrícola
        $palabrasAgricolas = ['agricol', 'hortaliza', 'fruta', 'cereal', 'insumo', 'semilla', 'fertilizante'];
        foreach ($palabrasAgricolas as $palabra) {
            if (strpos($lower, $palabra) !== false) {
                return 'agricola';
            }
        }

        // Detectar construcción
        $palabrasConstruccion = ['construccion', 'materiales', 'herramienta', 'cemento', 'acero', 'ladrillo', 'madera'];
        foreach ($palabrasConstruccion as $palabra) {
            if (strpos($lower, $palabra) !== false) {
                return 'construccion';
            }
        }

        return null;
    }
}