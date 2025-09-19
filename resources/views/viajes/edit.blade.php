@extends('layouts.app')

@section('content')
<div class="p-3">
    <h2><i class="fas fa-edit text-primary"></i> Editar Viaje #{{ $viaje->id }}</h2>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb" style="background-color: transparent; padding: 0; font-size: 0.9rem;">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" style="color: #198754;">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('viajes.index') }}" style="color: #198754;">Viajes</a></li>
            <li class="breadcrumb-item active" style="color: #ccc;">Editar</li>
        </ol>
    </nav>

    <form action="{{ route('viajes.update', $viaje->id) }}" method="POST" id="viaje-form">
        @csrf
        @method('PUT')

        <!-- Datos del viaje -->
        <div style="
            background-color: #1a1a1a;
            border-radius: 0.375rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border: 1px solid #444;
        ">
            <h5 style="color: #fff; margin-top: 0; border-bottom: 1px solid #444; padding-bottom: 0.5rem;">
                <i class="fas fa-info-circle me-1"></i> Datos del Viaje
            </h5>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" style="color: #eee;">Cliente</label>
                    <select name="cliente_id" id="cliente_id" class="form-control" style="background-color: #333; border: 1px solid #555; color: #eee;">
                        <option value="">Seleccionar cliente</option>
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}"
                                    data-direccion="{{ htmlspecialchars($cliente->direccion ?? '', ENT_QUOTES) }}"
                                    data-localidad="{{ htmlspecialchars($cliente->localidad ?? '', ENT_QUOTES) }}"
                                    data-provincia="{{ htmlspecialchars($cliente->provincia ?? '', ENT_QUOTES) }}"
                                    {{ $viaje->cliente_id == $cliente->id ? 'selected' : '' }}>
                                {{ $cliente->nombre }} ({{ ucfirst($cliente->tipo) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tipo de viaje para filtrar -->
                <div class="col-md-6">
                    <label class="form-label" style="color: #eee;">Tipo de Viaje</label>
                    <select id="tipo_viaje" class="form-control" style="background-color: #333; border: 1px solid #555; color: #eee;">
                        <option value="">Todos los productos</option>
                        <option value="agrícola">Distribución de Productos Agrícolas</option>
                        <option value="construccion">Distribución de Materiales de Construcción</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label" style="color: #eee;">Vehículo</label>
                    <select name="vehiculo_id" class="form-control" required style="background-color: #333; border: 1px solid #555; color: #eee;">
                        <option value="">Seleccionar vehículo</option>
                        @foreach($vehiculos as $v)
                            <option value="{{ $v->id }}" {{ $viaje->vehiculo_id == $v->id ? 'selected' : '' }}>
                                {{ $v->patente }} - {{ $v->marca }} {{ $v->modelo }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label" style="color: #eee;">Chofer</label>
                    <select name="chofer_id" class="form-control" required style="background-color: #333; border: 1px solid #555; color: #eee;">
                        <option value="">Seleccionar chofer</option>
                        @foreach($choferes as $c)
                            <option value="{{ $c->id }}" {{ $viaje->chofer_id == $c->id ? 'selected' : '' }}>
                                {{ $c->nombre }} (Lic: {{ $c->licencia_numero }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label" style="color: #eee;">Origen</label>
                    <input type="text" name="origen" class="form-control" value="{{ old('origen', $viaje->origen) }}" required style="background-color: #333; border: 1px solid #555; color: #eee;">
                </div>

                <div class="col-md-6">
                    <label class="form-label" style="color: #eee;">Destino</label>
                    <input type="text" name="destino" id="destino" class="form-control" value="{{ old('destino', $viaje->destino) }}" required style="background-color: #333; border: 1px solid #555; color: #eee;">
                </div>

                <div class="col-md-6">
                    <label class="form-label" style="color: #eee;">Fecha de Salida</label>
                    <input type="date" name="fecha_salida" class="form-control" value="{{ old('fecha_salida', $viaje->fecha_salida) }}" required style="background-color: #333; border: 1px solid #555; color: #eee;">
                </div>

                <div class="col-md-6">
                    <label class="form-label" style="color: #eee;">Fecha de Llegada</label>
                    <input type="date" name="fecha_llegada" class="form-control" value="{{ old('fecha_llegada', $viaje->fecha_llegada) }}" style="background-color: #333; border: 1px solid #555; color: #eee;">
                </div>

                <div class="col-md-6">
                    <label class="form-label" style="color: #eee;">Kilómetros</label>
                    <input type="number" name="kilometros" class="form-control" value="{{ old('kilometros', $viaje->kilometros) }}" required min="1" style="background-color: #333; border: 1px solid #555; color: #eee;">
                </div>

                <div class="col-md-6">
                    <label class="form-label" style="color: #eee;">Estado</label>
                    <select name="estado" class="form-control" required style="background-color: #333; border: 1px solid #555; color: #eee;">
                        <option value="en curso" {{ old('estado', $viaje->estado) == 'en curso' ? 'selected' : '' }}>En curso</option>
                        <option value="completado" {{ old('estado', $viaje->estado) == 'completado' ? 'selected' : '' }}>Completado</option>
                        <option value="cancelado" {{ old('estado', $viaje->estado) == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label" style="color: #eee;">Descripción de carga</label>
                    <textarea name="descripcion_carga" class="form-control" rows="2" required style="background-color: #333; border: 1px solid #555; color: #eee;">{{ old('descripcion_carga', $viaje->descripcion_carga) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Sección: Productos Transportados -->
        <div style="
            background-color: #1a1a1a;
            border-radius: 0.375rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border: 1px solid #444;
        ">
            <h5 style="color: #fff; margin-top: 0; border-bottom: 1px solid #444; padding-bottom: 0.5rem;">
                <i class="fas fa-box me-1"></i> Productos Transportados
            </h5>

            <p style="color: #ccc; font-size: 0.9rem;">
                Seleccione el <strong>tipo de viaje</strong> arriba para filtrar los productos.
            </p>

            <!-- Contenedor con scroll -->
            <div style="
                max-height: 300px;
                overflow-y: auto;
                border: 1px solid #444;
                border-radius: 0.375rem;
                background-color: #121212;
                padding: 0;
                margin: 0;
            ">
                <table style="
                    width: 100%;
                    border-collapse: collapse;
                    font-size: 0.9rem;
                    background-color: #121212;
                    color: #eee;
                    margin: 0;
                    table-layout: fixed;
                ">
                    <thead style="background-color: #000; color: #fff;">
                        <tr>
                            <th style="padding: 0.5rem 0.6rem; text-align: left;">Sel</th>
                            <th style="padding: 0.5rem 0.6rem; text-align: left;">Producto</th>
                            <th style="padding: 0.5rem 0.6rem; text-align: left;">Categoría</th>
                            <th style="padding: 0.5rem 0.6rem; text-align: right;">Cantidad</th>
                            <th style="padding: 0.5rem 0.6rem; text-align: right;">Notas</th>
                        </tr>
                    </thead>
                    <tbody id="tabla-productos-body">
                        @foreach($productos as $producto)
                            @php
                                // Verificar si el producto está asociado al viaje
                                $pivot = null;
                                foreach ($viaje->productos as $p) {
                                    if ($p->id == $producto->id) {
                                        $pivot = $p->pivot;
                                        break;
                                    }
                                }
                                $isSelected = (bool)$pivot;

                                // Para manejar old()
                                $oldProductoIds = old('producto_id', []);
                                $isOldSelected = in_array($producto->id, $oldProductoIds);
                                $checked = $isOldSelected || $isSelected;

                                $index = $loop->index;
                            @endphp
                            <tr style="border-bottom: 1px solid #333; background-color: #121212;"
                                data-categoria="{{ strtolower(trim($producto->categoria)) }}">
                                <td style="padding: 0.4rem 0.6rem; text-align: left;">
                                    <input type="checkbox"
                                           name="producto_id[]"
                                           value="{{ $producto->id }}"
                                           id="prod_{{ $producto->id }}"
                                           {{ $checked ? 'checked' : '' }}
                                           class="producto-checkbox"
                                           style="cursor: pointer; transform: scale(1.2);">
                                </td>
                                <td style="padding: 0.4rem 0.6rem; color: #ddd;">
                                    <label for="prod_{{ $producto->id }}" style="cursor: pointer; margin: 0;">
                                        <strong>{{ $producto->nombre }}</strong>
                                        @if($producto->precio)
                                            <br><small style="color: #aaa;">{{ $producto->precio_formatted }}</small>
                                        @endif
                                    </label>
                                </td>
                                <td style="padding: 0.4rem 0.6rem;">
                                    <span style="
                                        font-size: 0.75rem;
                                        padding: 0.2em 0.5em;
                                        border-radius: 4px;
                                        background-color: {{ $producto->categoria == 'agrícola' || strpos(strtolower($producto->categoria), 'hortaliza') !== false ? '#28a745' : '#fd7e14' }};
                                        color: white;
                                    ">
                                        {{ ucfirst($producto->categoria) }}
                                    </span>
                                </td>
                                <td style="padding: 0.4rem 0.6rem; text-align: right;">
                                    <input type="number"
                                           name="cantidad[]"
                                           value="{{ old("cantidad.$index", $pivot ? $pivot->cantidad : 0) }}"
                                           min="0"
                                           placeholder="0"
                                           {{ !$checked ? 'disabled' : '' }}
                                           style="
                                               width: 70px;
                                               padding: 0.25rem;
                                               text-align: right;
                                               background-color: {{ !$checked ? '#444' : '#333' }};
                                               border: 1px solid #555;
                                               color: {{ !$checked ? '#777' : '#eee' }};
                                               border-radius: 4px;
                                               font-size: 0.85rem;
                                           ">
                                </td>
                                <td style="padding: 0.4rem 0.6rem; text-align: right;">
                                    <input type="text"
                                           name="notas[]"
                                           value="{{ old("notas.$index", $pivot ? $pivot->notas : '') }}"
                                           placeholder="Ej: Frágil"
                                           {{ !$checked ? 'disabled' : '' }}
                                           style="
                                               width: 110px;
                                               padding: 0.25rem;
                                               background-color: {{ !$checked ? '#444' : '#333' }};
                                               border: 1px solid #555;
                                               color: {{ !$checked ? '#777' : '#eee' }};
                                               border-radius: 4px;
                                               font-size: 0.85rem;
                                           ">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Errores -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert" style="background-color: #dc3545; color: #fff; border: none; border-radius: 0.375rem;">
                <strong>Errores:</strong>
                <ul class="mb-0" style="margin-left: 1rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Botones -->
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success px-4">
                <i class="fas fa-save me-1"></i>Actualizar Viaje
            </button>
            <a href="{{ route('viajes.show', $viaje->id) }}" class="btn btn-secondary">
                <i class="fas fa-times me-1"></i>Cancelar
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var tipoViajeSelect = document.getElementById('tipo_viaje');
    var tablaBody = document.getElementById('tabla-productos-body');
    var filas = tablaBody.getElementsByTagName('tr');
    var formModified = false;

    // Detectar cambios
    document.getElementById('viaje-form').addEventListener('input', function () {
        formModified = true;
    });

    // Filtrar productos por tipo de viaje
    if (tipoViajeSelect) {
        tipoViajeSelect.addEventListener('change', function () {
            var tipo = this.value.toLowerCase();

            for (var i = 0; i < filas.length; i++) {
                var row = filas[i];
                var categoria = row.getAttribute('data-categoria') || '';

                var mostrar = true;

                if (tipo === 'agrícola') {
                    mostrar = (
                        categoria.indexOf('agrícola') !== -1 ||
                        categoria.indexOf('agricola') !== -1 ||
                        categoria.indexOf('hortaliza') !== -1 ||
                        categoria.indexOf('fruta') !== -1 ||
                        categoria.indexOf('cereal') !== -1 ||
                        categoria.indexOf('insumo') !== -1 ||
                        categoria.indexOf('semilla') !== -1 ||
                        categoria.indexOf('fertilizante') !== -1
                    );
                } else if (tipo === 'construccion') {
                    mostrar = (
                        categoria.indexOf('construccion') !== -1 ||
                        categoria.indexOf('construcción') !== -1 ||
                        categoria.indexOf('materiales') !== -1 ||
                        categoria.indexOf('herramienta') !== -1 ||
                        categoria.indexOf('cemento') !== -1 ||
                        categoria.indexOf('acero') !== -1 ||
                        categoria.indexOf('madera') !== -1 ||
                        categoria.indexOf('ladrillo') !== -1
                    );
                }

                row.style.display = mostrar ? '' : 'none';
            }
        });
    }

    // Autocompletar destino con dirección del cliente
    var clienteSelect = document.getElementById('cliente_id');
    var destinoInput = document.getElementById('destino');

    if (clienteSelect && destinoInput) {
        clienteSelect.addEventListener('change', function () {
            var option = this.options[this.selectedIndex];
            var direccion = option.getAttribute('data-direccion') || '';
            var localidad = option.getAttribute('data-localidad') || '';
            var provincia = option.getAttribute('data-provincia') || '';

            var parts = [direccion.trim(), localidad.trim(), provincia.trim()].filter(function (part) {
                return part !== '';
            });

            if (parts.length > 0) {
                destinoInput.value = parts.join(', ');
                formModified = true;
            }
        });
    }

    // Habilitar/deshabilitar campos al checkear
    var checkboxes = document.querySelectorAll('.producto-checkbox');
    for (var j = 0; j < checkboxes.length; j++) {
        (function(checkbox) {
            var row = checkbox.closest('tr');
            var inputs = row.querySelectorAll('input[type="number"], input[type="text"]');

            checkbox.addEventListener('change', function () {
                for (var k = 0; k < inputs.length; k++) {
                    inputs[k].disabled = !this.checked;
                    inputs[k].style.backgroundColor = this.checked ? '#333' : '#444';
                    inputs[k].style.color = this.checked ? '#eee' : '#777';
                }
                formModified = true;
            });
        })(checkboxes[j]);
    }

    // Confirmar salida
    var links = document.querySelectorAll('a');
    for (var l = 0; l < links.length; l++) {
        links[l].addEventListener('click', function (e) {
            if (formModified && !this.href.includes('#') && !this.closest('button')) {
                if (!confirm('Tiene cambios sin guardar.\n\n¿Desea salir sin guardar?')) {
                    e.preventDefault();
                }
            }
        });
    }
});
</script>
@endpush
@endsection