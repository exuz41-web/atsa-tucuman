@extends('layouts.app')

@section('title', 'Gremial | ATSA Tucumán')
@section('meta_description', 'Actividad gremial de ATSA Tucumán: paritarias, comunicados, escalas salariales, delegados y derechos del trabajador de la sanidad.')
@section('og_image', asset('images/historia/movilizacion-atsa-sanidad.jpg'))

@php
    use App\Models\PageSection;
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Storage;

    $gremialHero     = PageSection::get('gremial', 'hero');
    $gremialHeroImg  = $gremialHero?->imageUrl(asset('images/historia/movilizacion-atsa-sanidad.jpg'))
                       ?: asset('images/historia/movilizacion-atsa-sanidad.jpg');
    $gremialHeroTitle    = $gremialHero?->title    ?: 'Defensa de derechos laborales';
    $gremialHeroSubtitle = $gremialHero?->subtitle ?: 'Paritarias, comunicados, representación y asesoramiento para los trabajadores de la sanidad en Tucumán.';

    $gremialIntro = PageSection::get('gremial', 'intro');
    $gremialIntroTitle    = $gremialIntro?->title    ?: 'Lo que hacemos por los trabajadores de la sanidad';
    $gremialIntroSubtitle = $gremialIntro?->subtitle ?: 'ATSA Tucumán defiende los derechos laborales del sector salud con presencia, negociación y acompañamiento real.';

    $categoryImages = [
        'gremial'      => asset('images/historia/movilizacion-atsa-sanidad.jpg'),
        'institucional'=> asset('images/historia/ciudad-deportiva-atsa.jpg'),
        'formacion'    => asset('images/historia/formacion-cent-74.jpg'),
        'filiales'     => asset('images/filiales/filial-este-banda.jpg'),
        'eventos'      => asset('images/historia/infraestructura-ciudad-deportiva.jpg'),
    ];

    $fallback = collect([
        (object) [
            'title' => 'Paritarias Salud 2026: acuerdo del 11% para trabajadores sanitarios',
            'slug' => 'paritarias-salud-2026',
            'body' => 'El 6 de marzo de 2026 el Gobierno de Tucumán y ATSA firmaron el acuerdo salarial de la primera ronda paritaria 2026. Establece un aumento del 11% sobre el básico más ocho puntos adicionales de la carrera sanitaria. El convenio fue firmado junto a SUMAR, AME, SITAS, UPCN y ATE en un acto presidido por el ministro Luis Medina Ruiz.',
            'category' => 'gremial',
            'published_at' => \Carbon\Carbon::parse('2026-03-06'),
            'image' => null,
        ],
        (object) [
            'title' => 'Estabilidad laboral para más de 500 trabajadores de la salud',
            'slug' => 'estabilidad-laboral-500-trabajadores-salud',
            'body' => 'ATSA logró la continuidad laboral con planta transitoria para más de 500 trabajadores del sistema de salud provincial, incluyendo reemplazantes, cobertura de cargos y trabajadores discontinuos. El secretario general Renée Ramírez remarcó que el sector emplea aproximadamente 23.000 trabajadores en la provincia.',
            'category' => 'gremial',
            'published_at' => \Carbon\Carbon::parse('2025-07-10'),
            'image' => null,
        ],
        (object) [
            'title' => 'Acuerdo paritario 2025: suba del 10% en tres etapas para la sanidad',
            'slug' => 'paritarias-salud-2025-primer-acuerdo',
            'body' => 'El Gobierno provincial firmó con ATSA un acuerdo salarial del 10% en tres etapas: 5% desde el 1° de febrero, 2,5% desde el 1° de abril y 2,5% desde el 1° de mayo. Incluyó el pago del 100% de la Carrera Sanitaria para trabajadores dentro de los diez años de jubilación.',
            'category' => 'gremial',
            'published_at' => \Carbon\Carbon::parse('2025-02-01'),
            'image' => null,
        ],
    ]);
    $items = ($posts ?: collect())->count() ? $posts : $fallback;
@endphp

@section('content')

    {{-- HERO --}}
    <section class="atsa-page-hero py-14 position-relative"
             style="background-image: url('{{ $gremialHeroImg }}'); min-height: 520px;">
        <div class="container-fluid position-relative z-1">
            <div class="col-lg-8">
                <span class="badge bg-white text-primary fs-3 fw-bolder px-3 py-2 mb-4">
                    <i class="ti ti-scale me-1"></i>GREMIAL
                </span>
                <h1 class="fw-bolder display-4 text-white mb-4">{{ $gremialHeroTitle }}</h1>
                <p class="fs-5 text-white text-opacity-75 mb-5 col-lg-9">
                    {{ $gremialHeroSubtitle }}
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('escalas.index') }}" class="btn btn-light text-primary fw-bold py-8 px-9">
                        <i class="ti ti-cash me-2"></i>Escalas salariales
                    </a>
                    <a href="#paritarias-publico" class="btn btn-outline-light fw-bold py-8 px-9">
                        <i class="ti ti-building-bank me-2"></i>Paritarias 2026
                    </a>
                    <a href="#paritarias-privados" class="btn btn-outline-light fw-bold py-8 px-9">
                        <i class="ti ti-building-hospital me-2"></i>Sector privado
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- LOGROS GREMIALES (STATS) --}}
    <section class="py-10 py-lg-14" style="background: linear-gradient(135deg, #1e3a5f 0%, #0d1f35 100%);">
        <div class="container-fluid">
            <div class="row g-4 text-center">
                @foreach ([
                    ['icon' => 'ti-calendar-stats', 'count' => '100+', 'label' => 'Años de representación', 'sub' => 'Desde 1925'],
                    ['icon' => 'ti-users',           'count' => '5.000+','label' => 'Afiliados activos',   'sub' => 'En toda la provincia'],
                    ['icon' => 'ti-building-bank',   'count' => '200',  'label' => 'Viviendas entregadas', 'sub' => 'Barrio ATSA'],
                    ['icon' => 'ti-school',          'count' => '16',   'label' => 'Sedes del CENT N°74',  'sub' => 'En toda Tucumán'],
                ] as $st)
                    <div class="col-lg-3 col-sm-6">
                        <div class="py-5 px-4">
                            <div class="d-flex justify-content-center mb-4">
                                <div class="rounded-circle d-flex align-items-center justify-content-center"
                                     style="width:70px;height:70px;background:rgba(255,255,255,.1);">
                                    <i class="ti {{ $st['icon'] }} text-white" style="font-size:32px;"></i>
                                </div>
                            </div>
                            <h2 class="text-white fw-bolder mb-1" style="font-size:3rem;line-height:1;">
                                {{ $st['count'] }}
                            </h2>
                            <p class="text-white fw-bold fs-5 mb-1">{{ $st['label'] }}</p>
                            <p class="mb-0 fs-3" style="color:rgba(255,255,255,.55);">{{ $st['sub'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- QUÉ HACE EL GREMIO --}}
    <section class="py-10 py-lg-14 bg-light">
        <div class="container-fluid">
            <div class="text-center mb-8">
                <p class="text-primary fs-4 fw-bolder mb-2">NUESTRO ROL GREMIAL</p>
                <h2 class="fw-bolder fs-9 mb-3">{{ $gremialIntroTitle }}</h2>
                <p class="text-muted fs-5 col-lg-7 mx-auto">
                    {{ $gremialIntroSubtitle }}
                </p>
            </div>
            <div class="row g-4">
                @foreach ([
                    ['icon' => 'ti-scale',         'color' => 'primary', 'title' => 'Negociación salarial',     'desc' => 'Participamos en paritarias provinciales y acuerdos con el Ministerio de Salud para actualizar salarios y condiciones laborales del sector.'],
                    ['icon' => 'ti-shield-check',  'color' => 'success', 'title' => 'Defensa del afiliado',     'desc' => 'Acompañamos cada reclamo, despido, sanción o conflicto laboral con asesoramiento jurídico especializado en derecho del trabajo sanitario.'],
                    ['icon' => 'ti-users-group',   'color' => 'info',    'title' => 'Red de delegados',         'desc' => 'Contamos con delegados en clínicas, sanatorios, laboratorios y hospitales de toda la provincia para una representación cercana y efectiva.'],
                    ['icon' => 'ti-file-certificate','color' => 'warning','title' => 'Convenio colectivo',      'desc' => 'Gestionamos y vigilamos la aplicación del Convenio Colectivo de Trabajo, garantizando categorías, salarios y condiciones de cada trabajador.'],
                    ['icon' => 'ti-home-2',        'color' => 'danger',  'title' => 'Viviendas para afiliados', 'desc' => 'Logramos la construcción del Barrio ATSA con 200 viviendas en Av. Ejército del Norte y unidades en Lomas del Tafí y Manantial Sur.'],
                    ['icon' => 'ti-heart-handshake','color' => 'primary', 'title' => 'Acción social',           'desc' => 'Asistencia para anteojos, prótesis, medicamentos, ayuda económica y acompañamiento en situaciones de emergencia para afiliados y su familia.'],
                ] as $card)
                    <div class="col-lg-4 col-md-6">
                        <div class="card border-0 rounded-3 h-100 gremial-feature-card">
                            <div class="card-body p-6">
                                <div class="gremial-icon-box bg-{{ $card['color'] }}-subtle mb-4">
                                    <i class="ti {{ $card['icon'] }} text-{{ $card['color'] }}"></i>
                                </div>
                                <h4 class="fw-bolder mb-2">{{ $card['title'] }}</h4>
                                <p class="text-muted fs-4 mb-0">{{ $card['desc'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- COMUNICADOS --}}
    <section class="py-10 py-lg-14" id="comunicados">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-8">
                <div>
                    <p class="text-primary fs-4 fw-bolder mb-2">COMUNICADOS</p>
                    <h2 class="fw-bolder fs-9 mb-0">Últimas novedades gremiales</h2>
                </div>
                <a href="{{ route('novedades.index') }}?categoria=gremial" class="btn btn-outline-primary fw-bold">
                    Ver todas las novedades
                </a>
            </div>

            <div class="row">
                @foreach ($items as $post)
                    @php
                        $image = $post->image
                            ? Storage::disk('public')->url($post->image)
                            : ($categoryImages[$post->category] ?? $categoryImages['gremial']);
                        $postUrl = isset($post->id) ? route('novedades.show', $post->slug) : route('novedades.index');
                        $date = $post->published_at ? $post->published_at->format('d/m/Y') : now()->format('d/m/Y');
                    @endphp
                    <div class="col-lg-4 col-md-6 mb-7">
                        <div class="card rounded-3 overflow-hidden h-100 atsa-blog-card">
                            <a href="{{ $postUrl }}" class="position-relative d-block overflow-hidden">
                                <img src="{{ $image }}" alt="{{ $post->title }}" class="w-100 img-fluid atsa-card-img" loading="lazy">
                                <span class="position-absolute top-0 start-0 m-3 badge bg-primary text-white fw-bold px-3 py-2">
                                    Gremial
                                </span>
                            </a>
                            <div class="p-6 d-flex flex-column h-100">
                                <h3 class="fw-bolder fs-5 mb-3 line-clamp-2">{{ $post->title }}</h3>
                                <p class="fs-4 text-muted flex-grow-1 line-clamp-3 mb-4">{{ Str::limit(strip_tags($post->body), 130) }}</p>
                                <div class="d-flex align-items-center justify-content-between">
                                    <p class="mb-0 fs-2 text-muted"><i class="ti ti-calendar me-1"></i>{{ $date }}</p>
                                    <a href="{{ $postUrl }}" class="text-primary fw-bold fs-4">Leer más <i class="ti ti-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- PARITARIAS Y RECURSOS --}}
    <section class="py-10 py-lg-14 bg-light" id="paritarias">
        <div class="container-fluid">
            <div class="text-center mb-8">
                <p class="text-primary fs-4 fw-bolder mb-2">RECURSOS GREMIALES</p>
                <h2 class="fw-bolder fs-9 mb-0">Información para el trabajador</h2>
            </div>
            <div class="row g-4">
                @foreach ([
                    ['Escalas salariales', 'Consultá acuerdos vigentes e históricos de paritarias del sector salud.', route('escalas.index'), 'ti-cash', 'primary'],
                    ['Delegados', 'Conocé a los referentes gremiales por filial y sector de trabajo.', route('delegados.index'), 'ti-users', 'success'],
                    ['Documentos institucionales', 'Estatutos, convenios colectivos, balances y reglamentos disponibles.', route('documentos.index'), 'ti-file-text', 'warning'],
                ] as $card)
                    <div class="col-lg-4">
                        <div class="card border-0 rounded-3 h-100 gremial-resource-card">
                            <div class="card-body p-7 d-flex flex-column">
                                <div class="gremial-resource-icon bg-{{ $card[4] }}-subtle mb-4">
                                    <i class="ti {{ $card[3] }} text-{{ $card[4] }} fs-7"></i>
                                </div>
                                <h3 class="fw-bolder fs-5 mb-2">{{ $card[0] }}</h3>
                                <p class="fs-4 text-muted flex-grow-1 mb-5">{{ $card[1] }}</p>
                                <a href="{{ $card[2] }}" class="btn btn-{{ $card[4] }} fw-bold mt-auto">
                                    <i class="ti ti-arrow-right me-2"></i>Ver sección
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- PARITARIAS SECTOR PÚBLICO TUCUMÁN --}}
    <section class="py-10 py-lg-14" id="paritarias-publico">
        <div class="container-fluid">

            <div class="text-center mb-8">
                <p class="text-primary fs-4 fw-bolder mb-2">SECTOR PÚBLICO — TUCUMÁN</p>
                <h2 class="fw-bolder fs-9 mb-3">Paritarias del personal de salud provincial</h2>
                <p class="text-muted fs-5 col-lg-7 mx-auto">
                    ATSA Tucumán negocia directamente con el <strong>Ministerio de Salud Pública de la Provincia</strong>
                    los salarios y condiciones laborales del personal de salud estatal. Así quedaron los últimos acuerdos.
                </p>
            </div>

            {{-- Acuerdo vigente 2026 --}}
            <div class="card border-0 rounded-4 shadow-sm overflow-hidden mb-5">
                <div class="px-6 py-4 d-flex align-items-center gap-3 flex-wrap"
                     style="background: linear-gradient(135deg, #1e3a5f 0%, #0f2236 100%);">
                    <span class="rounded-3 p-3 d-flex" style="background:rgba(255,255,255,.15);">
                        <i class="ti ti-writing text-white fs-7"></i>
                    </span>
                    <div>
                        <h4 class="text-white fw-bolder mb-0">Acuerdo paritario 2026</h4>
                        <small class="text-white text-opacity-75">Firmado el 6 de marzo de 2026 · Ministerio de Salud Pública de Tucumán</small>
                    </div>
                    <span class="ms-auto badge rounded-pill px-3 py-2 fs-2 fw-bold"
                          style="background:#1D9E75;color:#fff;">Vigente</span>
                </div>
                <div class="card-body p-6">
                    <div class="row g-5">
                        <div class="col-lg-7">
                            <p class="text-muted fs-4 mb-5">
                                ATSA firmó junto a <strong>SUMAR, AME, SITAS y UPCN</strong> el acuerdo salarial 2026
                                con el Gobierno de Tucumán, presidido por el Ministro <strong>Luis Medina Ruiz</strong>.
                                El secretario general de ATSA, <strong>Reneé Ramírez</strong>, destacó que el sector
                                emplea aproximadamente <strong>23.000 trabajadores</strong> en la provincia.
                            </p>

                            {{-- Items del acuerdo --}}
                            <div class="d-flex flex-column gap-3">
                                @foreach ([
                                    ['success', 'ti-trending-up',    '11% de aumento sobre el básico bonificable y remunerativo, con actualización por inflación.'],
                                    ['info',    'ti-heart-rate-monitor','8 puntos porcentuales adicionales de Carrera Sanitaria, llevándola al 60%.'],
                                    ['warning', 'ti-users',          'Más de 500 trabajadores pasaron a planta transitoria, garantizando estabilidad laboral.'],
                                    ['primary', 'ti-award',          '100% de la Carrera Sanitaria para trabajadores a 12 años o menos de jubilarse.'],
                                    ['danger',  'ti-map-pin',        'Actualización de la zona desfavorable para trabajadores no asistenciales.'],
                                ] as $item)
                                    <div class="d-flex align-items-start gap-3 rounded-3 p-4"
                                         style="background:#f8fafc; border:1px solid #e8edf2;">
                                        <span class="badge bg-{{ $item[0] }}-subtle text-{{ $item[0] }} rounded-2 p-2 flex-shrink-0">
                                            <i class="ti {{ $item[1] }} fs-5"></i>
                                        </span>
                                        <p class="mb-0 fs-4 text-dark fw-semibold">{{ $item[2] }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-lg-5">
                            {{-- Piso salarial --}}
                            <div class="rounded-3 p-5 mb-4"
                                 style="background: linear-gradient(135deg, #ECF2FF, #dde8ff); border:1px solid #c5d8ff;">
                                <p class="fs-3 fw-bolder text-primary mb-3">
                                    <i class="ti ti-cash me-2"></i>Piso salarial 2026
                                </p>
                                <div class="d-flex justify-content-between align-items-center py-3 border-bottom border-light">
                                    <span class="fs-4 text-muted">Ingresante Carrera Sanitaria</span>
                                    <strong class="fs-5 text-dark">$940.000</strong>
                                </div>
                                <div class="d-flex justify-content-between align-items-center py-3">
                                    <span class="fs-4 text-muted">Proyectado noviembre 2026</span>
                                    <strong class="fs-5 text-success">+$1.000.000</strong>
                                </div>
                                <p class="fs-3 text-muted mt-2 mb-0">Con ajuste por inflación según acuerdo.</p>
                            </div>
                            {{-- Carrera Sanitaria --}}
                            <div class="rounded-3 p-4" style="background:#f0fdf8; border:1px solid #b6efda;">
                                <p class="fs-3 fw-bolder mb-2" style="color:#0d6e4e;">
                                    <i class="ti ti-heart-rate-monitor me-2"></i>Carrera Sanitaria
                                </p>
                                <div class="d-flex align-items-center gap-3 mb-2">
                                    <div class="flex-grow-1 rounded-pill overflow-hidden" style="height:10px;background:#d1f5e6;">
                                        <div style="width:60%;height:100%;background:#1D9E75;border-radius:999px;"></div>
                                    </div>
                                    <strong class="text-success fs-4">60%</strong>
                                </div>
                                <p class="fs-3 text-muted mb-0">40% pendiente en próximas rondas paritarias.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Historial 2025 --}}
            <div class="card border-0 rounded-4 shadow-sm mb-5">
                <div class="card-body p-6">
                    <h5 class="fw-bolder mb-5 d-flex align-items-center gap-2">
                        <i class="ti ti-history text-primary fs-6"></i>
                        Acuerdos 2025 — historial de negociaciones
                    </h5>
                    <div class="row g-4">
                        @foreach ([
                            [
                                'Febrero 2025',
                                'primary',
                                'ti-calendar',
                                '10% en tres etapas: 5% desde el 1° de febrero, 2,5% desde el 1° de abril y 2,5% desde el 1° de mayo.',
                                '1° ronda paritaria'
                            ],
                            [
                                'Junio 2025',
                                'info',
                                'ti-refresh',
                                'Revisión salarial: +5% adicional para compensar inflación de mayo-junio. Primer encuentro de la 2° ronda de negociación.',
                                '2° ronda paritaria'
                            ],
                            [
                                'Agosto–Noviembre 2025',
                                'success',
                                'ti-heart-rate-monitor',
                                'Carrera Sanitaria: +4% en agosto, +4% en octubre y +2% en noviembre. Pase a planta transitoria de más de 500 trabajadores.',
                                'Carrera Sanitaria'
                            ],
                            [
                                'Diciembre 2025',
                                'warning',
                                'ti-award',
                                'Pago del 100% de la Carrera Sanitaria para trabajadores a menos de 10 años de jubilarse (+$150.000/$200.000 de bolsillo).',
                                'Beneficio especial'
                            ],
                        ] as $h)
                            <div class="col-lg-6">
                                <div class="d-flex gap-3 rounded-3 p-4 h-100"
                                     style="background:#f8fafc; border:1px solid #e8edf2;">
                                    <span class="badge bg-{{ $h[1] }}-subtle text-{{ $h[1] }} rounded-2 p-2 align-self-start flex-shrink-0"
                                          style="font-size:18px;">
                                        <i class="ti {{ $h[2] }}"></i>
                                    </span>
                                    <div>
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <strong class="fs-4 text-dark">{{ $h[0] }}</strong>
                                            <span class="badge bg-{{ $h[1] }}-subtle text-{{ $h[1] }} rounded-pill px-2 fs-2">{{ $h[4] }}</span>
                                        </div>
                                        <p class="fs-4 text-muted mb-0">{{ $h[3] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- CTA + fuente --}}
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="rounded-4 p-6 h-100 d-flex flex-column flex-lg-row align-items-center justify-content-between gap-4"
                         style="background: linear-gradient(135deg, #1e3a5f 0%, #0d1f35 100%);">
                        <div class="text-white">
                            <h5 class="fw-bolder mb-1">¿Querés ver la escala salarial completa?</h5>
                            <p class="mb-0 text-white text-opacity-75 fs-4">
                                Consultá los básicos por categoría y antigüedad actualizados al último acuerdo.
                            </p>
                        </div>
                        <a href="{{ route('escalas.index') }}"
                           class="btn btn-light text-primary fw-bold py-7 px-8 flex-shrink-0">
                            <i class="ti ti-cash me-2"></i>Ver escalas
                        </a>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card border-0 rounded-4 h-100 shadow-sm">
                        <div class="card-body p-5 d-flex flex-column justify-content-center">
                            <p class="fs-3 fw-bolder mb-2 text-muted">
                                <i class="ti ti-building-bank me-2 text-primary"></i>Fuente oficial
                            </p>
                            <p class="fs-4 text-muted mb-3">
                                Actas y comunicados publicados por el Ministerio de Salud Pública de Tucumán.
                            </p>
                            <a href="https://msptucuman.gov.ar/se-realizo-la-firma-de-acta-acuerdo-por-paritarias-en-salud-2026/"
                               target="_blank" rel="noopener"
                               class="btn btn-outline-primary fw-bold">
                                <i class="ti ti-external-link me-2"></i>Ver acta 2026
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- PARITARIAS SECTOR PRIVADO --}}
    <section class="py-10 py-lg-14 bg-light" id="paritarias-privados">
        <div class="container-fluid">

            <div class="text-center mb-8">
                <p class="text-primary fs-4 fw-bolder mb-2">SECTOR PRIVADO</p>
                <h2 class="fw-bolder fs-9 mb-3">Paritarias para trabajadores de clínicas y sanatorios</h2>
                <p class="text-muted fs-5 col-lg-7 mx-auto">
                    Los trabajadores de la sanidad privada en Tucumán están encuadrados en los Convenios Colectivos
                    negociados por <strong>FATSA</strong> (Federación de Asociaciones de Trabajadores de la Sanidad Argentina),
                    de la que ATSA forma parte. A continuación los acuerdos vigentes.
                </p>
            </div>

            {{-- Acuerdo vigente 2026 --}}
            <div class="row g-4 mb-6">
                {{-- CCT 122/75 --}}
                <div class="col-lg-6">
                    <div class="card border-0 rounded-4 h-100 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 d-flex align-items-center gap-3"
                             style="background: linear-gradient(135deg, #1e3a5f 0%, #2a5298 100%);">
                            <span class="rounded-3 p-3 d-flex" style="background:rgba(255,255,255,.15);">
                                <i class="ti ti-building-hospital text-white fs-7"></i>
                            </span>
                            <div>
                                <h4 class="text-white fw-bolder mb-0">CCT 122/75</h4>
                                <small class="text-white text-opacity-75">Clínicas, sanatorios, hospitales privados y geriátricos</small>
                            </div>
                            <span class="ms-auto badge rounded-pill px-3 py-2 fs-2 fw-bold"
                                  style="background:rgba(255,255,255,.18);color:#fff;">Vigente 2026</span>
                        </div>
                        <div class="card-body p-6">
                            <p class="text-muted fs-4 mb-4">
                                Acuerdo firmado en <strong>marzo 2026</strong> con vigencia del
                                <strong>1° de febrero 2026 al 31 de enero 2027</strong>.
                                Aumentos en tres tramos acumulativos más suma no remunerativa mensual.
                            </p>
                            <div class="table-responsive mb-4">
                                <table class="table table-bordered table-sm align-middle mb-0" style="font-size:13px;">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>Mes</th>
                                            <th>Aumento s/básico</th>
                                            <th>Suma NR mensual</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold">Febrero 2026</td>
                                            <td><span class="badge bg-success-subtle text-success fw-bold">+1,8%</span></td>
                                            <td>$80.000</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Marzo 2026</td>
                                            <td><span class="badge bg-success-subtle text-success fw-bold">+1,7%</span></td>
                                            <td>$85.000</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Abril 2026</td>
                                            <td><span class="badge bg-success-subtle text-success fw-bold">+1,6%</span></td>
                                            <td>$90.000</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="rounded-3 p-4 mb-4" style="background:#f8f9ff; border:1px solid #dde8ff;">
                                <p class="fs-3 mb-1 fw-bold text-primary">
                                    <i class="ti ti-calendar-event me-2"></i>Asignación especial
                                </p>
                                <p class="fs-3 text-muted mb-0">
                                    Pago único por el <strong>Día del Trabajador de la Sanidad (21 de septiembre)</strong>:
                                    <strong class="text-dark">$63.369,91</strong>
                                </p>
                            </div>
                            <a href="https://www.sanidad.org.ar/ContentManager/Files/ContentFileManager/acciongremial/cct_pdfs/c122/cct122_acuerdomarzo_2026.pdf"
                               target="_blank" rel="noopener"
                               class="btn btn-primary fw-bold w-100">
                                <i class="ti ti-file-download me-2"></i>Descargar acuerdo completo (PDF)
                            </a>
                        </div>
                    </div>
                </div>

                {{-- CCT 108/75 --}}
                <div class="col-lg-6">
                    <div class="card border-0 rounded-4 h-100 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 d-flex align-items-center gap-3"
                             style="background: linear-gradient(135deg, #0d5c3e 0%, #1D9E75 100%);">
                            <span class="rounded-3 p-3 d-flex" style="background:rgba(255,255,255,.15);">
                                <i class="ti ti-microscope text-white fs-7"></i>
                            </span>
                            <div>
                                <h4 class="text-white fw-bolder mb-0">CCT 108/75</h4>
                                <small class="text-white text-opacity-75">Diagnóstico e imagen médica (CADIME / CEDIM)</small>
                            </div>
                            <span class="ms-auto badge rounded-pill px-3 py-2 fs-2 fw-bold"
                                  style="background:rgba(255,255,255,.18);color:#fff;">Vigente 2026</span>
                        </div>
                        <div class="card-body p-6">
                            <p class="text-muted fs-4 mb-4">
                                Acuerdo simultáneo con el CCT 122/75. Misma vigencia y misma estructura de aumentos.
                                Aplica a trabajadores de laboratorios, centros de diagnóstico e imagen médica.
                            </p>
                            <div class="table-responsive mb-4">
                                <table class="table table-bordered table-sm align-middle mb-0" style="font-size:13px;">
                                    <thead style="background:#d6f5eb;">
                                        <tr>
                                            <th>Mes</th>
                                            <th>Aumento s/básico</th>
                                            <th>Suma NR mensual</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold">Febrero 2026</td>
                                            <td><span class="badge bg-success-subtle text-success fw-bold">+1,8%</span></td>
                                            <td>$80.000</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Marzo 2026</td>
                                            <td><span class="badge bg-success-subtle text-success fw-bold">+1,7%</span></td>
                                            <td>$85.000</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Abril 2026</td>
                                            <td><span class="badge bg-success-subtle text-success fw-bold">+1,6%</span></td>
                                            <td>$90.000</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="rounded-3 p-4 mb-4" style="background:#f0fdf8; border:1px solid #c0eedd;">
                                <p class="fs-3 mb-1 fw-bold text-success">
                                    <i class="ti ti-info-circle me-2"></i>¿Trabajás en diagnóstico?
                                </p>
                                <p class="fs-3 text-muted mb-0">
                                    Si tu empleador es un centro de diagnóstico o laboratorio privado, este es tu convenio.
                                    Consultá con el gremio para verificar tu encuadre correcto.
                                </p>
                            </div>
                            <a href="https://www.sanidad.org.ar/ContentManager/Files/ContentFileManager/acciongremial/cct_pdfs/c108/cct108_acuerdomarzo_2026.pdf"
                               target="_blank" rel="noopener"
                               class="btn fw-bold w-100"
                               style="background:#1D9E75; color:#fff;">
                                <i class="ti ti-file-download me-2"></i>Descargar acuerdo completo (PDF)
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Escala de referencia --}}
            <div class="card border-0 rounded-4 shadow-sm mb-6">
                <div class="card-body p-6">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <span class="rounded-3 p-3" style="background:#ECF2FF;">
                            <i class="ti ti-table text-primary fs-6"></i>
                        </span>
                        <div>
                            <h4 class="fw-bolder mb-0">Escalas de referencia — CCT 122/75 (básicos mensuales)</h4>
                            <small class="text-muted">Valores orientativos. Para la escala completa descargá el PDF oficial.</small>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="font-size:13.5px;">
                            <thead class="table-light">
                                <tr>
                                    <th>Categoría</th>
                                    <th class="text-end">Febrero 2026</th>
                                    <th class="text-end">Marzo 2026</th>
                                    <th class="text-end">Abril 2026</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ([
                                    ['Profesionales (Bioquímico, Nutricionista, Kinesiólogo)', '$1.328.002', '$1.350.578', '$1.372.188'],
                                    ['Técnico superior / Paramédico', '$1.200.000', '$1.220.400', '$1.239.967'],
                                    ['Auxiliar especializado / Administrativo A', '$1.090.000', '$1.108.630', '$1.126.768'],
                                    ['Auxiliar general / Administrativo B', '$1.010.000', '$1.027.170', '$1.043.625'],
                                    ['Personal de maestranza / Mucama', '$950.117', '$965.669', '$981.730'],
                                ] as $row)
                                    <tr>
                                        <td class="fw-semibold">{{ $row[0] }}</td>
                                        <td class="text-end text-muted">{{ $row[1] }}</td>
                                        <td class="text-end text-muted">{{ $row[2] }}</td>
                                        <td class="text-end fw-bold text-dark">{{ $row[3] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <p class="text-muted fs-3 mt-3 mb-0">
                        <i class="ti ti-alert-circle me-1"></i>
                        Valores de básico convencional. No incluyen antigüedad, presentismo, ni otras asignaciones
                        particulares del establecimiento. Consultá la escala completa en
                        <a href="https://www.sanidad.org.ar/acciongremial/cct/c122.aspx" target="_blank" rel="noopener" class="text-primary fw-bold">sanidad.org.ar</a>.
                    </p>
                </div>
            </div>

            {{-- Derechos y licencias especiales CCT 122/75 --}}
            <div class="card border-0 rounded-4 shadow-sm mb-6">
                <div class="card-body p-6">
                    <div class="d-flex align-items-center gap-3 mb-5">
                        <span class="rounded-3 p-3" style="background:#fff3e0;">
                            <i class="ti ti-shield-check fs-6" style="color:#e65100;"></i>
                        </span>
                        <div>
                            <h4 class="fw-bolder mb-0">Derechos y licencias especiales — CCT 122/75</h4>
                            <small class="text-muted">Lo que te corresponde por convenio, más allá del salario</small>
                        </div>
                    </div>
                    <div class="row g-3">
                        @foreach ([
                            ['ti-heart',         'warning', 'Casamiento',              '14 días corridos',          'Derecho a licencia paga desde el día de la ceremonia.'],
                            ['ti-baby-carriage', 'info',    'Nacimiento de hijo/a',    '3 días corridos',           'Contados desde el nacimiento o adopción.'],
                            ['ti-ribbon',        'danger',  'Fallecimiento de cónyuge o hijos', '7 días corridos', 'También aplica para padres, hermanos y suegros.'],
                            ['ti-ribbon',        'danger',  'Fallecimiento de padres o hermanos', '7 días corridos','Incluye nueras, yernos y cuñados directos.'],
                            ['ti-ribbon',        'secondary','Fallecimiento de abuelos', '2 días corridos',         'Abuelos, nietos y otros familiares de 2° grado.'],
                            ['ti-radioactive',   'success', 'Personal de radiología',  '14 días extra por año',    'Licencia especial adicional para trabajadores expuestos a radiaciones.'],
                            ['ti-stethoscope',   'primary', 'Examen médico preventivo','Tiempo necesario',         'Derecho a ausentarse para chequeos médicos sin descontar.'],
                            ['ti-sun',           'warning', 'Vacaciones',              'Según antigüedad',         '14 días hasta 5 años; 21 días entre 5 y 10; 28 días de 10 a 20; 35 días más de 20 años.'],
                        ] as $lic)
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="rounded-3 p-4 h-100" style="background:#f8fafc; border:1px solid #e8edf2;">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <span class="badge bg-{{ $lic[1] }}-subtle text-{{ $lic[1] }} rounded-2 p-2">
                                            <i class="ti {{ $lic[0] }} fs-5"></i>
                                        </span>
                                        <strong class="fs-3">{{ $lic[2] }}</strong>
                                    </div>
                                    <p class="fw-bolder fs-4 text-dark mb-1">{{ $lic[3] }}</p>
                                    <p class="fs-3 text-muted mb-0">{{ $lic[4] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <p class="text-muted fs-3 mt-4 mb-0">
                        <i class="ti ti-alert-circle me-1"></i>
                        Información basada en el texto del CCT 122/75 vigente. Para casos particulares o dudas sobre tu situación laboral, consultá con el gremio.
                        <a href="https://www.sanidad.org.ar/ContentManager/Files/ContentFileManager/acciongremial/cct_pdfs/c122/cct122_textocompleto_2025.pdf"
                           target="_blank" rel="noopener" class="text-primary fw-bold">Ver texto completo del convenio →</a>
                    </p>
                </div>
            </div>

            {{-- CTA Consulta --}}
            <div class="rounded-4 p-6 p-lg-8 d-flex flex-column flex-lg-row align-items-center justify-content-between gap-4"
                 style="background: linear-gradient(135deg, #1e3a5f 0%, #0d1f35 100%);">
                <div class="text-white">
                    <h4 class="fw-bolder mb-1">¿No sabés en qué convenio estás encuadrado?</h4>
                    <p class="mb-0 text-white text-opacity-75 fs-4">
                        Consultá con ATSA Tucumán. Asesoramos a trabajadores del sector privado en forma gratuita.
                    </p>
                </div>
                <div class="d-flex gap-3 flex-shrink-0 flex-wrap">
                    <a href="https://wa.me/543814331665" target="_blank" rel="noopener"
                       class="btn btn-success fw-bold py-7 px-8">
                        <i class="ti ti-brand-whatsapp me-2"></i>Consultar por WhatsApp
                    </a>
                    <a href="{{ route('contacto.index') }}"
                       class="btn fw-bold py-7 px-8"
                       style="background:rgba(255,255,255,.1);color:#fff;border:1px solid rgba(255,255,255,.2);">
                        <i class="ti ti-mail me-2"></i>Escribir al gremio
                    </a>
                </div>
            </div>

        </div>
    </section>

    {{-- DERECHOS DEL TRABAJADOR --}}
    <section class="py-10 py-lg-14 bg-white" id="derechos">
        <div class="container-fluid">
            <div class="row g-5">
                <div class="col-lg-5">
                    <p class="text-primary fs-4 fw-bolder mb-2">DERECHOS</p>
                    <h2 class="fw-bolder fs-9 mb-4">Derechos del trabajador de la sanidad</h2>
                    <p class="fs-4 text-body mb-5">
                        Conocer tus derechos es el primer paso para defenderlos. El sindicato está para
                        acompañarte en cada situación laboral, desde una consulta hasta un conflicto grave.
                    </p>
                    <div class="rounded-3 p-5" style="background: linear-gradient(135deg, #1e3a5f, #0f2236);">
                        <h4 class="text-white fw-bolder mb-3">¿Necesitás asesoramiento?</h4>
                        <p class="text-white text-opacity-75 fs-4 mb-4">
                            Acercate a cualquier filial de ATSA o escribinos por WhatsApp.
                            La atención gremial es gratuita para todos los afiliados.
                        </p>
                        <a href="https://wa.me/543814331665" target="_blank" rel="noopener"
                           class="btn btn-success fw-bold py-7 px-9">
                            <i class="ti ti-brand-whatsapp me-2"></i>Consultar por WhatsApp
                        </a>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="accordion gremial-accordion" id="faqDerechos">
                        @foreach ([
                            ['¿Qué hace ATSA por los trabajadores?',
                             'ATSA Tucumán representa colectivamente a los trabajadores de la sanidad, negocia paritarias con el Gobierno Provincial, acompaña reclamos laborales individuales y colectivos, y gestiona beneficios sociales, turísticos y educativos para los afiliados y sus familias.'],
                            ['¿Cómo reclamar ante un despido injustificado?',
                             'Acercate de inmediato al sindicato con telegramas, recibos de sueldo y documentación laboral. ATSA brinda asesoramiento jurídico gratuito para afiliados ante situaciones de conflicto laboral.'],
                            ['¿Qué es el Convenio Colectivo de Trabajo?',
                             'Es la norma que regula los derechos, categorías, salarios mínimos y condiciones de trabajo del sector sanitario. ATSA negocia y vigila su cumplimiento en clínicas, sanatorios, laboratorios y demás establecimientos de salud.'],
                            ['¿Puedo afiliarme si trabajo en una farmacia o laboratorio?',
                             'Sí, si tu actividad corresponde al encuadre del sector salud o sanidad. ATSA representa a trabajadores de clínicas, sanatorios, farmacias, laboratorios, droguerías, y establecimientos vinculados al sector sanitario en Tucumán.'],
                            ['¿Qué beneficios tiene un afiliado activo?',
                             'Los afiliados acceden a asesoramiento gremial y jurídico, representación en paritarias, beneficios de turismo y recreación (Ciudad Deportiva, Hotel ATSA Termas), acción social (anteojos, prótesis, medicamentos), formación en el CENT N°74 y atención personalizada en filiales.'],
                            ['¿Dónde consulto las escalas salariales vigentes?',
                             'Las escalas salariales actualizadas están disponibles en la sección "Escalas salariales" del sitio, donde también encontrás el historial de acuerdos paritarios. También podés consultar en tu filial más cercana.'],
                        ] as $faq)
                            <div class="accordion-item border-0 mb-3 rounded-3 overflow-hidden bg-white shadow-sm">
                                <h2 class="accordion-header">
                                    <button class="accordion-button {{ $loop->index > 0 ? 'collapsed' : '' }} fw-bold py-4 px-5 fs-4"
                                            type="button" data-bs-toggle="collapse"
                                            data-bs-target="#derecho{{ $loop->index }}">
                                        <i class="ti ti-help-circle text-primary me-3 fs-5 flex-shrink-0"></i>
                                        {{ $faq[0] }}
                                    </button>
                                </h2>
                                <div id="derecho{{ $loop->index }}"
                                     class="accordion-collapse collapse {{ $loop->index === 0 ? 'show' : '' }}"
                                     data-bs-parent="#faqDerechos">
                                    <div class="accordion-body pt-0 pb-5 px-5 ps-md-12">
                                        <p class="text-muted fs-4 mb-0">{{ $faq[1] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA AFILIACIÓN --}}
    <section class="py-10 py-lg-14">
        <div class="container-fluid">
            <div class="rounded-3 overflow-hidden p-7 p-lg-12 position-relative"
                 style="background: linear-gradient(135deg, #1e3a5f 0%, #0d1f35 100%);">
                <div class="row align-items-center g-5">
                    <div class="col-lg-7 text-white">
                        <span class="badge rounded-pill px-4 py-2 mb-4 fs-3 fw-semibold"
                              style="background:rgba(255,255,255,.1);color:#d8f4ff;border:1px solid rgba(255,255,255,.12);">
                            SUMATE AL SINDICATO
                        </span>
                        <h2 class="text-white fw-bolder fs-9 mb-3">
                            ¿Trabajás en la sanidad tucumana y querés estar representado?
                        </h2>
                        <p class="fs-5 mb-0" style="color:rgba(255,255,255,.82);">
                            Afiliarte a ATSA Tucumán es contar con respaldo gremial, asesoramiento legal, beneficios
                            para tu familia y una organización con más de 100 años al servicio del trabajador de la salud.
                        </p>
                    </div>
                    <div class="col-lg-5">
                        <div class="d-grid gap-3">
                            <a href="{{ route('afiliacion.create') }}"
                               class="btn btn-light text-primary fw-bold py-9 fs-5">
                                <i class="ti ti-user-plus me-2"></i>Afiliarme ahora
                            </a>
                            <a href="{{ route('afiliados.index') }}#beneficios"
                               class="btn fw-bold py-9 fs-5"
                               style="background:rgba(255,255,255,.08);color:#fff;border:1px solid rgba(255,255,255,.18);">
                                <i class="ti ti-gift me-2"></i>Ver beneficios para afiliados
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
    .gremial-feature-card {
        box-shadow: 0 12px 32px rgba(42,53,71,.07);
        transition: transform .25s ease, box-shadow .25s ease;
    }
    .gremial-feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 48px rgba(42,53,71,.13);
    }
    .gremial-icon-box {
        width: 60px;
        height: 60px;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
    }
    .gremial-resource-card {
        box-shadow: 0 12px 32px rgba(42,53,71,.07);
        transition: transform .25s ease, box-shadow .25s ease;
    }
    .gremial-resource-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 48px rgba(42,53,71,.13);
    }
    .gremial-resource-icon {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .gremial-accordion .accordion-button {
        background: transparent;
        box-shadow: none;
    }
    .gremial-accordion .accordion-button:not(.collapsed) {
        background: #f0f6ff;
        color: #1e3a5f;
    }
</style>
@endpush
