<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #2a3547; }
        .box { border: 2px solid #1e3a5f; border-radius: 16px; padding: 28px; }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e5eaef; padding-bottom: 18px; margin-bottom: 24px; }
        h1 { color: #1e3a5f; margin: 0; font-size: 28px; }
        .badge { background: #13deb9; color: #063b32; padding: 8px 14px; border-radius: 999px; font-weight: bold; }
        .row { margin-bottom: 12px; }
        .label { color: #7c8fac; font-size: 12px; text-transform: uppercase; letter-spacing: .08em; }
        .value { font-size: 18px; font-weight: bold; }
        .footer { margin-top: 28px; font-size: 12px; color: #5a6a85; }
        .qr { width: 150px; height: 150px; }
    </style>
</head>
<body>
<div class="box">
    <div class="header">
        <div>
            <h1>Permiso de examen</h1>
            <div>CENT N°74 · {{ $permiso->codigo }}</div>
        </div>
        <span class="badge">{{ strtoupper(str_replace('_', ' ', $permiso->estado)) }}</span>
    </div>

    <table width="100%">
        <tr>
            <td width="70%" valign="top">
                <div class="row"><div class="label">Alumno</div><div class="value">{{ $permiso->alumno->name }}</div></div>
                <div class="row"><div class="label">DNI</div><div class="value">{{ $permiso->alumno->dni ?: 'No registrado' }}</div></div>
                <div class="row"><div class="label">Carrera</div><div class="value">{{ $permiso->mesa->materia->carrera->name ?: '-' }}</div></div>
                <div class="row"><div class="label">Materia</div><div class="value">{{ $permiso->mesa->materia->name ?: '-' }}</div></div>
                <div class="row"><div class="label">Mesa</div><div class="value">{{ $permiso->mesa->fecha?->format('d/m/Y') }} {{ $permiso->mesa->hora }}</div></div>
                <div class="row"><div class="label">Sede</div><div class="value">{{ $permiso->mesa->sede->nombre ?: 'CENT N°74' }}</div></div>
            </td>
            <td width="30%" align="center" valign="top">
                <img class="qr" src="data:image/png;base64,{{ $qrCode }}" alt="QR">
                <div class="label">Verificación QR</div>
            </td>
        </tr>
    </table>

    <div class="footer">
        Este permiso es válido únicamente si su estado figura como habilitado al escanear el QR.
    </div>
</div>
</body>
</html>
