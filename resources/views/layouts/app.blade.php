<!DOCTYPE html>
<html lang="es">
<head>
    <!-- PWA -->
<link rel="manifest" href="/manifest.json">
<meta name="theme-color" content="#0056b3">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="mobile-web-app-capable" content="yes">

<!-- Service Worker -->
<script>
  if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
      navigator.serviceWorker.register('/service-worker.js');
    });
  }
</script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LogísticaApp')</title>

    <!-- Bootstrap 5 CSS (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Estilos personalizados - Dark Mode Profesional -->
    <style>
        :root {
            --bg-dark: #1a1a1a;
            --card-bg: #2d2d2d;
            --text-light: #e0e0e0;
            --text-muted: #aaa;
            --border-dark: #444;
            --primary-dark: #0056b3;
            --hover-dark: #004085;
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-light);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            padding-top: 70px;
            padding-bottom: 60px;
            margin: 0;
        }

        /* Navbar oscura con gradiente */
        .navbar {
            background: linear-gradient(180deg, #1a1a1a, #121212) !important;
            border-bottom: 1px solid var(--border-dark);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .navbar-brand {
            font-weight: 600;
            font-size: 1.3rem;
        }

        .nav-link {
            font-size: 0.9rem;
            padding: 0.5rem 1rem !important;
            color: var(--text-light) !important;
            transition: all 0.2s ease;
        }

        .nav-link:hover, .nav-link.active {
            color: #0dcaf0 !important;
            background-color: rgba(13, 202, 240, 0.1);
            border-radius: 5px;
        }

        /* Tarjetas con fondo oscuro */
        .card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-dark);
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
            overflow: hidden;
        }

        .card-header {
            background-color: rgba(255, 255, 255, 0.05);
            border-bottom: 1px solid var(--border-dark);
            font-weight: 500;
            color: var(--text-light);
        }

        .card-body {
            color: var(--text-light);
        }

        /* Botones más pequeños y elegantes */
        .btn {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-weight: 500;
        }

        .btn-sm {
            padding: 0.2rem 0.4rem;
            font-size: 0.75rem;
        }

        .btn-primary {
            background-color: var(--primary-dark);
            border: 1px solid #004a99;
        }

        .btn-primary:hover {
            background-color: var(--hover-dark);
            border-color: #003570;
        }

        /* Tablas oscuras */
        .table {
            color: var(--text-light);
            background-color: var(--card-bg);
            border-collapse: separate;
            border-spacing: 0;
        }

        .table thead th {
            background-color: #3a3a3a;
            border: none;
            padding: 0.7rem 0.5rem;
            font-weight: 500;
        }

        .table tbody tr:nth-child(even) {
            background-color: #333333;
        }

        .table tbody tr:hover {
            background-color: #404040 !important;
        }

        .table td {
            border-top: 1px solid var(--border-dark);
            padding: 0.5rem;
        }

        /* Alertas */
        .alert {
            border: 1px solid var(--border-dark);
            border-radius: 6px;
            padding: 0.7rem 1rem;
        }

        /* Formularios - Texto SIEMPRE visible */
        .form-control,
        .form-select {
            background-color: #333 !important;
            border: 1px solid var(--border-dark);
            color: var(--text-light) !important;
            caret-color: var(--text-light);
        }

        .form-control::placeholder,
        .form-control {
            color: var(--text-light) !important;
            opacity: 0.8;
        }

        .form-control:focus,
        .form-select:focus {
            background-color: #3a3a3a !important;
            color: var(--text-light) !important;
            border-color: #0dcaf0;
            box-shadow: 0 0 0 0.2rem rgba(13, 202, 240, 0.25);
        }

        /* Badges */
        .badge {
            font-size: 0.75rem;
            padding: 0.35em 0.5em;
        }

        /* Scrollbar personalizada */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-dark);
        }

        ::-webkit-scrollbar-thumb {
            background: #444;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 20px 0;
            color: var(--text-muted);
            font-size: 0.85rem;
            margin-top: 2rem;
            border-top: 1px solid var(--border-dark);
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="bi bi-truck"></i> 
                <span class="d-none d-md-inline">LogísticaApp</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                           href="{{ route('dashboard') }}">
                            <i class="bi bi-house-door"></i> Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('vehiculos.*') ? 'active' : '' }}"
                           href="{{ route('vehiculos.index') }}">
                            <i class="bi bi-truck"></i> Vehículos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('viajes.*') ? 'active' : '' }}"
                           href="{{ route('viajes.index') }}">
                            <i class="bi bi-geo-alt"></i> Viajes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('choferes.*') ? 'active' : '' }}"
                           href="{{ route('choferes.index') }}">
                            <i class="bi bi-person-badge"></i> Choferes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('gastos.*') ? 'active' : '' }}"
                           href="{{ route('gastos.index') }}">
                            <i class="bi bi-cash"></i> Gastos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard.alertas') ? 'active' : '' }}"
                           href="{{ route('dashboard.alertas') }}">
                            <i class="bi bi-exclamation-triangle"></i> Alertas
                        </a>
                    </li>
                </ul>

                <span class="navbar-text d-none d-md-block">
                    <small>Modo administrador</small>
                </span>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; {{ date('Y') }} <strong>LogísticaApp</strong> – Sistema de Gestión de Transporte</p>
    </footer>
<!-- Modal de Confirmación Universal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-light">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">¿Guardar cambios?</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas guardar este registro? Esta acción no se puede deshacer.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success btn-sm" id="confirmSave">Guardar</button>
            </div>
        </div>
    </div>
    </div>
    
    @stack('scripts')

<script>
    // Variable global para almacenar el formulario que se va a enviar
    let formToSubmit = null;

    // Escuchar todos los formularios con clase 'needs-confirmation'
    document.addEventListener('DOMContentLoaded', function () {
        const forms = document.querySelectorAll('form.needs-confirmation');

        forms.forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault(); // No enviar el formulario aún

                // Guardar el formulario para enviarlo después
                formToSubmit = form;

                // Mostrar el modal
                const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
                modal.show();
            });
        });

        // Botón "Guardar" del modal
        document.getElementById('confirmSave').addEventListener('click', function () {
            if (formToSubmit) {
                formToSubmit.submit(); // Enviar el formulario
                const modal = bootstrap.Modal.getInstance(document.getElementById('confirmModal'));
                modal.hide();
            }
        });
    });
    </script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
   

<script>
    // Variable para guardar el formulario que se quiere enviar
    let formToSubmit = null;

    // Cuando la página carga
    document.addEventListener('DOMContentLoaded', function () {
        // Busca todos los formularios con clase 'needs-confirmation'
        const forms = document.querySelectorAll('form.needs-confirmation');

        forms.forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault(); // No envíes el formulario todavía

                // Guarda el formulario para usarlo después
                formToSubmit = form;

                // Obtiene el mensaje personalizado (o uno por defecto)
                const message = form.getAttribute('data-message') || 
                               '¿Estás seguro de que deseas guardar este registro? Esta acción no se puede deshacer.';

                // Pon ese mensaje en el modal
                document.querySelector('#confirmModal .modal-body').textContent = message;

                // Muestra el modal
                const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
                modal.show();
            });
        });

        // Cuando haces clic en "Guardar" en el modal
        document.getElementById('confirmSave').addEventListener('click', function () {
            if (formToSubmit) {
                formToSubmit.submit(); // Envía el formulario
                const modal = bootstrap.Modal.getInstance(document.getElementById('confirmModal'));
                modal.hide();
            }
        });
    });
</script>
</body>
</html>