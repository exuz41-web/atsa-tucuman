@php
    $siteLogo = \App\Models\SiteSetting::logoUrl();
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recuperar contraseña - ATSA Tucumán</title>
    <link rel="stylesheet" href="{{ asset('modernize/css/styles.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f4f7fb] text-[#2a3547]">
    <main class="grid min-h-screen place-items-center px-5 py-10">
        <section class="w-full max-w-md rounded-xl bg-white p-8 shadow-[0_18px_45px_rgba(42,53,71,.12)]">
            <div class="text-center">
                <img src="{{ $siteLogo }}" alt="ATSA Tucumán" class="mx-auto h-16 w-auto">
                <p class="mt-6 text-sm font-black uppercase tracking-[.18em] text-[#49beff]">Recuperación de acceso</p>
                <h1 class="mt-3 text-3xl font-black text-[#2a3547]">Olvidé mi contraseña</h1>
                <p class="mt-3 text-sm leading-6 text-[#5a6a85]">Ingresá tu email, DNI o número de afiliado para generar un enlace de recuperación.</p>
            </div>

            @if (session('status'))
                <div class="mt-6 rounded-lg border border-[#bdefff] bg-[#eaf8ff] px-4 py-3 text-sm font-bold text-[#1e3a5f]">{{ session('status') }}</div>
            @endif

            @if (session('reset_link'))
                <div class="mt-4 rounded-lg bg-[#f4f7fb] p-4 text-sm text-[#5a6a85]">
                    <p class="font-bold text-[#2a3547]">Enlace local de recuperación:</p>
                    <a href="{{ session('reset_link') }}" class="mt-2 block break-all font-black text-[#5d87ff]">{{ session('reset_link') }}</a>
                    <p class="mt-2 text-xs font-semibold">Cuando se configure el correo SMTP, este enlace se enviará por email.</p>
                </div>
            @endif

            <form method="POST" action="{{ route('afiliados.password.email') }}" class="mt-7 grid gap-5">
                @csrf
                <label class="grid gap-2">
                    <span class="text-sm font-black text-[#2a3547]">Email, DNI o número de afiliado</span>
                    <input name="identificador" value="{{ old('identificador') }}" class="rounded-lg border border-[#dfe5ef] px-4 py-3 outline-none focus:border-[#5d87ff] focus:ring-4 focus:ring-[#5d87ff]/15" required autofocus>
                    @error('identificador') <span class="text-sm font-bold text-red-600">{{ $message }}</span> @enderror
                </label>
                <button class="rounded-lg bg-[#5d87ff] px-5 py-3 text-sm font-black text-white transition hover:bg-[#1e3a5f]">Generar enlace</button>
            </form>

            <div class="mt-6 flex items-center justify-between gap-4 text-sm font-bold">
                <a href="{{ route('afiliados.login') }}" class="text-[#1e3a5f]">Volver al login</a>
                <a href="{{ route('afiliados.register') }}" class="text-[#5d87ff]">Crear cuenta</a>
            </div>
        </section>
    </main>
</body>
</html>
