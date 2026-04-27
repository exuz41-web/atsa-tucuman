@extends('layouts.cent-public')

@section('title', 'Preinscripción recibida')

@section('content')
<section class="py-5 py-lg-11 bg-light">
    <div class="container">
        <div class="card cent-card mx-auto overflow-hidden" style="max-width: 920px;">
            <div class="bg-cent-blue p-5 text-white text-center">
                <div class="feature-icon bg-white text-primary mx-auto mb-3"><i class="ti ti-check"></i></div>
                <h1 class="fw-bolder text-white">Preinscripción recibida</h1>
                <p class="fs-5 text-white-50 mb-0">Tu solicitud fue registrada correctamente y quedó pendiente de revisión.</p>
            </div>
            <div class="card-body p-4 p-lg-5">
                <div class="alert alert-info rounded-4">
                    Conservá este código para consultar o presentar tu trámite:
                    <strong class="d-inline-block ms-1">{{ $preinscripcion->codigo }}</strong>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="p-4 rounded-4 bg-light h-100">
                            <small class="text-muted d-block">Carrera</small>
                            <strong>{{ $preinscripcion->carrera->name }}</strong>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-4 rounded-4 bg-light h-100">
                            <small class="text-muted d-block">Sede</small>
                            <strong>{{ $preinscripcion->sede->nombre }}</strong>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-4 rounded-4 bg-light h-100">
                            <small class="text-muted d-block">Aspirante</small>
                            <strong>{{ $preinscripcion->apellido_nombre }}</strong>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-4 rounded-4 bg-light h-100">
                            <small class="text-muted d-block">Estado</small>
                            <span class="badge bg-warning-subtle text-warning">{{ ucfirst(str_replace('_', ' ', $preinscripcion->estado)) }}</span>
                        </div>
                    </div>
                </div>

                <div class="cent-muted-box p-4 mb-4">
                    <h4 class="fw-bold mb-3">Próximos pasos</h4>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="d-flex gap-3">
                                <span class="badge bg-primary rounded-pill align-self-start">1</span>
                                <p class="text-muted mb-0">Descargá la ficha PDF y conservá tu código.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex gap-3">
                                <span class="badge bg-primary rounded-pill align-self-start">2</span>
                                <p class="text-muted mb-0">La sede revisará los datos y la documentación.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex gap-3">
                                <span class="badge bg-primary rounded-pill align-self-start">3</span>
                                <p class="text-muted mb-0">Si se aprueba, se habilitará la matrícula y el acceso institucional.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 flex-wrap justify-content-center">
                    <a href="{{ route('cent.preinscripcion.ficha', $preinscripcion->codigo) }}" class="btn btn-cent">
                        <i class="ti ti-download me-1"></i> Descargar ficha PDF
                    </a>
                    <a href="{{ route('cent.preinscripcion.consulta') }}" class="btn btn-outline-cent">Consultar estado</a>
                    <a href="{{ route('cent.index') }}" class="btn btn-light">Volver al CENT</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
