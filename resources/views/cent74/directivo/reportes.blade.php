@extends('layouts.cent')

@section('title', 'Reportes CENT')
@section('header', 'Reportes')

@section('content')
<section class="portal-hero p-4 p-xl-5 mb-4">
    <span class="badge bg-white text-primary mb-3">Dirección</span>
    <h1 class="display-6 fw-bolder mb-3">Reportes académicos</h1>
    <p class="fs-5 text-muted mb-0">Indicadores rápidos para tomar decisiones sobre carreras, sedes, comisiones y estados académicos.</p>
</section>

<div class="modern-card p-4 mb-4">
    <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between">
        <div>
            <h3 class="fw-bolder mb-1">Exportaciones</h3>
            <p class="text-muted mb-0">Descargá reportes para administración, dirección y archivo institucional.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('cent.directivo.reportes.pdf') }}" class="btn btn-primary"><i class="ti ti-file-type-pdf me-1"></i> Reporte PDF</a>
            <a href="{{ route('cent.directivo.reportes.alumnos') }}" class="btn btn-outline-primary"><i class="ti ti-table-export me-1"></i> Alumnos CSV</a>
            <a href="{{ route('cent.directivo.reportes.cuotas') }}" class="btn btn-outline-primary"><i class="ti ti-receipt me-1"></i> Cuotas CSV</a>
            <a href="{{ route('cent.directivo.reportes.preinscripciones') }}" class="btn btn-outline-primary"><i class="ti ti-file-pencil me-1"></i> Preinscripciones CSV</a>
            <a href="{{ route('cent.directivo.reportes.actas') }}" class="btn btn-outline-primary"><i class="ti ti-clipboard-check me-1"></i> Actas CSV</a>
            <a href="{{ route('cent.directivo.reportes.mesas') }}" class="btn btn-outline-primary"><i class="ti ti-certificate me-1"></i> Mesas CSV</a>
            <a href="{{ route('cent.directivo.reportes.finales') }}" class="btn btn-outline-primary"><i class="ti ti-school me-1"></i> Finales CSV</a>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-xl-4">
        <div class="modern-card p-4 h-100">
            <h3 class="fw-bolder mb-4">Matrículas por carrera</h3>
            @forelse($matriculasPorCarrera as $item)
                <div class="d-flex justify-content-between border-bottom py-3">
                    <strong>{{ $item->carrera->name ?: 'Sin carrera' }}</strong>
                    <span class="badge bg-primary-subtle text-primary">{{ $item->total }}</span>
                </div>
            @empty
                <p class="text-muted mb-0">Sin datos.</p>
            @endforelse
        </div>
    </div>

    <div class="col-xl-4">
        <div class="modern-card p-4 h-100">
            <h3 class="fw-bolder mb-4">Matrículas por sede</h3>
            @forelse($matriculasPorSede as $item)
                <div class="d-flex justify-content-between border-bottom py-3">
                    <strong>{{ $item->sede->nombre ?: 'Sin sede' }}</strong>
                    <span class="badge bg-info-subtle text-info">{{ $item->total }}</span>
                </div>
            @empty
                <p class="text-muted mb-0">Sin datos.</p>
            @endforelse
        </div>
    </div>

    <div class="col-xl-4">
        <div class="modern-card p-4 h-100">
            <h3 class="fw-bolder mb-4">Preinscripciones</h3>
            @forelse($preinscripcionesPorEstado as $item)
                <div class="d-flex justify-content-between border-bottom py-3">
                    <strong>{{ ucfirst(str_replace('_', ' ', $item->estado)) }}</strong>
                    <span class="badge bg-warning-subtle text-warning">{{ $item->total }}</span>
                </div>
            @empty
                <p class="text-muted mb-0">Sin datos.</p>
            @endforelse
        </div>
    </div>
</div>

<div class="modern-card p-4">
    <h3 class="fw-bolder mb-4">Comisiones activas</h3>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Materia</th>
                    <th>Carrera</th>
                    <th>Sede</th>
                    <th>Docente</th>
                    <th>Alumnos</th>
                    <th>Acta</th>
                </tr>
            </thead>
            <tbody>
            @forelse($comisiones as $comision)
                <tr>
                    <td class="fw-bold">{{ $comision->materia->name }}</td>
                    <td>{{ $comision->materia->carrera->name }}</td>
                    <td>{{ $comision->sede->nombre ?: 'CENT N°74' }}</td>
                    <td>{{ $comision->docente->name ?: 'A designar' }}</td>
                    <td>{{ $comision->inscripciones_count }}</td>
                    <td><span class="badge bg-primary-subtle text-primary">{{ ucfirst($comision->acta_estado ?: 'abierta') }}</span></td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-muted">No hay comisiones cargadas.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

