@php
    $labels = [
        'parcial1' => 'Parcial 1',
        'parcial2' => 'Parcial 2',
        'recuperatorio' => 'Recuperatorio',
        'final' => 'Final',
    ];
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; color: #1f2d3d; font-size: 10px; margin: 0; }
        .header { border-bottom: 3px solid #1e3a5f; padding-bottom: 14px; margin-bottom: 18px; }
        .brand { color: #1e3a5f; font-size: 22px; font-weight: 800; }
        .subtitle { color: #5d6b82; font-size: 11px; }
        .title { text-align: center; font-size: 18px; color: #1e3a5f; font-weight: 800; text-transform: uppercase; margin: 16px 0; }
        .meta { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
        .meta td { border: 1px solid #d8e1ec; padding: 7px 9px; }
        .meta .label { background: #eef4fb; color: #1e3a5f; font-weight: 700; width: 15%; }
        table.notas { width: 100%; border-collapse: collapse; }
        table.notas th, table.notas td { border: 1px solid #ccd7e2; padding: 6px; vertical-align: top; }
        table.notas th { background: #1e3a5f; color: #fff; text-align: left; }
        table.notas tr:nth-child(even) td { background: #f7f9fc; }
        .badge { display: inline-block; padding: 2px 7px; border-radius: 999px; background: #eef4fb; color: #1e3a5f; font-weight: 700; }
        .footer { position: fixed; bottom: 0; left: 0; right: 0; border-top: 1px solid #d8e1ec; padding-top: 7px; color: #6b7a90; font-size: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="brand">CENT N°74</div>
        <div class="subtitle">Centro Educativo de Nivel Terciario - Ficha académica</div>
    </div>

    <div class="title">Ficha académica del alumno</div>

    <table class="meta">
        <tr>
            <td class="label">Alumno</td>
            <td>{{ $alumno->name }}</td>
            <td class="label">DNI</td>
            <td>{{ $alumno->dni ?: 'No registrado' }}</td>
        </tr>
        <tr>
            <td class="label">Carrera</td>
            <td>{{ $matricula->carrera->name }}</td>
            <td class="label">Legajo</td>
            <td>{{ $matricula->legajo }}</td>
        </tr>
        <tr>
            <td class="label">Sede</td>
            <td>{{ $matricula->sede->nombre ?: 'CENT N°74' }}</td>
            <td class="label">Estado</td>
            <td>{{ ucfirst($matricula->estado) }}</td>
        </tr>
    </table>

    <table class="notas">
        <thead>
            <tr>
                <th>Materia</th>
                <th>Instancia</th>
                <th>Nota</th>
                <th>Estado</th>
                <th>Docente / carga</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @forelse($notas as $nota)
                <tr>
                    <td>
                        <strong>{{ $nota->comision->materia->name }}</strong><br>
                        {{ $nota->comision->materia->carrera->name }}
                    </td>
                    <td>{{ $labels[$nota->type] ?? ucfirst($nota->type) }}</td>
                    <td><span class="badge">{{ $nota->grade ?: '-' }}</span></td>
                    <td>{{ ucfirst($nota->status) }}</td>
                    <td>{{ $nota->cargadaPor->name ?: 'CENT N°74' }}</td>
                    <td>{{ $nota->created_at?->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">El alumno aún no tiene calificaciones cargadas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="title" style="font-size: 14px; margin-top: 22px;">Mesas de examen</div>

    <table class="notas">
        <thead>
            <tr>
                <th>Materia</th>
                <th>Fecha</th>
                <th>Sede</th>
                <th>Estado</th>
                <th>Nota</th>
                <th>Observaciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse(($inscripcionesMesa ?: collect()) as $inscripcion)
                <tr>
                    <td>
                        <strong>{{ $inscripcion->mesa->materia->name }}</strong><br>
                        {{ $inscripcion->mesa->materia->carrera->name }}
                    </td>
                    <td>{{ $inscripcion->mesa->fecha->format('d/m/Y') }}</td>
                    <td>{{ $inscripcion->mesa->sede->nombre ?: 'CENT N°74' }}</td>
                    <td>{{ ucfirst($inscripcion->estado) }}</td>
                    <td><span class="badge">{{ $inscripcion->nota ?: '-' }}</span></td>
                    <td>{{ $inscripcion->observaciones ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">El alumno aún no tiene inscripciones a mesas de examen.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Ficha generada el {{ now()->format('d/m/Y H:i') }} - CENT N°74.
    </div>
</body>
</html>
