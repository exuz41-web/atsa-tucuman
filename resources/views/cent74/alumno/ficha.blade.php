@extends('layouts.cent')

@section('title', 'Ficha académica')
@section('header', 'Ficha académica')

@section('content')
<div class="d-flex justify-content-between align-items-center gap-3 mb-4 no-print">
    <div>
        <h1 class="fw-bolder mb-1">Ficha académica</h1>
        <p class="text-muted mb-0">Comprobante imprimible con tus datos principales del CENT N°74.</p>
    </div>
    <button class="btn btn-primary" onclick="window.print()">
        <i class="ti ti-printer me-1"></i> Imprimir
    </button>
</div>

<div class="modern-card p-4 p-xl-5">
    <div class="d-flex justify-content-between align-items-start gap-4 border-bottom pb-4 mb-4">
        <div>
            <div class="text-primary fw-bolder text-uppercase mb-2">CENT N°74</div>
            <h2 class="fw-bolder mb-1">Ficha académica del alumno</h2>
            <p class="text-muted mb-0">Emitida el {{ now()->format('d/m/Y H:i') }}</p>
        </div>
        <div class="text-end">
            <div class="fw-bolder">{{ $matriculaActiva?->legajo ?: 'Legajo pendiente' }}</div>
            <div class="text-muted small">Legajo</div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="text-muted small">Alumno</div>
            <strong>{{ $alumno->name }}</strong>
        </div>
        <div class="col-md-3">
            <div class="text-muted small">DNI</div>
            <strong>{{ $alumno->dni ?: '-' }}</strong>
        </div>
        <div class="col-md-3">
            <div class="text-muted small">Email</div>
            <strong>{{ $alumno->email }}</strong>
        </div>
        <div class="col-md-6">
            <div class="text-muted small">Carrera</div>
            <strong>{{ $matriculaActiva?->carrera?->name ?: 'Pendiente' }}</strong>
        </div>
        <div class="col-md-3">
            <div class="text-muted small">Sede</div>
            <strong>{{ $matriculaActiva?->sede?->nombre ?: 'Pendiente' }}</strong>
        </div>
        <div class="col-md-3">
            <div class="text-muted small">Ciclo lectivo</div>
            <strong>{{ $matriculaActiva?->ciclo_lectivo ?: now()->year }}</strong>
        </div>
    </div>

    <h4 class="fw-bolder mb-3">Resumen de materias</h4>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Materia</th>
                    <th>Año</th>
                    <th>Cuatrimestre</th>
                    <th>Horas</th>
                </tr>
            </thead>
            <tbody>
            @forelse($materiasPlan as $materia)
                <tr>
                    <td class="fw-bold">{{ $materia->name }}</td>
                    <td>{{ $materia->year }}°</td>
                    <td>{{ $materia->semester ? $materia->semester.'°' : 'Anual' }}</td>
                    <td>{{ $materia->hours ?: '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-muted">Sin materias cargadas.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
