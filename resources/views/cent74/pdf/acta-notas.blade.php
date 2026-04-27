@php
    $labels = [
        'parcial1' => 'Parcial 1',
        'parcial2' => 'Parcial 2',
        'recuperatorio' => 'Recuperatorio',
        'final' => 'Final',
    ];

    $estadoLabels = [
        'aprobado' => 'Aprobado',
        'desaprobado' => 'Desaprobado',
        'ausente' => 'Ausente',
        'libre' => 'Libre',
    ];

    $actaEstado = $comision->acta_estado ?: 'abierta';
    $actaLabel = match ($actaEstado) {
        'cerrada' => 'Cerrada',
        'aprobada' => 'Aprobada',
        default => 'Abierta',
    };
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        * { box-sizing: border-box; }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            color: #1f2d3d;
            font-size: 10px;
            margin: 0;
        }

        .header {
            border-bottom: 3px solid #1e3a5f;
            padding-bottom: 14px;
            margin-bottom: 18px;
        }

        .brand {
            color: #1e3a5f;
            font-size: 22px;
            font-weight: 800;
            margin-bottom: 3px;
        }

        .subtitle {
            color: #5d6b82;
            font-size: 11px;
        }

        .title {
            text-align: center;
            font-size: 18px;
            font-weight: 800;
            color: #1e3a5f;
            margin: 16px 0;
            text-transform: uppercase;
        }

        .meta {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        .meta td {
            border: 1px solid #d8e1ec;
            padding: 7px 9px;
        }

        .meta .label {
            background: #eef4fb;
            color: #1e3a5f;
            font-weight: 700;
            width: 15%;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 999px;
            color: #ffffff;
            font-weight: 700;
        }

        .badge-abierta { background: #378add; }
        .badge-cerrada { background: #ba7517; }
        .badge-aprobada { background: #1d9e75; }

        table.acta {
            width: 100%;
            border-collapse: collapse;
        }

        table.acta th,
        table.acta td {
            border: 1px solid #ccd7e2;
            padding: 6px;
            vertical-align: top;
        }

        table.acta th {
            background: #1e3a5f;
            color: #ffffff;
            font-weight: 700;
            text-align: left;
        }

        table.acta tr:nth-child(even) td { background: #f7f9fc; }

        .small {
            color: #5d6b82;
            font-size: 9px;
        }

        .status {
            display: block;
            color: #5d6b82;
            font-size: 8px;
            margin-top: 2px;
        }

        .observaciones {
            border: 1px solid #d8e1ec;
            background: #f7f9fc;
            padding: 9px 11px;
            margin: 0 0 18px;
        }

        .signatures {
            width: 100%;
            margin-top: 34px;
            border-collapse: collapse;
        }

        .signatures td {
            width: 33.33%;
            text-align: center;
            padding-top: 28px;
        }

        .line {
            border-top: 1px solid #1f2d3d;
            padding-top: 7px;
            margin: 0 24px;
            font-weight: 700;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            border-top: 1px solid #d8e1ec;
            padding-top: 7px;
            color: #6b7a90;
            font-size: 8px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="brand">CENT N°74</div>
        <div class="subtitle">Centro Educativo de Nivel Terciario - Formación superior en salud</div>
    </div>

    <div class="title">Acta de notas</div>

    <table class="meta">
        <tr>
            <td class="label">Materia</td>
            <td>{{ $comision->materia->name }}</td>
            <td class="label">Carrera</td>
            <td>{{ $comision->materia->carrera->name }}</td>
        </tr>
        <tr>
            <td class="label">Sede</td>
            <td>{{ $comision->sede->nombre ?: 'CENT N°74' }}</td>
            <td class="label">Ciclo</td>
            <td>{{ $comision->year_cycle }}</td>
        </tr>
        <tr>
            <td class="label">Docente</td>
            <td>{{ $comision->docente->name ?: 'A designar' }}</td>
            <td class="label">Horario</td>
            <td>{{ $comision->schedule ?: 'No informado' }}</td>
        </tr>
        <tr>
            <td class="label">Estado</td>
            <td><span class="badge badge-{{ $actaEstado }}">{{ $actaLabel }}</span></td>
            <td class="label">Cierre</td>
            <td>
                @if($comision->acta_cerrada_at)
                    {{ $comision->acta_cerrada_at->format('d/m/Y H:i') }}
                    @if($comision->cerradaPor) - {{ $comision->cerradaPor->name }} @endif
                @else
                    Sin cerrar
                @endif
            </td>
        </tr>
        <tr>
            <td class="label">Aprobación</td>
            <td colspan="3">
                @if($comision->acta_aprobada_at)
                    {{ $comision->acta_aprobada_at->format('d/m/Y H:i') }}
                    @if($comision->aprobadaPor) - {{ $comision->aprobadaPor->name }} @endif
                @else
                    Pendiente
                @endif
            </td>
        </tr>
    </table>

    @if($comision->acta_observaciones)
        <div class="observaciones">
            <strong>Observaciones:</strong> {{ $comision->acta_observaciones }}
        </div>
    @endif

    <table class="acta">
        <thead>
            <tr>
                <th style="width: 23%;">Alumno</th>
                <th style="width: 10%;">DNI</th>
                <th style="width: 10%;">Inscripción</th>
                @foreach($tiposNota as $type)
                    <th>{{ $labels[$type] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
        @forelse($comision->inscripciones as $inscripcion)
            @php($alumnoNotas = $notas->get($inscripcion->alumno_id, collect()))
            <tr>
                <td>
                    <strong>{{ $inscripcion->alumno->name }}</strong>
                    <div class="small">{{ $inscripcion->alumno->email }}</div>
                </td>
                <td>{{ $inscripcion->alumno->dni ?: '-' }}</td>
                <td>{{ ucfirst($inscripcion->status) }}</td>
                @foreach($tiposNota as $type)
                    @php($nota = $alumnoNotas->get($type))
                    <td>
                        <strong>{{ $nota?->grade ?: '-' }}</strong>
                        <span class="status">{{ $nota?->status ? ($estadoLabels[$nota->status] ?? ucfirst($nota->status)) : 'Sin cargar' }}</span>
                    </td>
                @endforeach
            </tr>
        @empty
            <tr>
                <td colspan="{{ 3 + count($tiposNota) }}">Esta comisión no tiene alumnos inscriptos.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <table class="signatures">
        <tr>
            <td><div class="line">Firma docente</div></td>
            <td><div class="line">Dirección académica</div></td>
            <td><div class="line">Sello institucional</div></td>
        </tr>
    </table>

    <div class="footer">
        Acta generada el {{ now()->format('d/m/Y H:i') }} - CENT N°74 - Documento académico emitido desde el sistema institucional.
    </div>
</body>
</html>
