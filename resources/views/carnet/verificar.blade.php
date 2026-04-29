<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verificación de Carnet - ATSA Tucumán</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-800">
    @php
        use App\Models\SiteSetting;
        use App\Support\CarnetSupport;
        $fotoUrl = CarnetSupport::fotoUrl($afiliado);
        $valido = $afiliado->carnet_activo && ! $vencido;
        $siteLogo = SiteSetting::logoUrl();
    @endphp

    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-5xl items-center justify-between px-5 py-5">
            <img src="{{ $siteLogo }}" alt="ATSA Tucumán" class="h-14 w-auto">
            <div class="text-right">
                <p class="text-xs font-black uppercase tracking-widest text-[#c0392b]">Verificación oficial</p>
                <h1 class="text-xl font-black text-[#1e3a5f]">Carnet Digital</h1>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-3xl px-5 py-10">
        <section class="rounded-2xl bg-white p-8 text-center shadow-xl">
            @if ($valido)
                <div class="mx-auto grid h-20 w-20 place-items-center rounded-full bg-green-100 text-5xl font-black text-green-600">✓</div>
                <span class="mt-5 inline-flex rounded-full bg-green-100 px-5 py-2 text-sm font-black text-green-700">CARNET VALIDO</span>
            @elseif ($vencido)
                <div class="mx-auto grid h-20 w-20 place-items-center rounded-full bg-orange-100 text-5xl font-black text-orange-600">!</div>
                <span class="mt-5 inline-flex rounded-full bg-orange-100 px-5 py-2 text-sm font-black text-orange-700">CARNET VENCIDO</span>
            @else
                <div class="mx-auto grid h-20 w-20 place-items-center rounded-full bg-red-100 text-5xl font-black text-red-600">×</div>
                <span class="mt-5 inline-flex rounded-full bg-red-100 px-5 py-2 text-sm font-black text-red-700">CARNET INACTIVO</span>
            @endif

            <div class="mx-auto mt-7 grid h-28 w-28 place-items-center overflow-hidden rounded-full bg-slate-100 text-3xl font-black text-[#1e3a5f]">
                @if ($fotoUrl)
                    <img src="{{ $fotoUrl }}" alt="Foto afiliado" class="h-full w-full object-cover">
                @else
                    {{ CarnetSupport::initials($afiliado->name) }}
                @endif
            </div>

            <h2 class="mt-5 text-3xl font-black text-[#1e3a5f]">{{ $afiliado->name }}</h2>
            <p class="mt-1 text-sm font-bold text-slate-500">{{ $afiliado->numero_afiliado }}</p>

            <div class="mx-auto mt-8 max-w-xs rounded-2xl border-4 border-slate-100 bg-white p-4 shadow-sm">
                <img
                    src="data:image/png;base64,{{ $qrCode }}"
                    alt="QR de verificación del carnet"
                    class="mx-auto h-64 w-64 max-w-full rounded-xl object-contain"
                >
                <p class="mt-3 text-xs font-black uppercase tracking-widest text-slate-500">QR del carnet</p>
            </div>

            <dl class="mx-auto mt-8 grid max-w-xl gap-3 text-left sm:grid-cols-2">
                <div class="rounded-lg bg-slate-50 p-4"><dt class="text-xs font-black uppercase text-slate-500">DNI</dt><dd class="mt-1 font-black">{{ CarnetSupport::maskedDni($afiliado->dni) }}</dd></div>
                <div class="rounded-lg bg-slate-50 p-4"><dt class="text-xs font-black uppercase text-slate-500">Filial</dt><dd class="mt-1 font-black">{{ $afiliado->filial?->name ?: 'Central' }}</dd></div>
                <div class="rounded-lg bg-slate-50 p-4"><dt class="text-xs font-black uppercase text-slate-500">Vigente hasta</dt><dd class="mt-1 font-black">{{ optional($afiliado->carnet_vencimiento)->format('d/m/Y') ?: 'Sin fecha' }}</dd></div>
                <div class="rounded-lg bg-slate-50 p-4"><dt class="text-xs font-black uppercase text-slate-500">Verificado</dt><dd class="mt-1 font-black">{{ now()->format('d/m/Y H:i') }}</dd></div>
            </dl>

            @if ($valido)
                <p class="mx-auto mt-7 max-w-xl text-lg font-bold text-slate-700">Este afiliado está habilitado para acceder a todos los beneficios de ATSA Tucumán.</p>
            @elseif ($vencido)
                <p class="mx-auto mt-7 max-w-xl text-lg font-bold text-orange-700">Este carnet está vencido. Solicitá renovación en tu filial o sede central.</p>
            @else
                <p class="mx-auto mt-7 max-w-xl text-lg font-bold text-red-700">Este carnet no está habilitado. Contactá a la sede central para más información.</p>
            @endif
        </section>
    </main>

    <footer class="px-5 pb-8 text-center text-sm font-semibold text-slate-500">
        <p>Paraguay y Thames, San Miguel de Tucumán · 0381 4331665</p>
        <p class="mt-1">Verificación oficial - ATSA Tucumán</p>
    </footer>
</body>
</html>

