@extends('layouts.cent')

@section('title', 'Asistencia')
@section('header', 'Asistencia docente')

@php
    $labels = [
        'presente' => 'Presente',
        'ausente' => 'Ausente',
        'tarde' => 'Tarde',
        'justificado' => 'Justificado',
    ];

    $classes = [
        'presente' => 'bg-success-subtle text-success',
        'ausente' => 'bg-danger-subtle text-danger',
        'tarde' => 'bg-warning-subtle text-warning',
        'justificado' => 'bg-info-subtle text-info',
    ];
@endphp

@section('content')
<section class="portal-hero p-4 p-xl-5 mb-4">
    <div class="row g-4 align-items-center">
        <div class="col-xl-8">
            <span class="badge bg-white text-primary mb-3">Asistencia</span>
            <h1 class="display-6 fw-bolder mb-3">{{ $comision->materia->name }}</h1>
            <p class="fs-5 text-muted mb-0">
                {{ $comision->materia->carrera->name }} &middot; {{ $comision->sede->nombre ?: 'CENT N°74' }} &middot; Ciclo {{ $comision->year_cycle }}
            </p>
        </div>
        <div class="col-xl-4">
            <div class="modern-card p-4 text-dark">
                <div class="small text-muted">Fecha de asistencia</div>
                <form method="GET" class="d-flex gap-2 mt-2">
                    <input type="date" name="fecha" value="{{ $fecha }}" class="form-control">
                    <button class="btn btn-primary">Ver</button>
                </form>
            </div>
        </div>
    </div>
</section>

<div class="d-flex justify-content-between gap-3 flex-wrap mb-4">
    <a href="{{ route('cent.docente.comisiones') }}" class="btn btn-light">
        <i class="ti ti-arrow-left me-1"></i> Volver
    </a>
    <button form="asistenciaForm" class="btn btn-primary">
        <i class="ti ti-device-floppy me-1"></i> Guardar asistencia
    </button>
</div>

<form id="asistenciaForm" action="{{ route('cent.docente.asistencia.guardar', $comision) }}" method="POST">
    @csrf
    <input type="hidden" name="fecha" value="{{ $fecha }}">

    <div class="modern-card p-4">
        <div class="table-responsive">
            <table class="table align-middle table-hover">
                <thead>
                    <tr>
                        <th>Alumno</th>
                        <th>DNI</th>
                        <th>Estado</th>
                        <th>Observaciones</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($comision->inscripciones as $inscripcion)
                    @php($asistencia = $asistencias->get($inscripcion->alumno_id))
                    <tr>
                        <td>
                            <strong>{{ $inscripcion->alumno->name }}</strong>
                            <div class="small text-muted">{{ $inscripcion->alumno->email }}</div>
                        </td>
                        <td>{{ $inscripcion->alumno->dni ?: '-' }}</td>
                        <td style="min-width: 220px;">
                            <select name="asistencias[{{ $inscripcion->alumno_id }}][estado]" class="form-select">
                                @foreach($estadosAsistencia as $estado)
                                    <option value="{{ $estado }}" @selected(old('asistencias.'.$inscripcion->alumno_id.'.estado', $asistencia?->estado ?: 'presente') === $estado)>
                                        {{ $labels[$estado] }}
                                    </option>
                                @endforeach
                            </select>
                            @if($asistencia)
                                <span class="badge {{ $classes[$asistencia->estado] ?? 'bg-light text-muted' }} mt-2">{{ $labels[$asistencia->estado] ?? ucfirst($asistencia->estado) }}</span>
                            @endif
                        </td>
                        <td>
                            <input
                                type="text"
                                name="asistencias[{{ $inscripcion->alumno_id }}][observaciones]"
                                value="{{ old('asistencias.'.$inscripcion->alumno_id.'.observaciones', $asistencia?->observaciones) }}"
                                class="form-control"
                                placeholder="Opcional"
                            >
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-5">
                            Esta comisión no tiene alumnos inscriptos.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</form>
@endsection
