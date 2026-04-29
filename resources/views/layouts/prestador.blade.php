<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#1e3a5f">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>@yield('title', 'Portal de prestadores') | ATSA Tucumán</title>
    <link rel="manifest" href="{{ route('prestadores.manifest') }}">
    <link rel="stylesheet" href="{{ asset('modernize/css/styles.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.41.1/dist/tabler-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { background: #f6f8fb; }
        .provider-shell { min-height: 100vh; }
        .provider-header { background: #fff; border-bottom: 1px solid #e5eaef; }
        .provider-card { background: #fff; border: 1px solid #e5eaef; border-radius: 12px; box-shadow: 0 14px 32px rgba(42, 53, 71, .07); }
        .provider-badge { border-radius: 999px; padding: 6px 12px; font-weight: 800; font-size: 12px; }
        .badge-emitida { background: #fff6df; color: #9a6200; }
        .badge-aceptada { background: #e8f4ff; color: #1e5f99; }
        .badge-observada { background: #fff6df; color: #9a6200; }
        .badge-entregada { background: #e8f8f1; color: #13795b; }
        .badge-anulada { background: #fdecec; color: #b42318; }
    </style>
</head>
<body>
<div class="provider-shell">
    <header class="provider-header">
        <div class="container py-4 d-flex align-items-center justify-content-between gap-3">
            <a href="{{ route('prestadores.portal', $prestador->portal_token) }}" class="d-flex align-items-center gap-3 text-decoration-none">
                <img src="{{ \App\Models\SiteSetting::logoUrl() }}" alt="ATSA Tucumán" style="height:58px;max-width:180px;object-fit:contain;">
                <div>
                    <p class="text-muted fw-bold mb-0 fs-2">Portal de prestadores</p>
                    <h1 class="h4 fw-bolder mb-0 text-dark">{{ $prestador->nombre }}</h1>
                </div>
            </a>
            <div class="d-flex gap-2 flex-wrap justify-content-end">
                <a href="{{ route('prestadores.validar', $prestador->portal_token) }}" class="btn btn-primary shadow-none">
                    <i class="ti ti-qrcode me-2"></i>Validar afiliado
                </a>
                <form method="POST" action="{{ route('prestadores.logout') }}">
                    @csrf
                    <button class="btn btn-light shadow-none" type="submit">
                        <i class="ti ti-logout me-2"></i>Salir
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="container py-4 py-lg-5">
        @if (session('status') || session('error'))
            <div class="alert {{ session('error') ? 'alert-danger' : 'alert-success' }} border-0 rounded-3 shadow-sm">
                {{ session('status') ?? session('error') }}
            </div>
        @endif

        @yield('content')
    </main>
</div>
<script>
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/sw.js').catch(() => {});
    }
</script>
@stack('scripts')
</body>
</html>
