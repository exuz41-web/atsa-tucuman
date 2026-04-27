@extends('layouts.app')

@section('title', 'Filiales | ATSA Tucumán')

@php
    $filialesAtsa = collect([
        (object) [
            'name' => 'Central Ciudad Deportiva',
            'address' => 'Paraguay y Thames, San Miguel de Tucumán',
            'phone' => '0381 4331665',
            'whatsapp' => '543814331665',
            'image_url' => asset('images/filiales/central-ciudad-deportiva.jpg'),
            'schedule' => 'Lun a Vie: 8:00 - 16:00 hs',
            'responsible' => 'Secretaría General',
            'color' => 'primary',
        ],
        (object) [
            'name' => 'Filial del Sur',
            'address' => 'Julio Argentino Roca 371, Concepción, Tucumán',
            'phone' => '03865 421030',
            'whatsapp' => null,
            'image_url' => asset('images/filiales/filial-sur-concepcion.jpg'),
            'schedule' => 'Martes y Jueves: 9:00 - 13:00 hs',
            'responsible' => 'Zona Sur',
            'color' => 'success',
        ],
        (object) [
            'name' => 'Filial Este',
            'address' => 'Cam. del Carmen 90, Banda del Río Salí',
            'phone' => '381 5677170',
            'whatsapp' => '543815677170',
            'image_url' => asset('images/filiales/filial-este-banda.jpg'),
            'schedule' => 'Miércoles: 9:00 - 13:00 hs',
            'responsible' => 'Zona Este',
            'color' => 'warning',
        ],
    ]);

    $centFiliales = [
        'Capital - San Miguel de Tucumán', 'Trancas', 'Delfín Gallo', 'Banda del Río Salí',
        'Concepción', 'Los Ralos', 'Simoca', 'Santa Rosa de Leales',
        'Tafí Viejo', 'Lules', 'Graneros', 'Aguilares',
        'La Ramada', 'Amaicha del Valle', 'Famaillá', 'Monteros',
    ];
@endphp

@section('content')
    {{-- HERO MODERNIZADO --}}
    <section class="atsa-page-hero py-14 position-relative" style="background-image: url('{{ asset('images/filiales/central-ciudad-deportiva.jpg') }}'); min-height: 500px;">
        <div class="container-fluid text-center position-relative z-1">
            <div class="section-badge bg-white bg-opacity-20 text-white mb-3">
                <i class="ti ti-building"></i>
                FILIALES
            </div>
            <h1 class="fw-bolder display-4 text-white mb-4">Presencia territorial</h1>
            <p class="fs-5 text-white text-opacity-80 mb-0 col-lg-6 mx-auto">Filiales gremiales de ATSA Tucumán y sedes formativas del CENT N°74 en toda la provincia</p>
        </div>
        <div class="position-absolute bottom-0 start-0 w-100">
            <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 80L60 72C120 64 240 48 360 40C480 32 600 32 720 36C840 40 960 48 1080 52C1200 56 1320 56 1380 56L1440 56V80H1380C1320 80 1200 80 1080 80C960 80 840 80 720 80C600 80 480 80 360 80C240 80 120 80 60 80H0Z" fill="white"/>
            </svg>
        </div>
    </section>

    {{-- FILIALES PRINCIPALES CARDS --}}
    <section class="py-10 py-lg-16">
        <div class="container-fluid">
            <div class="text-center mb-10">
                <div class="section-badge bg-primary-subtle text-primary mb-3">
                    <i class="ti ti-map-pin"></i>
                    ATSA TUCUMÁN
                </div>
                <h2 class="fw-bolder fs-9 mb-3">Filiales gremiales</h2>
                <p class="text-muted fs-5 col-lg-6 mx-auto">Atención sindical, trámites gremiales y acompañamiento al afiliado</p>
            </div>

            <div class="row g-4">
                @foreach ($filialesAtsa as $filial)
                    <div class="col-lg-4 col-md-6">
                        <div class="feature-card border-0 h-100 overflow-hidden">
                            {{-- Imagen --}}
                            <div class="position-relative overflow-hidden" style="height: 220px;">
                                @if (! empty($filial->image_url))
                                    <img src="{{ $filial->image_url }}" alt="{{ $filial->name }}" class="w-100 h-100 object-fit-cover transition-transform hover-scale">
                                @else
                                    <div class="w-100 h-100 bg-{{ $filial->color }}-subtle d-flex align-items-center justify-content-center">
                                        <i class="ti ti-building text-{{ $filial->color }}" style="font-size: 80px; opacity: 0.3;"></i>
                                    </div>
                                @endif
                                <div class="position-absolute top-0 start-0 m-4">
                                    <span class="badge bg-{{ $filial->color }} text-white fs-2 fw-bold px-3 py-2">
                                        {{ $filial->responsible }}
                                    </span>
                                </div>
                            </div>

                            {{-- Header con color --}}
                            <div class="bg-atsa-blue p-5" style="background: linear-gradient(135deg, #1e3a5f 0%, #2a3547 100%);">
                                <h3 class="text-white fw-bold mb-1">{{ $filial->name }}</h3>
                                <p class="text-white text-opacity-70 fs-4 mb-0">{{ $filial->address }}</p>
                            </div>

                            {{-- Info --}}
                            <div class="card-body p-6">
                                <div class="d-flex flex-column gap-3">
                                    @if ($filial->phone)
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="stat-icon bg-success-subtle text-success" style="width: 44px; height: 44px;">
                                                <i class="ti ti-phone"></i>
                                            </div>
                                            <div>
                                                <p class="text-muted fs-3 mb-0">Teléfono</p>
                                                <p class="fw-semibold mb-0">{{ $filial->phone }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="d-flex align-items-center gap-3">
                                        <div class="stat-icon bg-warning-subtle text-warning" style="width: 44px; height: 44px;">
                                            <i class="ti ti-clock"></i>
                                        </div>
                                        <div>
                                            <p class="text-muted fs-3 mb-0">Horario</p>
                                            <p class="fw-semibold mb-0">{{ $filial->schedule }}</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Botones --}}
                                <div class="d-flex gap-2 mt-5 pt-4 border-top">
                                    @if ($filial->whatsapp)
                                        <a href="https://wa.me/{{ preg_replace('/\D+/', '', $filial->whatsapp) }}" target="_blank" class="btn btn-success flex-grow-1 py-6">
                                            <i class="ti ti-brand-whatsapp me-2"></i>WhatsApp
                                        </a>
                                    @endif
                                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($filial->address) }}" target="_blank" class="btn btn-outline-primary flex-grow-1 py-6">
                                        <i class="ti ti-map-pin me-2"></i>Cómo llegar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- MAPA GOOGLE MODERNIZADO --}}
    <section class="py-10 py-lg-16 bg-light">
        <div class="container-fluid">
            <div class="card feature-card border-0 overflow-hidden">
                <div class="card-body p-0">
                    <iframe src="https://www.google.com/maps?q=Paraguay%20y%20Thames%2C%20San%20Miguel%20de%20Tucum%C3%A1n&output=embed" width="100%" height="500" style="border:0; filter: grayscale(0.2) contrast(1.1);" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </section>

    {{-- SEDES CENT --}}
    <section class="py-10 py-lg-16">
        <div class="container-fluid">
            <div class="text-center mb-10">
                <div class="section-badge bg-primary-subtle text-primary mb-3">
                    <i class="ti ti-school"></i>
                    CENT N°74
                </div>
                <h2 class="fw-bolder fs-9 mb-3">Filiales y sedes de formación</h2>
                <p class="text-muted fs-5 col-lg-6 mx-auto">Localidades donde el CENT articula propuestas educativas y administrativas</p>
            </div>

            <div class="row g-3">
                @foreach ($centFiliales as $index => $sede)
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="feature-card bg-light border-0 p-5 h-100 d-flex align-items-center gap-3">
                            <div class="stat-icon bg-primary-subtle text-primary flex-shrink-0" style="width: 48px; height: 48px; font-size: 20px;">
                                <span class="fw-bold">{{ $index + 1 }}</span>
                            </div>
                            <h5 class="fw-semibold mb-0">{{ $sede }}</h5>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA CONTACTO --}}
    <section class="py-10 py-lg-16">
        <div class="container-fluid">
            <div class="card feature-card border-0 text-center p-8 p-lg-12" style="background: linear-gradient(135deg, #1e3a5f 0%, #2a3547 100%);">
                <div class="d-inline-flex align-items-center justify-content-center gap-2 rounded-pill px-4 py-2 mb-4 mx-auto" style="width:auto;max-width:max-content;background:rgba(73,190,255,.16);border:1px solid rgba(73,190,255,.35);color:#d8f4ff;font-size:13px;font-weight:800;letter-spacing:.08em;">
                    <i class="ti ti-headset"></i>
                    CONTACTO
                </div>
                <h2 class="fw-bolder fs-8 text-white mb-4">¿Necesitás más información?</h2>
                <p class="text-white text-opacity-80 fs-5 col-lg-6 mx-auto mb-6">Contactanos por teléfono, WhatsApp o acercate a cualquiera de nuestras filiales</p>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="tel:03814331665" class="btn btn-light btn-lg py-8 px-10 fw-bold text-primary">
                        <i class="ti ti-phone me-2"></i>0381 4331665
                    </a>
                    <a href="https://wa.me/543814331665" target="_blank" class="btn btn-success btn-lg py-8 px-10 fw-bold">
                        <i class="ti ti-brand-whatsapp me-2"></i>WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
