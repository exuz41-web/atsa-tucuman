@extends('layouts.cent-public')

@section('title', 'Sedes CENT N°74')
@section('meta_description', 'Sedes educativas del CENT N°74 en Tucumán. Presencia en capital, interior y valles para acercar la formación sanitaria a toda la provincia.')

@section('content')
<section class="py-5 bg-light">
    <div class="container py-lg-5">
        <div class="row align-items-end g-4">
            <div class="col-lg-8">
                <span class="section-badge bg-info-subtle text-info"><i class="ti ti-map-2 me-1"></i>Sedes educativas</span>
                <h1 class="display-5 fw-bolder">CENT N°74 en toda la provincia</h1>
                <p class="fs-5 text-muted text-justify mb-0">
                    La propuesta del CENT se despliega en capital y el interior para acercar formación sanitaria a cada comunidad tucumana.
                </p>
            </div>
            <div class="col-lg-4">
                <div class="card cent-card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3">
                            <span class="feature-icon bg-primary-subtle text-primary flex-shrink-0"><i class="ti ti-map-pin"></i></span>
                            <div>
                                <h2 class="fw-bolder text-primary mb-0">{{ $sedes->count() }}</h2>
                                <p class="text-muted mb-0">Sedes activas en Tucumán</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 py-lg-10">
    <div class="container">
        <div class="row g-4">
            @foreach($sedes as $sede)
                @php $sedeCarreras = $sede->carreras->isNotEmpty() ? $sede->carreras : collect(); @endphp
                <div class="col-md-6 col-xl-4">
                    <div class="card cent-card h-100 overflow-hidden">
                        <div class="position-relative">
                            <img src="{{ $sede->imagen_url }}" alt="{{ $sede->nombre }}" class="w-100" style="height: 210px; object-fit: cover;" loading="lazy">
                            <span class="badge bg-white text-primary position-absolute top-0 start-0 m-3 rounded-pill px-3 py-2 fw-bold">{{ $sede->ciudad }}</span>
                        </div>
                        <div class="card-body p-4">
                            <h3 class="fw-bold mb-2">{{ $sede->nombre }}</h3>

                            <div class="d-flex flex-column gap-3">
                                <div class="d-flex gap-3">
                                    <span class="rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px;"><i class="ti ti-map-pin"></i></span>
                                    <div>
                                        <small class="text-muted d-block">Dirección</small>
                                        <strong>{{ $sede->direccion ?: 'A confirmar' }}</strong>
                                    </div>
                                </div>

                                @if($sede->telefono)
                                    <div class="d-flex gap-3">
                                        <span class="rounded-circle bg-info-subtle text-info d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px;"><i class="ti ti-phone"></i></span>
                                        <div>
                                            <small class="text-muted d-block">Teléfono</small>
                                            <strong>{{ $sede->telefono }}</strong>
                                        </div>
                                    </div>
                                @endif

                                @if($sedeCarreras->isNotEmpty())
                                    <div class="d-flex gap-3">
                                        <span class="rounded-circle bg-success-subtle text-success d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px;"><i class="ti ti-school"></i></span>
                                        <div>
                                            <small class="text-muted d-block">Carreras disponibles</small>
                                            <div class="d-flex flex-wrap gap-2 mt-1">
                                                @foreach($sedeCarreras->take(4) as $carrera)
                                                    <span class="badge bg-light text-primary border">{{ $carrera->name }}</span>
                                                @endforeach
                                                @if($sedeCarreras->count() > 4)
                                                    <span class="badge bg-light text-muted border">+{{ $sedeCarreras->count() - 4 }} más</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="d-flex gap-3">
                                        <span class="rounded-circle bg-warning-subtle text-warning d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px;"><i class="ti ti-info-circle"></i></span>
                                        <div>
                                            <small class="text-muted d-block">Oferta académica</small>
                                            <strong class="text-muted">Consultar disponibilidad</strong>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer bg-white border-0 p-4 pt-0">
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ $sede->maps_url }}" target="_blank" rel="noopener" class="btn btn-outline-cent flex-fill">
                                    <i class="ti ti-route me-1"></i>Cómo llegar
                                </a>
                                <a href="{{ route('cent.preinscripcion') }}" class="btn btn-cent flex-fill">
                                    <i class="ti ti-user-plus me-1"></i>Preinscribirme
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <div class="card cent-card">
            <div class="card-body p-4 p-lg-5">
                <div class="row align-items-center g-4">
                    <div class="col-lg-8">
                        <span class="section-badge bg-primary-subtle text-primary">Ingreso</span>
                        <h2 class="fw-bolder">Elegí la sede más cercana para iniciar tu formación</h2>
                        <p class="text-muted text-justify mb-lg-0">
                            La preinscripción online te permite seleccionar carrera y sede. Luego el equipo académico revisa la solicitud, la documentación y te informa los pasos siguientes.
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <a href="{{ route('cent.preinscripcion') }}" class="btn btn-cent btn-lg">
                            <i class="ti ti-user-plus me-2"></i>Preinscribirme
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
