@extends('layouts.cent')

@section('title', $modo === 'crear' ? 'Nueva comisión' : 'Editar comisión')
@section('header', $modo === 'crear' ? 'Nueva comisión' : 'Editar comisión')

@php
    $isEdit = $modo === 'editar';
@endphp

@section('content')
<section class="portal-hero p-4 p-xl-5 mb-4">
    <div class="row g-4 align-items-center">
        <div class="col-xl-8">
            <span class="badge bg-white text-primary mb-3">Gestión académica</span>
            <h1 class="display-6 fw-bolder mb-3">{{ $isEdit ? 'Editar comisión' : 'Abrir nueva comisión' }}</h1>
            <p class="fs-5 text-muted mb-0">
                Definí materia, sede, docente, ciclo lectivo y horario. Luego podés inscribir alumnos con matrícula activa.
            </p>
        </div>
        <div class="col-xl-4 text-xl-end">
            <a href="{{ route('cent.directivo.comisiones') }}" class="btn btn-light">
                <i class="ti ti-arrow-left me-1"></i> Volver
            </a>
        </div>
    </div>
</section>

<div class="row g-4">
    <div class="col-xl-7">
        <div class="modern-card p-4">
            <h4 class="fw-bolder mb-3">Datos de la comisión</h4>
            <form method="POST" action="{{ $isEdit ? route('cent.directivo.comisiones.actualizar', $comision) : route('cent.directivo.comisiones.guardar') }}" class="row g-3">
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif

                <div class="col-12">
                    <label class="form-label fw-bold">Materia</label>
                    <select name="materia_id" class="form-select" required>
                        <option value="">Seleccionar materia</option>
                        @foreach($materias as $materia)
                            <option value="{{ $materia->id }}" @selected((int) old('materia_id', $comision->materia_id) === $materia->id)>
                                {{ $materia->carrera->name }} - {{ $materia->year }}° año - {{ $materia->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Sede</label>
                    <select name="cent_sede_id" class="form-select" required>
                        <option value="">Seleccionar sede</option>
                        @foreach($sedes as $sede)
                            <option value="{{ $sede->id }}" @selected((int) old('cent_sede_id', $comision->cent_sede_id) === $sede->id)>
                                {{ $sede->nombre }} - {{ $sede->ciudad }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Docente</label>
                    <select name="docente_id" class="form-select" required>
                        <option value="">Seleccionar docente</option>
                        @foreach($docentes as $docente)
                            <option value="{{ $docente->id }}" @selected((int) old('docente_id', $comision->docente_id) === $docente->id)>
                                {{ $docente->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Ciclo lectivo</label>
                    <input type="number" name="year_cycle" min="2020" max="2100" class="form-control" value="{{ old('year_cycle', $comision->year_cycle ?: now()->year) }}" required>
                </div>

                <div class="col-md-8">
                    <label class="form-label fw-bold">Horario</label>
                    <input type="text" name="schedule" class="form-control" value="{{ old('schedule', $comision->schedule) }}" placeholder="Ej: Lunes y miércoles 18:00 a 21:00">
                </div>

                <div class="col-12">
                    <button class="btn btn-primary">
                        <i class="ti ti-device-floppy me-1"></i> {{ $isEdit ? 'Guardar cambios' : 'Crear comisión' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="col-xl-5">
        @if($isEdit)
            <div class="modern-card p-4 mb-4">
                <h4 class="fw-bolder mb-3">Inscribir alumno</h4>
                <form action="{{ route('cent.directivo.comisiones.inscribir', $comision) }}" method="POST" class="row g-3">
                    @csrf
                    <div class="col-12">
                        <label class="form-label fw-bold">Alumno con matrícula activa</label>
                        <select name="alumno_id" class="form-select" required>
                            <option value="">Seleccionar alumno</option>
                            @foreach($alumnosDisponibles as $alumno)
                                @php($matricula = $alumno->matriculasCent->firstWhere('carrera_id', $comision->materia->carrera_id))
                                <option value="{{ $alumno->id }}">
                                    {{ $alumno->name }} - {{ $alumno->dni ?: 'DNI pendiente' }} {{ $matricula?->legajo ? ' - '.$matricula->legajo : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Estado</label>
                        <select name="status" class="form-select" required>
                            <option value="aprobada">Aprobada</option>
                            <option value="pendiente">Pendiente</option>
                            <option value="rechazada">Rechazada</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary w-100">
                            <i class="ti ti-user-plus me-1"></i> Inscribir
                        </button>
                    </div>
                </form>
            </div>

            <div class="modern-card p-4">
                <div class="d-flex justify-content-between gap-3 mb-3">
                    <div>
                        <h4 class="fw-bolder mb-1">Alumnos inscriptos</h4>
                        <p class="text-muted mb-0">{{ $comision->inscripciones->count() }} registro/s</p>
                    </div>
                    <a href="{{ route('cent.docente.planilla', $comision) }}" class="btn btn-light btn-sm">Planilla</a>
                </div>

                <div class="vstack gap-3">
                    @forelse($comision->inscripciones as $inscripcion)
                        <div class="d-flex justify-content-between gap-3 border-bottom pb-3">
                            <div>
                                <strong>{{ $inscripcion->alumno->name }}</strong>
                                <div class="small text-muted">{{ $inscripcion->alumno->dni ?: 'DNI pendiente' }} &middot; {{ $inscripcion->alumno->email }}</div>
                            </div>
                            <span class="badge bg-info-subtle text-info align-self-start">{{ ucfirst($inscripcion->status) }}</span>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Aún no hay alumnos inscriptos.</p>
                    @endforelse
                </div>
            </div>
        @else
            <div class="modern-card p-4">
                <div class="stat-icon bg-primary-subtle text-primary mb-3"><i class="ti ti-info-circle"></i></div>
                <h4 class="fw-bolder">Después de crearla</h4>
                <p class="text-muted mb-0">
                    El sistema te llevará a la pantalla de edición para inscribir alumnos y abrir la planilla docente.
                </p>
            </div>
        @endif
    </div>
</div>
@endsection
