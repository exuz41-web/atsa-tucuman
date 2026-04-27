<!DOCTYPE html>
<html lang="es" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Portal de Afiliados') | ATSA Tucumán</title>

    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#1e3a5f">
    <link rel="stylesheet" href="{{ asset('modernize/css/styles.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.41.1/dist/tabler-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --atsa-blue: #1e3a5f;
            --atsa-blue-dark: #132a45;
            --atsa-sky: #49beff;
            --atsa-sidebar: 280px;
            --atsa-header: 74px;
        }

        html,
        body {
            min-height: 100%;
            background: #f6f8fb;
            overflow-x: hidden;
        }

        .afiliado-shell {
            min-height: 100vh;
            background:
                radial-gradient(circle at top right, rgba(73, 190, 255, .10), transparent 34rem),
                #f6f8fb;
        }

        .afiliado-sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            width: var(--atsa-sidebar);
            background: #ffffff;
            border-right: 1px solid #e5eaef;
            z-index: 1040;
            display: flex;
            flex-direction: column;
            transition: transform .22s ease;
        }

        .afiliado-brand {
            min-height: 112px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 24px;
            border-bottom: 1px solid #edf2f7;
        }

        .afiliado-brand img {
            max-height: 78px;
            max-width: 190px;
            object-fit: contain;
        }

        .afiliado-nav {
            flex: 1;
            overflow-y: auto;
            padding: 18px 18px 20px;
        }

        .afiliado-nav::-webkit-scrollbar {
            width: 6px;
        }

        .afiliado-nav::-webkit-scrollbar-thumb {
            background: #dbe5f0;
            border-radius: 999px;
        }

        .afiliado-nav-label {
            color: #7c8ba1;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
            margin: 8px 12px 12px;
        }

        .afiliado-nav-link {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 12px 14px;
            border-radius: 12px;
            color: #2a3547;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 6px;
            transition: all .18s ease;
        }

        .afiliado-nav-link i {
            font-size: 21px;
            color: inherit;
        }

        .afiliado-nav-link:hover {
            background: #eef7ff;
            color: var(--atsa-blue);
        }

        .afiliado-nav-link.active {
            background: var(--atsa-blue);
            color: #ffffff;
            box-shadow: 0 10px 18px rgba(30, 58, 95, .18);
        }

        .afiliado-sidebar-profile {
            margin: 0 18px 18px;
            padding: 16px;
            border-radius: 18px;
            background: #f6f8fb;
            border: 1px solid #e5eaef;
        }

        .afiliado-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: inline-grid;
            place-items: center;
            overflow: hidden;
            background: #ecf2ff;
            color: var(--atsa-blue);
            font-weight: 800;
            flex: 0 0 auto;
        }

        .afiliado-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .afiliado-main {
            min-height: 100vh;
            margin-left: var(--atsa-sidebar);
            padding-top: var(--atsa-header);
        }

        .afiliado-topbar {
            position: fixed;
            top: 0;
            right: 0;
            left: var(--atsa-sidebar);
            height: var(--atsa-header);
            background: rgba(255,255,255,.96);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid #e5eaef;
            z-index: 1030;
            display: flex;
            align-items: center;
            padding: 0 28px;
        }

        .afiliado-content {
            width: 100%;
            max-width: 1480px;
            margin: 0 auto;
            padding: 28px;
        }

        .portal-card {
            background: #ffffff;
            border: 1px solid #e5eaef;
            border-radius: 18px;
            box-shadow: 0 16px 34px rgba(42, 53, 71, .07);
        }

        .portal-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: inline-grid;
            place-items: center;
            background: #eef7ff;
            color: var(--atsa-blue);
            font-size: 24px;
            flex: 0 0 auto;
        }

        .text-atsa-blue {
            color: var(--atsa-blue) !important;
        }

        .portal-input,
        .portal-card .form-control,
        .portal-card .form-select {
            border: 1px solid #dbe5f0;
            border-radius: 12px;
            padding: 13px 15px;
        }

        .portal-input:focus,
        .portal-card .form-control:focus,
        .portal-card .form-select:focus {
            border-color: var(--atsa-blue);
            box-shadow: 0 0 0 .22rem rgba(30, 58, 95, .08);
        }

        .portal-footer {
            color: #7c8ba1;
            font-size: 14px;
            text-align: center;
            padding: 8px 0 24px;
        }

        .sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 34, 54, .42);
            z-index: 1035;
        }

        body.sidebar-open .sidebar-backdrop {
            display: block;
        }

        @media (max-width: 1199.98px) {
            .afiliado-sidebar {
                transform: translateX(-100%);
            }

            body.sidebar-open .afiliado-sidebar {
                transform: translateX(0);
            }

            .afiliado-main {
                margin-left: 0;
            }

            .afiliado-topbar {
                left: 0;
                padding: 0 18px;
            }
        }

        @media (max-width: 575.98px) {
            .afiliado-content {
                padding: 18px 14px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
@php
    $afiliadoLayoutUser = auth()->user();
    $afiliadoFotoUrl = $afiliadoLayoutUser ? \App\Support\CarnetSupport::fotoUrl($afiliadoLayoutUser) : null;
    $afiliadoIniciales = $afiliadoLayoutUser ? \App\Support\CarnetSupport::initials($afiliadoLayoutUser->name) : 'A';
    $menu = [
        ['label' => 'Dashboard', 'href' => url('/afiliados/dashboard'), 'active' => request()->is('afiliados/dashboard'), 'icon' => 'ti-layout-dashboard'],
        ['label' => 'Mi Carnet', 'href' => url('/afiliados/mi-carnet'), 'active' => request()->is('afiliados/mi-carnet*'), 'icon' => 'ti-id-badge-2'],
        ['label' => 'Mis Pedidos', 'href' => url('/afiliados/mis-pedidos'), 'active' => request()->is('afiliados/mis-pedidos*'), 'icon' => 'ti-clipboard-list'],
        ['label' => 'Nueva Solicitud', 'href' => url('/afiliados/nuevo-pedido'), 'active' => request()->is('afiliados/nuevo-pedido'), 'icon' => 'ti-circle-plus'],
        ['label' => 'Consultas', 'href' => url('/afiliados/mis-consultas'), 'active' => request()->is('afiliados/mis-consultas'), 'icon' => 'ti-message-dots'],
        ['label' => 'Mi Testimonio', 'href' => url('/afiliados/mi-testimonio'), 'active' => request()->is('afiliados/mi-testimonio'), 'icon' => 'ti-quote'],
        ['label' => 'Beneficios', 'href' => url('/afiliados/beneficios'), 'active' => request()->is('afiliados/beneficios'), 'icon' => 'ti-gift'],
        ['label' => 'Descargas', 'href' => url('/afiliados/descargas'), 'active' => request()->is('afiliados/descargas'), 'icon' => 'ti-download'],
        ['label' => 'Mis Datos', 'href' => url('/afiliados/mis-datos'), 'active' => request()->is('afiliados/mis-datos'), 'icon' => 'ti-user-circle'],
    ];
@endphp

<div class="afiliado-shell">
    <div class="sidebar-backdrop" data-sidebar-close></div>

    <aside class="afiliado-sidebar">
        <div class="afiliado-brand">
            <a href="{{ url('/afiliados/dashboard') }}">
                <img src="{{ \App\Models\SiteSetting::logoUrl() }}" alt="ATSA Tucumán">
            </a>
            <button class="btn btn-light d-xl-none rounded-circle p-2" type="button" data-sidebar-close aria-label="Cerrar menú">
                <i class="ti ti-x fs-6"></i>
            </button>
        </div>

        <nav class="afiliado-nav">
            <div class="afiliado-nav-label">Menú principal</div>
            @foreach ($menu as $item)
                <a href="{{ $item['href'] }}" class="afiliado-nav-link {{ $item['active'] ? 'active' : '' }}">
                    <i class="ti {{ $item['icon'] }}"></i>
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach

            <div class="mt-4 p-3 rounded-3" style="background:#eef7ff;border:1px solid #dbeeff;">
                <h6 class="fw-bolder text-atsa-blue mb-2">ATSA Tucumán</h6>
                <p class="text-muted fs-2 mb-3">Portal privado para afiliados.</p>
                <a href="{{ url('/') }}" target="_blank" class="btn btn-sm btn-primary w-100 shadow-none">Ver sitio público</a>
            </div>
        </nav>

        <div class="afiliado-sidebar-profile">
            <div class="d-flex align-items-center gap-3">
                <span class="afiliado-avatar">
                    @if ($afiliadoFotoUrl)
                        <img src="{{ $afiliadoFotoUrl }}" alt="Foto de {{ $afiliadoLayoutUser?->name }}">
                    @else
                        {{ $afiliadoIniciales }}
                    @endif
                </span>
                <div class="min-w-0">
                    <h6 class="fw-bolder text-truncate mb-0">{{ $afiliadoLayoutUser?->name }}</h6>
                    <p class="text-muted text-truncate mb-0 fs-2">{{ $afiliadoLayoutUser?->numero_afiliado ?: 'Afiliado' }}</p>
                </div>
            </div>
        </div>
    </aside>

    <main class="afiliado-main">
        <header class="afiliado-topbar">
            <button class="btn btn-light rounded-circle p-2 d-xl-none me-3" type="button" data-sidebar-open aria-label="Abrir menú">
                <i class="ti ti-menu-2 fs-6"></i>
            </button>

            <div class="min-w-0">
                <p class="text-muted fs-2 fw-semibold mb-0">Portal de afiliados</p>
                <h5 class="fw-bolder mb-0 text-truncate">@yield('page_title', trim($__env->yieldContent('title', 'Dashboard')))</h5>
            </div>

            <div class="ms-auto d-flex align-items-center gap-3">
                <a href="{{ route('afiliado.carnet') }}" class="btn btn-light d-none d-md-inline-flex align-items-center gap-2 shadow-none">
                    <i class="ti ti-id"></i>
                    Mi carnet
                </a>

                <div class="dropdown">
                    <button class="btn p-0 border-0 bg-transparent d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="d-none d-md-inline fw-semibold text-dark">{{ $afiliadoLayoutUser?->name }}</span>
                        <span class="afiliado-avatar" style="width:38px;height:38px;">
                            @if ($afiliadoFotoUrl)
                                <img src="{{ $afiliadoFotoUrl }}" alt="Foto de {{ $afiliadoLayoutUser?->name }}">
                            @else
                                {{ $afiliadoIniciales }}
                            @endif
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 p-2">
                        <a href="{{ url('/afiliados/mis-datos') }}" class="dropdown-item rounded-2 py-2">
                            <i class="ti ti-user me-2"></i>Mis datos
                        </a>
                        <a href="{{ url('/afiliados/mi-carnet') }}" class="dropdown-item rounded-2 py-2">
                            <i class="ti ti-id me-2"></i>Mi carnet
                        </a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('afiliados.logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item rounded-2 py-2 text-danger">
                                <i class="ti ti-logout me-2"></i>Cerrar sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <div class="afiliado-content">
            @if (session('status') || session('success') || session('error'))
                <div class="alert {{ session('error') ? 'alert-danger' : 'alert-success' }} alert-dismissible fade show rounded-3 border-0 shadow-sm" role="alert">
                    {{ session('status') ?? session('success') ?? session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif

            @yield('content')
        </div>

        <div class="portal-footer">
            © {{ date('Y') }} ATSA Tucumán. Portal privado de afiliados.
        </div>
    </main>
</div>

<script src="{{ asset('modernize/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('modernize/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('modernize/libs/simplebar/dist/simplebar.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-sidebar-open]').forEach(function (button) {
            button.addEventListener('click', function () {
                document.body.classList.add('sidebar-open');
            });
        });

        document.querySelectorAll('[data-sidebar-close]').forEach(function (button) {
            button.addEventListener('click', function () {
                document.body.classList.remove('sidebar-open');
            });
        });
    });
</script>
@stack('scripts')
</body>
</html>
