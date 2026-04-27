<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitud de Afiliación — {{ $solicitud->apellido_nombre }}</title>
    <style>
        @page { margin: 12mm; size: A4 portrait; }
        * { box-sizing: border-box; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10pt;
            color: #1a1a1a;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        table { width: 100%; border-collapse: collapse; }
        td, th { vertical-align: top; }

        /* ── MEMBRETE ── */
        .hdr-strip {
            background: #1e3a5f;
            color: #fff;
            font-size: 7pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-align: center;
            padding: 3px 0;
        }
        .hdr-strip span { color: #93c5fd; font-weight: normal; }

        .hdr-main { padding: 8px 0; }
        .hdr-logo { width: 14mm; height: auto; }
        .hdr-name {
            font-size: 13pt;
            font-weight: bold;
            color: #1e3a5f;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .hdr-leg {
            font-size: 8pt;
            color: #374151;
            font-weight: 600;
            margin-top: 2px;
        }
        .hdr-contact {
            font-size: 7.5pt;
            color: #6b7280;
            margin-top: 2px;
        }
        .hdr-line {
            height: 3px;
            background: linear-gradient(to right, #1e3a5f 70%, #49beff 100%);
        }

        /* ── TÍTULO ── */
        .title-bar {
            background: #eef2f7;
            border-bottom: 1px solid #c8d8ea;
            padding: 5px 0;
        }
        .title-name {
            font-size: 10pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #1e3a5f;
        }
        .title-date {
            font-size: 8.5pt;
            color: #374151;
            font-style: italic;
            text-align: right;
        }

        /* ── SECCIONES ── */
        .sec-lbl {
            font-size: 7pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #1e3a5f;
            border-bottom: 1px solid #1e3a5f;
            padding-bottom: 2px;
            margin-top: 10px;
            margin-bottom: 6px;
        }

        .field-table { margin-bottom: 4px; }
        .field-table td { padding: 2px 4px; }
        .field-label {
            font-size: 6.5pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #6b7280;
            letter-spacing: 0.5px;
            white-space: nowrap;
            padding-bottom: 1px;
        }
        .field-value {
            border-bottom: 1px solid #374151;
            min-height: 14px;
            font-size: 9.5pt;
            padding: 1px 2px 2px;
        }
        .field-empty { color: #9ca3af; font-style: italic; }

        /* ── AUTORIZACIÓN ── */
        .auth-box {
            border: 1px solid #d1d5db;
            border-left: 3px solid #1e3a5f;
            background: #f8fafc;
            padding: 6px 8px;
            margin: 8px 0;
            font-size: 8.5pt;
            text-align: justify;
            line-height: 1.35;
        }

        /* ── FIRMAS ── */
        .sig-table { margin-top: 6px; }
        .sig-table td { text-align: center; padding: 4px; }
        .sig-line {
            border-bottom: 1px solid #374151;
            height: 14mm;
            margin-bottom: 2px;
        }
        .sig-cap {
            font-size: 7.5pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #374151;
            letter-spacing: 0.5px;
        }
        .sig-sub { font-size: 6.5pt; color: #9ca3af; font-style: italic; }

        /* ── BANDA OFICIAL ── */
        .oficial-box {
            border: 1px dashed #d1d5db;
            background: #f9fafb;
            padding: 4px 6px;
            margin-top: 6px;
        }
        .oficial-table td { padding: 2px 4px; }
        .oficial-lbl {
            font-size: 6.5pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #9ca3af;
            letter-spacing: 0.5px;
        }
        .oficial-val {
            border-bottom: 1px dotted #d1d5db;
            min-height: 12px;
            font-size: 7.5pt;
        }

        /* ── PIE ── */
        .footer {
            margin-top: 6px;
            padding-top: 4px;
            border-top: 1px solid #e5e7eb;
            font-size: 6.5pt;
            color: #9ca3af;
            font-style: italic;
            text-align: center;
        }

        /* ── MARCA DE AGUA ── */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 70mm;
            opacity: 0.035;
            z-index: -1;
        }
    </style>
</head>
<body>

    {{-- Marca de agua --}}
    <img src="{{ public_path('images/logo-atsa.png') }}" alt="" class="watermark">

    {{-- Franja superior --}}
    <div class="hdr-strip">
        Asociación de Trabajadores de la Sanidad Argentina &nbsp;&middot;&nbsp; <span>Seccional Tucumán</span>
    </div>

    {{-- Logo + datos --}}
    <table class="hdr-main">
        <tr>
            <td style="width: 18mm;">
                <img src="{{ public_path('images/logo-atsa.png') }}" alt="ATSA" class="hdr-logo">
            </td>
            <td>
                <div class="hdr-name">ATSA Tucumán</div>
                <div class="hdr-leg">Personería Gremial N° 000394 &nbsp;&middot;&nbsp; Adherida a F.A.T.S.A y C.G.T.</div>
                <div class="hdr-contact">Paraguay y Thames, San Miguel de Tucumán &nbsp;&middot;&nbsp; Tel: 0381 433-1665 &nbsp;&middot;&nbsp; www.atsatucuman.org.ar</div>
            </td>
        </tr>
    </table>
    <div class="hdr-line"></div>

    {{-- Barra título --}}
    <table class="title-bar">
        <tr>
            <td class="title-name">Solicitud de Adhesión Sindical</td>
            <td class="title-date">
                San Miguel de Tucumán, {{ now()->format('d/m/Y') }}
            </td>
        </tr>
    </table>

    {{-- DATOS PERSONALES --}}
    <div class="sec-lbl">Datos personales del solicitante</div>

    <table class="field-table">
        <tr>
            <td style="width: 65%;">
                <div class="field-label">Apellido y Nombre</div>
                <div class="field-value">{{ $solicitud->apellido_nombre ?: '—' }}</div>
            </td>
            <td style="width: 35%;">
                <div class="field-label">Fecha de Nacimiento</div>
                <div class="field-value">{{ $solicitud->fecha_nacimiento?->format('d/m/Y') ?: '—' }}</div>
            </td>
        </tr>
    </table>

    <table class="field-table">
        <tr>
            <td style="width: 25%;">
                <div class="field-label">Nacionalidad</div>
                <div class="field-value">{{ $solicitud->nacionalidad ?: '—' }}</div>
            </td>
            <td style="width: 25%;">
                <div class="field-label">Estado Civil</div>
                <div class="field-value">{{ $solicitud->estado_civil ?: '—' }}</div>
            </td>
            <td style="width: 25%;">
                <div class="field-label">Tipo Doc.</div>
                <div class="field-value">{{ $solicitud->tipo_documento ?: '—' }}</div>
            </td>
            <td style="width: 25%;">
                <div class="field-label">N° de Documento</div>
                <div class="field-value">{{ $solicitud->numero_documento ?: '—' }}</div>
            </td>
        </tr>
    </table>

    <table class="field-table">
        <tr>
            <td style="width: 55%;">
                <div class="field-label">Domicilio Particular</div>
                <div class="field-value">{{ $solicitud->domicilio ?: '—' }}</div>
            </td>
            <td style="width: 22%;">
                <div class="field-label">Teléfono</div>
                <div class="field-value">{{ $solicitud->telefono ?: '—' }}</div>
            </td>
            <td style="width: 23%;">
                <div class="field-label">Correo Electrónico</div>
                <div class="field-value">{{ $solicitud->email ?: '—' }}</div>
            </td>
        </tr>
    </table>

    {{-- DATOS LABORALES --}}
    <div class="sec-lbl">Datos laborales</div>

    <table class="field-table">
        <tr>
            <td style="width: 50%;">
                <div class="field-label">Establecimiento al que Pertenece</div>
                <div class="field-value">{{ $solicitud->establecimiento ?: '—' }}</div>
            </td>
            <td style="width: 25%;">
                <div class="field-label">Condición en Institución</div>
                <div class="field-value">{{ $solicitud->condicion_institucion ?: '—' }}</div>
            </td>
            <td style="width: 25%;">
                <div class="field-label">Filial Preferida</div>
                <div class="field-value">{{ $solicitud->filial_preferida ?: '—' }}</div>
            </td>
        </tr>
    </table>

    <table class="field-table">
        <tr>
            <td style="width: 40%;">
                <div class="field-label">Profesión / Categoría</div>
                <div class="field-value">{{ $solicitud->profesion ?: '—' }}</div>
            </td>
            <td style="width: 30%;">
                <div class="field-label">Nivel</div>
                <div class="field-value">{{ $solicitud->nivel ?: '—' }}</div>
            </td>
            <td style="width: 30%;">
                <div class="field-label">Legajo N°</div>
                <div class="field-value">{{ $solicitud->legajo ?: '—' }}</div>
            </td>
        </tr>
    </table>

    {{-- DECLARACIÓN JURADA --}}
    <div class="auth-box">
        El/La que suscribe tiene el agrado de dirigirse a Ud. a los efectos de solicitar su incorporación como afiliado/a a la Asociación de Trabajadores de la Sanidad Argentina — Seccional Tucumán (ATSA) y de <strong>autorizar expresamente el descuento del 2,5&nbsp;% de su remuneración mensual</strong>, destinado a cuota sindical ordinaria, de conformidad con lo establecido en la Ley N°&nbsp;23.551 de Asociaciones Sindicales y la normativa laboral vigente.
    </div>

    {{-- FIRMAS --}}
    <div class="sec-lbl">Firmas requeridas</div>

    <table class="sig-table">
        <tr>
            <td style="width: 100%;">
                <div class="sig-line"></div>
                <div class="sig-cap">Firma y Aclaración del Afiliado / a</div>
                <div class="sig-sub">Aclaración de firma &middot; D.N.I.</div>
            </td>
        </tr>
    </table>

    <table class="sig-table">
        <tr>
            <td style="width: 33%;">
                <div class="sig-line"></div>
                <div class="sig-cap">{{ $solicitud->nombre_afiliador ?: 'Nombre del Afiliador' }}</div>
                <div class="sig-sub">Nombre y apellido</div>
            </td>
            <td style="width: 34%;">
                <div class="sig-line"></div>
                <div class="sig-cap">Firma del Afiliador</div>
                <div class="sig-sub">Firma y sello</div>
            </td>
            <td style="width: 33%;">
                <div class="sig-line"></div>
                <div class="sig-cap">{{ $solicitud->celular_afiliador ?: 'Celular del Afiliador' }}</div>
                <div class="sig-sub">Teléfono de contacto</div>
            </td>
        </tr>
    </table>

    {{-- USO OFICIAL ATSA --}}
    <div class="oficial-box">
        <table class="oficial-table">
            <tr>
                <td style="width: 33%;">
                    <div class="oficial-lbl">N° de afiliado (uso ATSA)</div>
                    <div class="oficial-val"></div>
                </td>
                <td style="width: 34%;">
                    <div class="oficial-lbl">Fecha de alta</div>
                    <div class="oficial-val"></div>
                </td>
                <td style="width: 33%;">
                    <div class="oficial-lbl">Firma responsable ATSA</div>
                    <div class="oficial-val"></div>
                </td>
            </tr>
        </table>
    </div>

    {{-- PIE --}}
    <div class="footer">
        ATSA Tucumán &middot; Formulario de Adhesión Sindical &middot; Uso exclusivo administrativo &nbsp;&middot;&nbsp;
        Paraguay y Thames, S.M. de Tucumán &middot; 0381 433-1665
    </div>

</body>
</html>
