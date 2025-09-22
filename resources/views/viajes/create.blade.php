@extends('layouts.app')

@section('content')
<div class="p-3">
    <h2><i class="fas fa-route text-primary"></i> Nuevo Viaje</h2>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb" style="background-color: transparent; padding: 0; font-size: 0.9rem;">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" style="color: #198754;">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('viajes.index') }}" style="color: #198754;">Viajes</a></li>
            <li class="breadcrumb-item active" style="color: #ccc;">Nuevo</li>
        </ol>
    </nav>

    <form action="{{ route('viajes.store') }}" method="POST" id="viaje-form">
        @csrf

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
                            @php
                                $direccion = htmlspecialchars($cliente->calle ?? '', ENT_QUOTES) . ' ' . htmlspecialchars($cliente->numero ?? '', ENT_QUOTES);
                                $localidad = htmlspecialchars($cliente->localidad ?? '', ENT_QUOTES);
                                $provincia = htmlspecialchars($cliente->provincia ?? '', ENT_QUOTES);
                            @endphp
                            <option value="{{ $cliente->id }}"
                                    data-direccion="{{ trim($direccion) }}"
                                    data-localidad="{{ $localidad }}"
                                    data-provincia="{{ $provincia }}">
                                {{ $cliente->nombre }}
                                @if($localidad || $provincia)
                                    ({{ $localidad }}{{ $localidad && $provincia ? ', ' : '' }}{{ $provincia }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- TIPO DE VIAJE -->
                <div class="col-md-6">
                    <label class="form-label" style="color: #eee;">Tipo de Viaje</label>
                    <select name="tipo" id="tipo_viaje" class="form-control" required style="background-color: #333; border: 1px solid #555; color: #eee;">
                        <option value="">Seleccionar tipo</option>
                        <option value="agricola" {{ old('tipo') == 'agricola' ? 'selected' : '' }}>Distribución de Productos Agrícolas</option>
                        <option value="construccion" {{ old('tipo') == 'construccion' ? 'selected' : '' }}>Distribución de Materiales de Construcción</option>
                        <option value="otros" {{ old('tipo') == 'otros' ? 'selected' : '' }}>Otros</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label" style="color: #eee;">Vehículo</label>
                    <select name="vehiculo_id" class="form-control" required style="background-color: #333; border: 1px solid #555; color: #eee;">
                        <option value="">Seleccionar vehículo</option>
                        @foreach($vehiculos as $v)
                            <option value="{{ $v->id }}" {{ old('vehiculo_id') == $v->id ? 'selected' : '' }}>
                                {{ $v->patente }} - {{ $v->marca }} {{ $v->modelo }}
                            </option>
                        @endforeach
                    </select>
                    @error('vehiculo_id')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" style="color: #eee;">Chofer</label>
                    <select name="chofer_id" class="form-control" required style="background-color: #333; border: 1px solid #555; color: #eee;">
                        <option value="">Seleccionar chofer</option>
                        @foreach($choferes as $c)
                            <option value="{{ $c->id }}" {{ old('chofer_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->nombre }} (Lic: {{ $c->licencia_numero ?? '–' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label" style="color: #eee;">Origen</label>
                    <input type="text" name="origen" class="form-control" value="{{ old('origen') }}" required style="background-color: #333; border: 1px solid #555; color: #eee;">
                </div>

                <div class="col-md-6">
                    <label class="form-label" style="color: #eee;">Destino</label>
                    <input type="text" name="destino" id="destino" class="form-control" value="{{ old('destino') }}" required style="background-color: #333; border: 1px solid #555; color: #eee;">
                </div>

                <div class="col-md-6">
                    <label class="form-label" style="color: #eee;">Fecha de Salida</label>
                    <input type="date" name="fecha_salida" class="form-control" value="{{ old('fecha_salida') }}" required style="background-color: #333; border: 1px solid #555; color: #eee;">
                </div>

                <div class="col-md-6">
                    <label class="form-label" style="color: #eee;">Fecha de Llegada</label>
                    <input type="date" name="fecha_llegada" class="form-control" value="{{ old('fecha_llegada') }}" style="background-color: #333; border: 1px solid #555; color: #eee;">
                </div>

                <div class="col-md-6">
                    <label class="form-label" style="color: #eee;">Kilómetros</label>
                    <input type="number" name="kilometros" class="form-control" value="{{ old('kilometros', 0) }}" required min="1" style="background-color: #333; border: 1px solid #555; color: #eee;">
                </div>

                <div class="col-md-6">
                    <label class="form-label" style="color: #eee;">Estado</label>
                    <select name="estado" class="form-control" required style="background-color: #333; border: 1px solid #555; color: #eee;">
                        <option value="en curso" {{ old('estado', 'en curso') == 'en curso' ? 'selected' : '' }}>En curso</option>
                        <option value="completado" {{ old('estado') == 'completado' ? 'selected' : '' }}>Completado</option>
                        <option value="cancelado" {{ old('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label" style="color: #eee;">Descripción de carga</label>
                    <textarea name="descripcion_carga" class="form-control" rows="2" required style="background-color: #333; border: 1px solid #555; color: #eee;">{{ old('descripcion_carga') }}</textarea>
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
            ">
                <table style="
                    width: 100%;
                    border-collapse: collapse;
                    font-size: 0.9rem;
                    background-color: #121212;
                    color: #eee;
                    margin: 0;
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
                                $index = $loop->index;
                                $categoria = strtolower(trim($producto->categoria ?? ''));
                                $isSelected = in_array($producto->id, old('producto_id', []));
                                
                                // Color por categoría (sin acentos)
                                if (strpos($categoria, 'agrícola') !== false || strpos($categoria, 'hortaliza') !== false || strpos($categoria, 'fruta') !== false) {
                                    $bgCategoria = '#28a745';
                                } elseif (strpos($categoria, 'construccion') !== false || strpos($categoria, 'materiales') !== false || strpos($categoria, 'cemento') !== false) {
                                    $bgCategoria = '#fd7e14';
                                } else {
                                    $bgCategoria = '#6c757d';
                                }
                            @endphp
                            <tr style="border-bottom: 1px solid #333;" 
                                data-categoria="{{ $categoria }}">
                                <td style="padding: 0.4rem 0.6rem; text-align: left;">
                                    <input type="checkbox"
                                           name="producto_id[]"
                                           value="{{ $producto->id }}"
                                           id="prod_{{ $producto->id }}"
                                           {{ $isSelected ? 'checked' : '' }}
                                           class="producto-checkbox"
                                           style="cursor: pointer; transform: scale(1.2);">
                                </td>
                                <td style="padding: 0.4rem 0.6rem; color: #ddd;">
                                    <label for="prod_{{ $producto->id }}" style="cursor: pointer; margin: 0;">
                                        <strong>{{ $producto->nombre }}</strong>
                                        @if(!empty($producto->precio))
                                            <br><small style="color: #aaa;">$ {{ number_format($producto->precio, 2, ',', '.') }}</small>
                                        @endif
                                    </label>
                                </td>
                                <td style="padding: 0.4rem 0.6rem;">
                                    <span style="
                                        font-size: 0.75rem;
                                        padding: 0.2em 0.5em;
                                        border-radius: 4px;
                                        background-color: {{ $bgCategoria }};
                                        color: white;
                                    ">
                                        {{ ucfirst($producto->categoria ?? '–') }}
                                    </span>
                                </td>
                                <td style="padding: 0.4rem 0.6rem; text-align: right;">
                                    <input type="number"
                                           name="cantidad[]"
                                           value="{{ old("cantidad.$index", 0) }}"
                                           min="0"
                                           placeholder="0"
                                           {{ $isSelected ? '' : 'disabled' }}
                                           style="
                                               width: 70px;
                                               padding: 0.25rem;
                                               text-align: right;
                                               background-color: {{ $isSelected ? '#333' : '#444' }};
                                               border: 1px solid #555;
                                               color: {{ $isSelected ? '#eee' : '#777' }};
                                               border-radius: 4px;
                                               font-size: 0.85rem;
                                           ">
                                </td>
                                <td style="padding: 0.4rem 0.6rem; text-align: right;">
                                    <input type="text"
                                           name="notas[]"
                                           value="{{ old("notas.$index") }}"
                                           placeholder="Ej: Frágil"
                                           {{ $isSelected ? '' : 'disabled' }}
                                           style="
                                               width: 110px;
                                               padding: 0.25rem;
                                               background-color: {{ $isSelected ? '#333' : '#444' }};
                                               border: 1px solid #555;
                                               color: {{ $isSelected ? '#eee' : '#777' }};
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

        <!-- Errores generales -->
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
                <i class="fas fa-save me-1"></i>Guardar Viaje
            </button>
            <a href="{{ route('viajes.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Volver
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var tipoViajeSelect = document.getElementById('tipo_viaje');
    var tablaBody = document.getElementById('tabla-productos-body');
    var filas = tablaBody ? tablaBody.getElementsByTagName('tr') : [];
    var formModified = false;

    // Detectar cambios
    document.getElementById('viaje-form').addEventListener('input', function () {
        formModified = true;
    });

    // Filtrar productos por tipo de viaje
    if (tipoViajeSelect && tablaBody) {
        tipoViajeSelect.addEventListener('change', function () {
            var tipo = this.value.toLowerCase();

            for (var i = 0; i < filas.length; i++) {
                var row = filas[i];
                var categoria = (row.getAttribute('data-categoria') || '').toLowerCase();

                var mostrar = true;

                if (tipo === 'agricola') {
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
                        categoria.indexOf('construcción') !== -1 ||
                        categoria.indexOf('construccion') !== -1 ||
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

            var parts = [];
            if (direccion.trim()) parts.push(direccion.trim());
            if (localidad.trim()) parts.push(localidad.trim());
            if (provincia.trim()) parts.push(provincia.trim());

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

    // Confirmar salida si hay cambios
    var links = document.querySelectorAll('a');
    for (var l = 0; l < links.length; l++) {
        if (!links[l].closest('button')) {
            links[l].addEventListener('click', function (e) {
                if (formModified && !this.href.includes('#')) {
                    if (!confirm('Tiene cambios sin guardar.\n\n¿Desea salir sin guardar?')) {
                        e.preventDefault();
                    }
                }
            });
        }
    }
});
</script>
@endpush
@endsection