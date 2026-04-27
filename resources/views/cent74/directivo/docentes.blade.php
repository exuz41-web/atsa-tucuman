@extends('layouts.cent')

@section('title', 'Docentes CENT')
@section('header', 'Docentes')

@section('content')
<section class="portal-hero p-4 p-xl-5 mb-4">
    <span class="badge bg-white text-primary mb-3">Dirección</span>
    <h1 class="display-6 fw-bolder mb-3">Docentes y comisiones</h1>
    <p class="fs-5 text-muted mb-0">Seguimiento de asignaciones docentes por materia, sede y ciclo lectivo.</p>
</section>

<div class="row g-4">
    @forelse($docentes as $docente)
        <div class="col-xl-6">
            <div class="modern-card p-4 h-100">
                <div class="d-flex justify-content-between gap-3 mb-4">
                    <div>
                        <h3 class="fw-bolder mb-1">{{ $docente->name }}</h3>
                        <p class="text-muted mb-0">{{ $docente->email }} &middot; {{ $docente->dni ?: 'DNI pendiente' }}</p>
                    </div>
                    <span class="badge bg-primary-subtle text-primary align-self-start">{{ $docente->comisiones_docente_count }} comisiones</span>
                </div>
                @forelse($docente->comisionesDocente as $comision)
                    <div class="border-bottom py-3">
                        <div class="d-flex justify-content-between gap-3">
                            <div>
                                <strong>{{ $comision->materia->name }}</strong>
                                <div class="small text-muted">{{ $comision->materia->carrera->name }} &middot; {{ $comision->sede->nombre ?: 'CENT N°74' }} &middot; {{ $comision->year_cycle }}</div>
                            </div>
                            <a href="{{ route('cent.directivo.comisiones.editar', $comision) }}" class="btn btn-sm btn-light align-self-start">Ver</a>
                        </div>
                    </div>
                @empty
                    <p class="text-muted mb-0">Sin comisiones asignadas.</p>
                @endforelse
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="modern-card p-5 text-center text-muted">No hay docentes cargados.</div>
        </div>
    @endforelse
</div>
@endsection
