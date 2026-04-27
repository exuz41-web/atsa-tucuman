@extends('layouts.app')

@section('title', 'ATSA Tucumán | Sindicato de Trabajadores de la Sanidad')

@php
    use App\Models\PageSection;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;

    $spHero = $sitePage?->block('hero') ?? [];
    $homeHeroSection = PageSection::get('home', 'hero');

    $homeHeroImage = \App\Models\SitePage::imageUrl($spHero['image'] ?? null)
        ?? $homeHeroSection?->imageUrl(asset('images/home/hero-atsa-movilizacion.jpeg'))
        ?? asset('images/home/hero-atsa-movilizacion.jpeg');

    $homeHeroLabel = $spHero['badge'] ?? $homeHeroSection?->label ?? 'Sindicato de trabajadores de la sanidad';
    $homeHeroTitle = $spHero['title'] ?? $homeHeroSection?->title ?? 'Representamos a los trabajadores de la salud de Tucumán';
    $homeHeroSubtitle = $spHero['subtitle'] ?? $homeHeroSection?->subtitle ?? 'ATSA Tucumán defiende los derechos laborales del sector sanitario desde hace más de 100 años.';
    $homeHeroPrimaryText = $spHero['btn1_text'] ?? $homeHeroSection?->button_text ?? 'Conocé tus derechos';
    $homeHeroSecondaryText = $spHero['btn2_text'] ?? $homeHeroSection?->secondary_button_text ?? 'Quiero afiliarme';

    $resolveHeroUrl = fn (?string $url, string $fallback): string => $url
        ? (Str::startsWith($url, ['http://', 'https://']) ? $url : url($url))
        : $fallback;

    $homeHeroPrimaryUrl = $resolveHeroUrl($spHero['btn1_url'] ?? $homeHeroSection?->button_url, route('gremial.index'));
    $homeHeroSecondaryUrl = $resolveHeroUrl($spHero['btn2_url'] ?? $homeHeroSection?->secondary_button_url, route('afiliacion.create'));

    $categoryImages = [
        'gremial' => asset('images/historia/movilizacion-atsa-sanidad.jpg'),
        'institucional' => asset('images/historia/ciudad-deportiva-atsa.jpg'),
        'formacion' => asset('images/historia/formacion-cent-74.jpg'),
        'filiales' => asset('images/filiales/filial-este-banda.jpg'),
        'eventos' => asset('images/historia/infraestructura-ciudad-deportiva.jpg'),
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
            'title' => 'Paritarias Salud 2026: acuerdo del 11% para trabajadores sanitarios',
            'slug' => 'paritarias-salud-2026',
            'body' => 'El 6 de marzo de 2026 el Gobierno de Tucumán y ATSA firmaron el acuerdo salarial de la primera ronda paritaria 2026. El acuerdo establece un aumento del 11% sobre el básico, bonificado y remunerativo, más ocho puntos adicionales de la carrera sanitaria. El secretario general Reneé Ramírez destacó que el acuerdo también incluye la actualización de los suplementos vinculados a la carrera sanitaria y un mecanismo de revisión automática según la inflación. El convenio fue firmado junto a SUMAR, AME, SITAS, UPCN y ATE en un acto presidido por el ministro de Salud Pública Luis Medina Ruiz.',
            'category' => 'gremial',
            'published_at' => \Carbon\Carbon::parse('2026-03-06'),
            'image' => null,
            'author' => (object) ['name' => 'ATSA Tucumán'],
        ],
        (object) [
            'title' => 'ATSA inauguró su nueva sede en el marco del centenario',
            'slug' => 'nueva-sede-atsa-centenario-2025',
            'body' => 'En el año del centenario, ATSA Tucumán inauguró su nueva y moderna sede gremial en el predio de Ciudad Deportiva. El acto contó con la presencia del vicegobernador Miguel Acevedo, el senador nacional Juan Manzur, y autoridades de FATSA y del Ministerio de Salud. El vicegobernador Acevedo destacó: "Estamos viendo instalaciones de primer nivel, pensadas para contener y recibir a los afiliados. Este lugar era un basural y hoy se convirtió en un complejo con salones, piscinas y una sede gremial fantástica." La obra marca el broche de oro de un siglo de construcción sindical.',
            'category' => 'institucional',
            'published_at' => \Carbon\Carbon::parse('2025-10-03'),
            'image' => null,
            'author' => (object) ['name' => 'ATSA Tucumán'],
        ],
        (object) [
            'title' => 'El Gobernador Jaldo inauguró la Escuela de Enfermería en Amaicha del Valle',
            'slug' => 'escuela-enfermeria-amaicha-del-valle-2025',
            'body' => 'El Gobernador Osvaldo Jaldo inauguró el séptimo edificio educativo del CENT N°74 de ATSA en Amaicha del Valle, en el corazón de los Valles Calchaquíes. La nueva escuela cuenta con tres aulas equipadas, baños, cocina, salón multiuso y pasillo cubierto, con una inversión de 500 millones de pesos. La institución tiene más de 250 alumnos provenientes de Tafí del Valle, Amaicha, Colalao, Santa María y Cafayate. Ofrece la carrera de Enfermero Profesional acreditada por el Ministerio de Educación provincial.',
            'category' => 'formacion',
            'published_at' => \Carbon\Carbon::parse('2025-09-15'),
            'image' => null,
            'author' => (object) ['name' => 'ATSA Tucumán'],
        ],
        (object) [
            'title' => 'Estabilidad laboral para más de 500 trabajadores de la salud',
            'slug' => 'estabilidad-laboral-500-trabajadores-salud',
            'body' => 'En la segunda ronda de negociaciones paritarias de 2025, ATSA logró la continuidad laboral con planta transitoria para más de 500 trabajadores del sistema de salud provincial, incluyendo reemplazantes, cobertura de cargos y trabajadores discontinuos. El secretario general Reneé Ramírez remarcó que el sector salud emplea aproximadamente 23.000 trabajadores y que el sindicato seguirá exigiendo la reglamentación de la Ley de Carrera Sanitaria y la equiparación salarial de más de 100 colegas.',
            'category' => 'gremial',
            'published_at' => \Carbon\Carbon::parse('2025-07-10'),
            'image' => null,
            'author' => (object) ['name' => 'ATSA Tucumán'],
        ],
        (object) [
            'title' => 'ATSA y el Ministerio de Salud avanzan en Licenciatura de Enfermería con la UNT',
            'slug' => 'licenciatura-enfermeria-unt-atsa-2025',
            'body' => 'En noviembre de 2025 representantes de ATSA Tucumán y del Ministerio de Salud Pública mantuvieron reuniones para avanzar en el dictado de la Licenciatura en Enfermería a través del CENT N°74 en convenio con la Universidad Nacional de Tucumán. Esta iniciativa permitiría jerarquizar académicamente a los enfermeros del sistema sanitario tucumano y ampliar las posibilidades de desarrollo profesional para miles de trabajadores ya en actividad.',
            'category' => 'formacion',
            'published_at' => \Carbon\Carbon::parse('2025-11-20'),
            'image' => null,
            'author' => (object) ['name' => 'ATSA Tucumán'],
        ],
        (object) [
            'title' => 'Acuerdo paritario 2025: suba del 10% en tres etapas para la sanidad',
            'slug' => 'paritarias-salud-2025-primer-acuerdo',
            'body' => 'A principios de 2025 el Gobierno provincial firmó con ATSA un acuerdo salarial que estableció un aumento del 10% distribuido en tres etapas: 5% desde el 1° de febrero, 2,5% desde el 1° de abril y otro 2,5% desde el 1° de mayo. El acuerdo también contempló el pago del 100% de la Carrera Sanitaria para trabajadores dentro de los diez años de jubilación. ATSA destacó que el compromiso del Gobierno con el sector salud es sostenido y que las negociaciones continuarán en el segundo semestre.',
            'category' => 'gremial',
            'published_at' => \Carbon\Carbon::parse('2025-02-01'),
            'image' => null,
            'author' => (object) ['name' => 'ATSA Tucumán'],
        ],
    ]);

    $news = ($posts ?? collect())->count() ? $posts : $fallbackPosts;
    $featuredNews = ($featuredPosts ?? collect())->count() ? $featuredPosts : $news->take(3);

    $fallbackFiliales = collect([
        (object) ['name' => 'Central Ciudad Deportiva', 'address' => 'Paraguay y Thames, San Miguel de Tucumán', 'phone' => '0381 4331665', 'image_url' => asset('images/filiales/central-ciudad-deportiva.jpg')],
        (object) ['name' => 'Filial del Sur', 'address' => 'Julio Argentino Roca 371, Concepción, Tucumán', 'phone' => '03865 421030', 'image_url' => asset('images/filiales/filial-sur-concepcion.jpg')],
        (object) ['name' => 'Filial Este', 'address' => 'Cam. del Carmen 90, Banda del Río Salí', 'phone' => '381 5677170', 'image_url' => asset('images/filiales/filial-este-banda.jpg')],
    ]);

    $featuredFiliales = ($filiales ?? collect())->count() ? $filiales : $fallbackFiliales;

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

    $monthEfemerides = ($efemerides ?? collect())->count() ? $efemerides : $fallbackEfemerides;

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

    $testimonialItems = ($testimonios ?? collect())->count() ? $testimonios : $fallbackTestimonios;
    $escalaTitulo = optional($escalaVigente ?? null)->titulo ?? 'Paritaria Salud Tucumán 2026';

    $homeGaleria = PageSection::get('home', 'galeria');
    $homeGaleriaTitle = $homeGaleria?->title ?: 'Ciudad Deportiva y sede gremial';
    $homeGaleriaDesc = $homeGaleria?->subtitle ?: 'Un espacio propio para el deporte, la recreación y la vida familiar de nuestros afiliados.';

    $homeTurismoCta = PageSection::get('home', 'cta_turismo');
    $homeTurismoTitle = $homeTurismoCta?->title ?: 'Descanso, deporte y convenios para afiliados';
    $homeTurismoSubtitle = $homeTurismoCta?->subtitle ?: 'Conocé la Ciudad Deportiva ATSA, el Hotel ATSA en Termas de Río Hondo y la red de hoteles y espacios recreativos vinculados a FATSA.';

    $tourismImage = asset('images/turismo/hotel-atsa-termas-fachada.webp');
    $educationImage = asset('images/historia/formacion-cent-74.jpg');
    $impactItems = [
        [
            'eyebrow' => 'CENTENARIO',
            'title' => '100 años de organización sindical',
            'text' => 'Una historia que comenzó en 1925 y hoy continúa con presencia gremial, educativa y social en toda la provincia.',
            'image' => asset('images/historia/movilizacion-atsa-sanidad.jpg'),
            'url' => route('sindicato.index') . '#historia',
        ],
        [
            'eyebrow' => 'NUEVA SEDE',
            'title' => 'Ciudad Deportiva y sede gremial',
            'text' => 'La sede central en Paraguay y Thames articula la atención gremial, mientras la Ciudad Deportiva en Ecuador y Thames reúne recreación, deporte y familia.',
            'image' => asset('images/historia/ciudad-deportiva-atsa.jpg'),
            'url' => route('filiales.index'),
        ],
        [
            'eyebrow' => 'FORMACIÓN',
            'title' => 'CENT N°74: educación sanitaria',
            'text' => 'Carreras y capacitación técnica para jerarquizar a trabajadores y formar nuevos profesionales de la salud.',
            'image' => asset('images/historia/formacion-cent-74.jpg'),
            'url' => 'https://cent74atsatucuman.ar',
        ],
    ];
@endphp

@section('meta_description', $homeHeroSubtitle)
@section('og_image', $homeHeroImage)

@section('content')
    <section class="atsa-hero d-flex align-items-center position-relative" style="background-image: url('{{ $homeHeroImage }}');">
        {{-- Partículas decorativas --}}
        <div class="hero-orb hero-orb-1"></div>
        <div class="hero-orb hero-orb-2"></div>

        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-xl-7 col-lg-9">
                    <div class="hero-badge-pill mb-5 animate-fade-in-up">
                        <span class="hero-badge-dot"></span>
                        <i class="ti ti-shield-check me-2"></i>{{ $homeHeroLabel }}
                    </div>
                    <h1 class="text-white fw-bolder mb-4 animate-fade-in-up delay-1" style="font-size:clamp(2.4rem,5vw,4rem);line-height:1.07;letter-spacing:-0.03em;">
                        {{ $homeHeroTitle }}
                    </h1>
                    <p class="text-white mb-6 animate-fade-in-up delay-2 col-lg-10" style="font-size:1.18rem;line-height:1.7;opacity:.88;">
                        {{ $homeHeroSubtitle }}
                    </p>

                    {{-- Mini trust bar --}}
                    <div class="hero-trust-bar mb-6 animate-fade-in-up delay-2">
                        @foreach([['ti-calendar-stats','100+ años','de trayectoria'],['ti-users','5.000+','afiliados'],['ti-school','16 sedes','del CENT N°74']] as $t)
                        <div class="hero-trust-item">
                            <i class="ti {{ $t[0] }}"></i>
                            <span><strong>{{ $t[1] }}</strong> {{ $t[2] }}</span>
                        </div>
                        @endforeach
                    </div>

                    <div class="d-flex gap-3 flex-sm-nowrap flex-wrap animate-fade-in-up delay-3">
                        <a href="{{ $homeHeroPrimaryUrl }}" class="btn btn-light text-atsa-blue py-9 px-10 fw-bold shadow-lg hero-btn-primary">
                            <i class="ti ti-scale me-2"></i>{{ $homeHeroPrimaryText }}
                        </a>
                        <a href="{{ $homeHeroSecondaryUrl }}" class="btn btn-outline-light py-9 px-10 fw-bold hero-btn-secondary">
                            <i class="ti ti-user-plus me-2"></i>{{ $homeHeroSecondaryText }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <a href="#novedades-section" class="position-absolute bottom-0 start-50 translate-middle-x mb-5 text-white text-decoration-none d-none d-lg-flex flex-column align-items-center gap-2 scroll-down-arrow" style="opacity:.75;">
            <span class="fs-3 fw-semibold text-uppercase" style="letter-spacing:2px;">Explorar</span>
            <i class="ti ti-chevrons-down fs-7 animate-bounce"></i>
        </a>
    </section>

    <section class="py-5 py-md-14 bg-light">
        <div class="container-fluid">
            <div class="row align-items-end mb-7">
                <div class="col-lg-7">
                    <p class="text-primary fs-4 fw-bolder mb-2">ATSA TUCUMÁN HOY</p>
                    <h2 class="fw-bolder fs-10 mb-0">Sindicato, educación e infraestructura al servicio de la sanidad</h2>
                </div>
                <div class="col-lg-5 mt-3 mt-lg-0">
                    <p class="fs-4 text-body mb-0">Tres pilares que nos definen: más de 100 años de lucha gremial, formación técnica de primer nivel a través del CENT N°74 y una infraestructura propia para el bienestar de los afiliados y sus familias.</p>
                </div>
            </div>

            <div class="row g-4">
                @foreach ($impactItems as $item)
                    <div class="col-lg-4">
                        <a href="{{ $item['url'] }}" class="text-decoration-none text-dark">
                            <article class="card h-100 border-0 rounded-3 overflow-hidden atsa-impact-card">
                                <div class="position-relative overflow-hidden">
                                    <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" class="w-100 object-fit-cover atsa-impact-img">
                                    <span class="position-absolute top-0 start-0 m-4 badge bg-white text-primary px-3 py-2 fw-bolder">{{ $item['eyebrow'] }}</span>
                                </div>
                                <div class="card-body p-5">
                                    <h3 class="fw-bolder fs-6 mb-3">{{ $item['title'] }}</h3>
                                    <p class="fs-4 text-body mb-4">{{ $item['text'] }}</p>
                                    <span class="text-primary fw-bold">Conocer más <i class="ti ti-arrow-right"></i></span>
                                </div>
                            </article>
                        </a>
                    </div>
                @endforeach
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
                            <div class="atsa-page-hero rounded-3 overflow-hidden p-7 p-lg-12 d-flex align-items-end" style="min-height: clamp(280px, 50vw, 430px); background-image: url('{{ $image }}');">
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

    {{-- STATS ANIMADOS --}}
    <section class="py-10 py-lg-14 stats-section" style="background: linear-gradient(135deg, #1e3a5f 0%, #0d1f35 100%);">
        <div class="container-fluid">
            <div class="row g-4 text-center">
                @foreach ([
                    ['icon' => 'ti-calendar-stats', 'count' => 100, 'suffix' => '+', 'label' => 'Años de trayectoria', 'sub' => '1925 - 2025'],
                    ['icon' => 'ti-users',           'count' => 5000, 'suffix' => '+', 'label' => 'Afiliados activos',   'sub' => 'En toda la provincia'],
                    ['icon' => 'ti-map-pin',         'count' => 3,    'suffix' => '',  'label' => 'Filiales gremiales',  'sub' => 'Capital, Sur y Este'],
                    ['icon' => 'ti-school',          'count' => 16,   'suffix' => '',  'label' => 'Sedes CENT N°74',     'sub' => 'Formación en toda la prov.'],
                ] as $st)
                    <div class="col-lg-3 col-sm-6">
                        <div class="py-6 px-4">
                            <div class="d-flex justify-content-center mb-4">
                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:70px;height:70px;background:rgba(255,255,255,.1);">
                                    <i class="ti {{ $st['icon'] }} text-white" style="font-size:32px;"></i>
                                </div>
                            </div>
                            <h2 class="text-white fw-bolder mb-1" style="font-size:3.2rem;line-height:1;">
                                <span class="counter" data-count="{{ $st['count'] }}" data-suffix="{{ $st['suffix'] }}">0</span>
                            </h2>
                            <p class="text-white fw-bold fs-5 mb-1">{{ $st['label'] }}</p>
                            <p class="mb-0 fs-3" style="color:rgba(255,255,255,.55);">{{ $st['sub'] }}</p>
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
                        <h2 class="fw-bolder fs-8 mb-0">{{ $efemeridesTitle ?? ('Efemérides del sector salud - ' . $monthNames[now()->month]) }}</h2>
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

    <section class="pb-5 pb-md-14" id="novedades-section">
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
                        $date = $post->published_at ? $post->published_at->format('d/m/Y') : now()->format('d/m/Y');
                        $postUrl = isset($post->id) ? route('novedades.show', $post->slug) : route('novedades.index');
                        $readingTime = max(1, (int) ceil(str_word_count(strip_tags($post->body ?? '')) / 200));
                        $authorInitials = collect(explode(' ', $post->author->name ?? 'ATSA Tucumán'))->filter()->take(2)->map(fn($p) => mb_strtoupper(mb_substr($p,0,1)))->implode('');
                    @endphp
                    <div class="col-lg-4 col-md-6 mb-7">
                        <div class="card rounded-3 overflow-hidden h-100 atsa-blog-card">
                            <a href="{{ $postUrl }}" class="position-relative d-block overflow-hidden">
                                <img src="{{ $image }}" alt="{{ $post->title }}" class="w-100 img-fluid atsa-card-img" loading="lazy" style="transition: transform .4s ease;">
                                <div class="position-absolute bottom-0 end-0 me-9 mb-3">
                                    <p class="text-dark fs-2 px-2 rounded-pill bg-white mb-0">
                                        <i class="ti ti-clock me-1"></i>{{ $readingTime }} min
                                    </p>
                                </div>
                                <div class="position-absolute bottom-0 ms-7 mb-n9">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white" style="width:44px;height:44px;background:#1e3a5f;border:3px solid #fff;font-size:14px;">
                                        {{ $authorInitials }}
                                    </div>
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

    {{-- GALERÍA FOTOGRÁFICA --}}
    <section class="py-10 py-lg-14" style="background:#f4f7fb;">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-7">
                <div>
                    <p class="text-primary fs-4 fw-bolder mb-2">GALERÍA</p>
                    <h2 class="fw-bolder fs-10 mb-0">{{ $homeGaleriaTitle }}</h2>
                    <p class="fs-4 text-body mt-2 mb-0">{{ $homeGaleriaDesc }}</p>
                </div>
                <a href="{{ route('turismo.index') }}" class="btn btn-outline-primary py-6 px-9">Ver turismo</a>
            </div>
            <div class="row g-3 atsa-gallery">
                {{-- Imagen grande a la izquierda --}}
                <div class="col-lg-6">
                    <div class="rounded-3 overflow-hidden h-100" style="min-height:320px;">
                        <img src="{{ asset('images/turismo/ciudad-deportiva/pileta-panorama.jpeg') }}"
                             alt="Pileta Ciudad Deportiva ATSA"
                             class="w-100 h-100 object-fit-cover atsa-gallery-img"
                             loading="lazy">
                    </div>
                </div>
                {{-- Columna derecha: 2 filas --}}
                <div class="col-lg-6">
                    <div class="row g-3 h-100">
                        <div class="col-6">
                            <div class="rounded-3 overflow-hidden" style="height:190px;">
                                <img src="{{ asset('images/turismo/ciudad-deportiva/pileta-familia.jpeg') }}"
                                     alt="Pileta familiar ATSA"
                                     class="w-100 h-100 object-fit-cover atsa-gallery-img" loading="lazy">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="rounded-3 overflow-hidden" style="height:190px;">
                                <img src="{{ asset('images/turismo/ciudad-deportiva/cancha-cubierta.jpeg') }}"
                                     alt="Cancha cubierta Ciudad Deportiva"
                                     class="w-100 h-100 object-fit-cover atsa-gallery-img" loading="lazy">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="rounded-3 overflow-hidden" style="height:190px;">
                                <img src="{{ asset('images/turismo/ciudad-deportiva/sector-recreativo.jpeg') }}"
                                     alt="Sector recreativo ATSA"
                                     class="w-100 h-100 object-fit-cover atsa-gallery-img" loading="lazy">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="rounded-3 overflow-hidden" style="height:190px;">
                                <img src="{{ asset('images/turismo/ciudad-deportiva/futbol-infantil.jpeg') }}"
                                     alt="Fútbol infantil Ciudad Deportiva"
                                     class="w-100 h-100 object-fit-cover atsa-gallery-img" loading="lazy">
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Fila inferior: 3 imágenes pequeñas --}}
                <div class="col-lg-4">
                    <div class="rounded-3 overflow-hidden" style="height:180px;">
                        <img src="{{ asset('images/turismo/ciudad-deportiva/cancha-bienvenidos.jpeg') }}"
                             alt="Entrada Ciudad Deportiva ATSA"
                             class="w-100 h-100 object-fit-cover atsa-gallery-img" loading="lazy">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="rounded-3 overflow-hidden" style="height:180px;">
                        <img src="{{ asset('images/turismo/ciudad-deportiva/tenis-mesa.jpeg') }}"
                             alt="Tenis de mesa Ciudad Deportiva"
                             class="w-100 h-100 object-fit-cover atsa-gallery-img" loading="lazy">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="rounded-3 overflow-hidden" style="height:180px;">
                        <img src="{{ asset('images/turismo/ciudad-deportiva/ingreso-atsa.jpeg') }}"
                             alt="Ingreso Ciudad Deportiva ATSA"
                             class="w-100 h-100 object-fit-cover atsa-gallery-img" loading="lazy">
                    </div>
                </div>
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
                                ? asset($testimonio->foto)
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

            {{-- CTA AFILIACIÓN --}}
            <div class="rounded-3 overflow-hidden mb-12 position-relative" style="background: linear-gradient(180deg, #17314f 0%, #1e3a5f 100%); border: 1px solid rgba(73, 190, 255, 0.12);">
                <div class="position-relative p-7 p-lg-12">
                    <div class="row align-items-center g-5">
                        <div class="col-lg-7 text-white">
                            <span class="badge rounded-pill px-4 py-2 mb-4 fs-3 fw-semibold" style="background: rgba(255, 255, 255, 0.08); color: #d8f4ff; border: 1px solid rgba(255, 255, 255, 0.12);">
                                AFILIACIÓN ATSA
                            </span>
                            <h2 class="text-white fw-bolder fs-9 mb-4">¿Sos trabajador de la sanidad y querés sumarte al sindicato?</h2>
                            <p class="mb-0 fs-5 text-white" style="max-width: 640px; color: rgba(255,255,255,0.82) !important; line-height: 1.75;">
                                Afiliarte a ATSA Tucumán te acerca representación gremial, asesoramiento legal, capacitación,
                                beneficios para tu familia y acompañamiento en cada etapa de tu trabajo.
                            </p>
                        </div>
                        <div class="col-lg-5">
                            <div class="bg-white rounded-3 p-6 p-lg-7 shadow-sm" style="border: 1px solid rgba(30, 58, 95, 0.08);">
                                <div class="d-flex align-items-start gap-3 mb-4">
                                    <div class="d-flex align-items-center justify-content-center flex-shrink-0 rounded-2" style="width: 52px; height: 52px; background: #eef7ff; color: #1e3a5f;">
                                        <i class="ti ti-user-plus" style="font-size: 24px;"></i>
                                    </div>
                                    <div>
                                        <p class="text-atsa-blue fw-bolder fs-5 mb-1">Empezá tu afiliación</p>
                                        <p class="text-muted mb-0 fs-3">Completá la solicitud online o conocé la propuesta gremial y social para afiliados.</p>
                                    </div>
                                </div>
                                <div class="d-grid gap-3">
                                    <a href="{{ route('afiliacion.create') }}" class="btn py-8 px-6 fw-bold fs-5" style="background: #1e3a5f; color: #ffffff; border-radius: 8px; box-shadow: none;">
                                        <i class="ti ti-user-check me-2"></i>Afiliarme ahora
                                    </a>
                                    <a href="{{ route('afiliados.index') }}#beneficios" class="btn py-8 px-6 fw-bold fs-5" style="background: #ffffff; color: #1e3a5f; border: 1px solid rgba(30, 58, 95, 0.18); border-radius: 8px;">
                                        <i class="ti ti-gift me-2"></i>Ver beneficios
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="atsa-page-hero rounded-3 overflow-hidden p-7 p-lg-12 mb-12" style="background-image: url('{{ asset('images/turismo/ciudad-deportiva/ingreso-atsa.jpeg') }}');">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <p class="text-white fs-4 fw-bolder">TURISMO Y RECREACIÓN</p>
                        <h2 class="text-white fw-bolder fs-10">{{ $homeTurismoTitle }}</h2>
                        <p class="text-white text-opacity-75 fs-4">{{ $homeTurismoSubtitle }}</p>
                        <div class="d-flex gap-3 flex-wrap">
                            <a href="{{ route('turismo.index') }}" class="btn btn-light text-primary fw-bold py-6 px-9">
                                <i class="ti ti-beach me-2"></i>Ver turismo
                            </a>
                            <a href="{{ route('turismo.index') }}#consulta-turismo" class="btn btn-outline-light fw-bold py-6 px-9">
                                <i class="ti ti-message-circle me-2"></i>Consultar disponibilidad
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-6 mt-5 mt-lg-0">
                        <div class="row">
                            @foreach ([
                                ['Ciudad Deportiva', 'Ecuador y Thames, Capital', asset('images/turismo/ciudad-deportiva/pileta-familia.jpeg')],
                                ['Hotel ATSA Termas', 'Termas de Río Hondo', asset('images/turismo/hotel-atsa-termas-fachada.webp')],
                                ['Convenios FATSA', 'Hoteles y recreación nacional', asset('images/turismo/convenios/hotel-sanidad-mar-del-plata.webp')],
                            ] as $destino)
                                <div class="col-md-4 mb-3">
                                    <a href="{{ route('turismo.index') }}" class="text-decoration-none d-block h-100">
                                        <div class="card rounded-3 overflow-hidden h-100 tourism-home-card">
                                            <img src="{{ $destino[2] }}" class="atsa-card-img" style="height: 130px;" alt="{{ $destino[0] }}">
                                            <div class="p-3">
                                                <strong class="d-block text-dark">{{ $destino[0] }}</strong>
                                                <span class="fs-2 text-muted">{{ $destino[1] }}</span>
                                            </div>
                                        </div>
                                    </a>
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
                                @foreach ([
                                    'Enfermería Profesional',
                                    'Tec. Sup. en Agente Socio Sanitario',
                                    'Tec. Sup. en Laboratorio de Análisis Clínicos',
                                    'Tec. Sup. en Diagnóstico por Imágenes',
                                    'Tec. Sup. en Farmacia',
                                    'Tec. Sup. en Esterilización',
                                ] as $career)
                                    <div class="col-sm-6 mb-3"><i class="ti ti-circle-check me-2"></i>{{ $career }}</div>
                                @endforeach
                            </div>
                            <div class="education-next-card mt-4">
                                <span class="education-next-icon">
                                    <i class="ti ti-clock-hour-4"></i>
                                </span>
                                <div>
                                    <p class="mb-1 fw-bolder text-white">Próximamente</p>
                                    <p class="mb-0 text-white text-opacity-75">Tec. en Emergencias Prehospitalarias</p>
                                </div>
                            </div>
                            <div class="d-flex justify-content-lg-start justify-content-center mt-4">
                                <a href="{{ route('afiliados.index') }}" class="btn btn-light text-atsa-blue py-6 px-9 fw-bold">Ver carreras</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 d-lg-block d-none">
                        <img src="{{ $educationImage }}" alt="Formación" class="position-absolute end-0 top-0 h-100 w-50 object-fit-cover opacity-75">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="pb-5 pb-md-14 bg-light">
        <div class="container-fluid">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="h-100 rounded-3 p-7 d-flex align-items-center gap-5" style="background: linear-gradient(135deg, #1e3a5f 0%, #2a3547 100%);">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:64px;height:64px;background:rgba(255,255,255,.15);">
                            <i class="ti ti-map-pin text-white" style="font-size:30px;"></i>
                        </div>
                        <div>
                            <h4 class="text-white fw-bold mb-2">Sede Central</h4>
                            <p class="text-white text-opacity-75 fs-4 mb-1"><i class="ti ti-building me-1"></i>Paraguay y Thames, SMT</p>
                            <p class="text-white text-opacity-75 fs-4 mb-1"><i class="ti ti-phone me-1"></i>0381 4331665</p>
                            <p class="text-white text-opacity-75 fs-4 mb-0"><i class="ti ti-clock me-1"></i>Lun a Vie 8:00 a 16:00 hs</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="h-100 rounded-3 p-7 d-flex flex-column justify-content-center align-items-center text-center bg-white shadow-sm">
                        <h4 class="fw-bold mb-3">¿Tenés alguna consulta?</h4>
                        <p class="text-muted fs-4 mb-5">Escribinos por WhatsApp o completá el formulario de contacto y te respondemos a la brevedad.</p>
                        <div class="d-flex gap-3 flex-wrap justify-content-center">
                            <a href="https://wa.me/543814331665" target="_blank" rel="noopener" class="btn btn-success py-7 px-9 fw-bold">
                                <i class="ti ti-brand-whatsapp me-2"></i>WhatsApp
                            </a>
                            <a href="{{ route('contacto.index') }}" class="btn btn-outline-primary py-7 px-9 fw-bold">
                                <i class="ti ti-mail me-2"></i>Escribirnos
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    /* ── Hero mejorado ── */
    .hero-orb {
        position: absolute;
        border-radius: 50%;
        pointer-events: none;
        z-index: 0;
    }
    .hero-orb-1 {
        width: 520px; height: 520px;
        right: -80px; top: -120px;
        background: radial-gradient(circle, rgba(73,190,255,.18) 0%, transparent 70%);
    }
    .hero-orb-2 {
        width: 320px; height: 320px;
        left: 40%; bottom: -60px;
        background: radial-gradient(circle, rgba(30,58,95,.35) 0%, transparent 70%);
    }
    .hero-badge-pill {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 10px 20px;
        border-radius: 999px;
        background: rgba(255,255,255,.12);
        border: 1px solid rgba(255,255,255,.22);
        color: #fff;
        font-size: 14px;
        font-weight: 700;
        letter-spacing: .04em;
        backdrop-filter: blur(6px);
    }
    .hero-badge-dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        background: #13deb9;
        box-shadow: 0 0 0 3px rgba(19,222,185,.3);
        animation: pulse-dot 1.8s ease-in-out infinite;
    }
    @keyframes pulse-dot {
        0%,100% { box-shadow: 0 0 0 3px rgba(19,222,185,.3); }
        50% { box-shadow: 0 0 0 7px rgba(19,222,185,.1); }
    }
    .hero-trust-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }
    .hero-trust-item {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        border-radius: 10px;
        background: rgba(255,255,255,.1);
        border: 1px solid rgba(255,255,255,.15);
        color: rgba(255,255,255,.9);
        font-size: 13px;
        backdrop-filter: blur(4px);
    }
    .hero-trust-item i { font-size: 16px; color: #49beff; }
    .hero-btn-primary {
        border-radius: 12px !important;
        font-size: 1rem !important;
        transition: all .25s ease !important;
    }
    .hero-btn-primary:hover { transform: translateY(-2px); box-shadow: 0 16px 40px rgba(0,0,0,.25) !important; }
    .hero-btn-secondary {
        border-radius: 12px !important;
        font-size: 1rem !important;
        backdrop-filter: blur(4px);
        transition: all .25s ease !important;
    }
    .hero-btn-secondary:hover { background: rgba(255,255,255,.12); transform: translateY(-2px); }

    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(10px); }
    }
    .animate-bounce { animation: bounce 1.6s ease-in-out infinite; }
    .atsa-blog-card:hover img { transform: scale(1.05); }
    .scroll-down-arrow:hover { opacity: 1 !important; }
    .atsa-impact-card {
        box-shadow: 0 14px 35px rgba(42, 53, 71, .08);
        transition: transform .25s ease, box-shadow .25s ease;
    }
    .atsa-impact-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 22px 48px rgba(42, 53, 71, .14);
    }
    .atsa-impact-img {
        height: 250px;
        transition: transform .45s ease;
    }
    .atsa-impact-card:hover .atsa-impact-img {
        transform: scale(1.04);
    }
    .tourism-home-card {
        border: 1px solid rgba(255, 255, 255, .18);
        background: rgba(255, 255, 255, .96);
        box-shadow: 0 18px 42px rgba(15, 34, 54, .2);
        transition: transform .25s ease, box-shadow .25s ease;
    }
    .tourism-home-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 24px 52px rgba(15, 34, 54, .28);
    }
    .tourism-home-card img {
        transition: transform .45s ease;
    }
    .tourism-home-card:hover img {
        transform: scale(1.05);
    }
    .education-next-card {
        display: inline-flex;
        align-items: center;
        gap: 14px;
        width: min(100%, 560px);
        padding: 16px 18px;
        border-radius: 8px;
        background: rgba(73, 190, 255, .16);
        border: 1px solid rgba(73, 190, 255, .38);
        box-shadow: 0 14px 34px rgba(0, 0, 0, .12);
    }
    .education-next-icon {
        width: 44px;
        height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 auto;
        border-radius: 8px;
        background: #49beff;
        color: #0f2236;
        font-size: 23px;
    }
    /* Gallery */
    .atsa-gallery-img {
        transition: transform .4s ease;
    }
    .atsa-gallery .rounded-3:hover .atsa-gallery-img {
        transform: scale(1.04);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Swiper
        if (window.Swiper) {
            new Swiper('.atsa-news-swiper', {
                loop: true,
                autoplay: { delay: 4500 },
                pagination: { el: '.atsa-news-swiper .swiper-pagination', clickable: true },
            });
        }

        // Animated counters with IntersectionObserver
        var counters = document.querySelectorAll('.counter');
        if (!counters.length) return;

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) return;
                var el = entry.target;
                var target = parseInt(el.dataset.count, 10);
                var suffix = el.dataset.suffix || '';
                var duration = 1800;
                var startTime = null;

                function step(timestamp) {
                    if (!startTime) startTime = timestamp;
                    var progress = Math.min((timestamp - startTime) / duration, 1);
                    // Ease out cubic
                    var eased = 1 - Math.pow(1 - progress, 3);
                    el.textContent = Math.floor(eased * target) + suffix;
                    if (progress < 1) {
                        requestAnimationFrame(step);
                    } else {
                        el.textContent = target + suffix;
                    }
                }
                requestAnimationFrame(step);
                observer.unobserve(el);
            });
        }, { threshold: 0.4 });

        counters.forEach(function (el) { observer.observe(el); });

        // Smooth scroll for scroll-down arrow
        var arrow = document.querySelector('.scroll-down-arrow');
        if (arrow) {
            arrow.addEventListener('click', function (e) {
                e.preventDefault();
                var target = document.querySelector(this.getAttribute('href'));
                if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        }
    });
</script>
@endpush
