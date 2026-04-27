<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Carnet no encontrado - ATSA Tucumán</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="grid min-h-screen place-items-center bg-slate-100 px-5 text-slate-800">
    @php($siteLogo = \App\Models\SiteSetting::logoUrl())
    <main class="max-w-xl rounded-2xl bg-white p-8 text-center shadow-xl">
        <img src="{{ $siteLogo }}" alt="ATSA Tucumán" class="mx-auto mb-6 h-16 w-auto">
        <div class="mx-auto grid h-20 w-20 place-items-center rounded-full bg-orange-100 text-4xl font-black text-orange-600">!</div>
        <h1 class="mt-6 text-3xl font-black text-[#1e3a5f]">Numero de afiliado no encontrado</h1>
        <p class="mt-4 text-lg font-semibold text-slate-600">Si creés que hay un error, contactá a ATSA Tucumán o acercate a tu filial más cercana.</p>
        <div class="mt-6 rounded-lg bg-slate-50 p-4 text-sm font-bold text-slate-600">
            Paraguay y Thames, San Miguel de Tucumán · 0381 4331665
        </div>
    </main>
</body>
</html>


