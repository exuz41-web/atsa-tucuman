@extends('layouts.afiliado')

@section('title', 'Descargas')
@section('page_title', 'Descargas')

@section('content')
@php
    $labels = [
        'formularios' => 'Formularios',
        'reglamentos' => 'Reglamentos',
        'requisitos' => 'Requisitos',
        'modelos' => 'Modelos',
    ];
@endphp

<div class="portal-card p-4 p-lg-5 mb-4">
    <div class="d-flex align-items-center gap-3">
        <span class="portal-icon"><i class="ti ti-download"></i></span>
        <div>
            <p class="text-primary fw-semibold fs-3 mb-1">DOCUMENTOS</p>
            <h2 class="fw-bolder mb-0">Descargas disponibles</h2>
        </div>
    </div>
</div>

<div class="vstack gap-4">
    @forelse ($descargas as $category => $items)
        <section class="portal-card p-4 p-lg-5">
            <h4 class="fw-bolder mb-4">{{ $labels[$category] ?? ucfirst($category) }}</h4>
            <div class="row g-3">
                @foreach ($items as $descarga)
                    <div class="col-md-6">
                        <article class="border rounded-3 p-3 h-100 d-flex align-items-center justify-content-between gap-3 bg-light">
                            <div class="d-flex align-items-center gap-3 min-w-0">
                                <span class="portal-icon" style="width:44px;height:44px;font-size:20px;"><i class="ti ti-file-text"></i></span>
                                <div class="min-w-0">
                                    <h6 class="fw-bolder text-truncate mb-1">{{ $descarga->title }}</h6>
                                    <p class="text-muted fs-2 mb-0">{{ $labels[$descarga->category] ?? ucfirst($descarga->category) }}</p>
                                </div>
                            </div>
                            <a href="{{ asset('storage/'.$descarga->file_path) }}" target="_blank" class="btn btn-primary btn-sm shadow-none">
                                Descargar
                            </a>
                        </article>
                    </div>
                @endforeach
            </div>
        </section>
    @empty
        <section class="portal-card p-5 text-center">
            <i class="ti ti-file-off text-muted fs-10 d-block mb-3"></i>
            <h4 class="fw-bolder mb-2">No hay documentos cargados</h4>
            <p class="text-muted mb-0">Cuando el sindicato publique formularios o reglamentos, los vas a encontrar acá.</p>
        </section>
    @endforelse
</div>
@endsection
