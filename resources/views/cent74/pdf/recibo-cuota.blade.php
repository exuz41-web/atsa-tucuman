<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #2a3547; }
        .box { border: 2px solid #1e3a5f; border-radius: 14px; padding: 28px; }
        h1 { color: #1e3a5f; margin: 0; }
        .muted { color: #7c8fac; }
        .amount { font-size: 34px; font-weight: bold; color: #1D9E75; }
        .row { margin: 12px 0; }
        .qr { width: 130px; height: 130px; }
        .badge { display: inline-block; background: #ecf7ff; color: #1e3a5f; border-radius: 999px; padding: 6px 12px; font-weight: bold; }
    </style>
</head>
<body>
<div class="box">
    <table width="100%">
        <tr>
            <td>
                <span class="badge">CENT N°74</span>
                <h1>Recibo oficial</h1>
                <div class="muted">{{ $recibo->numero }}</div>
            </td>
            <td align="right"><img class="qr" src="data:image/png;base64,{{ $qrCode }}" alt="QR"></td>
        </tr>
    </table>
    <hr>
    <div class="row"><strong>Alumno:</strong> {{ $recibo->alumno->name }} · DNI {{ $recibo->alumno->dni ?: '-' }}</div>
    <div class="row"><strong>Concepto:</strong> {{ $recibo->concepto }} {{ $recibo->periodo ? '· '.$recibo->periodo : '' }}</div>
    <div class="row"><strong>Carrera:</strong> {{ $recibo->cuota->matricula->carrera->name ?: '-' }}</div>
    <div class="row"><strong>Sede:</strong> {{ $recibo->cuota->matricula->sede->nombre ?: 'CENT N°74' }}</div>
    <div class="row"><strong>Emitido:</strong> {{ $recibo->emitido_at?->format('d/m/Y H:i') }}</div>
    <div class="amount">${{ number_format($recibo->monto, 2, ',', '.') }}</div>
    <p class="muted">Este recibo puede verificarse mediante el código QR.</p>
</div>
</body>
</html>
