@php
    $siteSetting = \App\Models\SiteSetting::current();
    $siteLogo = \App\Models\SiteSetting::logoUrl();
    $siteFavicon = \App\Models\SiteSetting::faviconUrl();
    $isCentSection = request()->routeIs('cent.*');
    $centUrl = env('CENT_URL', url('/cent74'));
    $facebookUrl = $siteSetting->facebook_url ?: 'https://www.facebook.com/ATSATucuman';
    $instagramUrl = $siteSetting->instagram_url ?: 'https://www.instagram.com/atsa.tucuman/';
    $tiktokUrl = $siteSetting->tiktok_url ?: 'https://www.tiktok.com/@atsa.tucuman';
    $youtubeUrl = $siteSetting->youtube_url;
    $portalUrl = $isCentSection
        ? route('cent.login')
        : (auth()->check() ? url('/afiliados/dashboard') : route('afiliados.login'));
    $portalLabel = $isCentSection ? 'Portal académico' : 'Área afiliados';
@endphp
<!DOCTYPE html>
<html lang="es" dir="ltr" data-bs-theme="light" data-color-theme="Blue_Theme" data-layout="vertical">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="shortcut icon" type="image/png" href="{{ $siteFavicon }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Outfit:wght@700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('modernize/css/styles.css') }}" />
    <link rel="stylesheet" href="{{ asset('modernize/libs/owl.carousel/dist/assets/owl.carousel.min.css') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.41.1/dist/tabler-icons.min.css" />
    <title>@yield('title', 'ATSA Tucumán')</title>
    <meta name="description" content="@yield('meta_description', 'Sindicato de los trabajadores de la sanidad en Tucumán. Representación gremial, afiliación, formación y beneficios.')" />
    <meta name="robots" content="index, follow" />
    <meta name="author" content="ATSA Tucumán" />
    <meta property="og:type" content="@yield('og_type', 'website')" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:title" content="@yield('title', 'ATSA Tucumán')" />
    <meta property="og:description" content="@yield('meta_description', 'Sindicato de los trabajadores de la sanidad en Tucumán. Representación gremial, afiliación, formación y beneficios.')" />
    <meta property="og:image" content="@yield('og_image', asset('images/logo-atsa.png'))" />
    <meta property="og:site_name" content="ATSA Tucumán" />
    <meta property="og:locale" content="es_AR" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="@yield('title', 'ATSA Tucumán')" />
    <meta name="twitter:description" content="@yield('meta_description', 'Sindicato de los trabajadores de la sanidad en Tucumán.')" />
    <meta name="twitter:image" content="@yield('og_image', asset('images/logo-atsa.png'))" />
    <link rel="canonical" href="{{ url()->current() }}" />
    @stack('head')
    @php $gaId = $siteSetting->google_analytics_id ?? null; @endphp
    @if($gaId)
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ $gaId }}');
        </script>
    @endif
    <style>
        :root {
            --atsa-blue: #1e3a5f;
            --atsa-blue-dark: #142940;
            --atsa-light-blue: #ecf2ff;
            --atsa-sky: #49beff;
            --atsa-red: #c0392b;
            --font-body: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            --font-heading: 'Outfit', 'Inter', sans-serif;
        }

        body, p, li, td, input, textarea, select, button {
            font-family: var(--font-body);
        }

        h1, h2, h3, h4, h5, h6, .fw-bold, .fw-bolder {
            font-family: var(--font-heading);
            letter-spacing: -0.02em;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background: #f7f9fd;
            color: #253248;
            -webkit-font-smoothing: antialiased;
            text-rendering: optimizeLegibility;
        }

        ::selection {
            background: rgba(73, 190, 255, .26);
            color: var(--atsa-blue-dark);
        }

        a,
        .btn,
        .dropdown-item,
        .nav-link {
            transition: color .18s ease, background-color .18s ease, border-color .18s ease, box-shadow .18s ease, transform .18s ease;
        }

        .btn:focus-visible,
        .nav-link:focus-visible,
        .dropdown-item:focus-visible,
        .form-control:focus,
        .form-select:focus {
            box-shadow: 0 0 0 .24rem rgba(73, 190, 255, .20) !important;
            outline: 0;
        }

        .header-fp .navbar {
            backdrop-filter: blur(14px);
            background: rgba(236, 242, 255, .94) !important;
            border-bottom: 1px solid rgba(30, 58, 95, .08);
            box-shadow: 0 14px 34px rgba(42, 53, 71, .08);
        }

        .header-fp .navbar-nav .nav-link {
            border-radius: 12px;
            padding: 10px 14px !important;
        }

        .header-fp .navbar-nav .nav-link:hover,
        .header-fp .navbar-nav .nav-link.active {
            background: rgba(93, 135, 255, .12);
            color: #5d87ff !important;
            transform: translateY(-1px);
        }

        .atsa-logo {
            height: 56px;
            width: auto;
            object-fit: contain;
        }

        .atsa-hero {
            min-height: 720px;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .atsa-page-hero {
            min-height: 400px;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .atsa-hero::before,
        .atsa-page-hero::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, rgba(15, 34, 54, .92), rgba(30, 58, 95, .76));
        }

        .atsa-hero > *,
        .atsa-page-hero > * {
            position: relative;
            z-index: 1;
        }

        .bg-atsa-blue { background: var(--atsa-blue) !important; }
        .text-atsa-blue { color: var(--atsa-blue) !important; }
        .text-atsa-sky { color: var(--atsa-sky) !important; }
        .text-atsa-red { color: var(--atsa-red) !important; }

        .section-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            width: auto;
            max-width: 100%;
            padding: 7px 16px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 800;
            line-height: 1.2;
            text-transform: uppercase;
            letter-spacing: .04em;
            vertical-align: middle;
        }

        .section-badge i {
            flex: 0 0 auto;
            font-size: 16px;
            line-height: 1;
        }

        .btn-atsa {
            background: var(--atsa-blue);
            border-color: var(--atsa-blue);
            color: #fff;
            box-shadow: 0 12px 22px rgba(30, 58, 95, .18);
        }

        .btn-atsa:hover {
            background: var(--atsa-blue-dark);
            border-color: var(--atsa-blue-dark);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 16px 28px rgba(30, 58, 95, .22);
        }

        .btn-outline-atsa {
            border: 2px solid var(--atsa-blue);
            color: var(--atsa-blue);
            background: transparent;
        }

        .btn-outline-atsa:hover {
            background: var(--atsa-blue);
            color: #fff;
            transform: translateY(-1px);
        }

        .btn-primary,
        .btn-light,
        .btn-atsa,
        .btn-outline-atsa {
            border-radius: 12px;
            font-weight: 700;
        }

        .header-fp .dropdown-menu {
            border: 1px solid rgba(30, 58, 95, .08);
            border-radius: 14px;
            box-shadow: 0 22px 54px rgba(42, 53, 71, .16);
            padding: 14px;
            min-width: 240px;
        }

        .header-fp .dropdown-item {
            border-radius: 12px;
            font-weight: 500;
            padding: 12px 16px;
            color: #2a3547;
        }

        .header-fp .dropdown-item:hover {
            background: var(--atsa-light-blue);
            color: var(--atsa-blue);
            transform: translateX(2px);
        }

        .main-wrapper {
            background: linear-gradient(180deg, #f7f9fd 0%, #fff 36%);
        }

        .main-wrapper .card,
        .main-wrapper .atsa-impact-card,
        .main-wrapper .atsa-blog-card,
        .main-wrapper .service-card {
            border: 1px solid rgba(30, 58, 95, .08) !important;
            box-shadow: 0 16px 34px rgba(42, 53, 71, .07);
        }

        .main-wrapper .card:hover,
        .main-wrapper .atsa-impact-card:hover,
        .main-wrapper .atsa-blog-card:hover,
        .main-wrapper .service-card:hover {
            box-shadow: 0 22px 46px rgba(42, 53, 71, .11);
            transform: translateY(-2px);
        }

        .section-badge {
            box-shadow: 0 10px 22px rgba(93, 135, 255, .10);
        }

        .atsa-hero,
        .atsa-page-hero {
            overflow: hidden;
            box-shadow: inset 0 -1px 0 rgba(255, 255, 255, .22);
        }

        .main-wrapper p {
            text-align: justify;
            text-justify: inter-word;
        }

        .main-wrapper .text-center p,
        .main-wrapper .badge,
        .main-wrapper .btn,
        .main-wrapper figcaption {
            text-align: inherit;
        }

        .mob-nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 14px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            color: #2a3547;
            text-decoration: none;
            border: none;
            background: transparent;
            width: 100%;
        }

        .mob-nav-link:hover,
        .mob-nav-link--active {
            background: #ecf2ff;
            color: var(--atsa-blue);
        }

        .mob-nav-link .ti {
            font-size: 18px;
            color: var(--atsa-blue);
            flex-shrink: 0;
        }

        .mob-subnav {
            padding: 4px 0 8px 18px;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .mob-subnav-link {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 9px 12px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            color: #5a6a85;
            text-decoration: none;
        }

        .mob-subnav-link:hover {
            background: #ecf2ff;
            color: var(--atsa-blue);
        }

        .mob-subnav-link .ti {
            color: var(--atsa-sky);
        }

        .atsa-footer {
            background: #0d1e30;
        }

        .atsa-footer-cta {
            background: linear-gradient(135deg, var(--atsa-blue) 0%, #163050 100%);
            padding: 48px 0;
            border-bottom: 1px solid rgba(73, 190, 255, .15);
        }

        .atsa-footer-cta-label {
            font-size: 12px;
            font-weight: 800;
            letter-spacing: .1em;
            color: var(--atsa-sky);
            margin-bottom: 8px;
        }

        .atsa-footer-cta-title {
            font-size: clamp(1.4rem, 2.5vw, 1.9rem);
            font-weight: 900;
            color: #fff;
            margin-bottom: 8px;
            line-height: 1.15;
        }

        .atsa-footer-cta-sub {
            color: rgba(255, 255, 255, .72);
            font-size: 15px;
            line-height: 1.6;
            margin: 0;
        }

        .atsa-footer-body {
            padding: 56px 0 0;
        }

        .footer-logo {
            height: 50px;
            width: auto;
            object-fit: contain;
            filter: brightness(0) invert(1);
            opacity: .9;
        }

        .atsa-footer-text {
            color: rgba(255, 255, 255, .55);
            font-size: 14px;
            line-height: 1.7;
        }

        .atsa-social-btn {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, .08);
            border: 1px solid rgba(255, 255, 255, .1);
            color: rgba(255, 255, 255, .7);
            font-size: 18px;
            text-decoration: none;
        }

        .atsa-social-btn:hover {
            background: var(--atsa-blue);
            border-color: var(--atsa-sky);
            color: #fff;
        }

        .atsa-footer-heading {
            font-size: 12px;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, .4);
            margin-bottom: 20px;
        }

        .atsa-footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .atsa-footer-links a {
            color: rgba(255, 255, 255, .62);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }

        .atsa-footer-links a:hover {
            color: var(--atsa-sky);
        }

        .atsa-footer-contact-row {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .atsa-footer-contact-row > i {
            font-size: 18px;
            color: var(--atsa-sky);
            margin-top: 2px;
            flex-shrink: 0;
        }

        .atsa-footer-contact-row strong {
            display: block;
            color: rgba(255, 255, 255, .8);
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 2px;
        }

        .atsa-footer-contact-row span {
            color: rgba(255, 255, 255, .5);
            font-size: 13px;
            line-height: 1.5;
        }

        .atsa-footer-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            padding: 20px 0;
            margin-top: 40px;
            border-top: 1px solid rgba(255, 255, 255, .08);
            color: rgba(255, 255, 255, .38);
            font-size: 13px;
        }

        .atsa-footer-bottom a {
            color: rgba(255, 255, 255, .38);
            text-decoration: none;
            font-size: 13px;
        }

        .atsa-footer-bottom a:hover {
            color: var(--atsa-sky);
        }

        .preloader {
            position: fixed;
            inset: 0;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            transition: opacity .5s ease, visibility .5s ease;
        }

        .preloader.hide {
            opacity: 0;
            visibility: hidden;
        }

        .wa-float:hover {
            transform: scale(1.1) translateY(-3px);
        }

        @media (min-width: 992px) {
            .header-fp .nav-item.dropdown:hover > .dropdown-menu {
                display: block;
                margin-top: 0;
            }
        }

        @media (max-width: 767.98px) {
            .header-fp .navbar {
                padding-top: 10px !important;
                padding-bottom: 10px !important;
            }

            .atsa-logo {
                height: 48px;
            }

            .atsa-hero {
                min-height: 560px;
            }

            .atsa-page-hero {
                min-height: 340px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="preloader">
        <img src="{{ $siteLogo }}" alt="ATSA Tucumán" class="img-fluid" style="max-width: 90px;" />
    </div>

    <header class="header-fp p-0 w-100 sticky-top">
        <nav class="navbar navbar-expand-lg bg-primary-subtle py-2 py-lg-10">
            <div class="custom-container d-flex align-items-center justify-content-between">
                <a href="{{ route('home') }}" class="text-nowrap logo-img">
                    <img src="{{ $siteLogo }}" class="atsa-logo" alt="ATSA Tucumán" />
                </a>

                <button class="navbar-toggler border-0 p-0 shadow-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                    <i class="ti ti-menu-2 fs-8"></i>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mx-auto mb-2 gap-xl-6 gap-5 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link fs-4 fw-bold text-dark link-primary {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Inicio</a>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link fs-4 fw-bold text-dark link-primary dropdown-toggle {{ request()->routeIs('sindicato.*') ? 'active' : '' }}" href="{{ route('sindicato.index') }}" role="button" data-bs-toggle="dropdown">El Sindicato</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('sindicato.index') }}#historia">Historia</a></li>
                                <li><a class="dropdown-item" href="{{ route('sindicato.index') }}#autoridades">Autoridades</a></li>
                                <li><a class="dropdown-item" href="{{ route('sindicato.index') }}#organigrama">Organigrama</a></li>
                                <li><a class="dropdown-item" href="{{ route('sindicato.index') }}#infraestructura">Infraestructura</a></li>
                            </ul>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link fs-4 fw-bold text-dark link-primary dropdown-toggle {{ request()->routeIs('gremial.*') || request()->routeIs('escalas.*') || request()->routeIs('delegados.*') || request()->routeIs('documentos.*') ? 'active' : '' }}" href="{{ route('gremial.index') }}" role="button" data-bs-toggle="dropdown">Gremial</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('gremial.index') }}#comunicados">Comunicados</a></li>
                                <li><a class="dropdown-item" href="{{ route('gremial.index') }}#paritarias">Paritarias y acuerdos</a></li>
                                <li><a class="dropdown-item" href="{{ route('escalas.index') }}">Escalas salariales</a></li>
                                <li><a class="dropdown-item" href="{{ route('delegados.index') }}">Delegados</a></li>
                                <li><a class="dropdown-item" href="{{ route('gremial.index') }}#derechos">Derechos del trabajador</a></li>
                                <li><a class="dropdown-item" href="{{ route('documentos.index') }}">Documentos institucionales</a></li>
                            </ul>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link fs-4 fw-bold text-dark link-primary dropdown-toggle {{ request()->routeIs('afiliados.index') || request()->routeIs('turismo.*') ? 'active' : '' }}" href="{{ route('afiliados.index') }}" role="button" data-bs-toggle="dropdown">Afiliados</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('afiliados.index') }}#beneficios">Beneficios</a></li>
                                <li><a class="dropdown-item" href="{{ route('afiliados.index') }}#afiliarse">Cómo afiliarse</a></li>
                                <li><a class="dropdown-item" href="{{ route('turismo.index') }}">Turismo y recreación</a></li>
                                <li><a class="dropdown-item" href="{{ route('afiliados.index') }}#accion-social">Acción social</a></li>
                                <li><a class="dropdown-item" href="{{ route('afiliados.index') }}#faq">Preguntas frecuentes</a></li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link fs-4 fw-bold text-dark link-primary" href="{{ $centUrl }}">Formación</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link fs-4 fw-bold text-dark link-primary {{ request()->routeIs('filiales.*') ? 'active' : '' }}" href="{{ route('filiales.index') }}">Filiales</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link fs-4 fw-bold text-dark link-primary {{ request()->routeIs('novedades.*') ? 'active' : '' }}" href="{{ route('novedades.index') }}">Novedades</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link fs-4 fw-bold text-dark link-primary {{ request()->routeIs('contacto.*') ? 'active' : '' }}" href="{{ route('contacto.index') }}">Contacto</a>
                        </li>
                    </ul>

                    <div>
                        <a href="{{ $portalUrl }}" class="btn btn-primary py-8 px-9">{{ $portalLabel }}</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel" style="width: 320px;">
        <div class="offcanvas-header border-bottom py-3 px-4">
            <a href="{{ route('home') }}"><img src="{{ $siteLogo }}" class="atsa-logo" alt="ATSA Tucumán" /></a>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
        </div>
        <div class="offcanvas-body px-3 py-3">
            <a href="{{ route('home') }}" class="mob-nav-link {{ request()->routeIs('home') ? 'mob-nav-link--active' : '' }}">
                <i class="ti ti-home-2"></i> Inicio
            </a>

            <div class="accordion accordion-flush" id="mobAccordion">
                <div class="mob-accordion-item">
                    <button class="mob-nav-link w-100 text-start d-flex justify-content-between {{ request()->routeIs('sindicato.*') ? 'mob-nav-link--active' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#mobSindicato" aria-expanded="{{ request()->routeIs('sindicato.*') ? 'true' : 'false' }}">
                        <span class="d-flex align-items-center gap-2"><i class="ti ti-building-community"></i> El Sindicato</span>
                        <i class="ti ti-chevron-down"></i>
                    </button>
                    <div id="mobSindicato" class="collapse {{ request()->routeIs('sindicato.*') ? 'show' : '' }}" data-bs-parent="#mobAccordion">
                        <div class="mob-subnav">
                            <a href="{{ route('sindicato.index') }}#historia" class="mob-subnav-link"><i class="ti ti-history"></i> Historia</a>
                            <a href="{{ route('sindicato.index') }}#autoridades" class="mob-subnav-link"><i class="ti ti-users-group"></i> Autoridades</a>
                            <a href="{{ route('sindicato.index') }}#organigrama" class="mob-subnav-link"><i class="ti ti-sitemap"></i> Organigrama</a>
                            <a href="{{ route('sindicato.index') }}#infraestructura" class="mob-subnav-link"><i class="ti ti-building"></i> Infraestructura</a>
                        </div>
                    </div>
                </div>

                <div class="mob-accordion-item">
                    <button class="mob-nav-link w-100 text-start d-flex justify-content-between {{ request()->routeIs('gremial.*') || request()->routeIs('escalas.*') || request()->routeIs('delegados.*') || request()->routeIs('documentos.*') ? 'mob-nav-link--active' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#mobGremial" aria-expanded="{{ request()->routeIs('gremial.*') || request()->routeIs('escalas.*') || request()->routeIs('delegados.*') || request()->routeIs('documentos.*') ? 'true' : 'false' }}">
                        <span class="d-flex align-items-center gap-2"><i class="ti ti-scale"></i> Gremial</span>
                        <i class="ti ti-chevron-down"></i>
                    </button>
                    <div id="mobGremial" class="collapse {{ request()->routeIs('gremial.*') || request()->routeIs('escalas.*') || request()->routeIs('delegados.*') || request()->routeIs('documentos.*') ? 'show' : '' }}" data-bs-parent="#mobAccordion">
                        <div class="mob-subnav">
                            <a href="{{ route('gremial.index') }}#comunicados" class="mob-subnav-link"><i class="ti ti-megaphone"></i> Comunicados</a>
                            <a href="{{ route('gremial.index') }}#paritarias" class="mob-subnav-link"><i class="ti ti-file-text"></i> Paritarias</a>
                            <a href="{{ route('escalas.index') }}" class="mob-subnav-link"><i class="ti ti-chart-bar"></i> Escalas</a>
                            <a href="{{ route('delegados.index') }}" class="mob-subnav-link"><i class="ti ti-user-star"></i> Delegados</a>
                            <a href="{{ route('documentos.index') }}" class="mob-subnav-link"><i class="ti ti-files"></i> Documentos</a>
                        </div>
                    </div>
                </div>

                <div class="mob-accordion-item">
                    <button class="mob-nav-link w-100 text-start d-flex justify-content-between {{ request()->routeIs('afiliados.index') || request()->routeIs('turismo.*') ? 'mob-nav-link--active' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#mobAfiliados" aria-expanded="{{ request()->routeIs('afiliados.index') || request()->routeIs('turismo.*') ? 'true' : 'false' }}">
                        <span class="d-flex align-items-center gap-2"><i class="ti ti-id-badge"></i> Afiliados</span>
                        <i class="ti ti-chevron-down"></i>
                    </button>
                    <div id="mobAfiliados" class="collapse {{ request()->routeIs('afiliados.index') || request()->routeIs('turismo.*') ? 'show' : '' }}" data-bs-parent="#mobAccordion">
                        <div class="mob-subnav">
                            <a href="{{ route('afiliados.index') }}#beneficios" class="mob-subnav-link"><i class="ti ti-gift"></i> Beneficios</a>
                            <a href="{{ route('afiliados.index') }}#afiliarse" class="mob-subnav-link"><i class="ti ti-user-plus"></i> Cómo afiliarse</a>
                            <a href="{{ route('turismo.index') }}" class="mob-subnav-link"><i class="ti ti-beach"></i> Turismo y recreación</a>
                            <a href="{{ route('afiliados.index') }}#accion-social" class="mob-subnav-link"><i class="ti ti-heart-handshake"></i> Acción social</a>
                            <a href="{{ route('afiliados.index') }}#faq" class="mob-subnav-link"><i class="ti ti-help-circle"></i> Preguntas frecuentes</a>
                        </div>
                    </div>
                </div>
            </div>

            <a href="{{ $centUrl }}" class="mob-nav-link">
                <i class="ti ti-school"></i> Formación
            </a>
            <a href="{{ route('filiales.index') }}" class="mob-nav-link {{ request()->routeIs('filiales.*') ? 'mob-nav-link--active' : '' }}">
                <i class="ti ti-map-pin"></i> Filiales
            </a>
            <a href="{{ route('novedades.index') }}" class="mob-nav-link {{ request()->routeIs('novedades.*') ? 'mob-nav-link--active' : '' }}">
                <i class="ti ti-newspaper"></i> Novedades
            </a>
            <a href="{{ route('contacto.index') }}" class="mob-nav-link {{ request()->routeIs('contacto.*') ? 'mob-nav-link--active' : '' }}">
                <i class="ti ti-mail"></i> Contacto
            </a>

            <div class="mt-4 d-flex flex-column gap-2">
                <a href="{{ $portalUrl }}" class="btn btn-primary w-100 py-8 fw-bold">
                    <i class="ti ti-login me-2"></i>{{ $portalLabel }}
                </a>
                <a href="{{ route('afiliacion.create') }}" class="btn btn-outline-primary w-100 py-8 fw-bold">
                    <i class="ti ti-user-plus me-2"></i>Quiero afiliarme
                </a>
            </div>
        </div>
    </div>

    <main class="main-wrapper overflow-hidden">
        @yield('content')
    </main>

    <footer class="atsa-footer">
        <div class="atsa-footer-cta">
            <div class="container-fluid">
                <div class="row align-items-center g-4">
                    <div class="col-lg-7">
                        <p class="atsa-footer-cta-label">SUMATE AL SINDICATO</p>
                        <h3 class="atsa-footer-cta-title">¿Trabajás en la sanidad tucumana?</h3>
                        <p class="atsa-footer-cta-sub">
                            Afiliarte a ATSA Tucumán es contar con representación gremial, beneficios, formación
                            y acompañamiento en cada etapa de tu vida laboral.
                        </p>
                    </div>
                    <div class="col-lg-5 text-lg-end d-flex flex-wrap gap-3 justify-content-lg-end">
                        <a href="{{ route('afiliacion.create') }}" class="btn btn-light text-atsa-blue fw-bold py-8 px-10">
                            <i class="ti ti-user-plus me-2"></i>Afiliarme ahora
                        </a>
                        <a href="https://wa.me/{{ $siteSetting->whatsapp ?: '543814331665' }}" target="_blank" rel="noopener" class="btn btn-success fw-bold py-8 px-10">
                            <i class="ti ti-brand-whatsapp me-2"></i>WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="atsa-footer-body">
            <div class="container-fluid">
                <div class="row g-5 mb-10">
                    <div class="col-lg-4 col-md-6">
                        <img src="{{ $siteLogo }}" class="footer-logo mb-4" alt="ATSA Tucumán" />
                        <p class="atsa-footer-text mb-4">
                            Asociación de Trabajadores de la Sanidad Argentina - Seccional Tucumán.
                            Más de 100 años defendiendo los derechos laborales del sector salud en toda la provincia.
                        </p>
                        <div class="d-flex gap-3 flex-wrap">
                            <a href="{{ $facebookUrl }}" target="_blank" rel="noopener" class="atsa-social-btn" aria-label="Facebook"><i class="ti ti-brand-facebook"></i></a>
                            <a href="{{ $instagramUrl }}" target="_blank" rel="noopener" class="atsa-social-btn" aria-label="Instagram"><i class="ti ti-brand-instagram"></i></a>
                            @if($tiktokUrl)
                                <a href="{{ $tiktokUrl }}" target="_blank" rel="noopener" class="atsa-social-btn" aria-label="TikTok">
                                    <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.35 6.35 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V9.01a8.16 8.16 0 004.77 1.52V7.1a4.85 4.85 0 01-1-.41z"/></svg>
                                </a>
                            @endif
                            @if($youtubeUrl)
                                <a href="{{ $youtubeUrl }}" target="_blank" rel="noopener" class="atsa-social-btn" aria-label="YouTube"><i class="ti ti-brand-youtube"></i></a>
                            @endif
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-6 col-6">
                        <h4 class="atsa-footer-heading">Institución</h4>
                        <ul class="atsa-footer-links">
                            <li><a href="{{ route('sindicato.index') }}">El Sindicato</a></li>
                            <li><a href="{{ route('sindicato.index') }}#historia">Historia</a></li>
                            <li><a href="{{ route('sindicato.index') }}#autoridades">Autoridades</a></li>
                            <li><a href="{{ route('filiales.index') }}">Filiales</a></li>
                            <li><a href="{{ route('novedades.index') }}">Novedades</a></li>
                        </ul>
                    </div>

                    <div class="col-lg-2 col-md-6 col-6">
                        <h4 class="atsa-footer-heading">Gremial</h4>
                        <ul class="atsa-footer-links">
                            <li><a href="{{ route('gremial.index') }}">Gremial</a></li>
                            <li><a href="{{ route('escalas.index') }}">Escalas salariales</a></li>
                            <li><a href="{{ route('delegados.index') }}">Delegados</a></li>
                            <li><a href="{{ route('documentos.index') }}">Documentos</a></li>
                            <li><a href="{{ route('afiliacion.create') }}">Afiliarme</a></li>
                        </ul>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <h4 class="atsa-footer-heading">Contacto y filiales</h4>
                        <div class="d-flex flex-column gap-3">
                            <div class="atsa-footer-contact-row">
                                <i class="ti ti-building"></i>
                                <div>
                                    <strong>Sede Central</strong>
                                    <span>Paraguay y Thames, San Miguel de Tucumán</span>
                                </div>
                            </div>
                            <div class="atsa-footer-contact-row">
                                <i class="ti ti-phone"></i>
                                <div>
                                    <strong>Teléfono</strong>
                                    <span><a href="tel:03814331665" class="text-decoration-none" style="color:inherit;">0381 4331665</a></span>
                                </div>
                            </div>
                            <div class="atsa-footer-contact-row">
                                <i class="ti ti-clock"></i>
                                <div>
                                    <strong>Horarios</strong>
                                    <span>Lun a Vie de 8:00 a 16:00 hs</span>
                                </div>
                            </div>
                            <div class="atsa-footer-contact-row">
                                <i class="ti ti-map-pin"></i>
                                <div>
                                    <strong>Filial Sur</strong>
                                    <span>Julio Argentino Roca 371, Concepción</span>
                                </div>
                            </div>
                            <div class="atsa-footer-contact-row">
                                <i class="ti ti-map-pin"></i>
                                <div>
                                    <strong>Filial Este</strong>
                                    <span>Camino del Carmen 90, Banda del Río Salí - 3815677170</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="atsa-footer-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <img src="{{ $siteLogo }}" alt="ATSA" style="height:30px;width:auto;opacity:.7;">
                        <span>© {{ date('Y') }} ATSA Tucumán - Asociación de Trabajadores de la Sanidad Argentina</span>
                    </div>
                    <div class="d-flex gap-5 flex-wrap">
                        <a href="{{ route('afiliados.login') }}">Área afiliados</a>
                        <a href="{{ route('contacto.index') }}">Contacto</a>
                        <a href="/verificar/0" style="opacity:.6;">Verificar carnet</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <a href="javascript:void(0)" class="top-btn btn btn-primary d-flex align-items-center justify-content-center round-54 p-0 rounded-circle">
        <i class="ti ti-arrow-up fs-7"></i>
    </a>

    <a href="https://wa.me/{{ $siteSetting->whatsapp ?: '543814331665' }}" target="_blank" rel="noopener"
        class="wa-float d-flex align-items-center justify-content-center rounded-circle shadow-lg"
        title="Contactanos por WhatsApp"
        style="position:fixed;bottom:90px;right:24px;width:54px;height:54px;background:#25d366;color:#fff;z-index:999;transition:transform .2s ease;font-size:28px;">
        <i class="ti ti-brand-whatsapp"></i>
    </a>

    <script src="{{ asset('modernize/js/vendor.min.js') }}"></script>
    <script src="{{ asset('modernize/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('modernize/libs/simplebar/dist/simplebar.min.js') }}"></script>
    <script src="{{ asset('modernize/js/theme/app.init.js') }}"></script>
    <script src="{{ asset('modernize/js/theme/theme.js') }}"></script>
    <script src="{{ asset('modernize/js/theme/app.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
    <script src="{{ asset('modernize/libs/owl.carousel/dist/owl.carousel.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        window.addEventListener('load', function () {
            var pre = document.querySelector('.preloader');
            if (pre) {
                pre.classList.add('hide');
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            var tips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            tips.forEach(function (el) {
                new bootstrap.Tooltip(el);
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
