<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; color: #1f2d3d; font-size: 13px; line-height: 1.6; }
        .header { border-bottom: 4px solid #1e3a5f; padding-bottom: 18px; margin-bottom: 34px; }
        .brand { color: #1e3a5f; font-size: 26px; font-weight: 800; }
        .subtitle { color: #5d6b82; }
        .title { text-align: center; color: #1e3a5f; font-size: 21px; font-weight: 800; text-transform: uppercase; margin: 28px 0; }
        .box { border: 1px solid #d8e1ec; background: #f7f9fc; padding: 18px; margin: 22px 0; }
        .badge { display: inline-block; padding: 4px 10px; background: #1e3a5f; color: white; border-radius: 999px; font-weight: 700; }
        .footer { position: fixed; bottom: 0; left: 0; right: 0; border-top: 1px solid #d8e1ec; padding-top: 8px; color: #6b7a90; font-size: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="brand">CENT N°74</div>
        <div class="subtitle">Comprobante de inscripción a mesa de examen</div>
    </div>

    <div class="title">Comprobante de inscripción</div>

    <p>
        Se deja constancia que <strong>{{ $inscripcion->alumno->name }}</strong>,
        DNI <strong>{{ $inscripcion->alumno->dni ?: 'no registrado' }}</strong>, se encuentra inscripto/a a la mesa de examen indicada.
    </p>

    <div class="box">
        <strong>Materia:</strong> {{ $inscripcion->mesa->materia->name }}<br>
        <strong>Carrera:</strong> {{ $inscripcion->mesa->materia->carrera->name }}<br>
        <strong>Sede:</strong> {{ $inscripcion->mesa->sede->nombre ?: 'CENT N°74' }}<br>
        <strong>Fecha:</strong> {{ $inscripcion->mesa->fecha->format('d/m/Y') }}<br>
        <strong>Hora:</strong> {{ $inscripcion->mesa->hora ?: 'A confirmar' }}<br>
        <strong>Aula:</strong> {{ $inscripcion->mesa->aula ?: 'A confirmar' }}<br>
        <strong>Estado:</strong> <span class="badge">{{ ucfirst($inscripcion->estado) }}</span>
    </div>

    <p>Emitido el {{ now()->format('d/m/Y H:i') }}.</p>

    <div class="footer">
        Documento emitido desde el sistema institucional del CENT N°74.
    </div>
</body>
</html>
