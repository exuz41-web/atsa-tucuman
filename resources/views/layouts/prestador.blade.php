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
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { background: #f6f8fb; }
        .provider-shell { min-height: 100vh; }
        .provider-header { background: #fff; border-bottom: 1px solid #e5eaef; }
        .provider-header-inner { padding-top: 1rem; padding-bottom: 1rem; }
        .provider-brand { min-width: 0; }
        .provider-logo { height: 58px; max-width: 180px; object-fit: contain; }
        .provider-title { min-width: 0; }
        .provider-title h1 { overflow-wrap: anywhere; line-height: 1.08; }
        .provider-actions { flex-shrink: 0; }
        .provider-card { background: #fff; border: 1px solid #e5eaef; border-radius: 12px; box-shadow: 0 14px 32px rgba(42, 53, 71, .07); }
        .provider-badge { border-radius: 999px; padding: 6px 12px; font-weight: 800; font-size: 12px; }
        .badge-emitida { background: #fff6df; color: #9a6200; }
        .badge-aceptada { background: #e8f4ff; color: #1e5f99; }
        .badge-observada { background: #fff6df; color: #9a6200; }
        .badge-entregada { background: #e8f8f1; color: #13795b; }
        .badge-anulada { background: #fdecec; color: #b42318; }
        .provider-scan-card { max-width: 720px; margin-inline: auto; }
        .provider-scan-video { aspect-ratio: 3 / 4; max-height: 62vh; object-fit: cover; }
        .provider-manual-toggle { color: #6c7a91; text-underline-offset: 4px; }
        .provider-help-card { background: #f8fafc; border: 1px solid #edf2f7; border-radius: 12px; }

        @media (max-width: 575.98px) {
            body { background: #f2f5f9; }
            .provider-header-inner { align-items: stretch !important; flex-direction: column; gap: 1rem !important; }
            .provider-brand { gap: .85rem !important; }
            .provider-logo { width: 106px; height: 56px; flex: 0 0 106px; }
            .provider-title .fs-2 { font-size: .86rem !important; line-height: 1.35; }
            .provider-title h1 { font-size: 1.52rem; }
            .provider-actions { display: grid !important; grid-template-columns: 1fr auto; width: 100%; gap: .7rem !important; }
            .provider-actions .btn { min-height: 48px; display: inline-flex; align-items: center; justify-content: center; border-radius: 12px; }
            .provider-actions form { margin: 0; }
            .provider-actions form .btn { width: 100%; }
            main.container { padding-inline: .85rem; padding-top: 1rem !important; }
            .provider-card { border-radius: 14px; box-shadow: 0 10px 24px rgba(42, 53, 71, .06); }
            .provider-card.p-4 { padding: 1.15rem !important; }
            .provider-scan-video { aspect-ratio: 10 / 13; max-height: 56vh; }
            .btn-lg { --bs-btn-padding-y: .82rem; --bs-btn-font-size: 1.05rem; }
            .table-responsive { border-radius: 12px; }
        }
    </style>
</head>
<body>
<div class="provider-shell">
    <header class="provider-header">
        <div class="container provider-header-inner d-flex align-items-center justify-content-between gap-3">
            <a href="{{ route('prestadores.portal', $prestador->portal_token) }}" class="provider-brand d-flex align-items-center gap-3 text-decoration-none">
                <img src="{{ \App\Models\SiteSetting::logoUrl() }}" alt="ATSA Tucumán" class="provider-logo">
                <div class="provider-title">
                    <p class="text-muted fw-bold mb-0 fs-2">Portal de prestadores</p>
                    <h1 class="h4 fw-bolder mb-0 text-dark">{{ $prestador->nombre }}</h1>
                </div>
            </a>
            <div class="provider-actions d-flex gap-2 flex-wrap justify-content-end">
                <a href="{{ route('prestadores.validar', ['token' => $prestador->portal_token, 'scan' => 1]) }}" class="btn btn-primary shadow-none">
                    <i class="ti ti-qrcode me-2"></i>Escanear QR
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
