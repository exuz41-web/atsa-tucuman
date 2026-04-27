@extends('layouts.cent-public')

@section('title', $carrera->name.' — CENT N°74')
@section('meta_description', 'Programa, requisitos y plan de materias de '.$carrera->name.' en el CENT N°74 de Tucumán.')

@push('styles')
<style>
    [data-aos] { opacity: 0; transform: translateY(24px); transition: opacity .5s ease, transform .5s ease; }
    [data-aos].aos-animate { opacity: 1; transform: none; }
    .career-hero-img { height: 320px; object-fit: cover; width: 100%; border-radius: 24px; }
    .materia-row { border-left: 3px solid var(--cent-sky); }
</style>
@endpush

@section('content')

@php
$careerMeta = [
    'enfermeria-profesional'                         => ['icon'=>'ti-heart-rate-monitor','gradient'=>'linear-gradient(135deg,#c0392b,#e74c3c)'],
    'tec-sup-en-agente-socio-sanitario'              => ['icon'=>'ti-heart-handshake',   'gradient'=>'linear-gradient(135deg,#1e8e6e,#27ae80)'],
    'tec-sup-en-diagnostico-por-imagenes'            => ['icon'=>'ti-scan',              'gradient'=>'linear-gradient(135deg,#1565c0,#1976d2)'],
    'tec-sup-en-farmacia'                            => ['icon'=>'ti-vaccine',            'gradient'=>'linear-gradient(135deg,#6a1b9a,#8e24aa)'],
    'tec-sup-en-laboratorio-de-analisis-clinicos'    => ['icon'=>'ti-microscope',         'gradient'=>'linear-gradient(135deg,#e65100,#ef6c00)'],
    'tec-sup-en-esterilizacion'                      => ['icon'=>'ti-shield-check',       'gradient'=>'linear-gradient(135deg,#00838f,#0097a7)'],
];
$meta   = $careerMeta[$carrera->slug] ?? ['icon'=>'ti-school','gradient'=>'linear-gradient(135deg,#1e3a5f,#2560a0)'];
$imgUrl = $carrera->imagen_url;
@endphp

{{-- ── Hero de la carrera ── --}}
<section class="py-5 bg-light">
    <div class="container py-lg-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-7" data-aos>
                <a href="{{ route('cent.carreras') }}" class="text-muted text-decoration-none small mb-3 d-inline-flex align-items-center gap-1">
                    <i class="ti ti-arrow-left"></i> Todas las carreras
                </a>
                <span class="section-badge bg-primary-subtle text-primary"><i class="ti ti-certificate me-1"></i>Programa de carrera</span>
                <h1 class="display-5 fw-bolder mb-2">{{ $carrera->name }}</h1>
                <p class="fs-5 text-muted mb-4">{{ $carrera->title_granted }} · {{ $carrera->duration }}</p>
                <div class="d-flex gap-2 flex-wrap">
                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">Modalidad presencial</span>
                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2"><i class="ti ti-heart-handshake me-1"></i>Prácticas con SIPROSA</span>
                    <span class="badge bg-info-subtle text-info rounded-pill px-3 py-2">{{ $carrera->materias->count() }} materias</span>
                    @if($sedesDeCarrera->isNotEmpty())
                        <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2">
                            <i class="ti ti-map-pin me-1"></i>{{ $sedesDeCarrera->count() }} {{ $sedesDeCarrera->count() === 1 ? 'sede' : 'sedes' }}
                        </span>
                    @endif
                </div>
            </div>
            <div class="col-lg-5" data-aos>
                @if($imgUrl)
                    <img src="{{ $imgUrl }}" alt="{{ $carrera->name }}" class="career-hero-img">
                @else
                    <div class="rounded-4 d-flex align-items-center justify-content-center" style="{{ 'background:'.$meta['gradient'].';height:320px;' }}">
                        <i class="ti {{ $meta['icon'] }} text-white" style="font-size:80px;opacity:.8;"></i>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- ── Contenido + sidebar ── --}}
<section class="py-5 py-lg-10">
    <div class="container">
        <div class="row g-5">

            {{-- Sidebar --}}
            <div class="col-lg-4">
                <div class="card cent-card sticky-top" style="top: 110px;">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-4">Ficha rápida</h4>

                        <div class="d-flex gap-3 border-bottom pb-3 mb-3">
                            <span class="rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width:42px;height:42px;"><i class="ti ti-clock"></i></span>
                            <div><small class="text-muted">Duración</small><strong class="d-block">{{ $carrera->duration }}</strong></div>
                        </div>
                        <div class="d-flex gap-3 border-bottom pb-3 mb-3">
                            <span class="rounded-circle bg-success-subtle text-success d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width:42px;height:42px;"><i class="ti ti-certificate"></i></span>
                            <div><small class="text-muted">Título</small><strong class="d-block">{{ $carrera->title_granted }}</strong></div>
                        </div>
                        <div class="d-flex gap-3 border-bottom pb-3 mb-3">
                            <span class="rounded-circle bg-info-subtle text-info d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width:42px;height:42px;"><i class="ti ti-building-hospital"></i></span>
                            <div><small class="text-muted">Modalidad</small><strong class="d-block">Presencial</strong></div>
                        </div>

                        @if($sedesDeCarrera->isNotEmpty())
                            <div class="d-flex gap-3 border-bottom pb-3 mb-3">
                                <span class="rounded-circle bg-warning-subtle text-warning d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width:42px;height:42px;"><i class="ti ti-map-pin"></i></span>
                                <div>
                                    <small class="text-muted">Se dicta en</small>
                                    <strong class="d-block">{{ $sedesDeCarrera->pluck('ciudad')->implode(' · ') }}</strong>
                                </div>
                            </div>
                        @endif

                        <h5 class="fw-bold mt-4 mb-2">Requisitos principales</h5>
                        <p class="text-muted mb-4" style="font-size:13.5px;">
                            {!! nl2br(e($carrera->requirements ?: "DNI\nEstudios secundarios completos o en trámite\nCurso de nivelación obligatorio")) !!}
                        </p>

                        <a href="{{ route('cent.preinscripcion') }}" class="btn btn-cent w-100 mb-2">
                            <i class="ti ti-user-plus me-1"></i>Preinscribirme
                        </a>
                        <a href="{{ route('cent.requisitos') }}" class="btn btn-outline-cent w-100">Ver documentación</a>
                    </div>
                </div>
            </div>

            {{-- Contenido principal --}}
            <div class="col-lg-8">

                {{-- Descripción --}}
                <div class="mb-5" data-aos>
                    <span class="section-badge bg-info-subtle text-info">Descripción</span>
                    <div class="fs-5 text-muted text-justify">
                        {!! nl2br(e(strip_tags($carrera->description))) !!}
                    </div>
                </div>

                {{-- Cards perfil + prácticas --}}
                <div class="row g-4 mb-5" data-aos>
                    <div class="col-md-6">
                        <div class="card cent-card h-100">
                            <div class="card-body p-4">
                                <div class="feature-icon bg-primary-subtle text-primary"><i class="ti ti-user-heart"></i></div>
                                <h3 class="h4 fw-bold mt-3">Perfil profesional</h3>
                                <p class="text-muted text-justify mb-0" style="font-size:14px;">Formación técnica orientada a desempeñarse con responsabilidad, criterio sanitario y compromiso con la comunidad.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card cent-card h-100">
                            <div class="card-body p-4">
                                <div class="feature-icon bg-success-subtle text-success"><i class="ti ti-briefcase"></i></div>
                                <h3 class="h4 fw-bold mt-3">Prácticas profesionalizantes</h3>
                                <p class="text-muted text-justify mb-0" style="font-size:14px;">Trayectos de práctica vinculados al sistema sanitario provincial mediante convenio con SIPROSA.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sedes --}}
                @if($sedesDeCarrera->isNotEmpty())
                <div class="mb-5" data-aos>
                    <span class="section-badge bg-primary-subtle text-primary">Sedes donde se dicta</span>
                    <div class="row g-3">
                        @foreach($sedesDeCarrera as $sede)
                        <div class="col-md-6">
                            <div class="cent-muted-box p-4 h-100">
                                <h3 class="h5 fw-bold mb-1">{{ $sede->ciudad }}</h3>
                                <div class="text-muted small">
                                    <i class="ti ti-map-pin text-primary me-1"></i>{{ $sede->direccion ?: 'Dirección a confirmar' }}
                                </div>
                                @if($sede->telefono)
                                    <div class="text-muted small mt-1">
                                        <i class="ti ti-phone text-primary me-1"></i>{{ $sede->telefono }}
                                    </div>
                                @endif
                                <a href="{{ $sede->maps_url }}" target="_blank" rel="noopener" class="small fw-bold text-primary mt-2 d-inline-block">
                                    <i class="ti ti-map me-1"></i>Cómo llegar
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Plan de estudios --}}
                <div data-aos>
                    <span class="section-badge bg-warning-subtle text-warning">Plan de estudios</span>
                    <h2 class="fw-bold mb-4">Materias por año</h2>
                    @forelse($carrera->materias->groupBy('year') as $year => $materias)
                    <div class="mb-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <span class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center fw-bolder"
                                  style="width:44px;height:44px;font-family:'Outfit',sans-serif;">{{ $year }}</span>
                            <h3 class="h5 fw-bold mb-0">{{ $year }}° año</h3>
                        </div>
                        <div class="shadow-sm rounded-4 overflow-hidden border">
                            @foreach($materias as $materia)
                            <div class="materia-row px-4 py-3 border-bottom bg-white">
                                <div class="d-flex justify-content-between gap-3 flex-wrap">
                                    <strong style="font-size:14px;">{{ $materia->name }}</strong>
                                    <span class="text-muted" style="font-size:13px;">{{ $materia->semester ?? $materia->semester.'° cuatrimestre' : 'Anual' }} · {{ $materia->hours ?: 0 }} hs</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @empty
                    <div class="alert alert-info rounded-4">El plan de materias será cargado próximamente desde el panel académico.</div>
                    @endforelse
                </div>

                {{-- Correlativas --}}
                @php $materiasConCorrelativas = $carrera->materias->filter(fn ($m) => filled($m->correlatives)); @endphp
                @if($materiasConCorrelativas->isNotEmpty())
                <div class="card cent-card mt-5" data-aos>
                    <div class="card-body p-4 p-lg-5">
                        <span class="section-badge bg-warning-subtle text-warning">Correlatividades</span>
                        <h2 class="fw-bold mb-4">Materias con correlativas</h2>
                        <div class="d-flex flex-column gap-3">
                            @foreach($materiasConCorrelativas as $materia)
                            <div class="p-3 rounded-4 bg-light">
                                <strong style="font-size:14px;">{{ $materia->name }}</strong>
                                <div class="text-muted mt-1" style="font-size:13px;">Requiere: {{ collect($materia->correlatives)->implode(', ') }}</div>
                            </div>
                            @endforeach
                        </div>
                        <p class="small text-muted mt-4 mb-0">El resto de correlatividades se confirma con el cuadro oficial disponible en la institución.</p>
                    </div>
                </div>
                @endif

                {{-- CTA --}}
                <div class="card cent-card mt-5" data-aos>
                    <div class="card-body p-4 p-lg-5">
                        <div class="row align-items-center g-4">
                            <div class="col-lg-8">
                                <h3 class="fw-bold">¿Querés cursar esta carrera?</h3>
                                <p class="text-muted mb-lg-0">Completá la preinscripción online y elegí la sede donde querés iniciar tu trayecto académico.</p>
                            </div>
                            <div class="col-lg-4 text-lg-end">
                                <a href="{{ route('cent.preinscripcion') }}" class="btn btn-cent">
                                    <i class="ti ti-user-plus me-1"></i>Preinscribirme
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    (function () {
        const els = document.querySelectorAll('[data-aos]');
        if (!els.length) return;
        const io = new IntersectionObserver((entries) => {
            entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('aos-animate'); io.unobserve(e.target); } });
        }, { threshold: 0.1 });
        els.forEach(el => io.observe(el));
    })();
</script>
@endpush
