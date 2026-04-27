@extends('layouts.cent')

@section('title', 'Portal Docente CENT')
@section('header', 'Panel docente')

@php
    $totalAlumnos = $comisiones->sum(fn ($comision) => $comision->inscripciones->count());
    $totalNotas = $comisiones->sum(fn ($comision) => $comision->notas->count());
    $totalAsistencias = $comisiones->sum(fn ($comision) => $comision->asistencias->count());
    $actasPendientes = $comisiones->where('acta_estado', 'abierta')->count();
@endphp

@section('content')
<section class="portal-hero p-4 p-xl-5 mb-4">
    <div class="row g-4 align-items-center">
        <div class="col-xl-8">
            <span class="portal-kicker"><i class="ti ti-chalkboard"></i> Portal docente</span>
            <h1 class="display-6 fw-bolder mt-3 mb-3">Hola, {{ $docente->name }}</h1>
            <p class="fs-5 text-muted mb-0">Gestioná comisiones, asistencia, notas, materiales y trabajos prácticos desde un panel simple.</p>
        </div>
        <div class="col-xl-4">
            <div class="bg-white bg-opacity-10 rounded-4 p-4 border border-white border-opacity-25">
                <div class="small text-white-50 fw-bold text-uppercase">Ciclo lectivo</div>
                <h4 class="text-white fw-bolder mb-2">{{ now()->year }}</h4>
                <a href="{{ route('cent.docente.aula') }}" class="btn btn-light btn-sm">Publicar en aula</a>
            </div>
        </div>
    </div>
</section>

<div class="row g-4 mb-4">
    @foreach([
        ['label' => 'Comisiones', 'value' => $comisiones->count(), 'icon' => 'ti-chalkboard', 'color' => 'primary'],
        ['label' => 'Alumnos', 'value' => $totalAlumnos, 'icon' => 'ti-users', 'color' => 'info'],
        ['label' => 'Notas cargadas', 'value' => $totalNotas, 'icon' => 'ti-report', 'color' => 'success'],
        ['label' => 'Actas abiertas', 'value' => $actasPendientes, 'icon' => 'ti-clipboard-list', 'color' => 'warning'],
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
                    <h3 class="fw-bolder mb-1">Mis comisiones</h3>
                    <p class="text-muted mb-0">Accedé directo a planilla, asistencia y aula virtual.</p>
                </div>
                <a href="{{ route('cent.docente.comisiones') }}" class="btn btn-light btn-sm">Ver todas</a>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Materia</th>
                            <th>Sede</th>
                            <th>Alumnos</th>
                            <th>Acta</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($comisiones->take(8) as $comision)
                        <tr>
                            <td>
                                <strong>{{ $comision->materia->name }}</strong>
                                <div class="small text-muted">{{ $comision->materia->carrera->name }}</div>
                            </td>
                            <td>{{ $comision->sede->nombre ?: 'CENT N°74' }}</td>
                            <td><span class="badge bg-info-subtle text-info">{{ $comision->inscripciones->count() }}</span></td>
                            <td><span class="badge bg-primary-subtle text-primary">{{ ucfirst($comision->acta_estado ?: 'abierta') }}</span></td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a href="{{ route('cent.docente.planilla', $comision) }}" class="btn btn-primary btn-sm">Planilla</a>
                                    <a href="{{ route('cent.docente.asistencia', $comision) }}" class="btn btn-outline-primary btn-sm">Asistencia</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-muted">Sin comisiones asignadas.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="modern-card p-4 h-100">
            <h3 class="fw-bolder mb-4">Accesos rápidos</h3>
            <div class="vstack gap-3">
                <a class="action-card" href="{{ route('cent.docente.aula') }}">
                    <span class="stat-icon bg-primary-subtle text-primary"><i class="ti ti-device-laptop"></i></span>
                    <div><strong>Aula virtual</strong><div class="small text-muted">Clases, materiales y TP</div></div>
                </a>
                <a class="action-card" href="{{ route('cent.docente.mesas') }}">
                    <span class="stat-icon bg-success-subtle text-success"><i class="ti ti-certificate"></i></span>
                    <div><strong>Mesas de examen</strong><div class="small text-muted">Resultados finales</div></div>
                </a>
                <a class="action-card" href="{{ route('cent.calendario') }}">
                    <span class="stat-icon bg-warning-subtle text-warning"><i class="ti ti-calendar-month"></i></span>
                    <div><strong>Calendario</strong><div class="small text-muted">Eventos académicos</div></div>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-7">
        <div class="modern-card p-4 h-100">
            <h3 class="fw-bolder mb-4">Avisos docentes</h3>
            <div class="vstack gap-3">
                @forelse($avisos as $aviso)
                    <div class="p-3 rounded-4 {{ $aviso->destacado ? 'bg-primary-subtle' : 'soft-panel' }}">
                        <h5 class="fw-bolder mb-1">{{ $aviso->titulo }}</h5>
                        <p class="text-muted mb-0">{{ $aviso->contenido }}</p>
                    </div>
                @empty
                    <p class="text-muted mb-0">No hay avisos publicados.</p>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-xl-5">
        <div class="modern-card p-4 h-100">
            <h3 class="fw-bolder mb-3">Estado de carga</h3>
            <p class="text-muted">Tenés <strong>{{ $actasPendientes }}</strong> acta/s abierta/s. Al finalizar la carga de notas, cerralas para que Dirección pueda aprobarlas.</p>
            <div class="row g-3 mt-2">
                <div class="col-6"><div class="soft-panel p-3"><div class="small text-muted">Asistencias</div><h3 class="fw-bolder mb-0">{{ $totalAsistencias }}</h3></div></div>
                <div class="col-6"><div class="soft-panel p-3"><div class="small text-muted">Notas</div><h3 class="fw-bolder mb-0">{{ $totalNotas }}</h3></div></div>
            </div>
        </div>
    </div>
</div>
@endsection
