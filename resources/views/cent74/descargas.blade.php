@extends('layouts.cent-public')

@section('title', 'Descargas - CENT N°74')
@section('meta_description', 'Formularios, reglamentos, planes de estudio y documentación institucional del CENT N°74.')

@section('content')
<section class="py-5 bg-light">
    <div class="container py-lg-5">
        <span class="section-badge bg-success-subtle text-success">
            <i class="ti ti-download"></i> Documentación
        </span>
        <h1 class="display-5 fw-bolder">Descargas del CENT N°74</h1>
        <p class="fs-5 text-muted text-justify mb-0">
            Formularios, reglamentos, planes de estudio, fichas de inscripción y archivos útiles para aspirantes, alumnos y docentes.
        </p>
    </div>
</section>

<section class="py-5 py-lg-10">
    <div class="container">
        @if($descargas->isEmpty())
            <div class="card cent-card">
                <div class="card-body p-5 text-center">
                    <span class="feature-icon bg-primary-subtle text-primary mx-auto mb-3"><i class="ti ti-files"></i></span>
                    <h2 class="fw-bold">Documentación en preparación</h2>
                    <p class="text-muted mb-4">La institución todavía no cargó archivos públicos en esta sección.</p>
                    <a href="{{ route('cent.contacto') }}" class="btn btn-cent">Solicitar información</a>
                </div>
            </div>
        @else
            <div class="row g-5">
                <div class="col-lg-3">
                    <div class="card cent-card sticky-top" style="top: 110px;">
                        <div class="card-body p-4">
                            <h4 class="fw-bold mb-3">Categorías</h4>
                            <div class="d-flex flex-column gap-2">
                                @foreach($descargas as $categoria => $items)
                                    <a href="#cat-{{ $categoria }}" class="btn btn-outline-cent btn-sm text-start">
                                        {{ $categorias[$categoria] ?? ucfirst($categoria) }}
                                        <span class="badge bg-primary-subtle text-primary float-end">{{ $items->count() }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="d-flex flex-column gap-5">
                        @foreach($descargas as $categoria => $items)
                            <section id="cat-{{ $categoria }}">
                                <div class="d-flex justify-content-between align-items-center gap-3 mb-3">
                                    <h2 class="fw-bold mb-0">{{ $categorias[$categoria] ?? ucfirst($categoria) }}</h2>
                                    <span class="badge bg-light text-primary rounded-pill">{{ $items->count() }} archivo{{ $items->count() === 1 ? '' : 's' }}</span>
                                </div>
                                <div class="row g-4">
                                    @foreach($items as $descarga)
                                        <div class="col-md-6">
                                            <article class="card cent-card h-100">
                                                <div class="card-body p-4">
                                                    <div class="d-flex gap-3">
                                                        <span class="rounded-4 bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width:52px;height:52px;">
                                                            <i class="ti ti-file-type-pdf fs-3"></i>
                                                        </span>
                                                        <div>
                                                            <h3 class="h5 fw-bold mb-1">{{ $descarga->titulo }}</h3>
                                                            @if($descarga->carrera)
                                                                <p class="small text-primary fw-bold mb-2">{{ $descarga->carrera->name }}</p>
                                                            @endif
                                                            @if($descarga->descripcion)
                                                                <p class="text-muted text-justify mb-0">{{ $descarga->descripcion }}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer bg-white border-0 p-4 pt-0">
                                                    @if($descarga->url_descarga)
                                                        <a href="{{ $descarga->url_descarga }}" target="_blank" rel="noopener" class="btn btn-cent w-100">
                                                            <i class="ti ti-download me-1"></i> Descargar
                                                        </a>
                                                    @else
                                                        <button class="btn btn-light w-100" disabled>Archivo pendiente</button>
                                                    @endif
                                                </div>
                                            </article>
                                        </div>
                                    @endforeach
                                </div>
                            </section>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
