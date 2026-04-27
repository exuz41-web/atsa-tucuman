@extends('layouts.cent')

@section('title', 'Mis comisiones')
@section('header', 'Mis comisiones')

@section('content')
<section class="portal-hero p-4 p-xl-5 mb-4">
    <span class="badge bg-white text-primary mb-3">Docente</span>
    <h1 class="display-6 fw-bolder mb-3">Comisiones y carga académica</h1>
    <p class="fs-5 text-muted mb-0">
        Cada comisión permite consultar alumnos, cargar calificaciones, registrar asistencia, cerrar actas y descargar planillas.
    </p>
</section>

@forelse($comisiones as $comision)
    <div class="modern-card p-4 mb-4">
        <div class="d-flex justify-content-between gap-3 flex-wrap mb-4">
            <div>
                <span class="badge bg-primary-subtle text-primary mb-2">{{ $comision->year_cycle }}</span>
                <h3 class="fw-bolder mb-1">{{ $comision->materia->name }}</h3>
                <p class="text-muted mb-0">
                    {{ $comision->materia->carrera->name }}
                    &middot; {{ $comision->sede->nombre ?: 'CENT N°74' }}
                    @if($comision->schedule)
                        &middot; {{ $comision->schedule }}
                    @endif
                </p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <span class="badge bg-info-subtle text-info align-self-start">{{ $comision->inscripciones->count() }} alumnos</span>
                <span class="badge bg-success-subtle text-success align-self-start">{{ $comision->notas->count() }} notas</span>
                <span class="badge bg-primary-subtle text-primary align-self-start">{{ ucfirst($comision->acta_estado ?: 'abierta') }}</span>
                <a href="{{ route('cent.docente.planilla', $comision) }}" class="btn btn-primary btn-sm">
                    <i class="ti ti-table me-1"></i> Planilla
                </a>
                <a href="{{ route('cent.docente.asistencia', $comision) }}" class="btn btn-outline-primary btn-sm">
                    <i class="ti ti-calendar-check me-1"></i> Asistencia
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Alumno</th>
                        <th>DNI</th>
                        <th>Estado</th>
                        <th>Última nota</th>
                        <th class="text-end">Ficha</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($comision->inscripciones as $inscripcion)
                    @php
                        $ultimaNota = $comision->notas
                            ->where('alumno_id', $inscripcion->alumno_id)
                            ->sortByDesc('created_at')
                            ->first();
                    @endphp
                    <tr>
                        <td class="fw-bold">{{ $inscripcion->alumno->name }}</td>
                        <td>{{ $inscripcion->alumno->dni ?: '-' }}</td>
                        <td><span class="badge bg-info-subtle text-info">{{ ucfirst($inscripcion->status) }}</span></td>
                        <td>
                            @if($ultimaNota)
                                <span class="badge bg-primary-subtle text-primary">
                                    {{ ucfirst($ultimaNota->type) }} &middot; {{ $ultimaNota->grade ?: '-' }}
                                </span>
                            @else
                                <span class="text-muted">Sin nota</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('cent.docente.alumnos.ficha-pdf', $inscripcion->alumno) }}" class="btn btn-light btn-sm">
                                PDF
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-muted">No hay alumnos inscriptos en esta comisión.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@empty
    <div class="modern-card p-5 text-center">
        <i class="ti ti-chalkboard-off fs-10 text-muted"></i>
        <h3 class="fw-bolder mt-3">Sin comisiones asignadas</h3>
        <p class="text-muted mb-0">Cuando dirección te asigne materias, van a aparecer en este panel.</p>
    </div>
@endforelse
@endsection
