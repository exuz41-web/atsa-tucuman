@php
    $siteLogo = \App\Models\SiteSetting::logoUrl();
@endphp
<!DOCTYPE html>
<html lang="es" dir="ltr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Página no encontrada — ATSA Tucumán</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Outfit:wght@700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('modernize/css/styles.css') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.41.1/dist/tabler-icons.min.css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --atsa-blue: #1e3a5f;
            --atsa-sky: #49beff;
            --font-body: 'Inter', sans-serif;
            --font-heading: 'Outfit', 'Inter', sans-serif;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: var(--font-body);
            background: #f4f7fb;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        /* Navbar */
        .err-nav {
            background: rgba(255,255,255,.95);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(0,0,0,.06);
            padding: 16px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .err-nav img { height: 48px; width: auto; }
        .err-nav a {
            color: var(--atsa-blue); font-size: 14px; font-weight: 600;
            text-decoration: none; padding: 8px 18px;
            border-radius: 8px; border: 1.5px solid var(--atsa-blue);
            transition: all .2s;
        }
        .err-nav a:hover { background: var(--atsa-blue); color: #fff; }

        /* Main */
        .err-main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 24px;
        }
        .err-card {
            background: #fff;
            border-radius: 32px;
            box-shadow: 0 20px 60px rgba(30,58,95,.10);
            padding: 56px 48px;
            max-width: 720px;
            width: 100%;
            text-align: center;
        }
        @media (max-width: 576px) {
            .err-card { padding: 36px 24px; border-radius: 20px; }
        }

        /* 404 big number */
        .err-number {
            font-family: var(--font-heading);
            font-size: clamp(80px, 20vw, 140px);
            font-weight: 900;
            line-height: 1;
            background: linear-gradient(135deg, var(--atsa-blue) 0%, var(--atsa-sky) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -4px;
            margin-bottom: 8px;
        }

        /* Illustration */
        .err-icon-wrap {
            width: 90px; height: 90px;
            border-radius: 24px;
            background: #ecf2ff;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 28px;
            font-size: 44px;
            color: var(--atsa-blue);
        }

        .err-title {
            font-family: var(--font-heading);
            font-size: clamp(1.4rem, 4vw, 2rem);
            font-weight: 900;
            color: #1e293b;
            margin-bottom: 12px;
        }
        .err-desc {
            font-size: 16px;
            color: #64748b;
            line-height: 1.65;
            max-width: 480px;
            margin: 0 auto 36px;
        }

        /* Buttons */
        .err-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            justify-content: center;
            margin-bottom: 40px;
        }
        .err-btn-primary {
            background: var(--atsa-blue);
            color: #fff;
            padding: 12px 28px;
            border-radius: 12px;
            text-decoration: none;
            font-size: 15px;
            font-weight: 700;
            display: inline-flex; align-items: center; gap: 8px;
            transition: all .25s ease;
        }
        .err-btn-primary:hover {
            background: #142940; color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(30,58,95,.25);
        }
        .err-btn-outline {
            border: 2px solid var(--atsa-blue);
            color: var(--atsa-blue);
            padding: 12px 28px;
            border-radius: 12px;
            text-decoration: none;
            font-size: 15px;
            font-weight: 700;
            display: inline-flex; align-items: center; gap: 8px;
            transition: all .25s ease;
            background: transparent;
        }
        .err-btn-outline:hover {
            background: var(--atsa-blue); color: #fff;
            transform: translateY(-2px);
        }

        /* Quick links */
        .err-links-title {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 14px;
        }
        .err-links {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: center;
        }
        .err-link-pill {
            padding: 7px 16px;
            border-radius: 50px;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            color: #475569;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: all .2s ease;
        }
        .err-link-pill:hover {
            background: #ecf2ff;
            border-color: var(--atsa-sky);
            color: var(--atsa-blue);
        }

        /* Footer */
        .err-footer {
            text-align: center;
            padding: 20px;
            font-size: 13px;
            color: #94a3b8;
        }
        .err-footer a { color: var(--atsa-sky); text-decoration: none; }
    </style>
</head>
<body>

    <nav class="err-nav">
        <a href="{{ url('/') }}">
            <img src="{{ $siteLogo }}" alt="ATSA Tucumán" />
        </a>
        <a href="{{ url('/') }}">Ir al inicio</a>
    </nav>

    <main class="err-main">
        <div class="err-card">

            <div class="err-icon-wrap">
                <i class="ti ti-map-search"></i>
            </div>

            <div class="err-number">404</div>

            <h1 class="err-title">¡Ups! Esta página no existe</h1>
            <p class="err-desc">
                La dirección que ingresaste no se encontró en nuestro sitio.
                Puede que haya sido movida, eliminada o que hayas escrito la URL incorrectamente.
            </p>

            <div class="err-actions">
                <a href="{{ url('/') }}" class="err-btn-primary">
                    <i class="ti ti-home-2"></i> Ir al inicio
                </a>
                <a href="{{ url('/contacto') }}" class="err-btn-outline">
                    <i class="ti ti-mail"></i> Contactar ATSA
                </a>
            </div>

            <p class="err-links-title">O visitá una de estas secciones</p>
            <div class="err-links">
                <a href="{{ url('/gremial') }}" class="err-link-pill"><i class="ti ti-gavel me-1"></i>Gremial</a>
                <a href="{{ url('/afiliados') }}" class="err-link-pill"><i class="ti ti-id-badge me-1"></i>Afiliados</a>
                <a href="{{ url('/novedades') }}" class="err-link-pill"><i class="ti ti-newspaper me-1"></i>Novedades</a>
                <a href="{{ url('/filiales') }}" class="err-link-pill"><i class="ti ti-map-pin me-1"></i>Filiales</a>
                <a href="{{ url('/escalas-salariales') }}" class="err-link-pill"><i class="ti ti-chart-bar me-1"></i>Escalas</a>
                <a href="{{ url('/turismo') }}" class="err-link-pill"><i class="ti ti-beach me-1"></i>Turismo</a>
            </div>

        </div>
    </main>

    <footer class="err-footer">
        © {{ date('Y') }} <a href="{{ url('/') }}">ATSA Tucumán</a> — Asociación de Trabajadores de la Sanidad Argentina
    </footer>

    <script src="{{ asset('modernize/js/vendor.min.js') }}"></script>
    <script src="{{ asset('modernize/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
