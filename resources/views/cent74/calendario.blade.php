@extends('layouts.cent')

@section('title', 'Calendario académico')
@section('header', 'Calendario académico')

@php
    $tipoColor = [
        'clase' => 'primary',
        'mesa' => 'warning',
        'inscripcion' => 'success',
        'feriado' => 'danger',
        'parcial' => 'info',
        'evento' => 'secondary',
        'otro' => 'secondary',
    ];
@endphp

@section('content')
<section class="portal-hero p-4 p-xl-5 mb-4">
    <span class="badge bg-white text-primary mb-3">Agenda CENT</span>
    <h1 class="display-6 fw-bolder mb-3">Calendario académico</h1>
    <p class="fs-5 text-muted mb-0">Fechas de clases, parciales, mesas, inscripciones, feriados y eventos institucionales.</p>
</section>

<div class="modern-card p-4">
    <div class="vstack gap-3">
        @forelse($eventos as $evento)
            @php($color = $tipoColor[$evento->tipo] ?? 'primary')
            <article class="p-4 rounded-4 bg-light">
                <div class="d-flex justify-content-between gap-3 flex-wrap">
                    <div class="d-flex gap-3">
                        <span class="stat-icon bg-{{ $color }}-subtle text-{{ $color }}"><i class="ti ti-calendar-event"></i></span>
                        <div>
                            <h4 class="fw-bolder mb-1">{{ $evento->titulo }}</h4>
                            <div class="text-muted">
                                {{ $evento->fecha_inicio->format('d/m/Y H:i') }}
                                @if($evento->fecha_fin) · hasta {{ $evento->fecha_fin->format('d/m/Y H:i') }} @endif
                            </div>
                            @if($evento->descripcion)
                                <p class="text-muted mt-2 mb-0">{{ $evento->descripcion }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-{{ $color }}-subtle text-{{ $color }}">{{ ucfirst($evento->tipo) }}</span>
                        <div class="small text-muted mt-2">{{ $evento->sede->nombre ?: 'Todas las sedes' }}</div>
                        <div class="small text-muted">{{ $evento->carrera->name ?: 'Todas las carreras' }}</div>
                    </div>
                </div>
            </article>
        @empty
            <div class="text-center py-5">
                <span class="stat-icon bg-light text-muted mx-auto mb-3"><i class="ti ti-calendar-off"></i></span>
                <h3 class="fw-bolder">No hay eventos publicados</h3>
                <p class="text-muted mb-0">Cuando Dirección cargue fechas importantes van a aparecer acá.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
