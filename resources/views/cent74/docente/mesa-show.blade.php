@extends('layouts.cent')

@section('title', 'Resultados de mesa')
@section('header', 'Resultados de mesa')

@php
    $estados = [
        'inscripto' => 'Inscripto',
        'cancelado' => 'Cancelado',
        'presente' => 'Presente',
        'ausente' => 'Ausente',
        'aprobado' => 'Aprobado',
        'desaprobado' => 'Desaprobado',
    ];
@endphp

@section('content')
<section class="portal-hero p-4 p-xl-5 mb-4">
    <div class="row g-4 align-items-center">
        <div class="col-xl-8">
            <span class="badge bg-white text-primary mb-3">Mesa de examen</span>
            <h1 class="display-6 fw-bolder mb-3">{{ $mesa->materia->name }}</h1>
            <p class="fs-5 text-muted mb-0">
                {{ $mesa->materia->carrera->name }} &middot; {{ $mesa->sede->nombre ?: 'CENT N°74' }}
                &middot; {{ $mesa->fecha->format('d/m/Y') }} {{ $mesa->hora }}
            </p>
        </div>
        <div class="col-xl-4">
            <div class="modern-card p-4 text-dark">
                <div class="small text-muted">Estado</div>
                <h3 class="fw-bolder mb-1">{{ ucfirst($mesa->estado) }}</h3>
                <div class="small text-muted">Acta: {{ ucfirst($mesa->acta_estado ?: 'abierta') }}</div>
            </div>
        </div>
    </div>
</section>

<div class="d-flex justify-content-between gap-3 flex-wrap mb-4">
    <a href="{{ route('cent.docente.mesas') }}" class="btn btn-light">
        <i class="ti ti-arrow-left me-1"></i> Volver
    </a>
    <button form="resultadosMesa" class="btn btn-primary">
        <i class="ti ti-device-floppy me-1"></i> Guardar resultados
    </button>
</div>

<form id="resultadosMesa" method="POST" action="{{ route('cent.docente.mesas.resultados', $mesa) }}">
    @csrf
    <div class="modern-card p-4">
        <div class="table-responsive">
            <table class="table align-middle table-hover">
                <thead>
                    <tr>
                        <th>Alumno</th>
                        <th>DNI</th>
                        <th>Estado</th>
                        <th>Nota</th>
                        <th>Observaciones</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($mesa->inscripciones as $inscripcion)
                    <tr>
                        <td>
                            <strong>{{ $inscripcion->alumno->name }}</strong>
                            <div class="small text-muted">{{ $inscripcion->alumno->email }}</div>
                        </td>
                        <td>{{ $inscripcion->alumno->dni ?: '-' }}</td>
                        <td style="min-width: 190px;">
                            <select name="resultados[{{ $inscripcion->id }}][estado]" class="form-select">
                                @foreach($estados as $value => $label)
                                    <option value="{{ $value }}" @selected(old('resultados.'.$inscripcion->id.'.estado', $inscripcion->estado) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td style="width: 130px;">
                            <input type="number" name="resultados[{{ $inscripcion->id }}][nota]" value="{{ old('resultados.'.$inscripcion->id.'.nota', $inscripcion->nota) }}" min="0" max="10" step="0.01" class="form-control">
                        </td>
                        <td>
                            <input type="text" name="resultados[{{ $inscripcion->id }}][observaciones]" value="{{ old('resultados.'.$inscripcion->id.'.observaciones', $inscripcion->observaciones) }}" class="form-control" placeholder="Opcional">
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-muted">No hay alumnos inscriptos en esta mesa.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="form-check mt-3">
            <input class="form-check-input" type="checkbox" value="1" name="cerrar_mesa" id="cerrarMesa">
            <label class="form-check-label" for="cerrarMesa">
                Finalizar mesa al guardar resultados
            </label>
        </div>
        <div class="mt-3">
            <label class="form-label fw-bold">Observaciones del acta</label>
            <textarea name="acta_observaciones" rows="3" class="form-control" placeholder="Opcional. Se incluirá en el acta final.">{{ old('acta_observaciones', $mesa->acta_observaciones) }}</textarea>
        </div>
    </div>
</form>
@endsection
