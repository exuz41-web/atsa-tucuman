@extends('layouts.cent')

@section('title', 'Mesas de examen')
@section('header', 'Mesas de examen')

@php
    $estadoColor = [
        'inscripto' => 'primary',
        'cancelado' => 'secondary',
        'presente' => 'info',
        'ausente' => 'warning',
        'aprobado' => 'success',
        'desaprobado' => 'danger',
    ];
@endphp

@section('content')
<section class="portal-hero p-4 p-xl-5 mb-4">
    <div class="row g-4 align-items-center">
        <div class="col-xl-8">
            <span class="badge bg-white text-primary mb-3">Finales</span>
            <h1 class="display-6 fw-bolder mb-3">Inscripción a mesas de examen</h1>
            <p class="fs-5 text-muted mb-0">
                Consultá mesas abiertas para tu carrera y sede. Al inscribirte se genera un comprobante PDF.
            </p>
        </div>
        <div class="col-xl-4">
            <div class="modern-card p-4 text-dark">
                <div class="small text-muted">Inscripciones registradas</div>
                <h2 class="fw-bolder mb-0">{{ $inscripcionesMesa->count() }}</h2>
            </div>
        </div>
    </div>
</section>

<div class="row g-4">
    <div class="col-xl-7">
        <div class="modern-card p-4 h-100">
            <div class="d-flex justify-content-between gap-3 flex-wrap mb-4">
                <div>
                    <h4 class="fw-bolder mb-1">Mesas disponibles</h4>
                    <p class="text-muted mb-0">Solo se muestran mesas compatibles con tu carrera y sede.</p>
                </div>
            </div>

            <div class="vstack gap-3">
                @forelse($mesas as $mesa)
                    @php
                        $yaInscripto = $inscripcionesMesa->contains('mesa_examen_cent_id', $mesa->id);
                        $inscriptos = $mesa->inscripciones->where('estado', '!=', 'cancelado')->count();
                    @endphp
                    <article class="p-4 rounded-4 border bg-white">
                        <div class="d-flex justify-content-between gap-3 flex-wrap">
                            <div class="d-flex gap-3">
                                <span class="stat-icon bg-primary-subtle text-primary flex-shrink-0">
                                    <i class="ti ti-calendar-event"></i>
                                </span>
                                <div>
                                    <h5 class="fw-bolder mb-1">{{ $mesa->materia->name }}</h5>
                                    <div class="text-muted small mb-2">{{ $mesa->materia->carrera->name }}</div>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <span class="badge bg-light text-dark"><i class="ti ti-map-pin me-1"></i>{{ $mesa->sede->nombre ?: 'CENT N°74' }}</span>
                                        <span class="badge bg-light text-dark"><i class="ti ti-calendar me-1"></i>{{ $mesa->fecha->format('d/m/Y') }}</span>
                                        <span class="badge bg-light text-dark"><i class="ti ti-clock me-1"></i>{{ $mesa->hora ?: 'A confirmar' }}</span>
                                        <span class="badge bg-light text-dark"><i class="ti ti-door me-1"></i>{{ $mesa->aula ?: 'Aula a confirmar' }}</span>
                                    </div>
                                    <div class="small text-muted mt-3">
                                        Cupo: <strong>{{ $mesa->cupo ? $inscriptos.'/'.$mesa->cupo : 'sin límite' }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end">
                                @if($yaInscripto)
                                    <span class="badge bg-success-subtle text-success">
                                        <i class="ti ti-circle-check me-1"></i>Ya inscripto
                                    </span>
                                @else
                                    <form method="POST" action="{{ route('cent.alumno.mesas.inscribir', $mesa) }}">
                                        @csrf
                                        <button class="btn btn-primary" onclick="return confirm('¿Confirmar inscripción a esta mesa?')">
                                            <i class="ti ti-file-plus me-1"></i> Inscribirme
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="text-center py-5">
                        <span class="stat-icon bg-light text-muted mx-auto mb-3"><i class="ti ti-calendar-off"></i></span>
                        <h5 class="fw-bolder">No hay mesas abiertas</h5>
                        <p class="text-muted mb-0">Cuando Dirección publique nuevas fechas compatibles con tu carrera, van a aparecer acá.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-xl-5">
        <div class="modern-card p-4 h-100">
            <h4 class="fw-bolder mb-3">Mis inscripciones</h4>
            <div class="vstack gap-3">
                @forelse($inscripcionesMesa as $inscripcion)
                    @php($color = $estadoColor[$inscripcion->estado] ?? 'primary')
                    <article class="p-3 rounded-4 bg-light">
                        <div class="d-flex justify-content-between gap-3">
                            <div>
                                <strong>{{ $inscripcion->mesa->materia->name }}</strong>
                                <div class="small text-muted">
                                    {{ $inscripcion->mesa->fecha->format('d/m/Y') }}
                                    @if($inscripcion->mesa->hora) · {{ $inscripcion->mesa->hora }} @endif
                                    · {{ $inscripcion->mesa->sede->nombre ?: 'CENT N°74' }}
                                </div>
                            </div>
                            <span class="badge bg-{{ $color }}-subtle text-{{ $color }} align-self-start">{{ ucfirst($inscripcion->estado) }}</span>
                        </div>
                        @if($inscripcion->nota)
                            <div class="mt-2 small">Nota: <strong>{{ $inscripcion->nota }}</strong></div>
                        @endif
                        @if($inscripcion->observaciones)
                            <p class="small text-muted mt-2 mb-0">{{ $inscripcion->observaciones }}</p>
                        @endif
                        <a href="{{ route('cent.alumno.mesas.comprobante', $inscripcion) }}" class="btn btn-sm btn-outline-primary mt-3">
                            <i class="ti ti-download me-1"></i> Comprobante
                        </a>
                    </article>
                @empty
                    <p class="text-muted mb-0">Todavía no tenés inscripciones a mesas.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
