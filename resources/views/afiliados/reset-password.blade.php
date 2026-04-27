@php
    $siteLogo = \App\Models\SiteSetting::logoUrl();
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nueva contraseña - ATSA Tucumán</title>
    <link rel="stylesheet" href="{{ asset('modernize/css/styles.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f4f7fb] text-[#2a3547]">
    <main class="grid min-h-screen place-items-center px-5 py-10">
        <section class="w-full max-w-md rounded-xl bg-white p-8 shadow-[0_18px_45px_rgba(42,53,71,.12)]">
            <div class="text-center">
                <img src="{{ $siteLogo }}" alt="ATSA Tucumán" class="mx-auto h-16 w-auto">
                <p class="mt-6 text-sm font-black uppercase tracking-[.18em] text-[#49beff]">Nuevo acceso</p>
                <h1 class="mt-3 text-3xl font-black text-[#2a3547]">Crear nueva contraseña</h1>
                <p class="mt-3 text-sm leading-6 text-[#5a6a85]">El enlace vence en 60 minutos por seguridad.</p>
            </div>

            <form method="POST" action="{{ route('afiliados.password.update') }}" class="mt-7 grid gap-5">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <label class="grid gap-2">
                    <span class="text-sm font-black text-[#2a3547]">Email</span>
                    <input type="email" name="email" value="{{ old('email', $email) }}" class="rounded-lg border border-[#dfe5ef] px-4 py-3 outline-none focus:border-[#5d87ff] focus:ring-4 focus:ring-[#5d87ff]/15" required>
                    @error('email') <span class="text-sm font-bold text-red-600">{{ $message }}</span> @enderror
                </label>
                <label class="grid gap-2">
                    <span class="text-sm font-black text-[#2a3547]">Nueva contraseña</span>
                    <input type="password" name="password" class="rounded-lg border border-[#dfe5ef] px-4 py-3 outline-none focus:border-[#5d87ff] focus:ring-4 focus:ring-[#5d87ff]/15" required>
                    @error('password') <span class="text-sm font-bold text-red-600">{{ $message }}</span> @enderror
                </label>
                <label class="grid gap-2">
                    <span class="text-sm font-black text-[#2a3547]">Confirmar contraseña</span>
                    <input type="password" name="password_confirmation" class="rounded-lg border border-[#dfe5ef] px-4 py-3 outline-none focus:border-[#5d87ff] focus:ring-4 focus:ring-[#5d87ff]/15" required>
                </label>
                <button class="rounded-lg bg-[#5d87ff] px-5 py-3 text-sm font-black text-white transition hover:bg-[#1e3a5f]">Guardar contraseña</button>
            </form>
        </section>
    </main>
</body>
</html>
