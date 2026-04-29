<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QR grande - ATSA Tucumán</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-white text-slate-900">
    <main class="flex min-h-screen flex-col items-center justify-center px-4 py-6 text-center">
        <p class="text-xs font-black uppercase tracking-widest text-slate-500">QR del carnet</p>
        <h1 class="mt-2 max-w-sm text-2xl font-black text-[#1e3a5f]">{{ $afiliado->name }}</h1>
        <p class="mt-1 text-sm font-bold text-slate-500">{{ $afiliado->numero_afiliado }}</p>

        <div class="mt-5 w-full max-w-[92vw] rounded-3xl border-8 border-white bg-white p-2 shadow-2xl sm:max-w-xl">
            <img
                src="data:image/png;base64,{{ $qrCode }}"
                alt="QR grande del carnet"
                class="mx-auto aspect-square w-full object-contain"
            >
        </div>

        <p class="mt-5 max-w-xs text-base font-bold text-slate-600">
            Sacá la foto cerca, derecha y con buena luz. Que se vea solo el cuadrado del QR.
        </p>
    </main>
</body>
</html>
