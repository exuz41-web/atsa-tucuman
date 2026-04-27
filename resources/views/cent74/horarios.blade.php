@extends('layouts.cent-public')

@section('title', 'Horarios de cursado - CENT N°74')
@section('meta_description', 'Horarios de cursado del CENT N°74 por carrera y sede.')

@section('content')
<section class="py-5 bg-light">
    <div class="container py-lg-5">
        <span class="section-badge bg-primary-subtle text-primary">
            <i class="ti ti-calendar-time"></i> Organización académica
        </span>
        <div class="row align-items-end g-4">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bolder">Horarios de cursado</h1>
                <p class="fs-5 text-muted text-justify mb-0">
                    Consultá los horarios publicados por la institución. Cada sede puede actualizar aulas, turnos y comisiones según disponibilidad académica.
                </p>
            </div>
            <div class="col-lg-4">
                <div class="card cent-card">
                    <div class="card-body p-4">
                        <div class="d-flex gap-3 align-items-center">
                            <span class="feature-icon bg-info-subtle text-info"><i class="ti ti-info-circle"></i></span>
                            <div>
                                <h4 class="fw-bold mb-1">Siempre verificá</h4>
                                <p class="text-muted mb-0">Los cambios oficiales se informan desde el portal y la sede.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 py-lg-10">
    <div class="container">
        @if($horarios->isEmpty())
            <div class="card cent-card">
                <div class="card-body p-5 text-center">
                    <span class="feature-icon bg-primary-subtle text-primary mx-auto mb-3"><i class="ti ti-calendar-plus"></i></span>
                    <h2 class="fw-bold">Horarios próximos a publicarse</h2>
                    <p class="text-muted mb-4">La administración académica todavía no cargó horarios públicos para este ciclo.</p>
                    <a href="{{ route('cent.contacto') }}" class="btn btn-cent">Consultar en sede</a>
                </div>
            </div>
        @else
            <div class="row g-4">
                @foreach($horarios as $horario)
                    <div class="col-md-6 col-xl-4">
                        <article class="card cent-card h-100">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                                    <span class="feature-icon bg-primary-subtle text-primary"><i class="ti ti-clock-hour-4"></i></span>
                                    @if($horario->ciclo_lectivo)
                                        <span class="badge bg-light text-primary rounded-pill">Ciclo {{ $horario->ciclo_lectivo }}</span>
                                    @endif
                                </div>
                                <h2 class="h4 fw-bold">{{ $horario->titulo }}</h2>
                                <div class="d-flex flex-column gap-2 text-muted mb-3">
                                    @if($horario->carrera)
                                        <span><i class="ti ti-school me-1 text-primary"></i>{{ $horario->carrera->name }}</span>
                                    @endif
                                    @if($horario->sede)
                                        <span><i class="ti ti-map-pin me-1 text-primary"></i>{{ $horario->sede->nombre }}</span>
                                    @endif
                                </div>
                                @if($horario->descripcion)
                                    <p class="text-muted text-justify">{{ $horario->descripcion }}</p>
                                @endif
                            </div>
                            @if($horario->archivo_url)
                                <div class="card-footer bg-white border-0 p-4 pt-0">
                                    <a href="{{ $horario->archivo_url }}" target="_blank" rel="noopener" class="btn btn-outline-cent w-100">
                                        <i class="ti ti-download me-1"></i> Ver horario
                                    </a>
                                </div>
                            @endif
                        </article>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
