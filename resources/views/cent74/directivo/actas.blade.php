@extends('layouts.cent')

@section('title', 'Actas académicas')
@section('header', 'Actas académicas')

@php
    $estadoMeta = [
        'abierta' => ['label' => 'Abierta', 'class' => 'bg-info-subtle text-info', 'icon' => 'ti-edit'],
        'cerrada' => ['label' => 'Cerrada', 'class' => 'bg-warning-subtle text-warning', 'icon' => 'ti-lock'],
        'aprobada' => ['label' => 'Aprobada', 'class' => 'bg-success-subtle text-success', 'icon' => 'ti-circle-check'],
    ];
@endphp

@section('content')
<section class="portal-hero p-4 p-xl-5 mb-4">
    <div class="row g-4 align-items-center">
        <div class="col-xl-8">
            <span class="badge bg-white text-primary mb-3">Dirección académica</span>
            <h1 class="display-6 fw-bolder mb-3">Control de actas</h1>
            <p class="fs-5 text-muted mb-0">
                Revisá planillas cerradas, aprobá actas definitivas y reabrí aquellas que necesiten corrección.
            </p>
        </div>
        <div class="col-xl-4">
            <div class="modern-card p-4 text-dark">
                <div class="small text-muted">Alumnos incluidos en actas</div>
                <h2 class="fw-bolder mb-0">{{ $resumen['alumnos'] }}</h2>
            </div>
        </div>
    </div>
</section>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-tile">
            <div class="stat-icon bg-info-subtle text-info mb-3"><i class="ti ti-edit"></i></div>
            <div class="small text-muted">Actas abiertas</div>
            <h3 class="fw-bolder mb-0">{{ $resumen['abiertas'] }}</h3>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-tile">
            <div class="stat-icon bg-warning-subtle text-warning mb-3"><i class="ti ti-lock"></i></div>
            <div class="small text-muted">Pendientes de revisión</div>
            <h3 class="fw-bolder mb-0">{{ $resumen['cerradas'] }}</h3>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-tile">
            <div class="stat-icon bg-success-subtle text-success mb-3"><i class="ti ti-circle-check"></i></div>
            <div class="small text-muted">Aprobadas</div>
            <h3 class="fw-bolder mb-0">{{ $resumen['aprobadas'] }}</h3>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-tile">
            <div class="stat-icon bg-primary-subtle text-primary mb-3"><i class="ti ti-clipboard-list"></i></div>
            <div class="small text-muted">Comisiones</div>
            <h3 class="fw-bolder mb-0">{{ $comisiones->count() }}</h3>
        </div>
    </div>
</div>

<div class="modern-card p-4">
    <div class="d-flex justify-content-between gap-3 flex-wrap mb-3">
        <div>
            <h4 class="fw-bolder mb-1">Listado de actas</h4>
            <p class="text-muted mb-0">Cada fila abre la planilla completa para revisión, PDF y aprobación.</p>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table align-middle table-hover">
            <thead>
                <tr>
                    <th>Materia</th>
                    <th>Sede</th>
                    <th>Docente</th>
                    <th>Alumnos</th>
                    <th>Notas</th>
                    <th>Estado</th>
                    <th>Movimiento</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
            @forelse($comisiones as $comision)
                @php
                    $estado = $comision->acta_estado ?: 'abierta';
                    $meta = $estadoMeta[$estado] ?? $estadoMeta['abierta'];
                @endphp
                <tr>
                    <td>
                        <strong>{{ $comision->materia->name }}</strong>
                        <div class="small text-muted">{{ $comision->materia->carrera->name }} &middot; Ciclo {{ $comision->year_cycle }}</div>
                    </td>
                    <td>{{ $comision->sede->nombre ?: 'CENT N°74' }}</td>
                    <td>{{ $comision->docente->name ?: 'A designar' }}</td>
                    <td>{{ $comision->inscripciones_count }}</td>
                    <td>{{ $comision->notas_count }}</td>
                    <td>
                        <span class="badge {{ $meta['class'] }}">
                            <i class="ti {{ $meta['icon'] }} me-1"></i>{{ $meta['label'] }}
                        </span>
                    </td>
                    <td class="small text-muted">
                        @if($comision->acta_aprobada_at)
                            Aprobada {{ $comision->acta_aprobada_at->format('d/m/Y H:i') }}
                            @if($comision->aprobadaPor) por {{ $comision->aprobadaPor->name }} @endif
                        @elseif($comision->acta_cerrada_at)
                            Cerrada {{ $comision->acta_cerrada_at->format('d/m/Y H:i') }}
                            @if($comision->cerradaPor) por {{ $comision->cerradaPor->name }} @endif
                        @else
                            Sin cierre
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="btn-group">
                            <a href="{{ route('cent.docente.planilla', $comision) }}" class="btn btn-sm btn-primary">
                                Revisar
                            </a>
                            <a href="{{ route('cent.docente.planilla.pdf', $comision) }}" class="btn btn-sm btn-outline-primary">
                                PDF
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-5">
                        Todavía no hay comisiones con actas para revisar.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
