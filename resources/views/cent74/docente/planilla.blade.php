@extends('layouts.cent')

@section('title', 'Planilla de notas')
@section('header', 'Planilla docente')

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
    $actaEditable = ! in_array($actaEstado, ['cerrada', 'aprobada'], true);
    $portalRole = auth()->user()?->cent_role ?: auth()->user()?->role;
    $puedeAprobar = in_array($portalRole, ['admin', 'directivo', 'coordinador'], true);

    $estadoMeta = [
        'abierta' => ['label' => 'Abierta', 'class' => 'bg-info-subtle text-info', 'icon' => 'ti-edit'],
        'cerrada' => ['label' => 'Cerrada', 'class' => 'bg-warning-subtle text-warning', 'icon' => 'ti-lock'],
        'aprobada' => ['label' => 'Aprobada', 'class' => 'bg-success-subtle text-success', 'icon' => 'ti-circle-check'],
    ][$actaEstado] ?? ['label' => 'Abierta', 'class' => 'bg-info-subtle text-info', 'icon' => 'ti-edit'];

    $totalAlumnos = $comision->inscripciones->count();
    $totalNotas = $comision->notas->count();
    $promedio = $comision->notas->whereNotNull('grade')->avg(fn ($nota) => (float) $nota->grade);
    $pendientes = max(($totalAlumnos * count($tiposNota)) - $totalNotas, 0);
@endphp

@section('content')
<section class="portal-hero p-4 p-xl-5 mb-4">
    <div class="row g-4 align-items-center">
        <div class="col-xl-8">
            <span class="badge bg-white text-primary mb-3">Acta de cursado</span>
            <h1 class="display-6 fw-bolder mb-3">{{ $comision->materia->name }}</h1>
            <p class="fs-5 text-muted mb-0">
                {{ $comision->materia->carrera->name }}
                &middot; {{ $comision->sede->nombre ?: 'CENT N°74' }}
                &middot; Ciclo {{ $comision->year_cycle }}
                @if($comision->schedule)
                    &middot; {{ $comision->schedule }}
                @endif
            </p>
        </div>
        <div class="col-xl-4">
            <div class="modern-card p-4 text-dark">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="small text-muted fw-bold text-uppercase">Estado del acta</div>
                        <span class="badge {{ $estadoMeta['class'] }} mt-2">
                            <i class="ti {{ $estadoMeta['icon'] }} me-1"></i>{{ $estadoMeta['label'] }}
                        </span>
                    </div>
                    <div class="stat-icon bg-primary-subtle text-primary">
                        <i class="ti ti-clipboard-list"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-tile">
            <div class="small text-muted">Alumnos</div>
            <h3 class="fw-bolder mb-0">{{ $totalAlumnos }}</h3>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-tile">
            <div class="small text-muted">Notas cargadas</div>
            <h3 class="fw-bolder mb-0">{{ $totalNotas }}</h3>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-tile">
            <div class="small text-muted">Promedio general</div>
            <h3 class="fw-bolder mb-0">{{ $promedio ? number_format($promedio, 2, ',', '.') : '-' }}</h3>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-tile">
            <div class="small text-muted">Instancias pendientes</div>
            <h3 class="fw-bolder mb-0">{{ $pendientes }}</h3>
        </div>
    </div>
</div>

<div class="modern-card p-4 mb-4">
    <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
        <div>
            <h4 class="fw-bolder mb-1">Control académico del acta</h4>
            <p class="text-muted mb-0">
                @if($actaEstado === 'abierta')
                    El docente puede guardar avances. Cuando termine la carga, debe cerrar el acta para revisión directiva.
                @elseif($actaEstado === 'cerrada')
                    El acta está cerrada. Dirección puede aprobarla o reabrirla para correcciones.
                @else
                    El acta fue aprobada. La planilla queda bloqueada como registro académico.
                @endif
            </p>
        </div>
        <div class="text-muted small">
            @if($comision->acta_cerrada_at)
                Cerrada: {{ $comision->acta_cerrada_at->format('d/m/Y H:i') }}
                @if($comision->cerradaPor) por {{ $comision->cerradaPor->name }} @endif
                <br>
            @endif
            @if($comision->acta_aprobada_at)
                Aprobada: {{ $comision->acta_aprobada_at->format('d/m/Y H:i') }}
                @if($comision->aprobadaPor) por {{ $comision->aprobadaPor->name }} @endif
            @endif
        </div>
    </div>

    @if($comision->acta_observaciones)
        <div class="alert alert-light border rounded-4 mt-3 mb-0">
            <strong>Observaciones:</strong> {{ $comision->acta_observaciones }}
        </div>
    @endif
</div>

<div class="d-flex justify-content-between gap-3 flex-wrap mb-4">
    <a href="{{ route('cent.docente.comisiones') }}" class="btn btn-light">
        <i class="ti ti-arrow-left me-1"></i> Volver a comisiones
    </a>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('cent.docente.planilla.pdf', $comision) }}" class="btn btn-outline-primary">
            <i class="ti ti-file-type-pdf me-1"></i> Descargar acta PDF
        </a>
        @if($actaEditable)
            <button form="planillaNotas" class="btn btn-primary">
                <i class="ti ti-device-floppy me-1"></i> Guardar borrador
            </button>
            <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#cerrarActa">
                <i class="ti ti-lock me-1"></i> Cerrar acta
            </button>
        @endif
        @if($puedeAprobar && $actaEstado === 'cerrada')
            <form method="POST" action="{{ route('cent.docente.planilla.aprobar', $comision) }}">
                @csrf
                <button class="btn btn-success" onclick="return confirm('¿Confirmar aprobación del acta?')">
                    <i class="ti ti-check me-1"></i> Aprobar
                </button>
            </form>
        @endif
        @if($puedeAprobar && in_array($actaEstado, ['cerrada', 'aprobada'], true))
            <button class="btn btn-outline-danger" type="button" data-bs-toggle="collapse" data-bs-target="#reabrirActa">
                <i class="ti ti-lock-open me-1"></i> Reabrir
            </button>
        @endif
    </div>
</div>

@if($actaEditable)
    <div class="collapse mb-4" id="cerrarActa">
        <div class="modern-card p-4">
            <form action="{{ route('cent.docente.planilla.cerrar', $comision) }}" method="POST" class="row g-3">
                @csrf
                <div class="col-12">
                    <label class="form-label fw-bold">Observaciones de cierre</label>
                    <textarea name="acta_observaciones" class="form-control" rows="3" placeholder="Detalle opcional para dirección">{{ old('acta_observaciones', $comision->acta_observaciones) }}</textarea>
                </div>
                <div class="col-12">
                    <button class="btn btn-warning" onclick="return confirm('¿Cerrar acta y bloquear carga de notas?')">
                        Confirmar cierre
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif

@if($puedeAprobar && in_array($actaEstado, ['cerrada', 'aprobada'], true))
    <div class="collapse mb-4" id="reabrirActa">
        <div class="modern-card p-4">
            <form action="{{ route('cent.docente.planilla.reabrir', $comision) }}" method="POST" class="row g-3">
                @csrf
                <div class="col-12">
                    <label class="form-label fw-bold">Motivo de reapertura</label>
                    <textarea name="acta_observaciones" class="form-control" rows="3" placeholder="Indicar motivo para corregir el acta">{{ old('acta_observaciones', $comision->acta_observaciones) }}</textarea>
                </div>
                <div class="col-12">
                    <button class="btn btn-outline-danger" onclick="return confirm('¿Reabrir acta para correcciones?')">
                        Confirmar reapertura
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif

<form id="planillaNotas" action="{{ route('cent.docente.planilla.guardar', $comision) }}" method="POST">
    @csrf
    <div class="modern-card p-3 p-xl-4">
        <div class="table-responsive">
            <table class="table align-middle table-hover">
                <thead>
                    <tr>
                        <th style="min-width: 260px;">Alumno</th>
                        <th>DNI</th>
                        <th>Inscripción</th>
                        @foreach($tiposNota as $type)
                            <th style="min-width: 230px;">{{ $labels[$type] }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                @forelse($comision->inscripciones as $inscripcion)
                    @php($alumnoNotas = $notas->get($inscripcion->alumno_id, collect()))
                    <tr>
                        <td>
                            <strong>{{ $inscripcion->alumno->name }}</strong>
                            <div class="small text-muted">{{ $inscripcion->alumno->email }}</div>
                        </td>
                        <td>{{ $inscripcion->alumno->dni ?: '-' }}</td>
                        <td>
                            <span class="badge bg-info-subtle text-info">{{ ucfirst($inscripcion->status) }}</span>
                        </td>
                        @foreach($tiposNota as $type)
                            @php($nota = $alumnoNotas->get($type))
                            <td>
                                <div class="d-flex gap-2">
                                    <input
                                        type="number"
                                        name="notas[{{ $inscripcion->alumno_id }}][{{ $type }}][grade]"
                                        value="{{ old('notas.'.$inscripcion->alumno_id.'.'.$type.'.grade', $nota?->grade) }}"
                                        min="0"
                                        max="10"
                                        step="0.01"
                                        class="form-control"
                                        placeholder="Nota"
                                        @disabled(! $actaEditable)
                                    >
                                    <select
                                        name="notas[{{ $inscripcion->alumno_id }}][{{ $type }}][status]"
                                        class="form-select"
                                        @disabled(! $actaEditable)
                                    >
                                        <option value="">Auto</option>
                                        @foreach($estadosNota as $estado)
                                            <option value="{{ $estado }}" @selected(old('notas.'.$inscripcion->alumno_id.'.'.$type.'.status', $nota?->status) === $estado)>
                                                {{ $estadoLabels[$estado] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ 3 + count($tiposNota) }}" class="text-center text-muted py-5">
                            Esta comisión todavía no tiene alumnos inscriptos.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</form>

<div class="modern-card p-4 mt-4">
    <h4 class="fw-bolder mb-3">Criterio sugerido</h4>
    <div class="row g-3">
        <div class="col-md-3"><span class="badge bg-success-subtle text-success w-100 py-2">6 a 10: aprobado</span></div>
        <div class="col-md-3"><span class="badge bg-danger-subtle text-danger w-100 py-2">0 a 5.99: desaprobado</span></div>
        <div class="col-md-3"><span class="badge bg-warning-subtle text-warning w-100 py-2">Sin nota: ausente</span></div>
        <div class="col-md-3"><span class="badge bg-secondary-subtle text-secondary w-100 py-2">Libre: condición especial</span></div>
    </div>
</div>
@endsection
