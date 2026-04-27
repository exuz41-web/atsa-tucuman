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
        1 => 'enero',
        2 => 'febrero',
        3 => 'marzo',
        4 => 'abril',
        5 => 'mayo',
        6 => 'junio',
        7 => 'julio',
        8 => 'agosto',
        9 => 'septiembre',
        10 => 'octubre',
        11 => 'noviembre',
        12 => 'diciembre',
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
    <section class="atsa-hero d-flex align-items-center" style="background-image: url('{{ $homeHeroImage }}');">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-xl-7 col-lg-8">
                    <span class="badge bg-white bg-opacity-10 text-white fs-3 fw-bolder px-3 py-2 mb-4">{{ $homeHeroLabel }}</span>
                    <h1 class="text-white fw-bolder fs-13 mb-4">{{ $homeHeroTitle }}</h1>
                    <p class="text-white text-opacity-75 fs-5 mb-5 col-lg-10">{{ $homeHeroSubtitle }}</p>
                    <div class="d-flex gap-3 flex-sm-nowrap flex-wrap">
                        <a href="{{ $homeHeroPrimaryUrl }}" class="btn btn-light text-atsa-blue py-8 px-9 fw-bold"><i class="ti ti-scale me-2"></i>{{ $homeHeroPrimaryText }}</a>
                        <a href="{{ $homeHeroSecondaryUrl }}" class="btn btn-outline-light py-8 px-9 fw-bold"><i class="ti ti-user-plus me-2"></i>{{ $homeHeroSecondaryText }}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 py-md-14">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-7">
                <div>
                    <p class="text-primary fs-4 fw-bolder mb-2">DESTACADAS</p>
                    <h2 class="fw-bolder fs-10 mb-0">Noticias principales</h2>
                </div>
            </div>

            <div class="swiper atsa-news-swiper">
                <div class="swiper-wrapper">
                    @foreach ($featuredNews->take(3) as $post)
                        @php
                            $image = $post->image ? Storage::disk('public')->url($post->image) : ($categoryImages[$post->category] ?? $categoryImages['institucional']);
                            $postUrl = isset($post->id) ? route('novedades.show', $post->slug) : route('novedades.index');
                        @endphp
                        <div class="swiper-slide">
                            <div class="atsa-page-hero rounded-3 overflow-hidden p-7 p-lg-12 d-flex align-items-end" style="min-height: 430px; background-image: url('{{ $image }}');">
                                <div class="col-lg-8">
                                    <span class="badge bg-white text-primary fs-2 fw-bolder px-3 py-2 mb-3">{{ $categoryLabels[$post->category] ?? ucfirst($post->category) }}</span>
                                    <h2 class="text-white fw-bolder fs-10">{{ $post->title }}</h2>
                                    <p class="text-white text-opacity-75 fs-4">{{ Str::limit(strip_tags($post->body), 150) }}</p>
                                    <a href="{{ $postUrl }}" class="btn btn-light text-primary fw-bold">Leer más</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>

    <section class="py-5 py-md-14">
        <div class="container-fluid">
            <div class="row">
                @foreach ([['100+', 'Años de trayectoria'], ['Miles', 'De afiliados'], ['3', 'Filiales gremiales'], ['1', 'Solo sindicato']] as $stat)
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card bg-primary-subtle border-0 shadow-none text-center p-5 rounded-3 h-100">
                            <h2 class="text-primary fw-bolder fs-12 mb-1">{{ $stat[0] }}</h2>
                            <p class="mb-0 fs-4 fw-semibold text-dark">{{ $stat[1] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="pb-5 pb-md-14">
        <div class="container-fluid">
            <div class="card rounded-3 p-7 data-shadow">
                <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-5">
                    <div>
                        <p class="text-primary fs-4 fw-bolder mb-2">CALENDARIO SANITARIO</p>
                        <h2 class="fw-bolder fs-8 mb-0">Efemérides del sector salud - {{ $monthNames[now()->month] }}</h2>
                    </div>
                    <a href="{{ route('efemerides.index') }}" class="btn btn-outline-primary">Ver calendario</a>
                </div>
                <div class="row">
                    @foreach ($monthEfemerides->take(4) as $efemeride)
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="bg-primary-subtle rounded-3 p-4 h-100">
                                <span class="text-primary fw-bolder fs-10">{{ $efemeride->dia }}</span>
                                <p class="text-uppercase fw-bold fs-2 mb-2">{{ $monthNames[$efemeride->mes] ?? '' }}</p>
                                <h4 class="fw-bolder">{{ $efemeride->titulo }}</h4>
                                <p class="fs-3 text-body mb-0">{{ $efemeride->descripcion }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="pb-5 pb-md-14">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-7">
                <div>
                    <p class="text-primary fs-4 fw-bolder mb-2">NOVEDADES</p>
                    <h2 class="fw-bolder fs-10 mb-0">Últimas noticias</h2>
                </div>
                <a href="{{ route('novedades.index') }}" class="btn btn-outline-primary py-6 px-9">Ver todas</a>
            </div>
            <div class="row">
                @foreach ($news as $post)
                    @php
                        $image = $post->image ? Storage::disk('public')->url($post->image) : ($categoryImages[$post->category] ?? $categoryImages['institucional']);
                        $date = $post->published_at ?? $post->published_at->format('d/m/Y') : now()->format('d/m/Y');
                        $postUrl = isset($post->id) ? route('novedades.show', $post->slug) : route('novedades.index');
                    @endphp
                    <div class="col-lg-4 col-md-6 mb-7">
                        <div class="card rounded-3 overflow-hidden h-100 atsa-blog-card">
                            <a href="{{ $postUrl }}" class="position-relative">
                                <img src="{{ $image }}" alt="{{ $post->title }}" class="w-100 img-fluid atsa-card-img">
                                <div class="position-absolute bottom-0 end-0 me-9 mb-3">
                                    <p class="text-dark fs-2 px-2 rounded-pill bg-white mb-0">2 min lectura</p>
                                </div>
                                <div class="position-absolute bottom-0 ms-7 mb-n9">
                                    <img src="{{ asset('modernize/images/profile/user-3.jpg') }}" alt="ATSA" class="rounded-circle" width="44" height="44">
                                </div>
                            </a>
                            <div class="mt-10 px-7 pb-7 h-100">
                                <div class="d-flex gap-3 flex-column h-100 justify-content-between">
                                    <div class="d-flex">
                                        <p class="fs-2 px-2 rounded-pill bg-muted bg-opacity-25 text-dark mb-0">{{ $categoryLabels[$post->category] ?? ucfirst($post->category) }}</p>
                                    </div>
                                    <a href="{{ $postUrl }}" class="fs-15 fw-bolder line-clamp-2">{{ $post->title }}</a>
                                    <p class="mb-0 fs-4 line-clamp-3">{{ Str::limit(strip_tags($post->body), 145) }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p class="mb-0 fs-2 fw-semibold text-dark">{{ $post->author->name ?? 'ATSA Tucumán' }}</p>
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="ti ti-circle fs-2"></i>
                                            <p class="mb-0 fs-2 fw-semibold text-dark">{{ $date }}</p>
                                        </div>
                                    </div>
                                    <a href="{{ $postUrl }}" class="text-primary fw-bold">Leer más <i class="ti ti-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-5 py-md-14 bg-primary-subtle">
        <div class="container-fluid">
            <div class="text-center mb-7">
                <p class="text-primary fs-4 fw-bolder mb-2">FILIALES</p>
                <h2 class="fw-bolder fs-10">Filiales gremiales de ATSA</h2>
            </div>
            <div class="row">
                @foreach ($featuredFiliales->take(3) as $filial)
                    @php
                        $filialImage = $filial->image_url ?? null;
                    @endphp
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card rounded-3 h-100 overflow-hidden">
                            @if ($filialImage)
                                <img src="{{ $filialImage }}" alt="{{ $filial->name }}" class="w-100 object-fit-cover" style="height: 190px;">
                            @endif
                            <div class="p-4 h-100">
                                <div class="d-flex gap-3">
                                    <span class="round-48 rounded-circle hstack justify-content-center bg-primary-subtle text-primary flex-shrink-0"><i class="ti ti-map-pin fs-7"></i></span>
                                    <div>
                                        <h4 class="fw-bolder mb-1">{{ $filial->name }}</h4>
                                        <p class="fs-4 text-body mb-2">{{ $filial->address }}</p>
                                        @if ($filial->phone)
                                            <p class="text-primary fw-semibold mb-0"><i class="ti ti-phone me-1"></i>{{ $filial->phone }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-5 py-md-14">
        <div class="custom-container">
            <div class="text-center mb-7">
                <p class="text-primary fs-4 fw-bolder mb-2">TESTIMONIOS</p>
                <h2 class="fw-bolder fs-10">Lo que dicen nuestros afiliados</h2>
            </div>
            <style>
                .testimonial-modern-card {
                    background: #ffffff;
                    box-shadow: 0 12px 28px rgba(42, 53, 71, .08);
                    transition: transform .25s ease, box-shadow .25s ease;
                }

                .testimonial-modern-card:hover {
                    transform: translateY(-4px);
                    box-shadow: 0 18px 36px rgba(42, 53, 71, .12);
                }

                .testimonial-modern-avatar {
                    width: 72px;
                    height: 72px;
                    display: inline-grid;
                    place-items: center;
                    flex: 0 0 auto;
                    overflow: hidden;
                    border-radius: 999px;
                    background: #1e3a5f;
                    color: #ffffff;
                    font-size: 27px;
                    font-weight: 800;
                    letter-spacing: 1px;
                }

                .testimonial-modern-avatar img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                }

                .testimonial-modern-text {
                    min-height: 74px;
                    margin-bottom: 22px;
                    color: #5a6a85;
                    font-size: 18px;
                    line-height: 1.55;
                    text-align: left !important;
                }

                .testimonial-modern-badge {
                    display: inline-flex;
                    align-items: center;
                    border-radius: 7px;
                    background: #ecf2ff;
                    color: #5d87ff;
                    padding: 7px 13px;
                    font-size: 15px;
                    font-weight: 600;
                }
            </style>
            <div class="row g-4 mb-12">
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
                        <div class="card h-100 border-0 rounded-4 testimonial-modern-card">
                            <div class="card-body p-4 p-md-5">
                                <div class="d-flex align-items-center gap-3 mb-5">
                                    <span class="testimonial-modern-avatar">
                                        @if ($testimonialPhoto)
                                            <img src="{{ $testimonialPhoto }}" alt="{{ $testimonio->nombre }}">
                                        @else
                                            {{ $testimonialInitials }}
                                        @endif
                                    </span>
                                    <div class="min-w-0">
                                        <h4 class="fw-bolder mb-1 text-truncate">{{ $testimonio->nombre }}</h4>
                                        <p class="fs-4 text-body mb-0 text-truncate">{{ $testimonio->cargo }}</p>
                                    </div>
                                </div>
                                <p class="testimonial-modern-text">“{{ $testimonio->texto }}”</p>
                                <span class="testimonial-modern-badge">{{ $testimonio->filial ?: 'ATSA Tucumán' }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="atsa-page-hero rounded-3 overflow-hidden p-7 p-lg-12 mb-12" style="background-image: url('https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?w=1920&q=80');">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <p class="text-white fs-4 fw-bolder">TURISMO Y RECREACIÓN</p>
                        <h2 class="text-white fw-bolder fs-10">Turismo y Recreación para afiliados</h2>
                        <p class="text-white text-opacity-75 fs-4">ATSA Tucumán ofrece acceso a actividades recreativas, convenios turísticos y a la Ciudad Deportiva ubicada en Paraguay y Thames.</p>
                        <a href="{{ route('turismo.index') }}" class="btn btn-light text-primary fw-bold py-6 px-9">Ver todos los beneficios</a>
                    </div>
                    <div class="col-lg-6 mt-5 mt-lg-0">
                        <div class="row">
                            @foreach ([['Tafí del Valle', 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?w=500&q=80'], ['Termas de Río Hondo', 'https://images.unsplash.com/photo-1540541338287-41700207dee6?w=500&q=80'], ['Mar del Plata', 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=500&q=80']] as $destino)
                                <div class="col-md-4 mb-3">
                                    <div class="card rounded-3 overflow-hidden">
                                        <img src="{{ $destino[1] }}" class="atsa-card-img" style="height: 110px;" alt="{{ $destino[0] }}">
                                        <div class="p-3"><strong>{{ $destino[0] }}</strong></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-atsa-blue rounded-3 p-7 p-lg-10 mb-12">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <p class="text-white fs-4 fw-bolder">PARITARIAS</p>
                        <h2 class="text-white fw-bolder fs-9">Escalas salariales vigentes</h2>
                        <p class="text-white text-opacity-75 fs-4 mb-lg-0">{{ $escalaTitulo }} - consultá el último acuerdo y el historial salarial.</p>
                    </div>
                    <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                        @if (optional($escalaVigente ?? null)->archivo)
                            <a href="{{ Storage::disk('public')->url($escalaVigente->archivo) }}" class="btn btn-light text-primary fw-bold me-2" target="_blank">Descargar escala salarial</a>
                        @endif
                        <a href="{{ route('escalas.index') }}" class="btn btn-outline-light fw-bold">Ver historial</a>
                    </div>
                </div>
            </div>

            <div class="bg-atsa-blue rounded-3 position-relative overflow-hidden">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="py-lg-12 ps-lg-12 py-5 px-lg-0 px-9">
                            <h2 class="fs-10 fw-bolder text-white text-lg-start text-center">Formación profesional para trabajadores de la salud</h2>
                            <p class="fs-4 text-white text-opacity-75 text-lg-start text-center">El CENT N°74 ofrece carreras técnicas para fortalecer el sector sanitario tucumano.</p>
                            <div class="row text-white mt-4">
                                @foreach (['Enfermería Profesional', 'Instrumentación Quirúrgica', 'Técnico en Hemoterapia', 'Técnico en Farmacia'] as $career)
                                    <div class="col-sm-6 mb-3"><i class="ti ti-circle-check me-2"></i>{{ $career }}</div>
                                @endforeach
                            </div>
                            <div class="d-flex justify-content-lg-start justify-content-center mt-4">
                                <a href="{{ route('afiliados.index') }}" class="btn btn-light text-atsa-blue py-6 px-9 fw-bold">Ver carreras</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 d-lg-block d-none">
                        <img src="https://images.unsplash.com/photo-1509062522246-3755977927d7?w=900&q=80" alt="Formación" class="position-absolute end-0 top-0 h-100 w-50 object-fit-cover opacity-75">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="pb-5 pb-md-14">
        <div class="container-fluid">
            <div class="card rounded-3 p-7 data-shadow">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h2 class="fw-bolder fs-8 mb-3">Contacto rápido</h2>
                        <div class="row">
                            <div class="col-md-4 fs-4 mb-2"><i class="ti ti-map-pin text-primary me-2"></i>Paraguay y Thames, SMT</div>
                            <div class="col-md-4 fs-4 mb-2"><i class="ti ti-phone text-primary me-2"></i>0381 4331665</div>
                            <div class="col-md-4 fs-4 mb-2"><i class="ti ti-clock text-primary me-2"></i>Lun a Vie 8:00 a 16:00 hs</div>
                        </div>
                    </div>
                    <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                        <a href="https://wa.me/543814331665" class="btn btn-success py-8 px-9"><i class="ti ti-brand-whatsapp me-2"></i>WhatsApp</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.Swiper) {
            new Swiper('.atsa-news-swiper', {
                loop: true,
                autoplay: { delay: 4500 },
                pagination: { el: '.atsa-news-swiper .swiper-pagination', clickable: true },
            });

        }
    });
</script>
@endpush
