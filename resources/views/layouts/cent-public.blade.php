<!DOCTYPE html>
<html lang="es" dir="ltr" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CENT N°74 — Formación en salud · Tucumán')</title>
    <meta name="description" content="@yield('meta_description', 'CENT N°74. Centro Educativo de Nivel Terciario. Carreras técnicas en ciencias de la salud en Tucumán.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Outfit:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('modernize/css/styles.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.41.1/dist/tabler-icons.min.css">
    <style>
        :root {
            --cent-blue: #1e3a5f;
            --cent-blue-dark: #102338;
            --cent-sky: #49beff;
            --cent-soft: #ecf7ff;
            --cent-ink: #1a2a3d;
            --cent-muted: #5a7184;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #ffffff;
            color: var(--cent-ink);
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4,
        .display-1, .display-2, .display-3,
        .display-4, .display-5, .display-6,
        .fw-bolder, .fw-bold {
            font-family: 'Outfit', sans-serif;
        }

        .cent-brand {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            line-height: 1;
            text-decoration: none;
            color: var(--cent-blue);
        }

        .cent-mark {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--cent-blue), var(--cent-sky));
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-family: 'Outfit', sans-serif;
            font-weight: 900;
            font-size: 18px;
            box-shadow: 0 12px 28px rgba(30, 58, 95, .22);
        }

        .cent-brand strong {
            display: block;
            font-size: 26px;
            font-weight: 900;
            letter-spacing: -.4px;
        }

        .cent-brand .cent-tagline {
            display: block;
            font-size: 11px;
            color: var(--cent-sky);
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            margin-top: 3px;
        }

        .cent-header {
            background: rgba(255, 255, 255, .97);
            backdrop-filter: blur(20px);
            box-shadow: 0 4px 20px rgba(30, 58, 95, .07);
            border-bottom: 1px solid rgba(30, 58, 95, .06);
        }

        .cent-header .nav-link {
            color: #2a3547;
            border-radius: 10px;
            padding: 8px 14px;
            font-size: 14px;
            font-weight: 600;
            transition: background .18s, color .18s;
        }

        .cent-header .nav-link:hover,
        .cent-header .nav-link.active {
            background: var(--cent-soft);
            color: var(--cent-blue);
        }

        .cent-hero {
            position: relative;
            background-size: cover;
            background-position: center;
            overflow: hidden;
        }

        .cent-hero::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(
                105deg,
                rgba(10, 26, 50, .93) 0%,
                rgba(30, 58, 95, .82) 55%,
                rgba(30, 58, 95, .45) 100%
            );
        }

        .cent-hero > * {
            position: relative;
            z-index: 1;
        }

        .btn-cent {
            background: var(--cent-blue);
            border-color: var(--cent-blue);
            color: #fff;
            border-radius: 12px;
            font-weight: 700;
            transition: background .18s, box-shadow .18s, transform .15s;
        }

        .btn-cent:hover {
            background: var(--cent-blue-dark);
            border-color: var(--cent-blue-dark);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(30, 58, 95, .25);
        }

        .btn-outline-cent {
            border: 2px solid var(--cent-blue);
            color: var(--cent-blue);
            background: transparent;
            border-radius: 12px;
            font-weight: 700;
            transition: background .18s, color .18s, transform .15s;
        }

        .btn-outline-cent:hover {
            background: var(--cent-blue);
            color: #fff;
            transform: translateY(-1px);
        }

        .bg-cent-blue {
            background: linear-gradient(135deg, var(--cent-blue-dark) 0%, var(--cent-blue) 60%, #2560a0 100%) !important;
        }

        .section-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 16px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 800;
            margin-bottom: 18px;
            letter-spacing: .2px;
        }

        .cent-card {
            border: 0;
            border-radius: 24px;
            box-shadow: 0 8px 30px rgba(30, 58, 95, .09);
            transition: transform .25s ease, box-shadow .25s ease;
        }

        .cent-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 50px rgba(30, 58, 95, .14);
        }

        .cent-muted-box {
            background: #f4f7fb;
            border: 1px solid #e2e9f1;
            border-radius: 18px;
        }

        .feature-icon,
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            font-size: 22px;
        }

        .career-header {
            height: 200px;
            display: flex;
            align-items: flex-end;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        .career-header-img {
            position: absolute;
            inset: 0;
            object-fit: cover;
            width: 100%;
            height: 100%;
            transition: transform .4s ease;
        }

        .cent-card:hover .career-header-img {
            transform: scale(1.05);
        }

        .career-header-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, transparent 20%, rgba(0, 0, 0, .65) 100%);
        }

        .career-header-content {
            position: relative;
            z-index: 1;
        }

        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .text-justify {
            text-align: justify;
            text-justify: inter-word;
        }

        .cent-footer {
            background: #0a1a32;
        }

        .cent-footer-link {
            color: rgba(255, 255, 255, .55);
            text-decoration: none;
            font-size: 14px;
            transition: color .18s;
        }

        .cent-footer-link:hover {
            color: var(--cent-sky);
        }

        .cent-social-btn {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: rgba(255, 255, 255, .08);
            border: 1px solid rgba(255, 255, 255, .12);
            color: rgba(255, 255, 255, .7);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            transition: background .18s, color .18s;
            text-decoration: none;
        }

        .cent-social-btn:hover {
            background: var(--cent-sky);
            color: #fff;
            border-color: var(--cent-sky);
        }

        @media (max-width: 991px) {
            .cent-brand strong {
                font-size: 21px;
            }

            .cent-mark {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
<header class="cent-header sticky-top">
    <nav class="navbar navbar-expand-lg py-2">
        <div class="container">
            <a href="{{ route('cent.index') }}" class="cent-brand">
                <span class="cent-mark">74</span>
                <span>
                    <strong>CENT N°74</strong>
                    <span class="cent-tagline">Formación en salud · Tucumán</span>
                </span>
            </a>

            <button class="navbar-toggler border-0 p-2" type="button" data-bs-toggle="collapse" data-bs-target="#centNav" aria-label="Abrir menú">
                <i class="ti ti-menu-2 fs-4 text-dark"></i>
            </button>

            <div class="collapse navbar-collapse" id="centNav">
                <ul class="navbar-nav mx-auto gap-lg-1 mt-3 mt-lg-0">
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('cent.index') ? 'active' : '' }}" href="{{ route('cent.index') }}">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('cent.carreras','cent.carrera') ? 'active' : '' }}" href="{{ route('cent.carreras') }}">Carreras</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('cent.sedes') ? 'active' : '' }}" href="{{ route('cent.sedes') }}">Sedes</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('cent.novedades') ? 'active' : '' }}" href="{{ route('cent.novedades') }}">Novedades</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('cent.mesas') ? 'active' : '' }}" href="{{ route('cent.mesas') }}">Mesas</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('cent.horarios') ? 'active' : '' }}" href="{{ route('cent.horarios') }}">Horarios</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('cent.requisitos') ? 'active' : '' }}" href="{{ route('cent.requisitos') }}">Ingreso</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('cent.descargas') ? 'active' : '' }}" href="{{ route('cent.descargas') }}">Descargas</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('cent.contacto') ? 'active' : '' }}" href="{{ route('cent.contacto') }}">Contacto</a></li>
                </ul>
                <div class="d-flex gap-2 mt-3 mt-lg-0">
                    <a href="{{ route('cent.preinscripcion') }}" class="btn btn-outline-cent btn-sm">
                        <i class="ti ti-user-plus me-1"></i>Preinscripción
                    </a>
                    <a href="{{ route('cent.login') }}" class="btn btn-cent btn-sm">
                        <i class="ti ti-school me-1"></i>Portal
                    </a>
                </div>
            </div>
        </div>
    </nav>
</header>

<main>@yield('content')</main>

<footer class="cent-footer text-white pt-5 pb-3">
    <div class="container">
        <div class="row g-5 pb-4">
            <div class="col-lg-4">
                <a href="{{ route('cent.index') }}" class="cent-brand text-white mb-3 d-inline-flex">
                    <span class="cent-mark">74</span>
                    <span>
                        <strong class="text-white">CENT N°74</strong>
                        <span class="cent-tagline">Educación superior en salud</span>
                    </span>
                </a>
                <p class="text-white-50 mt-3 mb-4" style="font-size:14px;line-height:1.7;">
                    Centro Educativo de Nivel Terciario N°74 de ATSA Tucumán. Formamos técnicos superiores en ciencias de la salud con presencia en toda la provincia.
                </p>
                @php
                    try { $centFooterSetting = \App\Models\SiteSetting::current(); } catch (\Throwable $e) { $centFooterSetting = null; }
                @endphp
                @if($centFooterSetting)
                <div class="d-flex gap-2">
                    @if($centFooterSetting->facebook_url)
                        <a href="{{ $centFooterSetting->facebook_url }}" target="_blank" rel="noopener" class="cent-social-btn" title="Facebook"><i class="ti ti-brand-facebook"></i></a>
                    @endif
                    @if($centFooterSetting->instagram_url)
                        <a href="{{ $centFooterSetting->instagram_url }}" target="_blank" rel="noopener" class="cent-social-btn" title="Instagram"><i class="ti ti-brand-instagram"></i></a>
                    @endif
                    @if($centFooterSetting->youtube_url)
                        <a href="{{ $centFooterSetting->youtube_url }}" target="_blank" rel="noopener" class="cent-social-btn" title="YouTube"><i class="ti ti-brand-youtube"></i></a>
                    @endif
                </div>
                @endif
            </div>

            <div class="col-6 col-lg-2">
                <h6 class="text-white fw-bold mb-3" style="font-size:13px;text-transform:uppercase;letter-spacing:.8px;">Institución</h6>
                <div class="d-flex flex-column gap-2">
                    <a class="cent-footer-link" href="{{ route('cent.index') }}">Inicio</a>
                    <a class="cent-footer-link" href="{{ route('cent.carreras') }}">Carreras</a>
                    <a class="cent-footer-link" href="{{ route('cent.sedes') }}">Sedes</a>
                    <a class="cent-footer-link" href="{{ route('cent.novedades') }}">Novedades</a>
                    <a class="cent-footer-link" href="{{ route('cent.mesas') }}">Mesas de examen</a>
                    <a class="cent-footer-link" href="{{ route('cent.horarios') }}">Horarios</a>
                    <a class="cent-footer-link" href="{{ route('cent.descargas') }}">Descargas</a>
                    <a class="cent-footer-link" href="{{ route('cent.requisitos') }}">Ingreso</a>
                    <a class="cent-footer-link" href="{{ route('cent.faq') }}">Preguntas frecuentes</a>
                    <a class="cent-footer-link" href="{{ route('cent.contacto') }}">Contacto</a>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <h6 class="text-white fw-bold mb-3" style="font-size:13px;text-transform:uppercase;letter-spacing:.8px;">Carreras</h6>
                <div class="d-flex flex-column gap-2">
                    <span class="cent-footer-link">Enfermería Profesional</span>
                    <span class="cent-footer-link">Agente Socio Sanitario</span>
                    <span class="cent-footer-link">Diagnóstico por Imágenes</span>
                    <span class="cent-footer-link">Farmacia</span>
                    <span class="cent-footer-link">Laboratorio de Análisis Clínicos</span>
                    <span class="cent-footer-link">Esterilización</span>
                </div>
            </div>

            <div class="col-lg-3">
                <h6 class="text-white fw-bold mb-3" style="font-size:13px;text-transform:uppercase;letter-spacing:.8px;">Contacto</h6>
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex gap-2">
                        <i class="ti ti-map-pin mt-1 flex-shrink-0" style="color:var(--cent-sky);"></i>
                        <span class="cent-footer-link">{{ $centFooterSetting?->address ?: 'Paraguay y Thames, San Miguel de Tucumán' }}</span>
                    </div>
                    <div class="d-flex gap-2">
                        <i class="ti ti-phone flex-shrink-0" style="color:var(--cent-sky);"></i>
                        <span class="cent-footer-link">{{ $centFooterSetting?->phone ?: '0381 4332175' }}</span>
                    </div>
                    @if($centFooterSetting?->email)
                    <div class="d-flex gap-2">
                        <i class="ti ti-mail flex-shrink-0" style="color:var(--cent-sky);"></i>
                        <a href="mailto:{{ $centFooterSetting->email }}" class="cent-footer-link">{{ $centFooterSetting->email }}</a>
                    </div>
                    @endif
                </div>
                <a href="{{ route('cent.preinscripcion') }}" class="btn btn-cent btn-sm mt-4 w-100">
                    <i class="ti ti-user-plus me-1"></i>Preinscribirme
                </a>
            </div>
        </div>

        <div class="border-top pt-4" style="border-color:rgba(255,255,255,.08)!important;">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <p class="text-white-50 mb-0" style="font-size:13px;">
                        © {{ date('Y') }} CENT N°74 · <a href="{{ route('home') }}" class="cent-footer-link">ATSA Tucumán</a> · Todos los derechos reservados.
                    </p>
                </div>
                <div class="col-md-4 text-md-end mt-2 mt-md-0">
                    <a href="{{ route('cent.login') }}" class="text-white-50 text-decoration-none" style="font-size:13px;">
                        <i class="ti ti-lock me-1"></i>Portal académico
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>

<script src="{{ asset('modernize/js/vendor.min.js') }}"></script>
<script src="{{ asset('modernize/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
@stack('scripts')
</body>
</html>
