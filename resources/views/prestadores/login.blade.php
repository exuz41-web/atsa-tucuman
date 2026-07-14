<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#1e3a5f">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>Ingreso prestadores | ATSA Tucumán</title>
    <link rel="manifest" href="{{ route('prestadores.manifest') }}">
    <link rel="stylesheet" href="{{ asset('modernize/css/styles.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.41.1/dist/tabler-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { min-height: 100vh; background: #f5f7fb; }
        .login-shell { min-height: 100vh; display: grid; place-items: center; padding: 24px; }
        .login-card { width: min(100%, 440px); background: #fff; border: 1px solid #e5eaef; border-radius: 14px; box-shadow: 0 24px 60px rgba(42, 53, 71, .12); }
        .password-wrap { position: relative; }
        .password-wrap .form-control { padding-right: 48px; }
        .password-toggle {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            border: 0;
            background: transparent;
            color: #5a6a85;
            width: 38px;
            height: 38px;
            display: grid;
            place-items: center;
        }
    </style>
</head>
<body>
<main class="login-shell">
    <section class="login-card p-4 p-lg-5">
        <div class="text-center mb-4">
            <img src="{{ \App\Models\SiteSetting::logoUrl() }}" alt="ATSA Tucumán" style="height:72px;max-width:210px;object-fit:contain;">
            <p class="text-primary fw-bold fs-3 mt-4 mb-1">PORTAL DE PRESTADORES</p>
            <h1 class="h3 fw-bolder text-dark mb-0">Ingresar</h1>
        </div>

        @if (session('status'))
            <div class="alert alert-success border-0">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger border-0">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('prestadores.login.submit') }}" class="d-grid gap-3">
            @csrf
            <div>
                <label class="form-label fw-bold">Usuario, email o CUIT</label>
                <input class="form-control" name="usuario" value="{{ old('usuario') }}" required autofocus autocomplete="username">
            </div>
            <div>
                <label class="form-label fw-bold">Contraseña</label>
                <div class="password-wrap">
                    <input id="prestador-password" class="form-control" type="password" name="password" required autocomplete="current-password">
                    <button class="password-toggle" type="button" data-toggle-password="#prestador-password" aria-label="Ver contraseña">
                        <i class="ti ti-eye"></i>
                    </button>
                </div>
            </div>
            <button class="btn btn-primary shadow-none py-2" type="submit">
                <i class="ti ti-login-2 me-2"></i>Entrar
            </button>
        </form>
    </section>
</main>
<script>
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/sw.js').catch(() => {});
    }

    document.querySelectorAll('[data-toggle-password]').forEach((button) => {
        button.addEventListener('click', () => {
            const input = document.querySelector(button.dataset.togglePassword);
            const icon = button.querySelector('i');
            if (!input) return;

            const visible = input.type === 'text';
            input.type = visible ? 'password' : 'text';
            button.setAttribute('aria-label', visible ? 'Ver contraseña' : 'Ocultar contraseña');
            icon?.classList.toggle('ti-eye', visible);
            icon?.classList.toggle('ti-eye-off', !visible);
        });
    });
</script>
</body>
</html>
