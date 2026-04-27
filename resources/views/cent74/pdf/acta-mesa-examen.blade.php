<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 11px; color: #1f2937; }
        .header { border-bottom: 3px solid #1e3a5f; padding-bottom: 10px; margin-bottom: 16px; }
        .title { font-size: 22px; font-weight: 800; color: #1e3a5f; margin: 0; }
        .muted { color: #64748b; }
        .grid { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        .grid td { padding: 7px 8px; border: 1px solid #dbe3ee; }
        .grid .label { width: 18%; background: #f4f7fb; font-weight: 700; color: #1e3a5f; }
        table.list { width: 100%; border-collapse: collapse; }
        table.list th { background: #1e3a5f; color: #fff; padding: 8px; text-align: left; }
        table.list td { border: 1px solid #dbe3ee; padding: 7px; vertical-align: top; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 999px; background: #e8f0fe; color: #1e3a5f; font-weight: 700; }
        .firmas { margin-top: 36px; width: 100%; }
        .firmas td { width: 33%; text-align: center; padding-top: 32px; border-top: 1px solid #111827; }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title">Acta final de mesa de examen</h1>
        <div class="muted">CENT N°74 - ATSA Tucumán</div>
    </div>

    <table class="grid">
        <tr>
            <td class="label">Materia</td>
            <td>{{ $mesa->materia->name }}</td>
            <td class="label">Carrera</td>
            <td>{{ $mesa->materia->carrera->name }}</td>
        </tr>
        <tr>
            <td class="label">Sede</td>
            <td>{{ $mesa->sede->nombre ?: 'CENT N°74' }}</td>
            <td class="label">Docente</td>
            <td>{{ $mesa->docente->name ?: 'A designar' }}</td>
        </tr>
        <tr>
            <td class="label">Fecha</td>
            <td>{{ $mesa->fecha->format('d/m/Y') }} {{ $mesa->hora }}</td>
            <td class="label">Aula / Turno</td>
            <td>{{ $mesa->aula ?: '-' }} / {{ $mesa->turno ?: '-' }}</td>
        </tr>
        <tr>
            <td class="label">Estado</td>
            <td><span class="badge">{{ ucfirst($mesa->acta_estado ?: 'abierta') }}</span></td>
            <td class="label">Libro / Folio</td>
            <td>{{ $mesa->acta_libro ?: '-' }} / {{ $mesa->acta_folio ?: '-' }}</td>
        </tr>
    </table>

    <table class="list">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th>Alumno</th>
                <th style="width: 14%;">DNI</th>
                <th style="width: 14%;">Estado</th>
                <th style="width: 10%;">Nota</th>
                <th>Observaciones</th>
            </tr>
        </thead>
        <tbody>
        @forelse($mesa->inscripciones as $inscripcion)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $inscripcion->alumno->name }}</td>
                <td>{{ $inscripcion->alumno->dni ?: '-' }}</td>
                <td>{{ ucfirst($inscripcion->estado) }}</td>
                <td>{{ $inscripcion->nota ?: '-' }}</td>
                <td>{{ $inscripcion->observaciones ?: '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6">No hay alumnos inscriptos.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    @if($mesa->acta_observaciones)
        <p><strong>Observaciones:</strong> {{ $mesa->acta_observaciones }}</p>
    @endif

    <table class="firmas">
        <tr>
            <td>Docente</td>
            <td>Dirección académica</td>
            <td>Secretaría</td>
        </tr>
    </table>
</body>
</html>
