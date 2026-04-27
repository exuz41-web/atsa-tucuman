@extends('layouts.cent')

@section('title', 'Mi carrera')
@section('header', 'Mi carrera')

@php
    $notasPorMateria = $notas->groupBy(fn ($nota) => $nota->comision?->materia_id);
@endphp

@section('content')
<section class="portal-hero p-4 p-xl-5 mb-4">
    <span class="badge bg-white text-primary mb-3">Plan académico</span>
    <h1 class="display-6 fw-bolder mb-3">{{ $matriculaActiva?->carrera?->name ?: 'Carrera pendiente' }}</h1>
    <p class="fs-5 text-muted mb-0">
        {{ $matriculaActiva?->carrera?->title_granted ?: 'Cuando se confirme tu matrícula vas a ver acá el detalle completo de tu carrera.' }}
    </p>
</section>

<div class="row g-4">
    <div class="col-xl-4">
        <div class="modern-card p-4 h-100">
            <h3 class="fw-bolder mb-4">Datos de cursado</h3>
            <div class="border-bottom py-3">
                <div class="text-muted small">Sede</div>
                <strong>{{ $matriculaActiva?->sede?->nombre ?: 'Pendiente' }}</strong>
            </div>
            <div class="border-bottom py-3">
                <div class="text-muted small">Legajo</div>
                <strong>{{ $matriculaActiva?->legajo ?: 'Pendiente' }}</strong>
            </div>
            <div class="border-bottom py-3">
                <div class="text-muted small">Ciclo lectivo</div>
                <strong>{{ $matriculaActiva?->ciclo_lectivo ?: now()->year }}</strong>
            </div>
            <div class="py-3">
                <div class="text-muted small">Estado</div>
                <span class="badge bg-success-subtle text-success">{{ ucfirst($matriculaActiva?->estado ?: 'pendiente') }}</span>
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        <div class="modern-card p-4">
            <h3 class="fw-bolder mb-1">Programa de la carrera</h3>
            <p class="text-muted mb-4">Materias organizadas por año y cuatrimestre.</p>

            @forelse($materiasPlan->groupBy('year') as $year => $materias)
                <div class="mb-4">
                    <h5 class="fw-bolder text-primary mb-3">{{ $year }}° año</h5>
                    <div class="row g-3">
                        @foreach($materias as $materia)
                            @php
                                $ultimaNota = $notasPorMateria->get($materia->id, collect())->sortByDesc('created_at')->first();
                            @endphp
                            <div class="col-md-6">
                                <div class="p-3 rounded-4 bg-light h-100">
                                    <div class="d-flex justify-content-between gap-3">
                                        <strong>{{ $materia->name }}</strong>
                                        @if($ultimaNota)
                                            <span class="badge bg-primary-subtle text-primary">{{ $ultimaNota->grade ? ucfirst($ultimaNota->status) : 'Sin nota' }}</span>
                                        @endif
                                    </div>
                                    <div class="small text-muted mt-2">
                                        @if($materia->semester) {{ $materia->semester }}° cuatrimestre @else Anual @endif
                                        @if($materia->hours) · {{ $materia->hours }} hs @endif
                                    </div>
                                    @if($materia->correlatives)
                                        <div class="small text-muted mt-2">
                                            Correlativas: {{ implode(', ', $materia->correlatives) }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <p class="text-muted mb-0">Todavía no hay materias cargadas para esta carrera.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
