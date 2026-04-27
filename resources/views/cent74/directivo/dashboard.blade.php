@extends('layouts.cent')

@section('title', 'Dirección Académica CENT')
@section('header', 'Gestión académica')

@section('content')
<section class="portal-hero p-4 p-xl-5 mb-4">
    <div class="row g-4 align-items-center">
        <div class="col-xl-8">
            <span class="portal-kicker"><i class="ti ti-building-bank"></i> Dirección</span>
            <h1 class="display-6 fw-bolder mt-3 mb-3">Tablero académico CENT N°74</h1>
            <p class="fs-5 text-muted mb-0">Preinscripciones, alumnos, docentes, sedes, carreras, comisiones, actas y finales con lectura rápida.</p>
        </div>
        <div class="col-xl-4">
            <div class="bg-white bg-opacity-10 rounded-4 p-4 border border-white border-opacity-25">
                <div class="small text-white-50 fw-bold text-uppercase">Panel administrativo</div>
                <h4 class="text-white fw-bolder mb-2">Gestión centralizada</h4>
                <a href="{{ url('/cent-admin') }}" target="_blank" class="btn btn-light btn-sm">Abrir admin CENT</a>
            </div>
        </div>
    </div>
</section>

<div class="row g-4 mb-4">
    @foreach([
        ['label' => 'Preinscripciones pendientes', 'value' => $preinscripcionesPendientes, 'icon' => 'ti-file-pencil', 'color' => 'warning'],
        ['label' => 'Alumnos', 'value' => $alumnos, 'icon' => 'ti-users', 'color' => 'primary'],
        ['label' => 'Docentes', 'value' => $docentes, 'icon' => 'ti-chalkboard', 'color' => 'success'],
        ['label' => 'Sedes activas', 'value' => $sedes, 'icon' => 'ti-map-pin', 'color' => 'info'],
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
                    <h3 class="fw-bolder mb-1">Últimas preinscripciones</h3>
                    <p class="text-muted mb-0">Aspirantes recientes para revisión administrativa.</p>
                </div>
                <a href="{{ url('/cent-admin/preinscripciones-cent') }}" target="_blank" class="btn btn-primary btn-sm">Gestionar</a>
            </div>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Aspirante</th>
                            <th>Carrera</th>
                            <th>Sede</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($ultimasPreinscripciones as $pre)
                        <tr>
                            <td class="fw-bold">{{ $pre->codigo }}</td>
                            <td>
                                <strong>{{ $pre->apellido_nombre }}</strong>
                                <div class="small text-muted">{{ $pre->dni }} · {{ $pre->telefono }}</div>
                            </td>
                            <td>{{ $pre->carrera->name }}</td>
                            <td>{{ $pre->sede->nombre }}</td>
                            <td><span class="badge bg-warning-subtle text-warning">{{ ucfirst(str_replace('_', ' ', $pre->estado)) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-muted">No hay preinscripciones cargadas.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="modern-card p-4 h-100">
            <h3 class="fw-bolder mb-4">Accesos de dirección</h3>
            <div class="vstack gap-3">
                <a class="action-card" href="{{ route('cent.directivo.reportes') }}">
                    <span class="stat-icon bg-primary-subtle text-primary"><i class="ti ti-chart-donut"></i></span>
                    <div><strong>Reportes</strong><div class="small text-muted">PDF y CSV institucional</div></div>
                </a>
                <a class="action-card" href="{{ route('cent.directivo.actas-mesas') }}">
                    <span class="stat-icon bg-success-subtle text-success"><i class="ti ti-certificate"></i></span>
                    <div><strong>Actas finales</strong><div class="small text-muted">{{ $mesasPendientes }} pendiente/s</div></div>
                </a>
                <a class="action-card" href="{{ route('cent.directivo.comisiones') }}">
                    <span class="stat-icon bg-info-subtle text-info"><i class="ti ti-calendar-stats"></i></span>
                    <div><strong>Comisiones</strong><div class="small text-muted">Docentes, sedes y ciclos</div></div>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-6">
        <div class="modern-card p-4 h-100">
            <h3 class="fw-bolder mb-4">Próximas mesas</h3>
            @forelse($proximasMesas as $mesa)
                <div class="d-flex gap-3 border-bottom py-3">
                    <span class="timeline-dot"></span>
                    <div class="flex-grow-1 d-flex justify-content-between gap-3">
                        <div>
                            <strong>{{ $mesa->materia->name }}</strong>
                            <div class="small text-muted">{{ $mesa->sede->nombre ?: 'CENT N°74' }} · {{ $mesa->docente->name ?: 'A designar' }}</div>
                        </div>
                        <span class="badge bg-primary-subtle text-primary align-self-start">{{ $mesa->fecha->format('d/m/Y') }}</span>
                    </div>
                </div>
            @empty
                <p class="text-muted mb-0">No hay mesas próximas cargadas.</p>
            @endforelse
        </div>
    </div>

    <div class="col-xl-6">
        <div class="modern-card p-4 h-100">
            <h3 class="fw-bolder mb-4">Matrículas por sede</h3>
            @forelse($matriculasPorSede as $grupo)
                <div class="d-flex justify-content-between align-items-center border-bottom py-3">
                    <div>
                        <strong>{{ $grupo->sede->nombre ?: 'Sin sede' }}</strong>
                        <div class="small text-muted">{{ $grupo->sede->ciudad ?: 'CENT N°74' }}</div>
                    </div>
                    <span class="badge bg-primary-subtle text-primary">{{ $grupo->total }}</span>
                </div>
            @empty
                <p class="text-muted mb-0">Aún no hay matrículas registradas.</p>
            @endforelse
        </div>
    </div>

    <div class="col-xl-5">
        <div class="modern-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
                <div>
                    <h3 class="fw-bolder mb-1">Carreras activas</h3>
                    <p class="text-muted mb-0">Oferta académica publicada.</p>
                </div>
                <a href="{{ url('/cent-admin/carreras') }}" target="_blank" class="btn btn-light btn-sm">Editar</a>
            </div>
            @forelse($carreras as $carrera)
                <div class="d-flex justify-content-between border-bottom py-3">
                    <div>
                        <strong>{{ $carrera->name }}</strong>
                        <div class="small text-muted">{{ $carrera->duration }}</div>
                    </div>
                    <span class="badge bg-info-subtle text-info">{{ $carrera->materias_count }} materias</span>
                </div>
            @empty
                <p class="text-muted mb-0">No hay carreras activas.</p>
            @endforelse
        </div>
    </div>

    <div class="col-xl-7">
        <div class="modern-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
                <div>
                    <h3 class="fw-bolder mb-1">Comisiones recientes</h3>
                    <p class="text-muted mb-0">Materias, docentes y sedes vinculadas.</p>
                </div>
                <a href="{{ route('cent.directivo.comisiones') }}" class="btn btn-light btn-sm">Gestionar</a>
            </div>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead><tr><th>Materia</th><th>Docente</th><th>Sede</th><th>Ciclo</th></tr></thead>
                    <tbody>
                    @forelse($comisiones as $comision)
                        <tr>
                            <td><strong>{{ $comision->materia->name }}</strong><div class="small text-muted">{{ $comision->materia->carrera->name }}</div></td>
                            <td>{{ $comision->docente->name ?: 'A designar' }}</td>
                            <td>{{ $comision->sede->nombre ?: 'CENT N°74' }}</td>
                            <td><span class="badge bg-primary-subtle text-primary">{{ $comision->year_cycle }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-muted">No hay comisiones cargadas.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
