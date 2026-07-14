@php
    $siteLogo = \App\Models\SiteSetting::logoUrl();
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ingreso afiliados - ATSA Tucumán</title>
    <link rel="stylesheet" href="{{ asset('modernize/css/styles.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .password-wrap { position: relative; }
        .password-wrap input { padding-right: 3.25rem; }
        .password-toggle {
            position: absolute;
            right: .5rem;
            top: 50%;
            transform: translateY(-50%);
            width: 2.5rem;
            height: 2.5rem;
            border: 0;
            border-radius: .65rem;
            background: transparent;
            color: #5a6a85;
            display: grid;
            place-items: center;
        }

        .password-toggle svg {
            width: 1.25rem;
            height: 1.25rem;
        }

        .password-toggle .icon-eye-off {
            display: none;
        }

        .password-toggle.is-visible .icon-eye {
            display: none;
        }

        .password-toggle.is-visible .icon-eye-off {
            display: block;
        }
    </style>
</head>
<body class="min-h-screen bg-[#f4f7fb] text-[#2a3547]">
    <main class="grid min-h-screen lg:grid-cols-[1fr_520px]">
        <section class="relative hidden overflow-hidden bg-[#0f2236] lg:block">
            <div class="absolute inset-0 bg-[url('{{ asset('images/turismo/ciudad-deportiva/ingreso-atsa.jpeg') }}')] bg-cover bg-center opacity-30"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-[#0f2236]/95 via-[#1e3a5f]/88 to-[#49beff]/40"></div>
            <div class="relative flex h-full flex-col justify-between p-12 text-white">
                <a href="{{ route('home') }}" class="inline-flex w-fit items-center gap-3 rounded-xl bg-white/95 px-4 py-3 shadow-lg">
                    <img src="{{ $siteLogo }}" alt="ATSA Tucumán" class="h-12 w-auto">
                </a>
                <div class="max-w-2xl">
                    <p class="text-sm font-black uppercase tracking-[.22em] text-[#49beff]">Área privada de afiliados</p>
                    <h1 class="mt-5 text-5xl font-black leading-tight">Tu vínculo digital con ATSA Tucumán</h1>
                    <p class="mt-5 max-w-xl text-lg leading-8 text-white/80">Consultá tu carnet, solicitudes, beneficios, documentos y novedades gremiales desde un panel seguro.</p>
                </div>
                <div class="grid max-w-2xl grid-cols-3 gap-4 text-sm">
                    <div class="rounded-xl bg-white/10 p-4 backdrop-blur"><strong class="block text-2xl">QR</strong><span class="text-white/70">Carnet verificable</span></div>
                    <div class="rounded-xl bg-white/10 p-4 backdrop-blur"><strong class="block text-2xl">24/7</strong><span class="text-white/70">Solicitudes online</span></div>
                    <div class="rounded-xl bg-white/10 p-4 backdrop-blur"><strong class="block text-2xl">ATSA</strong><span class="text-white/70">Beneficios activos</span></div>
                </div>
            </div>
        </section>

        <section class="flex min-h-screen items-center justify-center px-5 py-10">
            <div class="w-full max-w-md">
                <div class="mb-8 text-center lg:hidden">
                    <img src="{{ $siteLogo }}" alt="ATSA Tucumán" class="mx-auto h-16 w-auto">
                </div>

                <div class="rounded-xl bg-white p-8 shadow-[0_18px_45px_rgba(42,53,71,.12)]">
                    <p class="text-sm font-black uppercase tracking-[.18em] text-[#49beff]">Ingreso seguro</p>
                    <h2 class="mt-3 text-3xl font-black text-[#2a3547]">Ingresá a tu cuenta</h2>
                    <p class="mt-3 text-sm leading-6 text-[#5a6a85]">Usá tu email, número de afiliado o DNI junto con tu contraseña.</p>

                    @if (session('status'))
                        <div class="mt-5 rounded-lg border border-[#bdefff] bg-[#eaf8ff] px-4 py-3 text-sm font-bold text-[#1e3a5f]">{{ session('status') }}</div>
                    @endif

                    <form method="POST" action="{{ route('afiliados.login.submit') }}" class="mt-7 grid gap-5">
                        @csrf
                        <label class="grid gap-2">
                            <span class="text-sm font-black text-[#2a3547]">Email, número de afiliado o DNI</span>
                            <input name="numero_afiliado" value="{{ old('numero_afiliado') }}" class="rounded-lg border border-[#dfe5ef] px-4 py-3 outline-none transition focus:border-[#5d87ff] focus:ring-4 focus:ring-[#5d87ff]/15" placeholder="Ej: nombre@mail.com, ATSA2026-00001 o 12345678" required autofocus>
                            @error('numero_afiliado') <span class="text-sm font-bold text-red-600">{{ $message }}</span> @enderror
                        </label>

                        <label class="grid gap-2">
                            <span class="text-sm font-black text-[#2a3547]">Contraseña</span>
                            <span class="password-wrap">
                                <input id="afiliado-password" type="password" name="password" class="w-full rounded-lg border border-[#dfe5ef] px-4 py-3 outline-none transition focus:border-[#5d87ff] focus:ring-4 focus:ring-[#5d87ff]/15" required>
                                <button class="password-toggle" type="button" data-toggle-password="#afiliado-password" aria-label="Ver contraseña">
                                    <svg class="icon-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <path d="M2.1 12s3.6-7 9.9-7 9.9 7 9.9 7-3.6 7-9.9 7-9.9-7-9.9-7Z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    <svg class="icon-eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <path d="M3 3l18 18"/>
                                        <path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"/>
                                        <path d="M9.9 4.3A10.6 10.6 0 0 1 12 4c6.3 0 9.9 8 9.9 8a16.6 16.6 0 0 1-2.1 3.1"/>
                                        <path d="M6.6 6.7C3.8 8.6 2.1 12 2.1 12s3.6 8 9.9 8a10.3 10.3 0 0 0 5.1-1.4"/>
                                    </svg>
                                </button>
                            </span>
                            @error('password') <span class="text-sm font-bold text-red-600">{{ $message }}</span> @enderror
                        </label>

                        <div class="flex items-center justify-between gap-4 text-sm">
                            <label class="flex items-center gap-2 font-semibold text-[#5a6a85]">
                                <input type="checkbox" name="remember" value="1" class="rounded border-[#dfe5ef] text-[#5d87ff]">
                                Recordarme
                            </label>
                            <a href="{{ route('afiliados.password.request') }}" class="font-black text-[#5d87ff]">Olvidé mi contraseña</a>
                        </div>

                        <button class="rounded-lg bg-[#5d87ff] px-5 py-3 text-sm font-black text-white transition hover:bg-[#1e3a5f]">Ingresar</button>
                    </form>

                    <div class="mt-6 rounded-lg bg-[#f4f7fb] p-4 text-center text-sm font-semibold text-[#5a6a85]">
                        ¿Todavia no tenes usuario habilitado?
                        <a href="{{ route('afiliacion.create') }}" class="font-black text-[#1e3a5f]">Iniciar solicitud de afiliacion</a>
                    </div>
                </div>

                <p class="mt-6 text-center text-xs font-semibold text-[#5a6a85]">ATSA Tucumán · Paraguay y Thames · 0381 4331665</p>
            </div>
        </section>
    </main>
    <script>
        document.querySelectorAll('[data-toggle-password]').forEach((button) => {
            button.addEventListener('click', () => {
                const input = document.querySelector(button.dataset.togglePassword);
                if (!input) return;

                const visible = input.type === 'text';
                input.type = visible ? 'password' : 'text';
                button.classList.toggle('is-visible', !visible);
                button.setAttribute('aria-label', visible ? 'Ver contraseña' : 'Ocultar contraseña');
            });
        });
    </script>
</body>
</html>
