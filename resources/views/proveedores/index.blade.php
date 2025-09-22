@extends('layouts.app')

@section('content')
<div class="p-3">
    <h2><i class="fas fa-building text-primary"></i> Proveedores</h2>

    <!-- Mensaje de éxito -->
    @if(session('success'))
        <div style="background-color: #28a745; color: white; padding: 0.5rem; border-radius: 4px; margin-bottom: 1rem;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('warning'))
        <div style="background-color: #ffc107; color: #000; padding: 0.5rem; border-radius: 4px; margin-bottom: 1rem;">
            {{ session('warning') }}
        </div>
    @endif

    <!-- Búsqueda -->
    <div class="mb-3 d-flex flex-wrap gap-2 align-items-center">
        <div class="flex-grow-1" style="min-width: 200px;">
            <input type="text" id="search-proveedores" class="form-control form-control-sm" placeholder="Buscar nombre..."
                   style="background-color: #333; border: 1px solid #555; color: #eee;">
        </div>
        <a href="{{ route('proveedores.create') }}" class="btn btn-sm btn-success">
            <i class="fas fa-plus-circle"></i> Nuevo
        </a>
    </div>

    <!-- Tabla -->
    <div style="
        max-height: 60vh; 
        overflow-y: auto; 
        border-radius: 0.375rem; 
        border: 1px solid #444;
        background-color: #121212;
    ">
        <table style="
            width: 100%; 
            border-collapse: collapse; 
            font-size: 0.9rem; 
            color: #eee;
            background-color: #121212;
        ">
            <thead style="background-color: #000; color: #fff;">
                <tr>
                    <th style="padding: 0.5rem; text-align: left;">ID</th>
                    <th style="padding: 0.5rem; text-align: left;">Nombre</th>
                    <th style="padding: 0.5rem; text-align: left;">Contacto</th>
                    <th style="padding: 0.5rem; text-align: left;">Teléfono</th>
                    <th style="padding: 0.5rem; text-align: left;">Localidad</th>
                    <th style="padding: 0.5rem; text-align: right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($proveedores as $p)
                    <tr 
                        data-nombre="{{ strtolower($p->nombre) }}"
                        style="border-bottom: 1px solid #333;">
                        <td style="padding: 0.4rem 0.6rem; color: #fff;">{{ $p->id }}</td>
                        <td style="padding: 0.4rem 0.6rem; color: #ddd;">{{ $p->nombre }}</td>
                        <td style="padding: 0.4rem 0.6rem; color: #ccc;">{{ $p->contacto ?? '–' }}</td>
                        <td style="padding: 0.4rem 0.6rem; color: #ccc;">{{ $p->telefono ?? '–' }}</td>
                        <td style="padding: 0.4rem 0.6rem; color: #ccc;">{{ $p->localidad ?? '–' }}</td>
                        <td class="text-end" style="padding: 0.4rem 0.6rem; text-align: right;">
                            <a href="{{ route('proveedores.show', $p->id) }}" 
                            style="display: inline-block; padding: 0.15rem 0.3rem; font-size: 0.8rem; color: #fff; background-color: #198754; border-radius: 4px; text-decoration: none;">
                                <i class="fas fa-edit" style="margin-right: 0.2rem;"></i>Ver
                            </a>
                            <a href="{{ route('proveedores.edit', $p->id) }}" 
                               style="display: inline-block; padding: 0.15rem 0.3rem; font-size: 0.8rem; color: #fff; background-color: #0d6efd; border-radius: 4px; text-decoration: none;">
                              <i class="fas fa-edit" style="margin-right: 0.2rem;"></i>Editar
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search-proveedores');
    const rows = document.querySelectorAll('tbody tr');

    searchInput?.addEventListener('input', function () {
        const term = this.value.toLowerCase().trim();
        rows.forEach(row => {
            const name = row.getAttribute('data-nombre') || '';
            row.style.display = name.includes(term) ? '' : 'none';
        });
    });
});
</script>
@endpush
@endsection