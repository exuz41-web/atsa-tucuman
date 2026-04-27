@extends('layouts.cent')

@section('title', 'Actas finales de mesas')
@section('header', 'Actas finales')

@php
    $estadoMeta = [
        'abierta' => ['label' => 'Abierta', 'class' => 'bg-info-subtle text-info', 'icon' => 'ti-edit'],
        'cerrada' => ['label' => 'Pendiente de aprobación', 'class' => 'bg-warning-subtle text-warning', 'icon' => 'ti-lock'],
        'aprobada' => ['label' => 'Aprobada', 'class' => 'bg-success-subtle text-success', 'icon' => 'ti-circle-check'],
    ];
@endphp

@section('content')
<section class="portal-hero p-4 p-xl-5 mb-4">
    <div class="row g-4 align-items-center">
        <div class="col-xl-8">
            <span class="badge bg-white text-primary mb-3">Dirección académica</span>
            <h1 class="display-6 fw-bolder mb-3">Libro de actas finales</h1>
            <p class="fs-5 text-muted mb-0">
                Revisá mesas finalizadas, aprobá actas definitivas y descargá el PDF institucional para archivo.
            </p>
        </div>
        <div class="col-xl-4">
            <div class="modern-card p-4 text-dark">
                <div class="small text-muted">Inscriptos en mesas</div>
                <h2 class="fw-bolder mb-0">{{ $resumen['inscriptos'] }}</h2>
            </div>
        </div>
    </div>
</section>

<div class="row g-3 mb-4">
    @foreach([
        ['label' => 'Abiertas', 'value' => $resumen['abiertas'], 'icon' => 'ti-edit', 'color' => 'info'],
        ['label' => 'Para aprobar', 'value' => $resumen['cerradas'], 'icon' => 'ti-lock', 'color' => 'warning'],
        ['label' => 'Aprobadas', 'value' => $resumen['aprobadas'], 'icon' => 'ti-circle-check', 'color' => 'success'],
        ['label' => 'Mesas cargadas', 'value' => $mesas->count(), 'icon' => 'ti-calendar-event', 'color' => 'primary'],
    ] as $stat)
        <div class="col-sm-6 col-xl-3">
            <div class="stat-tile">
                <span class="stat-icon bg-{{ $stat['color'] }}-subtle text-{{ $stat['color'] }} mb-3">
                    <i class="ti {{ $stat['icon'] }}"></i>
                </span>
                <div class="small text-muted">{{ $stat['label'] }}</div>
                <h3 class="fw-bolder mb-0">{{ $stat['value'] }}</h3>
            </div>
        </div>
    @endforeach
</div>

<div class="modern-card p-4">
    <div class="d-flex justify-content-between gap-3 flex-wrap mb-4">
        <div>
            <h4 class="fw-bolder mb-1">Mesas de examen</h4>
            <p class="text-muted mb-0">Cada acta se puede aprobar, reabrir o descargar en PDF.</p>
        </div>
        <a href="{{ url('/cent-admin/mesas-examen') }}" target="_blank" class="btn btn-primary">
            <i class="ti ti-plus me-1"></i> Gestionar mesas
        </a>
    </div>

    <div class="table-responsive">
        <table class="table align-middle table-hover">
            <thead>
                <tr>
                    <th>Materia</th>
                    <th>Sede</th>
                    <th>Fecha</th>
                    <th>Docente</th>
                    <th>Inscriptos</th>
                    <th>Acta</th>
                    <th>Libro/Folio</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
            @forelse($mesas as $mesa)
                @php
                    $estado = $mesa->acta_estado ?: 'abierta';
                    $meta = $estadoMeta[$estado] ?? $estadoMeta['abierta'];
                @endphp
                <tr>
                    <td>
                        <strong>{{ $mesa->materia->name }}</strong>
                        <div class="small text-muted">{{ $mesa->materia->carrera->name }}</div>
                    </td>
                    <td>{{ $mesa->sede->nombre ?: 'CENT N°74' }}</td>
                    <td>{{ $mesa->fecha->format('d/m/Y') }} {{ $mesa->hora }}</td>
                    <td>{{ $mesa->docente->name ?: 'A designar' }}</td>
                    <td><span class="badge bg-primary-subtle text-primary">{{ $mesa->inscripciones_count }}</span></td>
                    <td>
                        <span class="badge {{ $meta['class'] }}">
                            <i class="ti {{ $meta['icon'] }} me-1"></i>{{ $meta['label'] }}
                        </span>
                        <div class="small text-muted mt-1">
                            @if($mesa->acta_aprobada_at)
                                Aprobada {{ $mesa->acta_aprobada_at->format('d/m/Y H:i') }}
                            @elseif($mesa->acta_cerrada_at)
                                Cerrada {{ $mesa->acta_cerrada_at->format('d/m/Y H:i') }}
                            @else
                                Sin cierre
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="small">Libro: <strong>{{ $mesa->acta_libro ?: '-' }}</strong></div>
                        <div class="small">Folio: <strong>{{ $mesa->acta_folio ?: '-' }}</strong></div>
                    </td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-2 flex-wrap">
                            <a href="{{ route('cent.docente.mesas.show', $mesa) }}" class="btn btn-sm btn-light">Ver</a>
                            <a href="{{ route('cent.directivo.actas-mesas.pdf', $mesa) }}" class="btn btn-sm btn-outline-primary">PDF</a>
                            @if($estado !== 'aprobada')
                                <form method="POST" action="{{ route('cent.directivo.actas-mesas.aprobar', $mesa) }}" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="acta_libro" value="{{ $mesa->acta_libro }}">
                                    <input type="hidden" name="acta_folio" value="{{ $mesa->acta_folio }}">
                                    <button class="btn btn-sm btn-success" onclick="return confirm('¿Aprobar acta final de esta mesa?')">Aprobar</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('cent.directivo.actas-mesas.reabrir', $mesa) }}" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-warning" onclick="return confirm('¿Reabrir mesa para correcciones?')">Reabrir</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-5">
                        Todavía no hay mesas de examen cargadas.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
