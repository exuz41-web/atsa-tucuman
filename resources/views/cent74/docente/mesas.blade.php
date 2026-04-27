@extends('layouts.cent')

@section('title', 'Mesas asignadas')
@section('header', 'Mesas de examen')

@php
    $estadoMeta = [
        'abierta' => ['label' => 'Abierta', 'color' => 'info'],
        'cerrada' => ['label' => 'Cerrada', 'color' => 'warning'],
        'finalizada' => ['label' => 'Finalizada', 'color' => 'success'],
    ];
@endphp

@section('content')
<section class="portal-hero p-4 p-xl-5 mb-4">
    <div class="row g-4 align-items-center">
        <div class="col-xl-8">
            <span class="badge bg-white text-primary mb-3">Docente</span>
            <h1 class="display-6 fw-bolder mb-3">Mesas de examen</h1>
            <p class="fs-5 text-muted mb-0">Control de inscriptos, asistencia a mesa y carga de resultados finales.</p>
        </div>
        <div class="col-xl-4">
            <div class="modern-card p-4 text-dark">
                <div class="small text-muted">Mesas asignadas</div>
                <h2 class="fw-bolder mb-0">{{ $mesas->count() }}</h2>
            </div>
        </div>
    </div>
</section>

<div class="row g-4">
    @forelse($mesas as $mesa)
        @php($meta = $estadoMeta[$mesa->estado] ?? $estadoMeta['abierta'])
        <div class="col-xl-6">
            <article class="modern-card p-4 h-100">
                <div class="d-flex justify-content-between gap-3 mb-3">
                    <span class="stat-icon bg-{{ $meta['color'] }}-subtle text-{{ $meta['color'] }}">
                        <i class="ti ti-calendar-event"></i>
                    </span>
                    <span class="badge bg-{{ $meta['color'] }}-subtle text-{{ $meta['color'] }}">{{ $meta['label'] }}</span>
                </div>
                <h3 class="h4 fw-bolder mb-1">{{ $mesa->materia->name }}</h3>
                <p class="text-muted mb-3">{{ $mesa->materia->carrera->name }}</p>
                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <div class="p-3 rounded-4 bg-light">
                            <small class="text-muted d-block">Sede</small>
                            <strong>{{ $mesa->sede->nombre ?: 'CENT N°74' }}</strong>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 rounded-4 bg-light">
                            <small class="text-muted d-block">Fecha</small>
                            <strong>{{ $mesa->fecha->format('d/m/Y') }} {{ $mesa->hora }}</strong>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 rounded-4 bg-light">
                            <small class="text-muted d-block">Aula</small>
                            <strong>{{ $mesa->aula ?: 'A confirmar' }}</strong>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 rounded-4 bg-light">
                            <small class="text-muted d-block">Inscriptos</small>
                            <strong>{{ $mesa->inscripciones_count }}</strong>
                        </div>
                    </div>
                </div>
                <a href="{{ route('cent.docente.mesas.show', $mesa) }}" class="btn btn-primary w-100">
                    <i class="ti ti-clipboard-check me-1"></i> Cargar resultados
                </a>
            </article>
        </div>
    @empty
        <div class="col-12">
            <div class="modern-card p-5 text-center">
                <span class="stat-icon bg-light text-muted mx-auto mb-3"><i class="ti ti-calendar-off"></i></span>
                <h3 class="fw-bolder">No hay mesas asignadas</h3>
                <p class="text-muted mb-0">Cuando Dirección cree mesas para tus materias, las vas a ver en este panel.</p>
            </div>
        </div>
    @endforelse
</div>
@endsection
