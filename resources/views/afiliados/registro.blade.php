@php
    $siteLogo = \App\Models\SiteSetting::logoUrl();
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Solicitud de afiliación - ATSA Tucumán</title>
    <link rel="stylesheet" href="{{ asset('modernize/css/styles.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f4f7fb] text-[#2a3547]">
    <main class="grid min-h-screen lg:grid-cols-[520px_1fr]">
        <section class="flex items-center justify-center px-5 py-10">
            <div class="w-full max-w-lg">
                <a href="{{ route('home') }}" class="mb-7 inline-flex items-center gap-3">
                    <img src="{{ $siteLogo }}" alt="ATSA Tucumán" class="h-14 w-auto">
                </a>

                <div class="rounded-xl bg-white p-8 shadow-[0_18px_45px_rgba(42,53,71,.12)]">
                    <p class="text-sm font-black uppercase tracking-[.18em] text-[#49beff]">Solicitud de afiliacion</p>
                    <h1 class="mt-3 text-3xl font-black text-[#2a3547]">Primero revisamos tu alta</h1>
                    <p class="mt-3 text-sm leading-6 text-[#5a6a85]">
                        El acceso al portal no se crea en forma automatica. Primero necesitamos recibir y validar tu
                        solicitud de afiliacion con DNI y recibo de sueldo.
                    </p>

                    @if (session('status'))
                        <div class="mt-5 rounded-lg border border-[#bdefff] bg-[#eaf8ff] px-4 py-3 text-sm font-bold text-[#1e3a5f]">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="mt-7 grid gap-4">
                        <div class="rounded-xl border border-[#dfe5ef] bg-[#f8fbff] p-5">
                            <p class="text-base font-black text-[#2a3547]">Como funciona</p>
                            <ol class="mt-3 grid gap-3 text-sm leading-6 text-[#5a6a85]">
                                <li>1. Completas la solicitud online.</li>
                                <li>2. Adjuntas DNI y recibo de sueldo.</li>
                                <li>3. ATSA revisa la documentacion.</li>
                                <li>4. Si se aprueba, se crea tu usuario y tu numero de afiliado.</li>
                            </ol>
                        </div>

                        <a href="{{ route('afiliacion.create') }}" class="rounded-lg bg-[#5d87ff] px-5 py-3 text-center text-sm font-black text-white transition hover:bg-[#1e3a5f]">
                            Completar solicitud de afiliacion
                        </a>
                    </div>

                    <p class="mt-6 text-center text-sm font-semibold text-[#5a6a85]">
                        Si ya tenes usuario aprobado,
                        <a href="{{ route('afiliados.login') }}" class="font-black text-[#1e3a5f]">ingresa aqui</a>
                    </p>
                </div>
            </div>
        </section>

        <section class="relative hidden overflow-hidden bg-[#0f2236] lg:block">
            <div class="absolute inset-0 bg-[url('{{ asset('images/historia/ciudad-deportiva-atsa.jpg') }}')] bg-cover bg-center opacity-30"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-[#0f2236]/95 via-[#1e3a5f]/88 to-[#49beff]/40"></div>
            <div class="relative flex h-full items-end p-12 text-white">
                <div class="max-w-2xl">
                    <p class="text-sm font-black uppercase tracking-[.22em] text-[#49beff]">ATSA Tucumán</p>
                    <h2 class="mt-5 text-5xl font-black leading-tight">La afiliacion se valida antes de habilitar el portal</h2>
                    <p class="mt-5 text-lg leading-8 text-white/80">
                        Esto nos permite revisar tu documentacion, confirmar tus datos y entregarte un acceso real con
                        numero de afiliado y carnet digital.
                    </p>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
