@php
    $user = auth()->user();
    $avatar = $user?->foto_perfil ? asset('storage/'.$user->foto_perfil) : null;
    $initials = collect(explode(' ', trim($user?->name ?: 'Usuario')))
        ->filter()
        ->take(2)
        ->map(fn ($part) => mb_substr($part, 0, 1))
        ->implode('');
    $portalRole = $user?->cent_role ?: $user?->role;
    $unreadNotifications = $user
        ? \App\Models\CentNotificacion::query()
            ->whereNull('leida_at')
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere(function ($query) use ($user) {
                        $sedeIds = $user->matriculasCent()->pluck('cent_sede_id')->filter();
                        $query->whereNull('user_id')->whereIn('cent_sede_id', $sedeIds);
                    });
            })
            ->count()
        : 0;

    $navSections = [
        'Alumno' => [
            ['show' => in_array($portalRole, ['alumno', 'admin', 'directivo', 'coordinador'], true), 'route' => 'cent.alumno.dashboard', 'active' => 'cent.alumno.dashboard', 'icon' => 'ti-layout-dashboard', 'label' => 'Dashboard'],
            ['show' => in_array($portalRole, ['alumno', 'admin', 'directivo', 'coordinador'], true), 'route' => 'cent.alumno.carrera', 'active' => 'cent.alumno.carrera', 'icon' => 'ti-school', 'label' => 'Mi carrera'],
            ['show' => in_array($portalRole, ['alumno', 'admin', 'directivo', 'coordinador'], true), 'route' => 'cent.alumno.aula', 'active' => 'cent.alumno.aula*', 'icon' => 'ti-device-laptop', 'label' => 'Aula virtual'],
            ['show' => in_array($portalRole, ['alumno', 'admin', 'directivo', 'coordinador'], true), 'route' => 'cent.alumno.notas', 'active' => 'cent.alumno.notas', 'icon' => 'ti-report-analytics', 'label' => 'Mis notas'],
            ['show' => in_array($portalRole, ['alumno', 'admin', 'directivo', 'coordinador'], true), 'route' => 'cent.alumno.ficha', 'active' => 'cent.alumno.ficha', 'icon' => 'ti-file-certificate', 'label' => 'Ficha academica'],
            ['show' => in_array($portalRole, ['alumno', 'admin', 'directivo', 'coordinador'], true), 'route' => 'cent.alumno.mesas', 'active' => 'cent.alumno.mesas*', 'icon' => 'ti-calendar-event', 'label' => 'Mesas de examen'],
            ['show' => in_array($portalRole, ['alumno', 'admin', 'directivo', 'coordinador'], true), 'route' => 'cent.alumno.permisos', 'active' => 'cent.alumno.permisos*', 'icon' => 'ti-qr-code', 'label' => 'Permisos'],
            ['show' => in_array($portalRole, ['alumno', 'admin', 'directivo', 'coordinador'], true), 'route' => 'cent.alumno.carnet', 'active' => 'cent.alumno.carnet*', 'icon' => 'ti-id', 'label' => 'Carnet'],
            ['show' => in_array($portalRole, ['alumno', 'admin', 'directivo', 'coordinador'], true), 'route' => 'cent.alumno.legajo', 'active' => 'cent.alumno.legajo', 'icon' => 'ti-folder-open', 'label' => 'Mi legajo'],
            ['show' => in_array($portalRole, ['alumno', 'admin', 'directivo', 'coordinador'], true), 'route' => 'cent.alumno.cuotas', 'active' => 'cent.alumno.cuotas*', 'icon' => 'ti-receipt-2', 'label' => 'Cuotas'],
        ],
        'Docente' => [
            ['show' => in_array($portalRole, ['docente', 'admin', 'directivo', 'coordinador'], true), 'route' => 'cent.docente.dashboard', 'active' => 'cent.docente.dashboard', 'icon' => 'ti-layout-dashboard', 'label' => 'Dashboard'],
            ['show' => in_array($portalRole, ['docente', 'admin', 'directivo', 'coordinador'], true), 'route' => 'cent.docente.aula', 'active' => 'cent.docente.aula*', 'icon' => 'ti-device-laptop', 'label' => 'Aula virtual'],
            ['show' => in_array($portalRole, ['docente', 'admin', 'directivo', 'coordinador'], true), 'route' => 'cent.docente.comisiones', 'active' => 'cent.docente.comisiones', 'icon' => 'ti-chalkboard', 'label' => 'Mis comisiones'],
            ['show' => in_array($portalRole, ['docente', 'admin', 'directivo', 'coordinador'], true), 'route' => 'cent.docente.mesas', 'active' => 'cent.docente.mesas*', 'icon' => 'ti-calendar-event', 'label' => 'Mesas'],
        ],
        'Directivo' => [
            ['show' => in_array($portalRole, ['coordinador', 'directivo', 'admin'], true), 'route' => 'cent.directivo.dashboard', 'active' => 'cent.directivo.dashboard', 'icon' => 'ti-building-bank', 'label' => 'Dashboard'],
            ['show' => in_array($portalRole, ['coordinador', 'directivo', 'admin'], true), 'route' => 'cent.directivo.alumnos', 'active' => 'cent.directivo.alumnos', 'icon' => 'ti-users', 'label' => 'Alumnos'],
            ['show' => in_array($portalRole, ['coordinador', 'directivo', 'admin'], true), 'route' => 'cent.directivo.docentes', 'active' => 'cent.directivo.docentes', 'icon' => 'ti-user-screen', 'label' => 'Docentes'],
            ['show' => in_array($portalRole, ['coordinador', 'directivo', 'admin'], true), 'route' => 'cent.directivo.comisiones', 'active' => 'cent.directivo.comisiones*', 'icon' => 'ti-calendar-stats', 'label' => 'Comisiones'],
            ['show' => in_array($portalRole, ['coordinador', 'directivo', 'admin'], true), 'route' => 'cent.directivo.actas', 'active' => 'cent.directivo.actas', 'icon' => 'ti-clipboard-check', 'label' => 'Actas cursado'],
            ['show' => in_array($portalRole, ['coordinador', 'directivo', 'admin'], true), 'route' => 'cent.directivo.actas-mesas', 'active' => 'cent.directivo.actas-mesas*', 'icon' => 'ti-certificate', 'label' => 'Actas finales'],
            ['show' => in_array($portalRole, ['coordinador', 'directivo', 'admin'], true), 'route' => 'cent.directivo.reportes', 'active' => 'cent.directivo.reportes', 'icon' => 'ti-chart-donut', 'label' => 'Reportes'],
        ],
    ];
@endphp
<!DOCTYPE html>
<html lang="es" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Portal CENT Nro 74')</title>
    <link rel="stylesheet" href="{{ asset('modernize/css/styles.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.41.1/dist/tabler-icons.min.css">
    <style>
        :root {
            --cent-blue: #1e3a5f;
            --cent-blue-dark: #102338;
            --cent-sky: #49beff;
            --cent-soft: #ecf7ff;
            --cent-ink: #2a3547;
        }

        body {
            background: #f6f8fb;
            font-family: "Plus Jakarta Sans", sans-serif;
            color: var(--cent-ink);
        }

        .cent-shell {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 280px minmax(0, 1fr);
        }

        .cent-sidebar {
            background: #ffffff;
            border-right: 1px solid #e5eaef;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            padding: 24px;
            z-index: 30;
        }

        .cent-brand {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            color: var(--cent-blue);
            text-decoration: none;
            line-height: 1;
        }

        .cent-mark {
            width: 48px;
            height: 48px;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-weight: 900;
            background: linear-gradient(135deg, var(--cent-blue), var(--cent-sky));
            box-shadow: 0 12px 30px rgba(30, 58, 95, .22);
        }

        .cent-brand-title {
            display: block;
            font-size: 24px;
            font-weight: 900;
            letter-spacing: -.4px;
        }

        .cent-brand-subtitle {
            display: block;
            margin-top: 4px;
            font-size: 11px;
            font-weight: 800;
            color: #5d87ff;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .cent-nav-label {
            margin: 28px 0 12px;
            color: #7c8fac;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .8px;
        }

        .cent-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 12px;
            color: var(--cent-ink);
            font-weight: 700;
            margin-bottom: 8px;
            text-decoration: none;
            transition: all .2s ease;
        }

        .cent-link i { font-size: 21px; }

        .cent-link:hover,
        .cent-link.active {
            background: #ecf2ff;
            color: var(--cent-blue);
        }

        .cent-main { min-width: 0; }

        .cent-topbar {
            height: 76px;
            background: rgba(255, 255, 255, .95);
            backdrop-filter: blur(14px);
            border-bottom: 1px solid #e5eaef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            position: sticky;
            top: 0;
            z-index: 20;
        }

        .avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
            background: #ecf2ff;
            color: #5d87ff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
        }

        .avatar-lg {
            width: 72px;
            height: 72px;
            font-size: 24px;
        }

        .content-wrap { padding: 32px; }

        .modern-card {
            border: 0;
            border-radius: 22px;
            box-shadow: 0 12px 35px rgba(42, 53, 71, .08);
            background: #ffffff;
        }

        .soft-panel {
            border-radius: 22px;
            background: #f6f8fb;
            border: 1px solid #e5eaef;
        }

        .portal-kicker {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 7px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: .7px;
            text-transform: uppercase;
            background: rgba(255, 255, 255, .95);
            color: var(--cent-blue);
        }

        .portal-hero {
            border-radius: 26px;
            background:
                radial-gradient(circle at top right, rgba(73, 190, 255, .22), transparent 28%),
                linear-gradient(135deg, var(--cent-blue-dark), var(--cent-blue));
            color: #ffffff;
            overflow: hidden;
        }

        .portal-hero .text-muted {
            color: rgba(255, 255, 255, .72) !important;
        }

        .stat-tile {
            border-radius: 20px;
            background: #ffffff;
            box-shadow: 0 10px 28px rgba(42, 53, 71, .07);
            padding: 22px;
            border: 1px solid rgba(229, 234, 239, .75);
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .stat-tile:hover {
            transform: translateY(-2px);
            box-shadow: 0 18px 42px rgba(42, 53, 71, .1);
        }

        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .table > :not(caption) > * > * { padding: 15px 14px; }

        .portal-mobilebar {
            display: none;
            background: #ffffff;
            border-bottom: 1px solid #e5eaef;
            padding: 14px 18px;
        }

        .action-card {
            display: flex;
            align-items: center;
            gap: 14px;
            border-radius: 18px;
            background: #ffffff;
            border: 1px solid #e5eaef;
            padding: 16px;
            text-decoration: none;
            color: var(--cent-ink);
            transition: all .2s ease;
        }

        .action-card:hover {
            transform: translateY(-2px);
            border-color: rgba(93, 135, 255, .4);
            box-shadow: 0 14px 34px rgba(42, 53, 71, .1);
            color: var(--cent-blue);
        }

        .timeline-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #5d87ff;
            box-shadow: 0 0 0 5px #ecf2ff;
            margin-top: 8px;
            flex: 0 0 auto;
        }

        @media (max-width: 991px) {
            .cent-shell { display: block; }

            .portal-mobilebar {
                display: flex;
                align-items: center;
                justify-content: space-between;
                position: sticky;
                top: 0;
                z-index: 40;
            }

            .cent-sidebar {
                position: fixed;
                inset: 0 auto 0 0;
                width: 280px;
                transform: translateX(-100%);
                transition: transform .25s ease;
                box-shadow: 0 24px 80px rgba(42, 53, 71, .2);
            }

            body.sidebar-open .cent-sidebar { transform: translateX(0); }

            .cent-topbar {
                position: static;
                height: auto;
                padding: 18px;
            }

            .content-wrap { padding: 18px; }
        }

        @media print {
            .cent-sidebar,
            .cent-topbar,
            .portal-mobilebar,
            .no-print { display: none !important; }

            .cent-shell { display: block; }
            .content-wrap { padding: 0; }
            body { background: #ffffff; }
        }
    </style>
    @stack('styles')
</head>
<body>
<div class="portal-mobilebar">
    <button class="btn btn-light" type="button" data-sidebar-toggle>
        <i class="ti ti-menu-2 fs-6"></i>
    </button>
    <a href="{{ route('cent.index') }}" class="cent-brand">
        <span class="cent-mark">74</span>
        <span>
            <span class="cent-brand-title">CENT Nro 74</span>
            <span class="cent-brand-subtitle">Portal</span>
        </span>
    </a>
</div>

<div class="cent-shell">
    <aside class="cent-sidebar">
        <a href="{{ route('cent.index') }}" class="cent-brand mb-4">
            <span class="cent-mark">74</span>
            <span>
                    <span class="cent-brand-title">CENT Nro 74</span>
                    <span class="cent-brand-subtitle">Portal academico</span>
            </span>
        </a>

        @foreach($navSections as $section => $items)
            @if(collect($items)->contains('show', true))
                <p class="cent-nav-label">{{ $section }}</p>
                <nav>
                    @foreach($items as $item)
                        @if($item['show'])
                            <a class="cent-link {{ request()->routeIs($item['active']) ? 'active' : '' }}" href="{{ route($item['route']) }}">
                                <i class="ti {{ $item['icon'] }}"></i>
                                <span>{{ $item['label'] }}</span>
                            </a>
                        @endif
                    @endforeach
                </nav>
            @endif
        @endforeach

        <p class="cent-nav-label">Accesos</p>
        <nav>
            <a class="cent-link {{ request()->routeIs('cent.calendario') ? 'active' : '' }}" href="{{ route('cent.calendario') }}">
                <i class="ti ti-calendar-month"></i>
                <span>Calendario</span>
            </a>
            <a class="cent-link {{ request()->routeIs('cent.avisos') ? 'active' : '' }}" href="{{ route('cent.avisos') }}">
                <i class="ti ti-speakerphone"></i>
                <span>Avisos</span>
            </a>
            <a class="cent-link {{ request()->routeIs('cent.notificaciones') ? 'active' : '' }}" href="{{ route('cent.notificaciones') }}">
                <i class="ti ti-bell"></i>
                <span>Notificaciones</span>
                @if($unreadNotifications)
                    <span class="badge bg-danger ms-auto">{{ $unreadNotifications }}</span>
                @endif
            </a>
            <a class="cent-link {{ request()->routeIs('cent.perfil') ? 'active' : '' }}" href="{{ route('cent.perfil') }}">
                <i class="ti ti-user-cog"></i>
                <span>Mi perfil</span>
            </a>
            <a class="cent-link" href="{{ route('cent.preinscripcion') }}">
                <i class="ti ti-file-pencil"></i>
                <span>Preinscripción</span>
            </a>
            <a class="cent-link" href="{{ route('cent.index') }}">
                <i class="ti ti-world"></i>
                <span>Sitio publico</span>
            </a>
        </nav>

        <div class="mt-5 p-3 rounded-4 bg-light">
            <div class="d-flex align-items-center gap-3">
                @if($avatar)
                    <img src="{{ $avatar }}" class="avatar" alt="{{ $user->name }}">
                @else
                    <span class="avatar">{{ strtoupper($initials ?: 'U') }}</span>
                @endif
                <div class="min-w-0">
                    <strong class="d-block text-truncate">{{ $user?->name }}</strong>
                    <div class="small text-muted">{{ ucfirst($portalRole ?: 'usuario') }}</div>
                </div>
            </div>
            <form action="{{ route('cent.logout') }}" method="POST" class="mt-3">
                @csrf
                <button class="btn btn-outline-primary w-100">
                    <i class="ti ti-logout me-1"></i> Salir
                </button>
            </form>
        </div>
    </aside>

    <main class="cent-main">
        <header class="cent-topbar">
            <div>
                <div class="fw-bolder fs-5">@yield('header', 'CENT Nro 74')</div>
                <div class="small text-muted">Sistema academico independiente</div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('cent.notificaciones') }}" class="btn btn-light btn-sm d-none d-md-inline-flex position-relative">
                    <i class="ti ti-bell me-1"></i> Notificaciones
                    @if($unreadNotifications)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{ $unreadNotifications }}</span>
                    @endif
                </a>
                <a href="{{ route('cent.preinscripcion') }}" class="btn btn-primary btn-sm d-none d-md-inline-flex">
                    <i class="ti ti-file-plus me-1"></i> Nueva preinscripción
                </a>
                @if($avatar)
                    <img src="{{ $avatar }}" class="avatar" alt="{{ $user->name }}">
                @else
                    <span class="avatar">{{ strtoupper($initials ?: 'U') }}</span>
                @endif
            </div>
        </header>

        <div class="content-wrap">
            @if(session('status'))
                <div class="alert alert-success rounded-4">{{ session('status') }}</div>
            @endif

            @if(isset($errors) && $errors->any())
                <div class="alert alert-danger rounded-4">
                    Revisá los datos cargados. Hay campos pendientes o con formato incorrecto.
                </div>
            @endif

            @yield('content')
        </div>
    </main>
</div>

<script src="{{ asset('modernize/js/vendor.min.js') }}"></script>
<script src="{{ asset('modernize/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script>
    document.querySelectorAll('[data-sidebar-toggle]').forEach((button) => {
        button.addEventListener('click', () => document.body.classList.toggle('sidebar-open'));
    });
</script>
@stack('scripts')
</body>
</html>

