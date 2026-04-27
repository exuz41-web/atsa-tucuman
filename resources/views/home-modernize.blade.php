@extends('layouts.app')

@section('title', 'ATSA Tucumán | Inicio')

@php
    use App\Models\PageSection;
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Storage;

    $homeHeroSection = PageSection::get('home', 'hero');
    $homeHeroImage = $homeHeroSection?->imageUrl(asset('images/home/hero-atsa-movilizacion.jpeg')) ?? asset('images/home/hero-atsa-movilizacion.jpeg');
    $homeHeroLabel = $homeHeroSection?->label ?: 'Sindicato de trabajadores de la sanidad';
    $homeHeroTitle = $homeHeroSection?->title ?: 'Representamos a los trabajadores de la salud de Tucumán';
    $homeHeroSubtitle = $homeHeroSection?->subtitle ?: 'ATSA Tucumán defiende los derechos laborales del sector sanitario desde hace más de 100 años.';
    $homeHeroPrimaryText = $homeHeroSection?->button_text ?: 'Conocé tus derechos';
    $homeHeroSecondaryText = $homeHeroSection?->secondary_button_text ?: 'Quiero afiliarme';
    $resolveHeroUrl = fn (?string $url, string $fallback): string => $url
        ?? (Str::startsWith($url, ['http://', 'https://']) ?? $url : url($url))
        : $fallback;
    $homeHeroPrimaryUrl = $resolveHeroUrl($homeHeroSection?->button_url, route('gremial.index'));
    $homeHeroSecondaryUrl = $resolveHeroUrl($homeHeroSection?->secondary_button_url, route('afiliacion.create'));

    $categoryImages = [
        'gremial' => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=900&q=80',
        'institucional' => 'https://images.unsplash.com/photo-1521791136064-7986c2920216?w=900&q=80',
        'formacion' => 'https://images.unsplash.com/photo-1509062522246-3755977927d7?w=900&q=80',
        'filiales' => 'https://images.unsplash.com/photo-1486325212027-8081e485255e?w=900&q=80',
        'eventos' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=900&q=80',
    ];

    $categoryLabels = [
        'gremial' => 'Gremial',
        'institucional' => 'Institucional',
        'formacion' => 'Formación',
        'filiales' => 'Filiales',
        'eventos' => 'Eventos',
    ];

    $fallbackPosts = collect([
        (object) [
            'title' => 'Acuerdo paritario para trabajadores de sanidad',
            'slug' => 'acuerdo-paritario-sanidad',
            'body' => 'Información gremial para acompañar la defensa salarial y las condiciones laborales del sector salud.',
            'category' => 'gremial',
            'published_at' => now(),
            'image' => null,
            'author' => (object) ['name' => 'ATSA Tucumán'],
        ],
        (object) [
            'title' => 'Inscripciones abiertas para formación profesional',
            'slug' => 'formacion-profesional',
            'body' => 'El CENT N°74 fortalece la capacitación técnica de trabajadores de la salud en toda la provincia.',
            'category' => 'formacion',
            'published_at' => now()->subDays(4),
            'image' => null,
            'author' => (object) ['name' => 'ATSA Tucumán'],
        ],
        (object) [
            'title' => 'Atención en filiales para afiliados',
            'slug' => 'atencion-filiales',
            'body' => 'Las sedes de ATSA Tucumán continúan acercando atención, asesoramiento y gestión a cada trabajador.',
            'category' => 'filiales',
            'published_at' => now()->subDays(8),
            'image' => null,
            'author' => (object) ['name' => 'ATSA Tucumán'],
        ],
    ]);

    $news = ($posts ?? collect())->count() ?$posts : $fallbackPosts;
    $featuredNews = ($featuredPosts ?? collect())->count() ?$featuredPosts : $news->take(3);

    $fallbackFiliales = collect([
        (object) ['name' => 'Central Ciudad Deportiva', 'address' => 'Paraguay y Thames, San Miguel de Tucumán', 'phone' => '0381 4331665', 'image_url' => null],
        (object) ['name' => 'Filial del Sur', 'address' => 'Julio Argentino Roca 371, Concepción, Tucumán', 'phone' => null, 'image_url' => null],
        (object) ['name' => 'Filial Este', 'address' => 'Cam. del Carmen 90, Banda del Río Salí', 'phone' => null, 'image_url' => null],
    ]);

    $featuredFiliales = ($filiales ?? collect())->count() ?$filiales : $fallbackFiliales;

    $monthNames = [
        1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril', 5 => 'mayo', 6 => 'junio',
        7 => 'julio', 8 => 'agosto', 9 => 'septiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre',
    ];

    $fallbackEfemerides = collect([
        (object) ['dia' => 21, 'mes' => 5, 'titulo' => 'Día del Trabajador de la Salud', 'descripcion' => 'Reconocimiento al compromiso sanitario.'],
        (object) ['dia' => 12, 'mes' => 5, 'titulo' => 'Día Internacional de la Enfermería', 'descripcion' => 'Homenaje a quienes cuidan la salud.'],
        (object) ['dia' => 3, 'mes' => 12, 'titulo' => 'Día del Médico', 'descripcion' => 'Saludo a profesionales médicos.'],
    ]);

    $monthEfemerides = ($efemerides ?? collect())->count() ?$efemerides : $fallbackEfemerides;

    $fallbackTestimonios = collect([
        (object) [
            'nombre' => 'María González',
            'cargo' => 'Enfermera, Hospital Padilla',
            'filial' => 'San Miguel',
            'texto' => 'ATSA nos acompaña en cada reclamo y nos acerca capacitación para crecer en el trabajo.',
            'foto' => null,
        ],
        (object) [
            'nombre' => 'Carlos Medina',
            'cargo' => 'Técnico de laboratorio',
            'filial' => 'Concepción',
            'texto' => 'La filial está cerca cuando necesitamos asesoramiento. Eso hace una diferencia enorme.',
            'foto' => null,
        ],
        (object) [
            'nombre' => 'Lucía Fernández',
            'cargo' => 'Administrativa de clínica',
            'filial' => 'Monteros',
            'texto' => 'Ser afiliada me dio respaldo, información y acceso a beneficios para mi familia.',
            'foto' => null,
        ],
    ]);

    $testimonialItems = ($testimonios ?? collect())->count() ?$testimonios : $fallbackTestimonios;
    $escalaTitulo = optional($escalaVigente ?? null)->titulo " 'Paritaria Salud Tucumán 2026';
@endphp

@section('content')
    {{-- HERO SECTION ULTRA-MODERNIZADA --}}
    <section class="atsa-hero d-flex align-items-center position-relative" style="background-image: url('{{ $homeHeroImage }}'); min-height: 780px;">
        <div class="container-fluid position-relative z-1">
            <div class="row align-items-center">
                <div class="col-xl-7 col-lg-8 animate-fade-in-up">
                    {{-- Badge estilo Modernize --}}
                    <div class="section-badge bg-white bg-opacity-20 text-white mb-4">
                        <i class="ti ti-shield-check"></i>
                        {{ $homeHeroLabel }}
                    </div>

                    <h1 class="text-white fw-bolder display-4 mb-4" style="line-height: 1.2;">{{ $homeHeroTitle }}</h1>
                    <p class="text-white text-opacity-80 fs-5 mb-5 col-lg-10">{{ $homeHeroSubtitle }}</p>

                    {{-- Avatar stack + contador --}}
                    <div class="d-flex align-items-center gap-4 mb-5 flex-wrap">
                        <div class="avatar-stack">
                            <img src="{{ asset('modernize/images/profile/user-1.jpg') }}" class="avatar" alt="Afiliado">
                            <img src="{{ asset('modernize/images/profile/user-2.jpg') }}" class="avatar" alt="Afiliado">
                            <img src="{{ asset('modernize/images/profile/user-3.jpg') }}" class="avatar" alt="Afiliado">
                            <img src="{{ asset('modernize/images/profile/user-4.jpg') }}" class="avatar" alt="Afiliado">
                        </div>
                        <p class="text-white fs-4 mb-0"><strong>15.000+</strong> trabajadores afiliados</p>
                    </div>

                    {{-- Botones Modernize --}}
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="{{ $homeHeroPrimaryUrl }}" class="btn btn-light btn-lg py-9 px-12 fw-bold text-primary d-flex align-items-center gap-2">
                            <i class="ti ti-scale fs-5"></i>
                            {{ $homeHeroPrimaryText }}
                        </a>
                        <a href="{{ $homeHeroSecondaryUrl }}" class="btn btn-outline-light btn-lg py-9 px-12 fw-bold d-flex align-items-center gap-2">
                            <i class="ti ti-user-plus fs-5"></i>
                            {{ $homeHeroSecondaryText }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Decoración inferior tipo Modernize --}}
        <div class="position-absolute bottom-0 start-0 w-100 d-none d-lg-block">
            <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 120L60 110C120 100 240 80 360 70C480 60 600 60 720 65C840 70 960 80 1080 85C1200 90 1320 90 1380 90L1440 90V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="white"/>
            </svg>
        </div>
    </section>

    {{-- STATS CARDS MODERNIZADAS --}}
    <section class="py-5" style="margin-top: -60px; position: relative; z-index: 10;">
        <div class="container-fluid">
            <div class="row g-4">
                @foreach ([
                    ['icon' => 'ti-award', 'number' => '100+', 'label' => 'Años de trayectoria', 'color' => 'primary'],
                    ['icon' => 'ti-users', 'number' => '15.000+', 'label' => 'Afiliados activos', 'color' => 'success'],
                    ['icon' => 'ti-building', 'number' => '3', 'label' => 'Filiales gremiales', 'color' => 'warning'],
                    ['icon' => 'ti-certificate', 'number' => '1', 'label' => 'Único sindicato provincial', 'color' => 'danger']
                ] as $stat)
                    <div class="col-lg-3 col-md-6">
                        <div class="stat-card bg-white shadow-sm">
                            <div class="d-flex align-items-center gap-4">
                                <div class="stat-icon bg-{{ $stat['color'] }}-subtle text-{{ $stat['color'] }}">
                                    <i class="ti {{ $stat['icon'] }}"></i>
                                </div>
                                <div>
                                    <h2 class="fw-bolder fs-8 mb-1">{{ $stat['number'] }}</h2>
                                    <p class="text-muted fs-4 mb-0">{{ $stat['label'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- FEATURE CARDS ESTILO MODERNIZE --}}
    <section class="py-10 py-lg-16">
        <div class="container-fluid">
            <div class="text-center mb-10">
                <div class="section-badge bg-primary-subtle text-primary mb-3">
                    <i class="ti ti-bolt"></i>
                    SERVICIOS
                </div>
                <h2 class="fw-bolder fs-9 mb-3">Todo lo que ATSA ofrece</h2>
                <p class="text-muted fs-5 col-lg-6 mx-auto">Defensa gremial, capacitación profesional, beneficios y representación para los trabajadores de la salud.</p>
            </div>

            <div class="row g-4">
                @foreach ([
                    ['icon' => 'ti-shield-check', 'title' => 'Defensa Gremial', 'desc' => 'Representación ante empleadores y organismos públicos.', 'color' => 'primary'],
                    ['icon' => 'ti-school', 'title' => 'Formación', 'desc' => 'Carreras técnicas en el CENT N°74 con título oficial.', 'color' => 'success'],
                    ['icon' => 'ti-heart-handshake', 'title' => 'Beneficios', 'desc' => 'Turismo, recreación y acceso a convenios exclusivos.', 'color' => 'warning'],
                    ['icon' => 'ti-file-text', 'title' => 'Información', 'desc' => 'Escalas salariales, derechos y documentación gremial.', 'color' => 'danger']
                ] as $feature)
                    <div class="col-lg-3 col-md-6">
                        <div class="feature-card bg-{{ $feature['color'] }}-subtle p-6 h-100">
                            <div class="feature-icon bg-{{ $feature['color'] }} text-white">
                                <i class="ti {{ $feature['icon'] }}"></i>
                            </div>
                            <h4 class="fw-bold mb-3">{{ $feature['title'] }}</h4>
                            <p class="text-muted fs-4 mb-0">{{ $feature['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- NOTICIAS DESTACADAS SWIPER --}}
    <section class="py-10 py-lg-16 bg-light">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-8">
                <div>
                    <div class="section-badge bg-white text-primary mb-2">
                        <i class="ti ti-news"></i>
                        DESTACADAS
                    </div>
                    <h2 class="fw-bolder fs-9 mb-0">Noticias principales</h2>
                </div>
                <a href="{{ route('novedades.index') }}" class="btn btn-outline-primary py-6 px-9">
                    Ver todas <i class="ti ti-arrow-right ms-2"></i>
                </a>
            </div>

            <div class="swiper atsa-news-swiper">
                <div class="swiper-wrapper">
                    @foreach ($featuredNews->take(3) as $post)
                        @php
                            $image = $post->image ? Storage::disk('public')->url($post->image) : ($categoryImages[$post->category] ?? $categoryImages['institucional']);
                            $postUrl = isset($post->id) ? route('novedades.show', $post->slug) : route('novedades.index');
                        @endphp
                        <div class="swiper-slide">
                            <div class="card feature-card border-0 overflow-hidden" style="background: linear-gradient(to bottom, rgba(0,0,0,0) 0%, rgba(0,0,0,0.7) 100%), url('{{ $image }}'); background-size: cover; background-position: center; min-height: 480px;">
                                <div class="card-body d-flex flex-column justify-content-end p-7">
                                    <span class="badge bg-white text-primary fs-3 fw-bold px-3 py-2 mb-3 align-self-start">
                                        {{ $categoryLabels[$post->category] ?? ucfirst($post->category) }}
                                    </span>
                                    <h3 class="text-white fw-bolder fs-7 mb-3">{{ $post->title }}</h3>
                                    <p class="text-white text-opacity-80 fs-4 mb-4">{{ Str::limit(strip_tags($post->body), 150) }}</p>
                                    <a href="{{ $postUrl }}" class="btn btn-light text-primary fw-bold align-self-start">
                                        Leer más <i class="ti ti-arrow-right ms-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination mt-5"></div>
            </div>
        </div>
    </section>

    {{-- EFEMÉRIDES ESTILO CALENDAR CARDS --}}
    <section class="py-10 py-lg-16">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-8">
                <div>
                    <div class="section-badge bg-primary-subtle text-primary mb-2">
                        <i class="ti ti-calendar-event"></i>
                        CALENDARIO SANITARIO
                    </div>
                    <h2 class="fw-bolder fs-9 mb-1">Efemérides de {{ $monthNames[now()->month] }}</h2>
                    <p class="text-muted fs-4 mb-0">Fechas importantes del sector salud</p>
                </div>
                <a href="{{ route('efemerides.index') }}" class="btn btn-outline-primary py-6 px-9">
                    Ver calendario <i class="ti ti-arrow-right ms-2"></i>
                </a>
            </div>

            <div class="row g-4">
                @foreach ($monthEfemerides->take(4) as $efemeride)
                    <div class="col-lg-3 col-md-6">
                        <div class="card feature-card border-0 h-100" style="background: linear-gradient(135deg, #ecf2ff 0%, #ffffff 100%);">
                            <div class="card-body p-6">
                                <div class="d-flex align-items-center gap-4 mb-4">
                                    <div class="text-center bg-primary text-white rounded-3 px-4 py-3">
                                        <span class="d-block fw-bolder fs-6">{{ $efemeride->dia }}</span>
                                        <span class="d-block fs-2 text-uppercase">{{ substr($monthNames[$efemeride->mes] ?? '', 0, 3) }}</span>
                                    </div>
                                    <div>
                                        <h4 class="fw-bold fs-5 mb-1 line-clamp-2">{{ $efemeride->titulo }}</h4>
                                    </div>
                                </div>
                                <p class="text-muted fs-4 mb-0">{{ $efemeride->descripcion }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ÚLTIMAS NOVEDADES GRID --}}
    <section class="py-10 py-lg-16 bg-light">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-8">
                <div>
                    <div class="section-badge bg-white text-primary mb-2">
                        <i class="ti ti-article"></i>
                        NOVEDADES
                    </div>
                    <h2 class="fw-bolder fs-9 mb-0">Últimas noticias</h2>
                </div>
                <a href="{{ route('novedades.index') }}" class="btn btn-outline-primary py-6 px-9">
                    Ver todas <i class="ti ti-arrow-right ms-2"></i>
                </a>
            </div>

            <div class="row g-4">
                @foreach ($news as $post)
                    @php
                        $image = $post->image ? Storage::disk('public')->url($post->image) : ($categoryImages[$post->category] ?? $categoryImages['institucional']);
                        $date = $post->published_at ?? $post->published_at->format('d/m/Y') : now()->format('d/m/Y');
                        $postUrl = isset($post->id) ? route('novedades.show', $post->slug) : route('novedades.index');
                    @endphp
                    <div class="col-lg-4 col-md-6">
                        <div class="card feature-card border-0 h-100 overflow-hidden">
                            <a href="{{ $postUrl }}" class="position-relative overflow-hidden" style="height: 220px;">
                                <img src="{{ $image }}" alt="{{ $post->title }}" class="w-100 h-100 object-fit-cover transition-transform hover-scale">
                                <div class="position-absolute top-0 end-0 m-3">
                                    <span class="badge bg-white text-dark fs-2 fw-semibold px-3 py-2">
                                        {{ $categoryLabels[$post->category] ?? ucfirst($post->category) }}
                                    </span>
                                </div>
                            </a>
                            <div class="card-body p-6 d-flex flex-column">
                                <div class="d-flex align-items-center gap-2 mb-3 text-muted fs-3">
                                    <i class="ti ti-calendar"></i>
                                    <span>{{ $date }}</span>
                                </div>
                                <h4 class="fw-bold fs-5 mb-3 line-clamp-2">
                                    <a href="{{ $postUrl }}" class="text-dark text-decoration-none hover-primary">{{ $post->title }}</a>
                                </h4>
                                <p class="text-muted fs-4 mb-4 line-clamp-3 flex-grow-1">{{ Str::limit(strip_tags($post->body), 120) }}</p>
                                <a href="{{ $postUrl }}" class="text-primary fw-semibold d-flex align-items-center gap-2 mt-auto">
                                    Leer más <i class="ti ti-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- FILIALES CARDS --}}
    <section class="py-10 py-lg-16">
        <div class="container-fluid">
            <div class="text-center mb-8">
                <div class="section-badge bg-primary-subtle text-primary mb-3">
                    <i class="ti ti-building"></i>
                    FILIALES
                </div>
                <h2 class="fw-bolder fs-9 mb-3">Nuestras sedes gremiales</h2>
                <p class="text-muted fs-5 col-lg-6 mx-auto">Atención personalizada en cada rincón de la provincia</p>
            </div>

            <div class="row g-4">
                @foreach ($featuredFiliales->take(3) as $filial)
                    <div class="col-lg-4 col-md-6">
                        <div class="card feature-card border-0 h-100">
                            <div class="card-body p-6">
                                <div class="d-flex align-items-start gap-4">
                                    <div class="flex-shrink-0">
                                        <div class="stat-icon bg-primary-subtle text-primary">
                                            <i class="ti ti-map-pin"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h4 class="fw-bold mb-2">{{ $filial->name }}</h4>
                                        <p class="text-muted fs-4 mb-2">
                                            <i class="ti ti-location me-2 text-primary"></i>{{ $filial->address }}
                                        </p>
                                        @if ($filial->phone)
                                            <p class="text-primary fw-semibold fs-4 mb-0">
                                                <i class="ti ti-phone me-2"></i>{{ $filial->phone }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent border-0 p-6 pt-0">
                                <a href="{{ route('filiales.index') }}" class="btn btn-outline-primary w-100 py-6">
                                    Ver en mapa <i class="ti ti-external-link ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- TESTIMONIOS MODERNIZADOS --}}
    <section class="py-10 py-lg-16 bg-primary" style="background: linear-gradient(135deg, #1e3a5f 0%, #2a3547 100%);">
        <div class="container-fluid">
            <div class="text-center mb-10">
                <div class="section-badge bg-white bg-opacity-20 text-white mb-3">
                    <i class="ti ti-messages"></i>
                    TESTIMONIOS
                </div>
                <h2 class="fw-bolder fs-9 text-white mb-3">Lo que dicen nuestros afiliados</h2>
                <p class="text-white text-opacity-70 fs-5 col-lg-6 mx-auto">Historias reales de trabajadores de la salud que confían en ATSA</p>
            </div>

            <div class="row g-4">
                @foreach ($testimonialItems->take(3) as $testimonio)
                    @php
                        $testimonialPhoto = $testimonio->foto_url ?? null;
                        if (! $testimonialPhoto && ! empty($testimonio->foto)) {
                            $testimonialPhoto = str_starts_with($testimonio->foto, 'images/')
                                ?? asset($testimonio->foto)
                                : Storage::disk('public')->url($testimonio->foto);
                        }
                        $testimonialInitials = collect(explode(' ', trim($testimonio->nombre)))
                            ->filter()
                            ->take(2)
                            ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
                            ->implode('');
                    @endphp
                    <div class="col-lg-4 col-md-6">
                        <div class="testimonial-card h-100">
                            <div class="d-flex align-items-center gap-4 mb-5">
                                <div class="testimonial-modern-avatar">
                                    @if ($testimonialPhoto)
                                        <img src="{{ $testimonialPhoto }}" alt="{{ $testimonio->nombre }}">
                                    @else
                                        {{ $testimonialInitials }}
                                    @endif
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1">{{ $testimonio->nombre }}</h5>
                                    <p class="text-muted fs-4 mb-0">{{ $testimonio->cargo }}</p>
                                </div>
                            </div>
                            <div class="mb-4">
                                <i class="ti ti-quote text-primary fs-1 opacity-25"></i>
                            </div>
                            <p class="text-dark fs-4 mb-4" style="line-height: 1.7;">"{{ $testimonio->texto }}"</p>
                            <span class="testimonial-modern-badge">
                                <i class="ti ti-building-community me-2"></i>{{ $testimonio->filial ?: 'ATSA Tucumán' }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- TURISMO CTA --}}
    <section class="py-10 py-lg-16">
        <div class="container-fluid">
            <div class="card feature-card border-0 overflow-hidden" style="background: linear-gradient(to right, rgba(30,58,95,0.95), rgba(30,58,95,0.8)), url('https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?w=1920&q=80'); background-size: cover; background-position: center;">
                <div class="card-body p-8 p-lg-12">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <div class="section-badge bg-white bg-opacity-20 text-white mb-3">
                                <i class="ti ti-beach"></i>
                                TURISMO Y RECREACIÓN
                            </div>
                            <h2 class="text-white fw-bolder fs-8 mb-4">Beneficios para afiliados</h2>
                            <p class="text-white text-opacity-80 fs-4 mb-5">Accedé a convenios turísticos, la Ciudad Deportiva y actividades recreativas para vos y tu familia.</p>
                            <a href="{{ route('turismo.index') }}" class="btn btn-light btn-lg py-8 px-10 fw-bold text-primary">
                                Ver beneficios <i class="ti ti-arrow-right ms-2"></i>
                            </a>
                        </div>
                        <div class="col-lg-6 mt-5 mt-lg-0">
                            <div class="row g-3">
                                @foreach ([
                                    ['Tafí del Valle', 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?w=500&q=80'],
                                    ['Termas de Río Hondo', 'https://images.unsplash.com/photo-1540541338287-41700207dee6?w=500&q=80'],
                                    ['Mar del Plata', 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=500&q=80']
                                ] as $destino)
                                    <div class="col-md-4">
                                        <div class="card border-0 overflow-hidden rounded-4" style="aspect-ratio: 1;">
                                            <img src="{{ $destino[1] }}" class="w-100 h-100 object-fit-cover" alt="{{ $destino[0] }}">
                                        </div>
                                        <p class="text-white text-center mt-2 mb-0 fw-semibold">{{ $destino[0] }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ESCALAS SALARIALES CTA --}}
    <section class="py-10 py-lg-16 bg-light">
        <div class="container-fluid">
            <div class="card feature-card border-0" style="background: linear-gradient(135deg, #1e3a5f 0%, #2a3547 100%);">
                <div class="card-body p-8 p-lg-12">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="section-badge bg-white bg-opacity-20 text-white mb-3">
                                <i class="ti ti-scale"></i>
                                PARITARIAS
                            </div>
                            <h2 class="text-white fw-bolder fs-8 mb-3">Escalas salariales vigentes</h2>
                            <p class="text-white text-opacity-80 fs-4 mb-0">{{ $escalaTitulo }} - Consultá el último acuerdo y el historial salarial completo.</p>
                        </div>
                        <div class="col-lg-4 text-lg-end mt-5 mt-lg-0">
                            @if (optional($escalaVigente ?? null)->archivo)
                                <a href="{{ Storage::disk('public')->url($escalaVigente->archivo) }}" class="btn btn-light btn-lg py-8 px-10 fw-bold text-primary me-2 mb-2" target="_blank">
                                    <i class="ti ti-download me-2"></i>Descargar escala
                                </a>
                            @endif
                            <a href="{{ route('escalas.index') }}" class="btn btn-outline-light btn-lg py-8 px-10 fw-bold mb-2">
                                Ver historial
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- FORMACIÓN CTA --}}
    <section class="py-10 py-lg-16">
        <div class="container-fluid">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6 order-lg-2">
                    <div class="position-relative">
                        <img src="https://images.unsplash.com/photo-1509062522246-3755977927d7?w=900&q=80" alt="Formación" class="img-fluid rounded-4 shadow-lg" style="aspect-ratio: 4/3; object-fit: cover;">
                        <div class="position-absolute -bottom-4 -start-4 bg-primary text-white p-5 rounded-4 shadow-lg d-none d-lg-block">
                            <span class="fs-1 fw-bolder d-block">CENT N°74</span>
                            <span class="fs-4">Formación profesional</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 order-lg-1">
                    <div class="section-badge bg-primary-subtle text-primary mb-3">
                        <i class="ti ti-school"></i>
                        EDUCACIÓN
                    </div>
                    <h2 class="fw-bolder fs-8 mb-4">Formación profesional para trabajadores de la salud</h2>
                    <p class="text-muted fs-4 mb-5">El CENT N°74 ofrece carreras técnicas para fortalecer el sector sanitario tucumano con títulos oficiales.</p>

                    <div class="row g-3 mb-5">
                        @foreach (['Enfermería Profesional', 'Instrumentación Quirúrgica', 'Técnico en Hemoterapia', 'Técnico en Farmacia'] as $career)
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3">
                                    <i class="ti ti-check-circle text-primary fs-4"></i>
                                    <span class="fw-semibold">{{ $career }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <a href="{{ route('afiliados.index') }}" class="btn btn-atsa btn-lg py-8 px-10">
                        Ver carreras <i class="ti ti-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- CONTACTO RÁPIDO --}}
    <section class="py-10 py-lg-16 bg-light">
        <div class="container-fluid">
            <div class="card feature-card border-0">
                <div class="card-body p-8 p-lg-10">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="section-badge bg-primary-subtle text-primary mb-3">
                                <i class="ti ti-headset"></i>
                                CONTACTO
                            </div>
                            <h2 class="fw-bolder fs-8 mb-4">Estamos para ayudarte</h2>
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="stat-icon bg-primary-subtle text-primary">
                                            <i class="ti ti-map-pin"></i>
                                        </div>
                                        <div>
                                            <p class="text-muted fs-4 mb-0">Dirección</p>
                                            <p class="fw-semibold mb-0">Paraguay y Thames, SMT</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="stat-icon bg-success-subtle text-success">
                                            <i class="ti ti-phone"></i>
                                        </div>
                                        <div>
                                            <p class="text-muted fs-4 mb-0">Teléfono</p>
                                            <p class="fw-semibold mb-0">0381 4331665</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="stat-icon bg-warning-subtle text-warning">
                                            <i class="ti ti-clock"></i>
                                        </div>
                                        <div>
                                            <p class="text-muted fs-4 mb-0">Horario</p>
                                            <p class="fw-semibold mb-0">Lun-Vie 8-16hs</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 text-lg-end mt-5 mt-lg-0">
                            <a href="https://wa.me/543814331665" target="_blank" class="btn btn-success btn-lg py-8 px-10">
                                <i class="ti ti-brand-whatsapp me-2"></i>WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Swiper para noticias destacadas
        if (window.Swiper) {
            new Swiper('.atsa-news-swiper', {
                slidesPerView: 1,
                spaceBetween: 24,
                loop: true,
                autoplay: { delay: 5000, disableOnInteraction: false },
                pagination: { el: '.atsa-news-swiper .swiper-pagination', clickable: true },
                breakpoints: {
                    768: { slidesPerView: 2 },
                    1200: { slidesPerView: 3 }
                }
            });
        }

        // Preloader
        const preloader = document.querySelector('.preloader');
        if (preloader) {
            setTimeout(() => preloader.classList.add('hide'), 500);
        }
    });
</script>
@endpush

@push('styles')
<style>
    .hover-scale { transition: transform .5s ease; }
    .hover-scale:hover { transform: scale(1.05); }
    .hover-primary { transition: color .2s ease; }
    .hover-primary:hover { color: var(--bs-primary) !important; }
    .transition-transform { transition: transform .3s ease; }
    .bg-success-subtle { background: rgba(25, 135, 84, 0.1) !important; }
    .bg-warning-subtle { background: rgba(255, 193, 7, 0.1) !important; }
</style>
@endpush
