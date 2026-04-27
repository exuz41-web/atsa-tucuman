<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; color: #1f2d3d; font-size: 13px; line-height: 1.6; }
        .header { border-bottom: 4px solid #1e3a5f; padding-bottom: 18px; margin-bottom: 36px; }
        .brand { color: #1e3a5f; font-size: 26px; font-weight: 800; }
        .subtitle { color: #5d6b82; }
        .title { text-align: center; color: #1e3a5f; font-size: 22px; font-weight: 800; text-transform: uppercase; margin: 30px 0; }
        .box { border: 1px solid #d8e1ec; background: #f7f9fc; padding: 18px; margin: 24px 0; }
        .signature { margin-top: 70px; text-align: center; }
        .line { border-top: 1px solid #1f2d3d; padding-top: 8px; width: 260px; margin: 0 auto; font-weight: 700; }
        .footer { position: fixed; bottom: 0; left: 0; right: 0; border-top: 1px solid #d8e1ec; padding-top: 8px; color: #6b7a90; font-size: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="brand">CENT N°74</div>
        <div class="subtitle">Centro Educativo de Nivel Terciario - Formación superior en salud</div>
    </div>

    <div class="title">Constancia de alumno regular</div>

    <p>
        Por la presente se deja constancia que <strong>{{ $alumno->name }}</strong>,
        DNI <strong>{{ $alumno->dni ?: 'no registrado' }}</strong>, se encuentra matriculado/a en
        <strong>{{ $matricula->carrera->name }}</strong>, correspondiente al ciclo lectivo
        <strong>{{ $matricula->ciclo_lectivo }}</strong>.
    </p>

    <div class="box">
        <strong>Legajo:</strong> {{ $matricula->legajo }}<br>
        <strong>Sede:</strong> {{ $matricula->sede->nombre ?: 'CENT N°74' }}<br>
        <strong>Estado:</strong> {{ ucfirst($matricula->estado) }}<br>
        <strong>Fecha de ingreso:</strong> {{ $matricula->fecha_ingreso?->format('d/m/Y') ?: 'No informada' }}
    </div>

    <p>
        Se extiende la presente constancia a solicitud del/de la interesado/a, para ser presentada ante quien corresponda.
    </p>

    <p>San Miguel de Tucumán, {{ now()->format('d/m/Y') }}.</p>

    <div class="signature">
        <div class="line">Dirección académica</div>
    </div>

    <div class="footer">
        Documento emitido desde el sistema institucional del CENT N°74 el {{ now()->format('d/m/Y H:i') }}.
    </div>
</body>
</html>
