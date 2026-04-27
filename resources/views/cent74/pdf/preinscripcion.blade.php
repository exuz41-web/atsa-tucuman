<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1f2a44;
            font-size: 11px;
            margin: 0;
            background: #ffffff;
        }
        .page {
            border: 2px solid #1e3a5f;
            padding: 18px 22px;
            min-height: 100%;
            position: relative;
        }
        .topline {
            height: 8px;
            background: linear-gradient(90deg, #1e3a5f, #49beff);
            margin: -18px -22px 18px;
        }
        .header {
            display: table;
            width: 100%;
            border-bottom: 2px solid #e5eaef;
            padding-bottom: 14px;
            margin-bottom: 16px;
        }
        .brand-cell, .code-cell { display: table-cell; vertical-align: middle; }
        .brand-cell { width: 66%; }
        .code-cell { width: 34%; text-align: right; }
        .brand {
            color: #1e3a5f;
            font-size: 26px;
            font-weight: bold;
            margin: 0;
            letter-spacing: -.4px;
        }
        .subtitle {
            color: #68758f;
            margin: 3px 0 0;
        }
        .code {
            background: #ecf7ff;
            color: #1e3a5f;
            font-weight: bold;
            padding: 8px 12px;
            display: inline-block;
            border-radius: 6px;
            border: 1px solid #cfe7fb;
        }
        h2 {
            color: #1e3a5f;
            font-size: 14px;
            margin: 16px 0 8px;
            border-bottom: 1px solid #d7deea;
            padding-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: .5px;
        }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 7px 6px; border-bottom: 1px dotted #9aa8bd; vertical-align: top; }
        .label { font-weight: bold; width: 30%; color: #42526b; }
        .note {
            background: #f6f8fb;
            padding: 11px;
            border-radius: 8px;
            margin-top: 14px;
            color: #42526b;
            border: 1px solid #e5eaef;
        }
        .docs {
            display: table;
            width: 100%;
            margin-top: 8px;
        }
        .doc {
            display: table-cell;
            width: 25%;
            padding: 8px;
            border: 1px solid #e5eaef;
            text-align: center;
        }
        .ok { color: #138a63; font-weight: bold; }
        .missing { color: #9a3412; font-weight: bold; }
        .sign { height: 58px; border-bottom: 1px solid #1f2a44; }
        .footer { margin-top: 16px; color: #68758f; font-size: 9px; text-align: center; }
    </style>
</head>
<body>
    <div class="page">
        <div class="topline"></div>

        <div class="header">
            <div class="brand-cell">
                <h1 class="brand">CENT N°74</h1>
                <p class="subtitle">Centro Educativo de Nivel Terciario · Ficha de preinscripción {{ $preinscripcion->ciclo_lectivo }}</p>
            </div>
            <div class="code-cell">
                <div class="code">{{ $preinscripcion->codigo }}</div>
            </div>
        </div>

        <h2>Datos académicos</h2>
        <table>
            <tr><td class="label">Carrera</td><td>{{ $preinscripcion->carrera->name }}</td></tr>
            <tr><td class="label">Sede</td><td>{{ $preinscripcion->sede->nombre }} - {{ $preinscripcion->sede->ciudad }}</td></tr>
            <tr><td class="label">Estado</td><td>{{ ucfirst(str_replace('_', ' ', $preinscripcion->estado)) }}</td></tr>
        </table>

        <h2>Datos personales</h2>
        <table>
            <tr><td class="label">Apellido y nombre</td><td>{{ $preinscripcion->apellido_nombre }}</td></tr>
            <tr><td class="label">Fecha de nacimiento</td><td>{{ optional($preinscripcion->fecha_nacimiento)->format('d/m/Y') ?: '-' }}</td></tr>
            <tr><td class="label">Documento</td><td>{{ $preinscripcion->tipo_documento }} {{ $preinscripcion->dni }}</td></tr>
            <tr><td class="label">Nacionalidad / Estado civil</td><td>{{ $preinscripcion->nacionalidad ?: '-' }} / {{ $preinscripcion->estado_civil ?: '-' }}</td></tr>
            <tr><td class="label">Domicilio</td><td>{{ $preinscripcion->domicilio ?: '-' }} - {{ $preinscripcion->localidad ?: '-' }}</td></tr>
            <tr><td class="label">Teléfono / Email</td><td>{{ $preinscripcion->telefono ?: '-' }} / {{ $preinscripcion->email }}</td></tr>
        </table>

        <h2>Datos educativos y laborales</h2>
        <table>
            <tr><td class="label">Establecimiento laboral</td><td>{{ $preinscripcion->establecimiento_laboral ?: '-' }}</td></tr>
            <tr><td class="label">Estudios</td><td>{{ $preinscripcion->nivel_estudios ?: '-' }} - {{ $preinscripcion->titulo_secundario ?: '-' }}</td></tr>
            <tr><td class="label">Observaciones</td><td>{{ $preinscripcion->observaciones_alumno ?: '-' }}</td></tr>
        </table>

        <h2>Documentación adjunta</h2>
        <div class="docs">
            <div class="doc">DNI<br><span class="{{ $preinscripcion->archivo_dni ? 'ok' : 'missing' }}">{{ $preinscripcion->archivo_dni ? 'Presentado' : 'Pendiente' }}</span></div>
            <div class="doc">Título/constancia<br><span class="{{ $preinscripcion->archivo_titulo ? 'ok' : 'missing' }}">{{ $preinscripcion->archivo_titulo ? 'Presentado' : 'Pendiente' }}</span></div>
            <div class="doc">Recibo<br><span class="{{ $preinscripcion->archivo_recibo ? 'ok' : 'missing' }}">{{ $preinscripcion->archivo_recibo ? 'Presentado' : 'No adjunto' }}</span></div>
            <div class="doc">Adicional<br><span class="{{ $preinscripcion->archivo_adicional ? 'ok' : 'missing' }}">{{ $preinscripcion->archivo_adicional ? 'Presentado' : 'No adjunto' }}</span></div>
        </div>

        <div class="note">
            Esta ficha deja constancia de la preinscripción. La vacante y la inscripción definitiva quedan sujetas a revisión de la documentación y confirmación de la sede correspondiente.
        </div>

        <table style="margin-top: 34px;">
            <tr>
                <td style="width: 45%; border:0;"><div class="sign"></div><p style="text-align:center;">Firma del aspirante</p></td>
                <td style="width: 10%; border:0;"></td>
                <td style="width: 45%; border:0;"><div class="sign"></div><p style="text-align:center;">Recepción CENT N°74</p></td>
            </tr>
        </table>

        <p class="footer">Ficha generada el {{ now()->format('d/m/Y H:i') }} · CENT N°74</p>
    </div>
</body>
</html>
