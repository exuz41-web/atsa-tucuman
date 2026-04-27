@extends('layouts.cent')

@section('title', 'Alumnos CENT')
@section('header', 'Alumnos')

@section('content')
<section class="portal-hero p-4 p-xl-5 mb-4">
    <span class="badge bg-white text-primary mb-3">Dirección</span>
    <h1 class="display-6 fw-bolder mb-3">Alumnos por carrera y sede</h1>
    <p class="fs-5 text-muted mb-0">Listado operativo para seguimiento académico, constancias y fichas de trayectoria.</p>
</section>

<div class="modern-card p-4">
    <div class="table-responsive">
        <table class="table align-middle table-hover">
            <thead>
                <tr>
                    <th>Alumno</th>
                    <th>DNI</th>
                    <th>Carrera</th>
                    <th>Sede</th>
                    <th>Legajo</th>
                    <th>Comisiones</th>
                    <th>Notas</th>
                    <th class="text-end">Documentos</th>
                </tr>
            </thead>
            <tbody>
            @forelse($alumnos as $alumno)
                @php($matricula = $alumno->matriculasCent->first())
                <tr>
                    <td>
                        <strong>{{ $alumno->name }}</strong>
                        <div class="small text-muted">{{ $alumno->email }}</div>
                    </td>
                    <td>{{ $alumno->dni ?: '-' }}</td>
                    <td>{{ $matricula?->carrera?->name ?: 'Sin matrícula' }}</td>
                    <td>{{ $matricula?->sede?->nombre ?: '-' }}</td>
                    <td><span class="badge bg-primary-subtle text-primary">{{ $matricula?->legajo ?: 'Pendiente' }}</span></td>
                    <td>{{ $alumno->inscripciones_academicas_count }}</td>
                    <td>{{ $alumno->notas_academicas_count }}</td>
                    <td class="text-end">
                        @if($matricula)
                            <div class="btn-group">
                                <a href="{{ route('cent.directivo.alumnos.constancia', $alumno) }}" class="btn btn-sm btn-outline-primary">
                                    Constancia
                                </a>
                                <a href="{{ route('cent.directivo.alumnos.ficha-pdf', $alumno) }}" class="btn btn-sm btn-primary">
                                    Ficha
                                </a>
                            </div>
                        @else
                            <span class="text-muted small">Sin matrícula</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-muted">No hay alumnos cargados.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
