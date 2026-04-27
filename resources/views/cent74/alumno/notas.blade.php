@extends('layouts.cent')

@section('title', 'Mis notas')
@section('header', 'Mis notas')

@php
    $labels = [
        'parcial1' => 'Parcial 1',
        'parcial2' => 'Parcial 2',
        'recuperatorio' => 'Recuperatorio',
        'final' => 'Final',
    ];

    $estadoClass = [
        'aprobado' => 'bg-success-subtle text-success',
        'desaprobado' => 'bg-danger-subtle text-danger',
        'ausente' => 'bg-warning-subtle text-warning',
        'libre' => 'bg-secondary-subtle text-secondary',
    ];

    $notasPorMateria = $notas->groupBy(fn ($nota) => $nota->comision->materia_id);
    $materiasConFinalAprobado = $notas
        ->filter(fn ($nota) => $nota->type === 'final' && $nota->status === 'aprobado')
        ->pluck('comision.materia_id')
        ->unique()
        ->count();
    $totalPlan = max($materiasPlan->count(), 1);
    $avance = min(100, round(($materiasConFinalAprobado / $totalPlan) * 100));
@endphp

@section('content')
<section class="portal-hero p-4 p-xl-5 mb-4">
    <div class="row g-4 align-items-center">
        <div class="col-xl-8">
            <span class="badge bg-white text-primary mb-3">Estado académico</span>
            <h1 class="display-6 fw-bolder mb-3">Notas y regularidad</h1>
            <p class="fs-5 text-muted mb-0">
                Consultá tus calificaciones, finales aprobados y el estado de cada materia de tu carrera.
            </p>
        </div>
        <div class="col-xl-4">
            <div class="modern-card p-4 text-dark">
                <div class="small text-muted">Avance estimado por finales aprobados</div>
                <div class="d-flex align-items-end gap-2 my-2">
                    <h2 class="fw-bolder mb-0">{{ $avance }}%</h2>
                    <span class="text-muted pb-1">{{ $materiasConFinalAprobado }} de {{ $materiasPlan->count() }} materias</span>
                </div>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar" style="width: {{ $avance }}%;"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="row g-4">
    <div class="col-xl-8">
        <div class="modern-card p-4">
            <div class="d-flex justify-content-between gap-3 align-items-center flex-wrap mb-3">
                <div>
                    <h4 class="fw-bolder mb-1">Historial por materia</h4>
                    <p class="text-muted mb-0">Vista ordenada del plan de estudio y las instancias cargadas.</p>
                </div>
                <span class="badge bg-primary-subtle text-primary">{{ $matriculaActiva?->carrera?->name ?: 'Carrera no asignada' }}</span>
            </div>

            <div class="vstack gap-3">
                @forelse($materiasPlan as $materia)
                    @php
                        $materiaNotas = $notasPorMateria->get($materia->id, collect())->sortBy('type');
                        $finalAprobado = $materiaNotas->contains(fn ($nota) => $nota->type === 'final' && $nota->status === 'aprobado');
                        $tieneNotas = $materiaNotas->isNotEmpty();
                        $estadoMateria = $finalAprobado ? 'Aprobada' : ($tieneNotas ? 'En curso' : 'Pendiente');
                        $estadoMateriaClass = $finalAprobado ? 'bg-success-subtle text-success' : ($tieneNotas ? 'bg-info-subtle text-info' : 'bg-light text-muted');
                    @endphp
                    <div class="border rounded-4 p-3">
                        <div class="d-flex justify-content-between gap-3 flex-wrap">
                            <div>
                                <h5 class="fw-bolder mb-1">{{ $materia->name }}</h5>
                                <div class="small text-muted">
                                    {{ $materia->year }}° año
                                    @if($materia->semester) &middot; {{ $materia->semester }}° cuatrimestre @endif
                                    @if($materia->hours) &middot; {{ $materia->hours }} hs @endif
                                </div>
                            </div>
                            <span class="badge {{ $estadoMateriaClass }} align-self-start">{{ $estadoMateria }}</span>
                        </div>

                        @if($materiaNotas->isNotEmpty())
                            <div class="row g-2 mt-3">
                                @foreach($materiaNotas as $nota)
                                    <div class="col-sm-6 col-lg-3">
                                        <div class="bg-light rounded-4 p-3 h-100">
                                            <div class="small text-muted">{{ $labels[$nota->type] ?? ucfirst($nota->type) }}</div>
                                            <div class="fw-bolder fs-5">{{ $nota->grade ?: '-' }}</div>
                                            <span class="badge {{ $estadoClass[$nota->status] ?? 'bg-light text-muted' }}">{{ ucfirst($nota->status) }}</span>
                                            <div class="small text-muted mt-2">{{ $nota->created_at?->format('d/m/Y') }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted mt-3 mb-0">Todavía no hay calificaciones cargadas para esta materia.</p>
                        @endif
                    </div>
                @empty
                    <div class="text-center text-muted py-5">
                        Aún no tenés un plan de materias asignado.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="modern-card p-4 mb-4">
            <h4 class="fw-bolder mb-3">Últimas notas</h4>
            <div class="vstack gap-3">
                @forelse($notas->take(8) as $nota)
                    <div class="d-flex justify-content-between gap-3 border-bottom pb-3">
                        <div>
                            <strong>{{ $nota->comision->materia->name }}</strong>
                            <div class="small text-muted">{{ $labels[$nota->type] ?? ucfirst($nota->type) }} &middot; {{ $nota->created_at?->format('d/m/Y') }}</div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bolder">{{ $nota->grade ?: '-' }}</div>
                            <span class="badge {{ $estadoClass[$nota->status] ?? 'bg-light text-muted' }}">{{ ucfirst($nota->status) }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-muted mb-0">Aún no hay notas cargadas.</p>
                @endforelse
            </div>
        </div>

        <div class="modern-card p-4">
            <h4 class="fw-bolder mb-3">Importante</h4>
            <p class="text-muted mb-0">
                Si detectás un error en una calificación, comunicate con la sede o con el docente de la comisión para solicitar la revisión correspondiente.
            </p>
        </div>
    </div>
</div>
@endsection
