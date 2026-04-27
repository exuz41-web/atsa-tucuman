@extends('layouts.cent-public')

@section('title', 'Carreras — CENT N°74 · Tucumán')
@section('meta_description', 'Oferta académica del CENT N°74: carreras técnicas superiores en salud con modalidad presencial y prácticas profesionalizantes.')

@push('styles')
<style>
    [data-aos] {
        opacity: 0;
        transform: translateY(24px);
        transition: opacity .5s ease, transform .5s ease;
    }

    [data-aos].aos-animate {
        opacity: 1;
        transform: none;
    }

    .career-banner {
        border-radius: 28px;
        overflow: hidden;
        background: linear-gradient(135deg, #102338, #1e3a5f);
        color: #fff;
    }

    .career-card-top {
        min-height: 178px;
        display: flex;
        align-items: end;
        padding: 22px;
        color: #fff;
    }
</style>
@endpush

@section('content')
<section class="py-5 bg-light">
    <div class="container py-lg-5">
        <div class="row align-items-end g-4">
            <div class="col-lg-8" data-aos>
                <span class="section-badge bg-primary-subtle text-primary"><i class="ti ti-school me-1"></i>Oferta académica</span>
                <h1 class="display-5 fw-bolder mb-2">Carreras del CENT N°74</h1>
                <p class="fs-5 text-muted text-justify mb-0">
                    Formación técnica superior en ciencias de la salud, reconocida oficialmente y con fuerte inserción territorial en Tucumán.
                </p>
            </div>
            <div class="col-lg-4" data-aos>
                <div class="card cent-card">
                    <div class="card-body p-4">
                        <div class="row g-3 text-center">
                            <div class="col-6">
                                <h3 class="fw-bolder text-primary mb-0">{{ $carreras->count() }}</h3>
                                <p class="text-muted mb-0" style="font-size:13px;">Carreras</p>
                            </div>
                            <div class="col-6">
                                <h3 class="fw-bolder text-success mb-0">{{ $sedesCount }}</h3>
                                <p class="text-muted mb-0" style="font-size:13px;">Sedes activas</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="career-banner p-4 p-lg-5 mb-5" data-aos>
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <span class="badge bg-white text-primary rounded-pill px-3 py-2 fw-bold mb-3">Perfil institucional</span>
                    <h2 class="display-6 fw-bolder text-white mb-3">Carreras pensadas para el sistema sanitario real</h2>
                    <p class="mb-0" style="color: rgba(255,255,255,.78); font-size: 18px;">
                        Cada propuesta combina formación técnica, práctica profesionalizante y preparación concreta para hospitales, clínicas, laboratorios, farmacias y centros de salud.
                    </p>
                </div>
                <div class="col-lg-4">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="p-3 rounded-4 text-center h-100" style="background: rgba(255,255,255,.1);">
                                <div class="fw-bolder text-white fs-3">3</div>
                                <div style="color: rgba(255,255,255,.72); font-size: 13px;">Años promedio</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded-4 text-center h-100" style="background: rgba(255,255,255,.1);">
                                <div class="fw-bolder text-white fs-3">100%</div>
                                <div style="color: rgba(255,255,255,.72); font-size: 13px;">Presencial</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @php
            $careerMeta = [
                'enfermeria-profesional' => ['icon' => 'ti-heart-rate-monitor', 'gradient' => 'linear-gradient(135deg,#c0392b,#e74c3c)'],
                'tec-sup-en-agente-socio-sanitario' => ['icon' => 'ti-heart-handshake', 'gradient' => 'linear-gradient(135deg,#1e8e6e,#27ae80)'],
                'tec-sup-en-diagnostico-por-imagenes' => ['icon' => 'ti-scan', 'gradient' => 'linear-gradient(135deg,#1565c0,#1976d2)'],
                'tec-sup-en-farmacia' => ['icon' => 'ti-vaccine', 'gradient' => 'linear-gradient(135deg,#6a1b9a,#8e24aa)'],
                'tec-sup-en-laboratorio-de-analisis-clinicos' => ['icon' => 'ti-microscope', 'gradient' => 'linear-gradient(135deg,#e65100,#ef6c00)'],
                'tec-sup-en-esterilizacion' => ['icon' => 'ti-shield-check', 'gradient' => 'linear-gradient(135deg,#00838f,#0097a7)'],
            ];
        @endphp

        <div class="row g-4">
            @forelse($carreras as $carrera)
                @php $meta = $careerMeta[$carrera->slug] ?? ['icon' => 'ti-school', 'gradient' => 'linear-gradient(135deg,#1e3a5f,#2560a0)']; @endphp
                <div class="col-md-6 col-xl-4" data-aos>
                    <div class="card cent-card h-100 overflow-hidden">
                        <div class="career-card-top" style="background: {{ $meta['gradient'] }};">
                            <div class="w-100 d-flex align-items-center justify-content-between">
                                <span class="rounded-3 d-inline-flex align-items-center justify-content-center" style="width:56px;height:56px;background:rgba(255,255,255,.15);font-size:27px;">
                                    <i class="ti {{ $meta['icon'] }}"></i>
                                </span>
                                <span class="badge bg-white text-primary rounded-pill px-3 py-2">{{ $carrera->duration }}</span>
                            </div>
                        </div>
                        <div class="card-body p-4 d-flex flex-column">
                            <h3 class="fw-bold mb-1" style="font-size:18px;">{{ $carrera->name }}</h3>
                            <p class="text-muted mb-2" style="font-size:13px;"><i class="ti ti-certificate me-1"></i>{{ $carrera->title_granted }}</p>
                            <p class="text-muted mb-4 flex-grow-1" style="font-size:14px;">
                                {{ \Illuminate\Support\Str::limit(strip_tags($carrera->description), 160) }}
                            </p>
                            <div class="d-flex align-items-center justify-content-between border-top pt-3">
                                <span class="text-muted" style="font-size:12px;">
                                    <i class="ti ti-books me-1"></i>{{ $carrera->materias_count }} materias
                                </span>
                                <a href="{{ route('cent.carrera', $carrera) }}" class="btn btn-sm btn-outline-cent">
                                    Ver programa <i class="ti ti-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info rounded-4">Las carreras se cargarán desde el panel administrativo.</div>
                </div>
            @endforelse
        </div>
    </div>
</section>

<section class="py-5 bg-cent-blue text-white">
    <div class="container text-center" data-aos>
        <h2 class="fw-bolder text-white display-6">¿Querés empezar tu formación?</h2>
        <p class="fs-5 mb-5 mx-auto" style="color:rgba(255,255,255,.75);max-width:640px;">
            Completá la preinscripción online, elegí carrera y sede, y seguí todo el proceso desde la plataforma institucional.
        </p>
        <a href="{{ route('cent.preinscripcion') }}" class="btn btn-light btn-lg px-5 fw-bold rounded-3 me-2">
            <i class="ti ti-user-plus me-2"></i>Iniciar preinscripción
        </a>
        <a href="{{ route('cent.requisitos') }}" class="btn btn-outline-light btn-lg px-4 rounded-3">
            Ver requisitos
        </a>
    </div>
</section>
@endsection

@push('scripts')
<script>
    (function () {
        const els = document.querySelectorAll('[data-aos]');
        if (!els.length) return;
        const io = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('aos-animate');
                    io.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12 });
        els.forEach(el => io.observe(el));
    })();
</script>
@endpush
