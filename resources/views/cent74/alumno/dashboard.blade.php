@extends('layouts.cent')

@section('title', 'Portal Alumno CENT')
@section('header', 'Mi estado académico')

@php
    $notasPorMateria = $notas->groupBy(fn ($nota) => $nota->comision?->materia_id);
    $presentes = $asistencias->where('estado', 'presente')->count();
    $totalAsistencias = max($asistencias->count(), 1);
    $porcentajeAsistencia = round(($presentes / $totalAsistencias) * 100);
    $foto = $alumno->foto_perfil ? asset('storage/'.$alumno->foto_perfil) : null;
@endphp

@section('content')
<section class="portal-hero p-4 p-xl-5 mb-4">
    <div class="row g-4 align-items-center">
        <div class="col-xl-8">
            <span class="portal-kicker"><i class="ti ti-school"></i> Portal alumno</span>
            <h1 class="display-6 fw-bolder mt-3 mb-3">Hola, {{ $alumno->name }}</h1>
            <p class="fs-5 text-muted mb-0">Tu carrera, asistencia, notas, cuotas, entregas y documentos en un solo panel.</p>
        </div>
        <div class="col-xl-4">
            <div class="bg-white bg-opacity-10 rounded-4 p-4 border border-white border-opacity-25 d-flex gap-3 align-items-center">
                @if($foto)
                    <img src="{{ $foto }}" class="avatar avatar-lg" alt="{{ $alumno->name }}">
                @else
                    <span class="avatar avatar-lg">{{ mb_substr($alumno->name, 0, 1) }}</span>
                @endif
                <div>
                    <div class="small text-white-50 fw-bold text-uppercase">Carrera actual</div>
                    <h5 class="text-white fw-bolder mb-1">{{ $matriculaActiva?->carrera?->name ?: 'Sin matrícula cargada' }}</h5>
                    <div class="text-white-50 small">{{ $matriculaActiva?->sede?->nombre ?: 'Sede pendiente' }} @if($matriculaActiva?->legajo) · Legajo {{ $matriculaActiva->legajo }} @endif</div>
                </div>
            </div>
        </div>
    </div>
</section>

@if($cuotasPendientes->isNotEmpty() || $trabajosPendientes->isNotEmpty())
    <div class="row g-4 mb-4">
        @if($cuotasPendientes->isNotEmpty())
            <div class="col-lg-6">
                <div class="alert alert-warning rounded-4 border-0 shadow-sm mb-0">
                    <h5 class="fw-bolder mb-2"><i class="ti ti-alert-triangle me-1"></i> Cuotas pendientes</h5>
                    <p class="mb-3">Tenés cuotas pendientes o próximas a vencer. Subí el comprobante si ya pagaste.</p>
                    <a href="{{ route('cent.alumno.cuotas') }}" class="btn btn-sm btn-warning">Ver cuotas</a>
                </div>
            </div>
        @endif
        @if($trabajosPendientes->isNotEmpty())
            <div class="col-lg-6">
                <div class="alert alert-info rounded-4 border-0 shadow-sm mb-0">
                    <h5 class="fw-bolder mb-2"><i class="ti ti-pencil-check me-1"></i> Entregas pendientes</h5>
                    <p class="mb-3">Hay trabajos prácticos disponibles para entregar desde el aula virtual.</p>
                    <a href="{{ route('cent.alumno.aula') }}" class="btn btn-sm btn-info text-white">Ir al aula</a>
                </div>
            </div>
        @endif
    </div>
@endif

<div class="row g-4 mb-4">
    @foreach([
        ['label' => 'Matrículas', 'value' => $matriculas->count(), 'icon' => 'ti-id-badge-2', 'color' => 'primary'],
        ['label' => 'Comisiones', 'value' => $inscripciones->count(), 'icon' => 'ti-users-group', 'color' => 'info'],
        ['label' => 'Notas cargadas', 'value' => $notas->count(), 'icon' => 'ti-report-analytics', 'color' => 'success'],
        ['label' => 'Asistencia', 'value' => $asistencias->count() ? $porcentajeAsistencia.'%' : '-', 'icon' => 'ti-calendar-check', 'color' => 'warning'],
    ] as $stat)
        <div class="col-md-6 col-xl-3">
            <div class="stat-tile h-100">
                <span class="stat-icon bg-{{ $stat['color'] }}-subtle text-{{ $stat['color'] }} mb-3"><i class="ti {{ $stat['icon'] }}"></i></span>
                <h2 class="fw-bolder mb-1">{{ $stat['value'] }}</h2>
                <p class="text-muted mb-0">{{ $stat['label'] }}</p>
            </div>
        </div>
    @endforeach
</div>

<div class="row g-4 mb-4">
    <div class="col-xl-8">
        <div class="modern-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
                <div>
                    <h3 class="fw-bolder mb-1">Actividad académica</h3>
                    <p class="text-muted mb-0">Accesos rápidos para lo que más vas a usar.</p>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <a class="action-card" href="{{ route('cent.alumno.aula') }}">
                        <span class="stat-icon bg-primary-subtle text-primary"><i class="ti ti-device-laptop"></i></span>
                        <div><strong>Aula virtual</strong><div class="small text-muted">{{ $trabajosPendientes->count() }} entrega/s pendiente/s</div></div>
                    </a>
                </div>
                <div class="col-md-6">
                    <a class="action-card" href="{{ route('cent.alumno.cuotas') }}">
                        <span class="stat-icon bg-warning-subtle text-warning"><i class="ti ti-receipt-2"></i></span>
                        <div><strong>Cuotas</strong><div class="small text-muted">{{ $cuotasPendientes->count() }} pendiente/s</div></div>
                    </a>
                </div>
                <div class="col-md-6">
                    <a class="action-card" href="{{ route('cent.alumno.permisos') }}">
                        <span class="stat-icon bg-success-subtle text-success"><i class="ti ti-qr-code"></i></span>
                        <div><strong>Permisos de examen</strong><div class="small text-muted">Descarga y verificación QR</div></div>
                    </a>
                </div>
                <div class="col-md-6">
                    <a class="action-card" href="{{ route('cent.alumno.legajo') }}">
                        <span class="stat-icon bg-info-subtle text-info"><i class="ti ti-folder-open"></i></span>
                        <div><strong>Legajo digital</strong><div class="small text-muted">Documentación obligatoria</div></div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="modern-card p-4 h-100">
            <h3 class="fw-bolder mb-4">Documentos rápidos</h3>
            <div class="d-grid gap-3">
                <a href="{{ route('cent.alumno.constancia-pdf') }}" class="btn btn-primary"><i class="ti ti-file-certificate me-1"></i> Descargar constancia</a>
                <a href="{{ route('cent.alumno.ficha-pdf') }}" class="btn btn-outline-primary"><i class="ti ti-school me-1"></i> Descargar ficha académica</a>
                <a href="{{ route('cent.alumno.carnet') }}" class="btn btn-light"><i class="ti ti-id me-1"></i> Ver carnet estudiantil</a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-7">
        <div class="modern-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
                <div>
                    <h3 class="fw-bolder mb-1">Plan de estudios</h3>
                    <p class="text-muted mb-0">Materias principales y avance reciente.</p>
                </div>
                <a href="{{ route('cent.alumno.carrera') }}" class="btn btn-light btn-sm">Ver detalle</a>
            </div>

            @forelse($materiasPlan->take(8) as $materia)
                @php
                    $materiaNotas = $notasPorMateria->get($materia->id, collect());
                    $ultimaNota = $materiaNotas->sortByDesc('created_at')->first();
                @endphp
                <div class="d-flex gap-3 border-bottom py-3">
                    <span class="timeline-dot"></span>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between gap-3">
                            <div>
                                <strong>{{ $materia->name }}</strong>
                                <div class="small text-muted">{{ $materia->year }}° año @if($materia->semester) · {{ $materia->semester }}° cuatrimestre @endif @if($materia->hours) · {{ $materia->hours }} hs @endif</div>
                            </div>
                            @if($ultimaNota)
                                <span class="badge bg-primary-subtle text-primary align-self-start">{{ ucfirst($ultimaNota->status) }} · {{ $ultimaNota->grade ?: '-' }}</span>
                            @else
                                <span class="badge bg-light text-muted align-self-start">Sin nota</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-muted mb-0">Todavía no hay plan de estudios asociado a tu matrícula.</p>
            @endforelse
        </div>
    </div>

    <div class="col-xl-5">
        <div class="modern-card p-4 mb-4">
            <h3 class="fw-bolder mb-4">Avisos importantes</h3>
            <div class="vstack gap-3">
                @forelse($avisos->take(4) as $aviso)
                    <div class="p-3 rounded-4 {{ $aviso->destacado ? 'bg-primary-subtle' : 'soft-panel' }}">
                        <h5 class="fw-bolder mb-1">{{ $aviso->titulo }}</h5>
                        <p class="text-muted mb-2">{{ $aviso->contenido }}</p>
                        <div class="small text-muted">{{ $aviso->carrera?->name ?: 'Todas las carreras' }} · {{ $aviso->sede?->nombre ?: 'Todas las sedes' }}</div>
                    </div>
                @empty
                    <p class="text-muted mb-0">No hay avisos publicados por ahora.</p>
                @endforelse
            </div>
        </div>

        <div class="modern-card p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bolder mb-0">Notas recientes</h3>
                <a href="{{ route('cent.alumno.notas') }}" class="btn btn-light btn-sm">Ver todas</a>
            </div>
            @forelse($notas->take(5) as $nota)
                <div class="d-flex justify-content-between gap-3 border-bottom py-3">
                    <div>
                        <strong>{{ $nota->comision->materia->name }}</strong>
                        <div class="small text-muted">{{ ucfirst($nota->type) }} · {{ ucfirst($nota->status) }}</div>
                    </div>
                    <span class="badge bg-primary-subtle text-primary fs-3">{{ $nota->grade ?: '-' }}</span>
                </div>
            @empty
                <p class="text-muted mb-0">Aún no hay notas cargadas.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
