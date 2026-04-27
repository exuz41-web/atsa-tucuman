@extends('layouts.cent')

@section('title', 'Mi legajo digital')
@section('header', 'Mi legajo')

@php
    $estadoColor = ['pendiente' => 'warning', 'aprobado' => 'success', 'rechazado' => 'danger'];
    $aprobados = $documentos->where('estado', 'aprobado')->count();
@endphp

@section('content')
<section class="portal-hero p-4 p-xl-5 mb-4">
    <div class="row g-4 align-items-center">
        <div class="col-xl-8">
            <span class="portal-kicker"><i class="ti ti-folder-open"></i> Documentación</span>
            <h1 class="display-6 fw-bolder mt-3 mb-3">Legajo digital</h1>
            <p class="fs-5 text-muted mb-0">Subí DNI, título, certificados y documentación requerida. Administración validará cada archivo.</p>
        </div>
        <div class="col-xl-4">
            <div class="bg-white bg-opacity-10 rounded-4 p-4 border border-white border-opacity-25">
                <div class="small text-white-50 fw-bold text-uppercase">Documentos aprobados</div>
                <h4 class="text-white fw-bolder mb-0">{{ $aprobados }} de {{ max($documentos->count(), 1) }}</h4>
            </div>
        </div>
    </div>
</section>

<div class="row g-4">
    <div class="col-xl-5">
        <form method="POST" action="{{ route('cent.alumno.legajo.subir') }}" enctype="multipart/form-data" class="modern-card p-4">
            @csrf
            <h4 class="fw-bolder mb-4">Subir documento</h4>
            <div class="mb-3">
                <label class="form-label fw-bold">Tipo</label>
                <select name="tipo" class="form-select" required>
                    <option value="">Seleccionar</option>
                    @foreach($tipos as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Archivo</label>
                <input type="file" name="archivo" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                <div class="small text-muted mt-2">PDF, JPG o PNG. Máximo 4 MB.</div>
            </div>
            <div class="mb-4">
                <label class="form-label fw-bold">Observaciones</label>
                <textarea name="observaciones" rows="3" class="form-control" placeholder="Ej.: constancia provisoria, archivo reemplazado, etc."></textarea>
            </div>
            <button class="btn btn-primary w-100"><i class="ti ti-upload me-1"></i> Enviar documento</button>
        </form>
    </div>

    <div class="col-xl-7">
        <div class="modern-card p-4 h-100">
            <h4 class="fw-bolder mb-4">Documentos cargados</h4>
            <div class="vstack gap-3">
                @forelse($documentos as $documento)
                    @php($color = $estadoColor[$documento->estado] ?? 'warning')
                    <div class="soft-panel p-3 d-flex justify-content-between gap-3 flex-wrap">
                        <div class="d-flex gap-3">
                            <span class="stat-icon bg-{{ $color }}-subtle text-{{ $color }}"><i class="ti ti-file-check"></i></span>
                            <div>
                                <strong>{{ $tipos[$documento->tipo] ?? ucfirst($documento->tipo) }}</strong>
                                <div class="small text-muted">Subido {{ $documento->created_at->format('d/m/Y H:i') }}</div>
                                @if($documento->observaciones)
                                    <div class="small text-muted mt-1">{{ $documento->observaciones }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-{{ $color }}-subtle text-{{ $color }}">{{ ucfirst($documento->estado) }}</span>
                            @if($documento->archivo)
                                <a href="{{ route('cent.archivos.legajo', $documento) }}" target="_blank" class="btn btn-sm btn-outline-primary d-block mt-2">Ver archivo</a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <span class="stat-icon bg-primary-subtle text-primary mb-3"><i class="ti ti-folder-plus"></i></span>
                        <h4 class="fw-bolder">Todavía no subiste documentos</h4>
                        <p class="text-muted mb-0">Cuando envíes documentación, podrás seguir el estado desde esta pantalla.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
