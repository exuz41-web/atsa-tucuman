@extends('layouts.cent-public')

@section('title', 'CENT N°74 — Formación técnica superior en salud · Tucumán')
@section('meta_description', 'Centro Educativo de Nivel Terciario N°74 de ATSA Tucumán. Carreras técnicas en salud, sedes en toda la provincia y preinscripción online.')

@push('styles')
<style>
    .hero-soft-card {
        border-radius: 28px;
        background: rgba(255, 255, 255, 0.94);
        box-shadow: 0 18px 48px rgba(14, 34, 54, 0.16);
    }

    .hero-stat-dot {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        font-family: 'Outfit', sans-serif;
    }

    .quick-link-card {
        border-radius: 24px;
        background: #fff;
        border: 1px solid #e5eaef;
        box-shadow: 0 12px 32px rgba(30, 58, 95, 0.06);
        padding: 24px;
        height: 100%;
        transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease;
    }

    .quick-link-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 44px rgba(30, 58, 95, 0.12);
        border-color: rgba(73, 190, 255, .45);
    }

    .quick-link-icon {
        width: 58px;
        height: 58px;
        border-radius: 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        margin-bottom: 18px;
    }

    .timeline-item {
        position: relative;
        padding-left: 58px;
    }

    .timeline-item:not(:last-child) {
        padding-bottom: 28px;
    }

    .timeline-item:not(:last-child)::before {
        content: '';
        position: absolute;
        left: 24px;
        top: 48px;
        bottom: 0;
        width: 2px;
        background: #dfe8f1;
    }

    .timeline-num {
        position: absolute;
        left: 0;
        top: 0;
        width: 48px;
        height: 48px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--cent-blue), var(--cent-sky));
        color: #fff;
        font-weight: 900;
        font-family: 'Outfit', sans-serif;
    }

    [data-aos] {
        opacity: 0;
        transform: translateY(24px);
        transition: opacity .5s ease, transform .5s ease;
    }

    [data-aos].aos-animate {
        opacity: 1;
        transform: none;
    }
</style>
@endpush

@section('content')
<section class="cent-hero d-flex align-items-center" style="background-image:url('{{ asset('images/historia/formacion-cent-74.jpg') }}'); min-height: 760px;">
    <div class="container py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-8">
                <span class="badge bg-white text-primary rounded-pill px-4 py-2 mb-4 fw-bold" style="font-size:13px;">
                    <i class="ti ti-certificate me-1"></i> Centro Educativo de Nivel Terciario N°74
                </span>
                <h1 class="display-2 fw-bolder text-white mb-4 lh-1">
                    Formación técnica<br>superior en salud
                </h1>
                <p class="fs-5 mb-5 text-justify" style="color:rgba(255,255,255,.82); max-width: 700px;">
                    El CENT N°74 forma profesionales para el sistema sanitario tucumano con carreras presenciales, prácticas profesionalizantes y sedes en toda la provincia. Es una propuesta educativa abierta a la comunidad.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('cent.preinscripcion') }}" class="btn btn-light btn-lg px-5 fw-bold rounded-3">
                        <i class="ti ti-user-plus me-2"></i>Preinscribirme
                    </a>
                    <a href="{{ route('cent.carreras') }}" class="btn btn-outline-light btn-lg px-4 rounded-3">
                        <i class="ti ti-school me-2"></i>Ver carreras
                    </a>
                    <a href="{{ route('cent.login') }}" class="btn btn-outline-light btn-lg px-4 rounded-3">
                        <i class="ti ti-login me-2"></i>Portal académico
                    </a>
                </div>
            </div>
            <div class="col-lg-4 d-none d-lg-block">
                <div class="hero-soft-card ms-auto p-4" style="max-width: 300px;">
                    <h6 class="fw-bold text-muted mb-4" style="font-size:11px;text-transform:uppercase;letter-spacing:1px;">CENT N°74 en números</h6>
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <span class="hero-stat-dot bg-primary-subtle text-primary">{{ $carreras->count() }}</span>
                            <div>
                                <strong class="d-block">Carreras técnicas</strong>
                                <small class="text-muted">Formación oficial</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <span class="hero-stat-dot bg-success-subtle text-success">{{ $sedes->count() }}</span>
                            <div>
                                <strong class="d-block">Sedes educativas</strong>
                                <small class="text-muted">Presencia territorial</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <span class="hero-stat-dot bg-info-subtle text-info">3</span>
                            <div>
                                <strong class="d-block">Años de cursado</strong>
                                <small class="text-muted">Modalidad presencial</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-4 bg-white border-bottom">
    <div class="container">
        <div class="row g-3 text-center">
            <div class="col-6 col-lg-3">
                <div class="p-3">
                    <h2 class="fw-bolder text-primary mb-1">{{ $carreras->count() }}</h2>
                    <p class="text-muted mb-0" style="font-size:14px;">Carreras técnicas</p>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="p-3">
                    <h2 class="fw-bolder text-primary mb-1">{{ $sedes->count() }}</h2>
                    <p class="text-muted mb-0" style="font-size:14px;">Sedes educativas</p>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="p-3">
                    <h2 class="fw-bolder text-primary mb-1">3</h2>
                    <p class="text-muted mb-0" style="font-size:14px;">Años de duración</p>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="p-3">
                    <h2 class="fw-bolder text-primary mb-1">80%</h2>
                    <p class="text-muted mb-0" style="font-size:14px;">Asistencia mínima</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 py-lg-10">
    <div class="container">
        <div class="row align-items-end g-4 mb-4">
            <div class="col-lg-7" data-aos>
                <span class="section-badge bg-info-subtle text-info"><i class="ti ti-layout-grid me-1"></i>Accesos rápidos</span>
                <h2 class="fw-bolder display-6 mb-2">Todo el circuito del CENT en un solo lugar</h2>
                <p class="text-muted fs-5 text-justify mb-0">
                    La portada conecta aspirantes, alumnos y familias con carreras, sedes, documentación, horarios, mesas, descargas y acceso al portal académico.
                </p>
            </div>
        </div>
        <div class="row g-4">
            @foreach([
                ['route' => route('cent.preinscripcion'), 'icon' => 'ti-user-plus', 'color' => 'primary', 'title' => 'Preinscripción online', 'text' => 'Completá el formulario, adjuntá documentación y descargá tu ficha institucional.'],
                ['route' => route('cent.carreras'), 'icon' => 'ti-school', 'color' => 'success', 'title' => 'Oferta académica', 'text' => 'Conocé duración, perfil profesional, materias, correlatividades y sedes por carrera.'],
                ['route' => route('cent.horarios'), 'icon' => 'ti-calendar-time', 'color' => 'info', 'title' => 'Horarios de cursado', 'text' => 'Consultá publicaciones vigentes por sede, carrera y ciclo lectivo.'],
                ['route' => route('cent.descargas'), 'icon' => 'ti-download', 'color' => 'warning', 'title' => 'Descargas institucionales', 'text' => 'Planes, reglamentos, formularios y archivos útiles del CENT N°74.'],
            ] as $item)
                <div class="col-md-6 col-xl-3" data-aos>
                    <a href="{{ $item['route'] }}" class="quick-link-card d-block text-decoration-none">
                        <span class="quick-link-icon bg-{{ $item['color'] }}-subtle text-{{ $item['color'] }}">
                            <i class="ti {{ $item['icon'] }}"></i>
                        </span>
                        <h3 class="h5 fw-bold text-dark mb-2">{{ $item['title'] }}</h3>
                        <p class="text-muted mb-0" style="font-size:14px;">{{ $item['text'] }}</p>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="py-5 py-lg-11">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-5" data-aos>
                <span class="section-badge bg-primary-subtle text-primary"><i class="ti ti-building-hospital me-1"></i>Institución</span>
                <h2 class="fw-bolder display-6">Un proyecto educativo para la salud tucumana</h2>
                <p class="fs-5 text-muted text-justify">
                    El CENT N°74 depende de ATSA Tucumán y está reconocido por el Ministerio de Educación de la Provincia. Su propuesta combina formación técnica, compromiso social y anclaje territorial.
                </p>
                <div class="cent-muted-box p-4 mt-4">
                    <h3 class="h5 fw-bold mb-2"><i class="ti ti-target me-2 text-primary"></i>Misión institucional</h3>
                    <p class="text-muted text-justify mb-0">
                        Formar técnicos superiores competentes, con ética profesional, sensibilidad social y herramientas concretas para desempeñarse en los servicios de salud públicos y privados.
                    </p>
                </div>
                <a href="{{ route('cent.requisitos') }}" class="btn btn-cent mt-4">
                    <i class="ti ti-file-check me-2"></i>Ver requisitos de ingreso
                </a>
            </div>
            <div class="col-lg-7" data-aos>
                <div class="row g-4">
                    @foreach([
                        ['icon' => 'ti-stethoscope', 'color' => 'primary', 'title' => 'Carreras sanitarias', 'text' => 'Enfermería, Farmacia, Laboratorio, Diagnóstico por Imágenes, Esterilización y Agente Socio Sanitario.'],
                        ['icon' => 'ti-map-pin', 'color' => 'info', 'title' => 'Presencia territorial', 'text' => 'Capital, Concepción, Banda del Río Salí, Famaillá, Aguilares, Amaicha del Valle, Tafí Viejo, Lules y más.'],
                        ['icon' => 'ti-heart-handshake', 'color' => 'success', 'title' => 'Prácticas reales', 'text' => 'Las prácticas profesionalizantes se articulan con establecimientos sanitarios del sistema provincial.'],
                        ['icon' => 'ti-clipboard-check', 'color' => 'warning', 'title' => 'Ingreso organizado', 'text' => 'Curso propedéutico con asistencia obligatoria y evaluación por áreas para ordenar el ingreso.'],
                    ] as $pillar)
                        <div class="col-md-6">
                            <div class="card cent-card h-100">
                                <div class="card-body p-4">
                                    <div class="feature-icon bg-{{ $pillar['color'] }}-subtle text-{{ $pillar['color'] }}">
                                        <i class="ti {{ $pillar['icon'] }}"></i>
                                    </div>
                                    <h3 class="fw-bold mt-3 mb-2">{{ $pillar['title'] }}</h3>
                                    <p class="text-muted mb-0" style="font-size:14px;">{{ $pillar['text'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 py-lg-10" style="background:#f4f7fb;">
    <div class="container">
        <div class="d-flex align-items-end justify-content-between gap-3 flex-wrap mb-5" data-aos>
            <div>
                <span class="section-badge bg-primary-subtle text-primary"><i class="ti ti-school me-1"></i>Oferta académica</span>
                <h2 class="fw-bolder display-6 mb-0">Carreras del CENT N°74</h2>
                <p class="text-muted mt-2 mb-0">Tres años de duración · Modalidad presencial · Título oficial reconocido</p>
            </div>
            <a href="{{ route('cent.carreras') }}" class="btn btn-outline-cent">
                Ver todas las carreras <i class="ti ti-arrow-right ms-1"></i>
            </a>
        </div>

        @php
            $careerMeta = [
                'enfermeria-profesional' => ['icon' => 'ti-heart-rate-monitor', 'color' => '#c0392b'],
                'tec-sup-en-agente-socio-sanitario' => ['icon' => 'ti-heart-handshake', 'color' => '#1e8e6e'],
                'tec-sup-en-diagnostico-por-imagenes' => ['icon' => 'ti-scan', 'color' => '#1565c0'],
                'tec-sup-en-farmacia' => ['icon' => 'ti-vaccine', 'color' => '#6a1b9a'],
                'tec-sup-en-laboratorio-de-analisis-clinicos' => ['icon' => 'ti-microscope', 'color' => '#e65100'],
                'tec-sup-en-esterilizacion' => ['icon' => 'ti-shield-check', 'color' => '#00838f'],
            ];
        @endphp

        <div class="row g-4">
            @forelse($carreras->take(6) as $carrera)
                @php $meta = $careerMeta[$carrera->slug] ?? ['icon' => 'ti-school', 'color' => '#1e3a5f']; @endphp
                <div class="col-md-6 col-xl-4" data-aos>
                    <div class="card cent-card h-100 overflow-hidden">
                        <div class="career-header" style="background: linear-gradient(135deg, {{ $meta['color'] }}, {{ $meta['color'] }}aa);">
                            <div class="career-header-content d-flex align-items-center justify-content-between w-100">
                                <span class="rounded-3 d-inline-flex align-items-center justify-content-center" style="width:54px;height:54px;background:rgba(255,255,255,.16);color:#fff;font-size:26px;">
                                    <i class="ti {{ $meta['icon'] }}"></i>
                                </span>
                                <span class="badge rounded-pill px-3 py-2 fw-bold" style="background:rgba(255,255,255,.18);color:#fff;">{{ $carrera->duration }}</span>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <h3 class="fw-bold mb-1" style="font-size:18px;">{{ $carrera->name }}</h3>
                            <p class="text-muted mb-2" style="font-size:13px;"><i class="ti ti-certificate me-1"></i>{{ $carrera->title_granted }}</p>
                            <p class="text-muted mb-4" style="font-size:14px;">
                                {{ \Illuminate\Support\Str::limit(strip_tags($carrera->description), 150) }}
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

<section class="py-5 py-lg-11">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-5" data-aos>
                <span class="section-badge bg-info-subtle text-info"><i class="ti ti-calendar-event me-1"></i>Ingreso</span>
                <h2 class="fw-bolder display-6">Curso propedéutico de nivelación</h2>
                <p class="fs-5 text-muted text-justify">
                    El ingreso contempla un curso obligatorio de nivelación. Para acceder a la condición de ingresante se requiere asistencia, participación y aprobación en cada área.
                </p>
                <a href="{{ route('cent.requisitos') }}" class="btn btn-cent mt-2">
                    <i class="ti ti-checklist me-2"></i>Ver documentación requerida
                </a>
            </div>
            <div class="col-lg-7" data-aos>
                <div class="ps-0 ps-lg-4">
                    @foreach([
                        ['n' => '1', 'icon' => 'ti-calendar-event', 'color' => 'primary', 'title' => 'Inicio del curso', 'text' => 'Se organiza cada ciclo lectivo con instancias previas al inicio formal del cursado.'],
                        ['n' => '2', 'icon' => 'ti-clock-hour-4', 'color' => 'info', 'title' => 'Duración acotada', 'text' => 'El curso tiene una duración máxima de cuatro semanas con seguimiento institucional.'],
                        ['n' => '3', 'icon' => 'ti-user-check', 'color' => 'success', 'title' => 'Asistencia mínima', 'text' => 'Se requiere cumplir con al menos el 80% de asistencia para sostener la regularidad del proceso.'],
                        ['n' => '4', 'icon' => 'ti-award', 'color' => 'warning', 'title' => 'Aprobación por áreas', 'text' => 'La aprobación habilita la inscripción definitiva en la carrera y sede seleccionadas.'],
                    ] as $step)
                        <div class="timeline-item">
                            <div class="timeline-num">{{ $step['n'] }}</div>
                            <div class="card cent-card">
                                <div class="card-body p-4">
                                    <div class="d-flex gap-3 align-items-start">
                                        <div class="stat-icon bg-{{ $step['color'] }}-subtle text-{{ $step['color'] }} flex-shrink-0">
                                            <i class="ti {{ $step['icon'] }}"></i>
                                        </div>
                                        <div>
                                            <h4 class="fw-bold mb-1" style="font-size:15px;">{{ $step['title'] }}</h4>
                                            <p class="text-muted mb-0" style="font-size:13.5px;">{{ $step['text'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 py-lg-10" style="background:#f4f7fb;">
    <div class="container">
        <div class="d-flex align-items-end justify-content-between gap-3 flex-wrap mb-5" data-aos>
            <div>
                <span class="section-badge bg-info-subtle text-info"><i class="ti ti-map me-1"></i>Sedes</span>
                <h2 class="fw-bolder display-6 mb-0">Presencia educativa territorial</h2>
                <p class="text-muted mt-2 mb-0">{{ $sedes->count() }} sedes activas en toda la provincia de Tucumán</p>
            </div>
            <a href="{{ route('cent.sedes') }}" class="btn btn-outline-cent">Ver todas <i class="ti ti-arrow-right ms-1"></i></a>
        </div>
        <div class="row g-4">
            @foreach($sedes->take(6) as $sede)
                <div class="col-md-6 col-xl-4" data-aos>
                    <div class="card cent-card h-100 overflow-hidden">
                        <img src="{{ $sede->imagen_url }}" alt="{{ $sede->nombre }}" class="w-100" style="height:180px;object-fit:cover;" loading="lazy">
                        <div class="card-body p-4">
                            <span class="badge bg-primary-subtle text-primary mb-2 rounded-pill">{{ $sede->ciudad }}</span>
                            <h3 class="h5 fw-bold mb-1">{{ $sede->nombre }}</h3>
                            <p class="text-muted mb-0" style="font-size:13.5px;">
                                <i class="ti ti-map-pin me-1"></i>{{ $sede->direccion ?: 'Dirección a confirmar' }}
                            </p>
                        </div>
                        <div class="card-footer bg-white border-0 p-4 pt-0">
                            <a href="{{ $sede->maps_url }}" target="_blank" rel="noopener" class="btn btn-outline-cent btn-sm w-100">
                                <i class="ti ti-route me-1"></i>Cómo llegar
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="py-5 py-lg-10">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-7" data-aos>
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <span class="section-badge bg-primary-subtle text-primary mb-2"><i class="ti ti-news me-1"></i>Novedades</span>
                        <h2 class="fw-bolder display-6 mb-0">Actualidad institucional</h2>
                    </div>
                    <a href="{{ route('cent.novedades') }}" class="btn btn-outline-cent btn-sm">Ver todas</a>
                </div>
                @if($ultimasNoticias->isEmpty())
                    <div class="cent-muted-box p-5 text-center">
                        <i class="ti ti-inbox text-muted" style="font-size:40px;"></i>
                        <p class="text-muted mt-3 mb-0">Cuando la institución publique avisos y novedades van a aparecer acá.</p>
                    </div>
                @else
                    <div class="d-flex flex-column gap-3">
                        @foreach($ultimasNoticias as $nov)
                            @php $cfg = $nov->tipo_config; @endphp
                            <article class="card cent-card">
                                <div class="card-body p-4">
                                    <div class="d-flex gap-4 align-items-start">
                                        <div class="rounded-3 d-inline-flex align-items-center justify-content-center flex-shrink-0 bg-{{ $cfg['color'] }}-subtle text-{{ $cfg['color'] }}" style="width:52px;height:52px;font-size:24px;">
                                            <i class="ti {{ $cfg['icon'] }}"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center justify-content-between gap-2 mb-1 flex-wrap">
                                                <span class="badge bg-{{ $cfg['color'] }}-subtle text-{{ $cfg['color'] }} rounded-pill" style="font-size:11px;">{{ $cfg['label'] }}</span>
                                                <small class="text-muted">{{ $nov->created_at->format('d/m/Y') }}</small>
                                            </div>
                                            <h4 class="fw-bold mb-1" style="font-size:15px;">{{ $nov->titulo }}</h4>
                                            <p class="text-muted mb-0" style="font-size:13px;">{{ \Illuminate\Support\Str::limit($nov->contenido, 130) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="col-lg-5" data-aos>
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <span class="section-badge bg-warning-subtle text-warning mb-2"><i class="ti ti-writing me-1"></i>Exámenes</span>
                        <h2 class="fw-bolder display-6 mb-0">Próximas mesas</h2>
                    </div>
                    <a href="{{ route('cent.mesas') }}" class="btn btn-outline-cent btn-sm">Ver todo</a>
                </div>

                @if($proximasMesas->isEmpty())
                    <div class="cent-muted-box p-4 mb-4">
                        <div class="d-flex gap-3">
                            <i class="ti ti-calendar-off text-muted flex-shrink-0" style="font-size:28px;"></i>
                            <div>
                                <p class="fw-bold mb-1">Sin fechas cargadas</p>
                                <p class="text-muted mb-0" style="font-size:13px;">Las próximas mesas y cronogramas se publicarán acá cuando estén disponibles.</p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="d-flex flex-column gap-3 mb-4">
                        @foreach($proximasMesas as $evento)
                            <div class="card cent-card">
                                <div class="card-body p-4">
                                    <div class="d-flex gap-3">
                                        <div class="text-center flex-shrink-0" style="min-width:52px;">
                                            <div class="rounded-3 bg-warning-subtle text-warning d-flex align-items-center justify-content-center" style="width:52px;height:52px;font-size:20px;">
                                                <i class="ti ti-writing"></i>
                                            </div>
                                            <small class="text-muted d-block mt-1" style="font-size:10px;">{{ $evento->fecha_inicio->format('d M') }}</small>
                                        </div>
                                        <div>
                                            <h5 class="fw-bold mb-1" style="font-size:14px;">{{ $evento->titulo }}</h5>
                                            <p class="text-muted mb-0" style="font-size:12px;">
                                                <i class="ti ti-calendar me-1"></i>{{ $evento->fecha_inicio->format('d/m/Y') }}
                                                @if($evento->sede)
                                                    · <i class="ti ti-map-pin me-1"></i>{{ $evento->sede->ciudad }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="card cent-card">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Canales útiles</h5>
                        <div class="d-flex flex-column gap-2">
                            <a href="{{ route('cent.horarios') }}" class="btn btn-outline-cent btn-sm text-start"><i class="ti ti-calendar-time me-1"></i> Horarios publicados</a>
                            <a href="{{ route('cent.descargas') }}" class="btn btn-outline-cent btn-sm text-start"><i class="ti ti-download me-1"></i> Reglamentos y formularios</a>
                            <a href="{{ route('cent.faq') }}" class="btn btn-outline-cent btn-sm text-start"><i class="ti ti-help me-1"></i> Preguntas frecuentes</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 py-lg-11 bg-cent-blue text-white">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-7" data-aos>
                <span class="badge bg-white text-primary rounded-pill px-4 py-2 mb-4 fw-bold">Inscripción abierta</span>
                <h2 class="display-6 fw-bolder text-white mb-3">Formación de calidad, abierta a toda la comunidad</h2>
                <p class="fs-5 mb-4" style="color:rgba(255,255,255,.8);max-width:600px;">
                    No necesitás ser afiliado. Completá el formulario online, elegí carrera y sede, adjuntá tu documentación y descargá tu ficha para presentar cuando la sede lo indique.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('cent.preinscripcion') }}" class="btn btn-light btn-lg px-5 fw-bold rounded-3">
                        <i class="ti ti-user-plus me-2"></i>Iniciar preinscripción
                    </a>
                    <a href="{{ route('cent.login') }}" class="btn btn-outline-light btn-lg px-4 rounded-3">
                        <i class="ti ti-login me-2"></i>Portal académico
                    </a>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-block" data-aos>
                <div class="row g-3">
                    @foreach([
                        ['val' => $carreras->count(), 'label' => 'Carreras', 'icon' => 'ti-school'],
                        ['val' => $sedes->count(), 'label' => 'Sedes educativas', 'icon' => 'ti-map-pin'],
                        ['val' => '3 años', 'label' => 'Duración media', 'icon' => 'ti-clock'],
                        ['val' => 'Abierto', 'label' => 'A toda la comunidad', 'icon' => 'ti-users'],
                    ] as $stat)
                        <div class="col-6">
                            <div class="p-4 rounded-4 text-center h-100" style="background:rgba(255,255,255,.1);backdrop-filter:blur(8px);">
                                <div class="mb-2"><i class="ti {{ $stat['icon'] }} fs-2" style="color:var(--cent-sky);"></i></div>
                                <h3 class="fw-bolder text-white mb-1">{{ $stat['val'] }}</h3>
                                <p class="mb-0" style="color:rgba(255,255,255,.65);font-size:13px;">{{ $stat['label'] }}</p>
                            </div>
                        </div>
                    @endforeach
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
