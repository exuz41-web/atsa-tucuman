@extends('layouts.cent-public')

@section('title', 'Contacto CENT N°74')
@section('meta_description', 'Contacto, sede principal y sedes educativas del CENT N°74 en Tucumán.')

@section('content')
<section class="py-5 bg-light">
    <div class="container py-lg-5">
        <span class="section-badge bg-primary-subtle text-primary"><i class="ti ti-phone me-1"></i>Contacto</span>
        <h1 class="display-5 fw-bolder">Comunicate con el CENT N°74</h1>
        <p class="fs-5 text-muted text-justify mb-0">
            Consultas sobre carreras, sedes, preinscripción, documentación, horarios y portal académico.
        </p>
    </div>
</section>

<section class="py-5 py-lg-10">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-5">
                <div class="card cent-card h-100">
                    <div class="card-body p-4 p-lg-5">
                        <h2 class="fw-bold mb-4">Atención académica</h2>
                        <div class="d-flex flex-column gap-4">
                            <div class="d-flex gap-3">
                                <span class="rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;"><i class="ti ti-building"></i></span>
                                <div>
                                    <small class="text-muted">Sede principal</small>
                                    <strong class="d-block">{{ $siteSetting?->address ?: 'Ciudad Deportiva ATSA, Paraguay y Thames, San Miguel de Tucumán' }}</strong>
                                </div>
                            </div>
                            <div class="d-flex gap-3">
                                <span class="rounded-circle bg-info-subtle text-info d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;"><i class="ti ti-phone"></i></span>
                                <div>
                                    <small class="text-muted">Teléfono</small>
                                    <strong class="d-block">{{ $siteSetting?->phone ?: '0381 4332175' }}</strong>
                                </div>
                            </div>
                            @if($siteSetting?->whatsapp)
                                <div class="d-flex gap-3">
                                    <span class="rounded-circle bg-success-subtle text-success d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;"><i class="ti ti-brand-whatsapp"></i></span>
                                    <div>
                                        <small class="text-muted">WhatsApp</small>
                                        <a href="https://wa.me/{{ preg_replace('/\D/', '', $siteSetting->whatsapp) }}" target="_blank" rel="noopener" class="fw-bold d-block text-success">Enviar mensaje</a>
                                    </div>
                                </div>
                            @endif
                            @if($siteSetting?->email)
                                <div class="d-flex gap-3">
                                    <span class="rounded-circle bg-warning-subtle text-warning d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;"><i class="ti ti-mail"></i></span>
                                    <div>
                                        <small class="text-muted">Correo electrónico</small>
                                        <a href="mailto:{{ $siteSetting->email }}" class="fw-bold d-block">{{ $siteSetting->email }}</a>
                                    </div>
                                </div>
                            @endif
                            <div class="d-flex gap-3">
                                <span class="rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;"><i class="ti ti-clock"></i></span>
                                <div>
                                    <small class="text-muted">Horarios de atención</small>
                                    <strong class="d-block">{{ $siteSetting?->schedule ?: 'Lunes a viernes de 8:00 a 16:00 hs' }}</strong>
                                </div>
                            </div>
                            <div class="d-flex gap-3">
                                <span class="rounded-circle bg-danger-subtle text-danger d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;"><i class="ti ti-file-download"></i></span>
                                <div>
                                    <small class="text-muted">Ficha aspirantes</small>
                                    <a href="https://cent74atsatucuman.ar/FICHA%20DE%20INSCRIPCION%20ASPIRANTES%202026.pdf" target="_blank" rel="noopener" class="fw-bold d-block">Descargar PDF 2026</a>
                                </div>
                            </div>
                        </div>

                        @if(($siteSetting?->facebook_url) || ($siteSetting?->instagram_url) || ($siteSetting?->youtube_url))
                            <hr>
                            <h5 class="fw-bold mb-3">Seguinos en redes</h5>
                            <div class="d-flex gap-2">
                                @if($siteSetting?->facebook_url)
                                    <a href="{{ $siteSetting->facebook_url }}" target="_blank" rel="noopener" class="btn btn-outline-secondary btn-sm rounded-circle" style="width:40px;height:40px;padding:0;display:inline-flex;align-items:center;justify-content:center;">
                                        <i class="ti ti-brand-facebook fs-5"></i>
                                    </a>
                                @endif
                                @if($siteSetting?->instagram_url)
                                    <a href="{{ $siteSetting->instagram_url }}" target="_blank" rel="noopener" class="btn btn-outline-secondary btn-sm rounded-circle" style="width:40px;height:40px;padding:0;display:inline-flex;align-items:center;justify-content:center;">
                                        <i class="ti ti-brand-instagram fs-5"></i>
                                    </a>
                                @endif
                                @if($siteSetting?->youtube_url)
                                    <a href="{{ $siteSetting->youtube_url }}" target="_blank" rel="noopener" class="btn btn-outline-secondary btn-sm rounded-circle" style="width:40px;height:40px;padding:0;display:inline-flex;align-items:center;justify-content:center;">
                                        <i class="ti ti-brand-youtube fs-5"></i>
                                    </a>
                                @endif
                            </div>
                        @endif

                        <hr>
                        <div class="d-grid gap-2">
                            <a href="{{ route('cent.preinscripcion') }}" class="btn btn-cent">
                                <i class="ti ti-user-plus me-2"></i>Iniciar preinscripción
                            </a>
                            <a href="{{ route('cent.descargas') }}" class="btn btn-outline-cent">
                                <i class="ti ti-download me-2"></i>Ver descargas útiles
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card cent-card mb-4">
                    <div class="card-body p-4 p-lg-5">
                        <h2 class="fw-bold mb-4">Sedes de consulta</h2>
                        <div class="row g-3">
                            @foreach($sedes as $sede)
                                <div class="col-md-6">
                                    <div class="p-3 rounded-4 bg-light h-100">
                                        <strong>{{ $sede->nombre }}</strong>
                                        <div class="text-muted small">{{ $sede->ciudad }}</div>
                                        <div class="small mt-2"><i class="ti ti-map-pin text-primary me-1"></i>{{ $sede->direccion ?: 'Dirección a confirmar' }}</div>
                                        @if($sede->telefono)
                                            <div class="small mt-1"><i class="ti ti-phone text-primary me-1"></i>{{ $sede->telefono }}</div>
                                        @endif
                                        @if($sede->whatsapp_url)
                                            <a href="{{ $sede->whatsapp_url }}" target="_blank" rel="noopener" class="small fw-bold text-success mt-1 d-inline-block"><i class="ti ti-brand-whatsapp me-1"></i>WhatsApp</a>
                                        @endif
                                        <a href="{{ $sede->maps_url }}" target="_blank" rel="noopener" class="small fw-bold text-primary mt-1 d-block">
                                            <i class="ti ti-map me-1"></i>Cómo llegar
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="card cent-card">
                    <div class="card-body p-0 overflow-hidden">
                        <iframe
                            src="https://www.google.com/maps?q=Paraguay%20y%20Thames%20San%20Miguel%20de%20Tucuman&output=embed"
                            width="100%"
                            height="380"
                            style="border:0;"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
