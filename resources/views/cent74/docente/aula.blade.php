@extends('layouts.cent')

@section('title', 'Aula docente')
@section('header', 'Aula virtual docente')

@section('content')
<section class="portal-hero p-4 p-xl-5 mb-4">
    <span class="portal-kicker"><i class="ti ti-device-laptop"></i> Aula docente</span>
    <h1 class="display-6 fw-bolder mt-3 mb-3">Publicar clases, materiales y trabajos</h1>
    <p class="fs-5 text-muted mb-0">Gestioná el contenido classroom de tus comisiones con formularios rápidos.</p>
</section>

<div class="row g-4 mb-4">
    <div class="col-xl-4">
        <div class="modern-card p-4 h-100">
            <div class="d-flex align-items-center gap-3 mb-4">
                <span class="stat-icon bg-primary-subtle text-primary"><i class="ti ti-calendar-plus"></i></span>
                <h3 class="fw-bolder mb-0">Nueva clase</h3>
            </div>
            <form action="{{ route('cent.docente.aula.clases') }}" method="POST" class="vstack gap-3">
                @csrf
                <select name="comision_id" class="form-select" required>
                    <option value="">Comisión</option>
                    @foreach($comisiones as $comision)
                        <option value="{{ $comision->id }}">{{ $comision->materia->name ?: 'Materia' }} - {{ $comision->sede->nombre ?: 'Sede' }}</option>
                    @endforeach
                </select>
                <input name="titulo" class="form-control" placeholder="Título" required>
                <input type="datetime-local" name="fecha_inicio" class="form-control" required>
                <input type="datetime-local" name="fecha_fin" class="form-control">
                <select name="modalidad" class="form-select" required>
                    <option value="presencial">Presencial</option>
                    <option value="virtual">Virtual</option>
                    <option value="mixta">Mixta</option>
                </select>
                <input name="aula" class="form-control" placeholder="Aula">
                <input name="link_virtual" class="form-control" placeholder="Link virtual">
                <textarea name="descripcion" rows="3" class="form-control" placeholder="Descripción"></textarea>
                <button class="btn btn-primary">Publicar clase</button>
            </form>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="modern-card p-4 h-100">
            <div class="d-flex align-items-center gap-3 mb-4">
                <span class="stat-icon bg-info-subtle text-info"><i class="ti ti-file-upload"></i></span>
                <h3 class="fw-bolder mb-0">Nuevo material</h3>
            </div>
            <form action="{{ route('cent.docente.aula.materiales') }}" method="POST" enctype="multipart/form-data" class="vstack gap-3">
                @csrf
                <select name="comision_id" class="form-select" required>
                    <option value="">Comisión</option>
                    @foreach($comisiones as $comision)
                        <option value="{{ $comision->id }}">{{ $comision->materia->name ?: 'Materia' }} - {{ $comision->sede->nombre ?: 'Sede' }}</option>
                    @endforeach
                </select>
                <input name="titulo" class="form-control" placeholder="Título" required>
                <select name="tipo" class="form-select" required>
                    <option value="apunte">Apunte</option>
                    <option value="video">Video</option>
                    <option value="link">Link</option>
                    <option value="presentacion">Presentación</option>
                    <option value="guia">Guía</option>
                    <option value="otro">Otro</option>
                </select>
                <input type="file" name="archivo" class="form-control">
                <input name="url" class="form-control" placeholder="URL externa">
                <textarea name="descripcion" rows="3" class="form-control" placeholder="Descripción"></textarea>
                <button class="btn btn-primary">Publicar material</button>
            </form>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="modern-card p-4 h-100">
            <div class="d-flex align-items-center gap-3 mb-4">
                <span class="stat-icon bg-success-subtle text-success"><i class="ti ti-pencil-check"></i></span>
                <h3 class="fw-bolder mb-0">Nuevo TP</h3>
            </div>
            <form action="{{ route('cent.docente.aula.trabajos') }}" method="POST" enctype="multipart/form-data" class="vstack gap-3">
                @csrf
                <select name="comision_id" class="form-select" required>
                    <option value="">Comisión</option>
                    @foreach($comisiones as $comision)
                        <option value="{{ $comision->id }}">{{ $comision->materia->name ?: 'Materia' }} - {{ $comision->sede->nombre ?: 'Sede' }}</option>
                    @endforeach
                </select>
                <input name="titulo" class="form-control" placeholder="Título" required>
                <input type="datetime-local" name="fecha_entrega" class="form-control">
                <input name="puntaje_maximo" type="number" step="0.01" class="form-control" placeholder="Puntaje máximo">
                <input type="file" name="archivo_consigna" class="form-control">
                <textarea name="consigna" rows="5" class="form-control" placeholder="Consigna" required></textarea>
                <button class="btn btn-primary">Publicar TP</button>
            </form>
        </div>
    </div>
</div>

<div class="modern-card p-4">
    <h3 class="fw-bolder mb-4">Resumen por comisión</h3>
    <div class="row g-4">
        @forelse($comisiones as $comision)
            <div class="col-lg-6">
                <div class="soft-panel p-4 h-100">
                    <h4 class="fw-bolder mb-1">{{ $comision->materia->name ?: 'Materia' }}</h4>
                    <p class="text-muted">{{ $comision->sede->nombre ?: 'Sede' }} · {{ $comision->year_cycle }}</p>
                    <div class="row g-3">
                        <div class="col-4"><div class="stat-tile p-3"><div class="fw-bolder fs-4">{{ $comision->clases->count() }}</div><div class="small text-muted">Clases</div></div></div>
                        <div class="col-4"><div class="stat-tile p-3"><div class="fw-bolder fs-4">{{ $comision->materiales->count() }}</div><div class="small text-muted">Materiales</div></div></div>
                        <div class="col-4"><div class="stat-tile p-3"><div class="fw-bolder fs-4">{{ $comision->trabajosPracticos->count() }}</div><div class="small text-muted">TP</div></div></div>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted mb-0">No tenés comisiones asignadas.</p>
        @endforelse
    </div>
</div>
@endsection
