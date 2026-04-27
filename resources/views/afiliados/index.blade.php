@extends('layouts.app')

@section('title', 'Afiliados | ATSA Tucumán')
@section('meta_description', 'Beneficios para afiliados de ATSA Tucumán: asesoramiento gremial, acción social, turismo, formación, convenios y trámites.')

@php
    use App\Models\Beneficio;

    $categoriaLabels = Beneficio::categorias();
    $categoriaColors = [
        'gremial' => '#1e3a5f',
        'accion_social' => '#e46a76',
        'turismo' => '#49beff',
        'formacion' => '#13deb9',
        'convenios' => '#ffae1f',
        'tramites' => '#5d87ff',
        'salud' => '#13deb9',
    ];

    $fallbackBeneficios = collect([
        (object) [
            'titulo' => 'Asesoramiento jurídico laboral',
            'categoria' => 'gremial',
            'descripcion_corta' => 'Orientación ante conflictos laborales, sanciones, despidos y reclamos salariales.',
            'descripcion_larga' => null,
            'imagen_url' => null,
            'icono' => 'ti-scale',
            'link' => route('contacto.index'),
            'solo_afiliados' => false,
            'destacado' => true,
        ],
        (object) [
            'titulo' => 'Turismo y recreación',
            'categoria' => 'turismo',
            'descripcion_corta' => 'Ciudad Deportiva, Hotel ATSA Termas y convenios turísticos vinculados a FATSA.',
            'descripcion_larga' => null,
            'imagen_url' => asset('images/turismo/ciudad-deportiva/ingreso-atsa.jpeg'),
            'icono' => 'ti-beach',
            'link' => route('turismo.index'),
            'solo_afiliados' => false,
            'destacado' => true,
        ],
        (object) [
            'titulo' => 'Formación profesional',
            'categoria' => 'formacion',
            'descripcion_corta' => 'Carreras técnicas y capacitación para jerarquizar a los trabajadores de la salud.',
            'descripcion_larga' => null,
            'imagen_url' => asset('images/historia/formacion-cent-74.jpg'),
            'icono' => 'ti-school',
            'link' => 'https://cent74atsatucuman.ar',
            'solo_afiliados' => false,
            'destacado' => true,
        ],
    ]);

    $items = ($beneficios ?: collect())->count() ? $beneficios : $fallbackBeneficios;
    $destacados = ($beneficiosDestacados ?: collect())->count() ? $beneficiosDestacados : $items->where('destacado', true)->take(4);
    $porCategoria = ($beneficiosPorCategoria ?: collect())->count() ? $beneficiosPorCategoria : $items->groupBy('categoria');
@endphp

@section('content')
    <section class="atsa-page-hero py-14 position-relative" style="background-image: url('{{ asset('images/historia/movilizacion-atsa-sanidad.jpg') }}'); min-height: clamp(320px, 55vw, 560px);">
        <div class="container-fluid position-relative z-1">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <span class="badge bg-white text-primary fs-3 fw-bolder px-3 py-2 mb-4">
                        <i class="ti ti-users me-1"></i>AFILIADOS
                    </span>
                    <h1 class="fw-bolder display-4 text-white mb-4">Beneficios y respaldo para la familia de la sanidad</h1>
                    <p class="fs-5 text-white text-opacity-75 mb-5">Ser parte de ATSA Tucumán es contar con acompañamiento gremial, formación, turismo, acción social y atención cercana en cada etapa de tu vida laboral.</p>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="{{ route('afiliacion.create') }}" class="btn btn-light text-primary fw-bold py-8 px-10">
                            <i class="ti ti-user-plus me-2"></i>Quiero afiliarme
                        </a>
                        <a href="#beneficios" class="btn btn-outline-light fw-bold py-8 px-10">
                            <i class="ti ti-gift me-2"></i>Ver beneficios
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-10 py-lg-14 bg-light">
        <div class="container-fluid">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="premium-info-card h-100">
                        <span class="premium-info-icon"><i class="ti ti-shield-check"></i></span>
                        <h3 class="fw-bolder fs-6 mb-2">Respaldo gremial</h3>
                        <p class="text-muted fs-4 mb-0">Defensa de derechos, orientación y presencia institucional para cada trabajador de la sanidad.</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="premium-info-card h-100">
                        <span class="premium-info-icon"><i class="ti ti-heart-handshake"></i></span>
                        <h3 class="fw-bolder fs-6 mb-2">Acompañamiento real</h3>
                        <p class="text-muted fs-4 mb-0">Acción social, pedidos, consultas y atención personalizada desde filiales y portal privado.</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="premium-info-card h-100">
                        <span class="premium-info-icon"><i class="ti ti-beach"></i></span>
                        <h3 class="fw-bolder fs-6 mb-2">Beneficios familiares</h3>
                        <p class="text-muted fs-4 mb-0">Turismo, recreación, formación y convenios para mejorar la calidad de vida de la familia afiliada.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-10 py-lg-16" id="beneficios">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-8">
                <div>
                    <p class="text-primary fs-4 fw-bolder mb-2">BENEFICIOS DESTACADOS</p>
                    <h2 class="fw-bolder fs-9 mb-0">Servicios pensados para vos y tu familia</h2>
                </div>
                <a href="{{ route('afiliados.login') }}" class="btn btn-outline-primary fw-bold">
                    <i class="ti ti-lock me-2"></i>Ingresar al área afiliados
                </a>
            </div>

            <div class="row g-4">
                @foreach ($destacados as $beneficio)
                    @php
                        $color = $categoriaColors[$beneficio->categoria] ?? '#1e3a5f';
                        $link = $beneficio->link ?: route('afiliados.login');
                    @endphp
                    <div class="col-xl-3 col-md-6">
                        <article class="benefit-feature-card h-100">
                            @if ($beneficio->imagen_url)
                                <img src="{{ $beneficio->imagen_url }}" alt="{{ $beneficio->titulo }}" class="benefit-feature-img">
                            @else
                                <div class="benefit-feature-empty" style="background: {{ $color }};">
                                    <i class="ti {{ $beneficio->icono ?: 'ti-gift' }}"></i>
                                </div>
                            @endif
                            <div class="p-5 d-flex flex-column h-100">
                                <span class="benefit-category-pill" style="--benefit-color: {{ $color }};">{{ $categoriaLabels[$beneficio->categoria] ?? $beneficio->categoria }}</span>
                                <h3 class="fw-bolder fs-5 mt-4 mb-2">{{ $beneficio->titulo }}</h3>
                                <p class="text-muted fs-3 flex-grow-1">{{ $beneficio->descripcion_corta }}</p>
                                <div class="d-flex align-items-center justify-content-between gap-3 mt-3">
                                    <a href="{{ $link }}" class="text-primary fw-bold">Ver detalle <i class="ti ti-arrow-right"></i></a>
                                    @if ($beneficio->solo_afiliados)
                                        <span class="badge bg-primary-subtle text-primary">Afiliados</span>
                                    @endif
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-10 py-lg-14 bg-light">
        <div class="container-fluid">
            <div class="text-center mb-8">
                <p class="text-primary fs-4 fw-bolder mb-2">TODOS LOS BENEFICIOS</p>
                <h2 class="fw-bolder fs-9">Organizados por área</h2>
            </div>

            <div class="row g-4">
                @foreach ($porCategoria as $categoria => $grupo)
                    @php $color = $categoriaColors[$categoria] ?? '#1e3a5f'; @endphp
                    <div class="col-lg-6">
                        <div class="category-benefit-card h-100">
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <span class="category-dot" style="background: {{ $color }};"></span>
                                <h3 class="fw-bolder fs-5 mb-0">{{ $categoriaLabels[$categoria] ?? ucfirst($categoria) }}</h3>
                            </div>
                            <div class="d-flex flex-column gap-3">
                                @foreach ($grupo as $beneficio)
                                    <div class="d-flex gap-3">
                                        <span class="round-40 rounded-circle hstack justify-content-center bg-primary-subtle text-primary flex-shrink-0">
                                            <i class="ti {{ $beneficio->icono ?: 'ti-gift' }}"></i>
                                        </span>
                                        <div>
                                            <h4 class="fw-bolder fs-4 mb-1">{{ $beneficio->titulo }}</h4>
                                            <p class="text-muted mb-0">{{ $beneficio->descripcion_corta }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-10 py-lg-16" id="turismo-preview">
        <div class="container-fluid">
            <div class="rounded-3 overflow-hidden position-relative p-7 p-lg-12" style="background: linear-gradient(90deg, rgba(15, 34, 54, .94), rgba(30, 58, 95, .72)), url('{{ asset('images/turismo/ciudad-deportiva/ingreso-atsa.jpeg') }}') center/cover;">
                <div class="row align-items-center g-5">
                    <div class="col-lg-7">
                        <p class="text-white fs-4 fw-bolder mb-2">TURISMO Y RECREACIÓN</p>
                        <h2 class="text-white fw-bolder fs-9 mb-3">Ciudad Deportiva, Hotel ATSA y convenios FATSA</h2>
                        <p class="text-white text-opacity-75 fs-4 mb-5">Un beneficio clave para el descanso, el deporte y la recreación familiar de los afiliados.</p>
                        <a href="{{ route('turismo.index') }}" class="btn btn-light text-primary fw-bold py-7 px-9">
                            <i class="ti ti-beach me-2"></i>Ver turismo
                        </a>
                    </div>
                    <div class="col-lg-5">
                        <div class="row g-3">
                            <div class="col-6"><img src="{{ asset('images/turismo/hotel-atsa-termas-fachada.webp') }}" class="w-100 rounded-3 object-fit-cover" style="height: 150px;" alt="Hotel ATSA"></div>
                            <div class="col-6"><img src="{{ asset('images/turismo/ciudad-deportiva/pileta-familia.jpeg') }}" class="w-100 rounded-3 object-fit-cover" style="height: 150px;" alt="Ciudad Deportiva"></div>
                            <div class="col-12"><img src="{{ asset('images/turismo/convenios/hotel-sanidad-mar-del-plata.webp') }}" class="w-100 rounded-3 object-fit-cover" style="height: 150px;" alt="Convenios FATSA"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-10 py-lg-16 bg-light" id="afiliarse">
        <div class="container-fluid">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6">
                    <p class="text-primary fs-4 fw-bolder mb-2">AFILIACIÓN</p>
                    <h2 class="fw-bolder fs-9 mb-5">Cómo afiliarse</h2>
                    <div class="d-flex flex-column gap-4">
                        @foreach ([
                            ['num' => '1', 'title' => 'Acercate a tu filial', 'desc' => 'Elegí la sede más cercana o iniciá el trámite online.'],
                            ['num' => '2', 'title' => 'Presentá documentación', 'desc' => 'DNI y recibo de sueldo del último mes.'],
                            ['num' => '3', 'title' => 'Completá el formulario', 'desc' => 'Datos personales, laborales y documentación respaldatoria.'],
                            ['num' => '4', 'title' => 'Ya sos parte de ATSA', 'desc' => 'Accedés al portal privado, carnet digital y beneficios.'],
                        ] as $step)
                            <div class="step-modern-card">
                                <span>{{ $step['num'] }}</span>
                                <div>
                                    <h4 class="fw-bolder mb-1">{{ $step['title'] }}</h4>
                                    <p class="text-muted mb-0">{{ $step['desc'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="join-card text-white">
                        <i class="ti ti-user-plus"></i>
                        <h3 class="fw-bolder fs-7 mt-4 mb-3">¿Querés afiliarte?</h3>
                        <p class="text-white text-opacity-75 fs-4 mb-5">Completá la solicitud online o acercate a tu filial para recibir orientación.</p>
                        <a href="{{ route('afiliacion.create') }}" class="btn btn-light text-primary fw-bold py-8 px-10">
                            Completar solicitud
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-10 py-lg-16" id="faq">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="text-center mb-8">
                        <p class="text-primary fs-4 fw-bolder mb-2">PREGUNTAS FRECUENTES</p>
                        <h2 class="fw-bolder fs-9">Afiliación y acceso a beneficios</h2>
                    </div>
                    <div class="accordion accordion-modern" id="faqAfiliados">
                        @foreach ([
                            ['q' => '¿Quiénes pueden afiliarse?', 'a' => 'Trabajadores del sector salud, clínicas, sanatorios, laboratorios, farmacias y áreas vinculadas al sector sanitario de Tucumán.'],
                            ['q' => '¿Qué documentación necesito?', 'a' => 'DNI, recibo de sueldo y formulario de afiliación. Según el caso, ATSA puede solicitar documentación adicional.'],
                            ['q' => '¿Cómo accedo a los beneficios?', 'a' => 'Con tu número de afiliado podés consultar en filiales o ingresar al portal privado de afiliados.'],
                            ['q' => '¿Los beneficios turísticos requieren reserva?', 'a' => 'Sí. Los espacios turísticos y recreativos están sujetos a disponibilidad y reglamento vigente.'],
                        ] as $index => $faq)
                            <div class="accordion-item border-0 mb-3 rounded-3 overflow-hidden bg-white shadow-sm">
                                <h2 class="accordion-header">
                                    <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }} fw-bold py-5 px-5 fs-5" type="button" data-bs-toggle="collapse" data-bs-target="#faq{{ $index }}">
                                        <i class="ti ti-help-circle text-primary me-3 fs-5"></i>{{ $faq['q'] }}
                                    </button>
                                </h2>
                                <div id="faq{{ $index }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" data-bs-parent="#faqAfiliados">
                                    <div class="accordion-body pt-0 pb-5 px-5">
                                        <p class="text-muted fs-4 mb-0 ps-8">{{ $faq['a'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    .premium-info-card,
    .benefit-feature-card,
    .category-benefit-card,
    .step-modern-card,
    .join-card {
        border-radius: 8px;
        background: #fff;
        border: 1px solid rgba(30, 58, 95, .08);
        box-shadow: 0 16px 38px rgba(42, 53, 71, .08);
    }
    .premium-info-card {
        padding: 28px;
    }
    .premium-info-icon {
        width: 54px;
        height: 54px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: #ecf2ff;
        color: #1e3a5f;
        font-size: 28px;
        margin-bottom: 18px;
    }
    .benefit-feature-card {
        overflow: hidden;
        transition: transform .25s ease, box-shadow .25s ease;
    }
    .benefit-feature-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 24px 52px rgba(42, 53, 71, .14);
    }
    .benefit-feature-img,
    .benefit-feature-empty {
        width: 100%;
        height: 190px;
        object-fit: cover;
    }
    .benefit-feature-empty {
        display: grid;
        place-items: center;
        color: #fff;
        font-size: 48px;
    }
    .benefit-category-pill {
        display: inline-flex;
        width: fit-content;
        border-radius: 999px;
        padding: 7px 13px;
        background: color-mix(in srgb, var(--benefit-color) 13%, white);
        color: var(--benefit-color);
        font-weight: 800;
        font-size: 12px;
    }
    .category-benefit-card {
        padding: 28px;
    }
    .category-dot {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        box-shadow: 0 0 0 7px rgba(93, 135, 255, .1);
    }
    .step-modern-card {
        display: flex;
        gap: 18px;
        align-items: flex-start;
        padding: 20px;
    }
    .step-modern-card span {
        width: 52px;
        height: 52px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 auto;
        border-radius: 8px;
        background: #1e3a5f;
        color: #fff;
        font-weight: 800;
        font-size: 20px;
    }
    .join-card {
        padding: 48px;
        background: linear-gradient(135deg, #1e3a5f 0%, #0f2236 100%);
    }
    .join-card > i {
        width: 86px;
        height: 86px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: rgba(255, 255, 255, .12);
        font-size: 42px;
    }
    .accordion-modern .accordion-button {
        background: transparent;
        box-shadow: none;
    }
</style>
@endpush
