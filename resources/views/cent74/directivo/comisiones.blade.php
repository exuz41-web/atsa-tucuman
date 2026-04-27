@extends('layouts.cent')

@section('title', 'Comisiones CENT')
@section('header', 'Comisiones')

@php
    $estadoMeta = [
        'abierta' => 'bg-info-subtle text-info',
        'cerrada' => 'bg-warning-subtle text-warning',
        'aprobada' => 'bg-success-subtle text-success',
    ];
@endphp

@section('content')
<section class="portal-hero p-4 p-xl-5 mb-4">
    <div class="row g-4 align-items-center">
        <div class="col-xl-8">
            <span class="badge bg-white text-primary mb-3">Gestión académica</span>
            <h1 class="display-6 fw-bolder mb-3">Comisiones, docentes y alumnos</h1>
            <p class="fs-5 text-muted mb-0">
                Creá comisiones por materia, sede y ciclo lectivo. Desde cada comisión podés asignar docentes e inscribir alumnos.
            </p>
        </div>
        <div class="col-xl-4 text-xl-end">
            <a href="{{ route('cent.directivo.comisiones.crear') }}" class="btn btn-light btn-lg">
                <i class="ti ti-plus me-1"></i> Nueva comisión
            </a>
        </div>
    </div>
</section>

<div class="modern-card p-4">
    <div class="d-flex justify-content-between gap-3 flex-wrap mb-3">
        <div>
            <h4 class="fw-bolder mb-1">Listado de comisiones</h4>
            <p class="text-muted mb-0">Panel operativo para apertura de cursadas y seguimiento docente.</p>
        </div>
        <span class="badge bg-primary-subtle text-primary align-self-start">{{ $comisiones->count() }} comisiones</span>
    </div>

    <div class="table-responsive">
        <table class="table align-middle table-hover">
            <thead>
                <tr>
                    <th>Materia</th>
                    <th>Sede</th>
                    <th>Docente</th>
                    <th>Ciclo</th>
                    <th>Alumnos</th>
                    <th>Notas</th>
                    <th>Acta</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
            @forelse($comisiones as $comision)
                <tr>
                    <td>
                        <strong>{{ $comision->materia->name }}</strong>
                        <div class="small text-muted">{{ $comision->materia->carrera->name }}</div>
                    </td>
                    <td>{{ $comision->sede->nombre ?: 'CENT N°74' }}</td>
                    <td>{{ $comision->docente->name ?: 'A designar' }}</td>
                    <td><span class="badge bg-primary-subtle text-primary">{{ $comision->year_cycle }}</span></td>
                    <td>{{ $comision->inscripciones_count }}</td>
                    <td>{{ $comision->notas_count }}</td>
                    <td>
                        <span class="badge {{ $estadoMeta[$comision->acta_estado ?: 'abierta']['badge'] ?? 'bg-info-subtle text-info' }}">
                            {{ ucfirst($comision->acta_estado ?: 'abierta') }}
                        </span>
                    </td>
                    <td class="text-end">
                        <div class="btn-group">
                            <a href="{{ route('cent.directivo.comisiones.editar', $comision) }}" class="btn btn-sm btn-primary">
                                Editar
                            </a>
                            <a href="{{ route('cent.docente.planilla', $comision) }}" class="btn btn-sm btn-outline-primary">
                                Planilla
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-5">
                        Todavía no hay comisiones creadas.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
