<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: DejaVu Sans, sans-serif; color: #1f2d3d; }
        .card {
            width: 85.6mm;
            height: 53.98mm;
            background: #fff;
            border-radius: 5mm;
            overflow: hidden;
            border: .3mm solid #dce5f0;
            position: relative;
        }
        .circle {
            position: absolute;
            right: -13mm;
            top: -18mm;
            width: 42mm;
            height: 42mm;
            border-radius: 50%;
            background: #ecf2ff;
        }
        .body { padding: 5mm; position: relative; z-index: 2; }
        .title { font-size: 4.5mm; font-weight: bold; color: #1e3a5f; }
        .sub { font-size: 2.3mm; color: #5d87ff; font-weight: bold; letter-spacing: .4mm; }
        .photo {
            width: 17mm;
            height: 17mm;
            border-radius: 50%;
            object-fit: cover;
            border: 1.4mm solid #ecf2ff;
        }
        .name { font-size: 4mm; font-weight: bold; margin-bottom: 1mm; }
        .label { font-size: 2mm; color: #7c8fac; }
        .value { font-size: 2.6mm; font-weight: bold; }
        .qr { width: 15mm; height: 15mm; background: #fff; }
        .footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(90deg, #1e3a5f, #49beff);
            color: #fff;
            text-align: center;
            padding: 2.2mm;
            font-size: 2.1mm;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="card">
    <div class="circle"></div>
    <div class="body">
        <table width="100%">
            <tr>
                <td>
                    <div class="title">CENT N°74</div>
                    <div class="sub">CREDENCIAL DE ESTUDIANTE</div>
                </td>
                <td align="right"><div class="value">ALUMNO REGULAR</div></td>
            </tr>
        </table>
        <table width="100%" style="margin-top: 5mm;">
            <tr>
                <td width="24%" align="center">
                    @if($alumno->foto_perfil)
                        <img class="photo" src="{{ storage_path('app/public/'.$alumno->foto_perfil) }}" alt="Foto">
                    @else
                        <div class="photo" style="background:#1e3a5f;color:#fff;line-height:15mm;font-size:7mm;text-align:center;">{{ mb_substr($alumno->name, 0, 1) }}</div>
                    @endif
                </td>
                <td width="52%">
                    <div class="name">{{ $alumno->name }}</div>
                    <div class="label">DNI</div><div class="value">{{ $alumno->dni ?: '-' }}</div>
                    <div class="label" style="margin-top:1.5mm;">Carrera</div><div class="value">{{ $matricula?->carrera?->name ?: '-' }}</div>
                    <div class="label" style="margin-top:1.5mm;">Sede</div><div class="value">{{ $matricula?->sede?->nombre ?: 'CENT N°74' }}</div>
                </td>
                <td width="24%" align="center">
                    <img class="qr" src="data:image/png;base64,{{ $qrCode }}" alt="QR">
                    <div class="label">Verificar</div>
                </td>
            </tr>
        </table>
    </div>
    <div class="footer">CENTRO EDUCATIVO DE NIVEL TERCIARIO N°74</div>
</div>
</body>
</html>
