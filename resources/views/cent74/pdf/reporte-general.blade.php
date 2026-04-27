<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #2a3547; font-size: 12px; }
        h1 { color: #1e3a5f; margin-bottom: 4px; }
        .stats { display: table; width: 100%; margin: 18px 0; }
        .stat { display: table-cell; padding: 12px; border: 1px solid #e5eaef; }
        .num { font-size: 24px; font-weight: bold; color: #1e3a5f; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border-bottom: 1px solid #e5eaef; padding: 8px; text-align: left; }
        th { background: #f6f8fb; }
    </style>
</head>
<body>
<h1>Reporte general CENT N°74</h1>
<div>Emitido: {{ now()->format('d/m/Y H:i') }}</div>

<div class="stats">
    <div class="stat"><div class="num">{{ $matriculas }}</div><div>Matrículas</div></div>
    <div class="stat"><div class="num">{{ $morosos }}</div><div>Cuotas pendientes/vencidas</div></div>
    <div class="stat"><div class="num">{{ $preinscripciones }}</div><div>Preinscripciones</div></div>
    <div class="stat"><div class="num">{{ $actasPendientes }}</div><div>Actas pendientes</div></div>
</div>

<h2>Últimas cuotas</h2>
<table>
    <thead><tr><th>Alumno</th><th>Concepto</th><th>Periodo</th><th>Final</th><th>Estado</th></tr></thead>
    <tbody>
    @foreach($cuotas as $cuota)
        <tr><td>{{ $cuota->alumno->name ?: '-' }}</td><td>{{ $cuota->concepto }}</td><td>{{ $cuota->periodo }}</td><td>${{ number_format($cuota->monto_final, 2, ',', '.') }}</td><td>{{ $cuota->estado }}</td></tr>
    @endforeach
    </tbody>
</table>

<h2>Mesas de examen</h2>
<table>
    <thead><tr><th>Materia</th><th>Fecha</th><th>Estado</th><th>Inscriptos</th></tr></thead>
    <tbody>
    @foreach($mesas as $mesa)
        <tr><td>{{ $mesa->materia->name ?: '-' }}</td><td>{{ $mesa->fecha?->format('d/m/Y') }}</td><td>{{ $mesa->estado }}</td><td>{{ $mesa->inscripciones_count }}</td></tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
