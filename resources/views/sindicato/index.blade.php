@extends('layouts.app')

@section('title', 'El Sindicato | ATSA Tucumán')
@section('meta_description', 'Historia, autoridades y estructura del sindicato ATSA Tucumán. Más de 100 años representando a los trabajadores de la sanidad en Tucumán.')
@section('og_image', asset('images/historia/movilizacion-atsa-sanidad.jpg'))

@php
    use App\Models\Autoridad;
    use App\Models\PageSection;

    $sindicatoHero   = PageSection::get('sindicato', 'hero');
    $sindicatoMision = PageSection::get('sindicato', 'mision');
    $sindicatoHeroImage = $sindicatoHero?->imageUrl(asset('images/historia/movilizacion-atsa-sanidad.jpg')) ?: asset('images/historia/movilizacion-atsa-sanidad.jpg');
    $sindicatoHeroLabel = $sindicatoHero?->label ?: 'INSTITUCIONAL';
    $sindicatoHeroTitle = $sindicatoHero?->title ?: 'El Sindicato';
    $sindicatoHeroSubtitle = $sindicatoHero?->subtitle ?: '100 años defendiendo a la sanidad tucumana';

    $autoridadesDb = Autoridad::query()
        ->where('activo', true)
        ->orderBy('orden')
        ->get();

    $autoridades = $autoridadesDb->isNotEmpty()
        ? $autoridadesDb->map(fn (Autoridad $autoridad) => (object) [
            'nombre' => $autoridad->nombre,
            'cargo' => $autoridad->cargo,
            'foto_url' => $autoridad->foto_url,
        ])
        : collect([
            (object) ['nombre' => 'Renee Ramirez', 'cargo' => 'Secretario General', 'foto_url' => asset('images/autoridades/renee-ramirez.jpg')],
            (object) ['nombre' => 'Dario Ramirez', 'cargo' => 'Secretario Adjunto', 'foto_url' => asset('images/autoridades/dario-ramirez.jpeg')],
            (object) ['nombre' => 'Mabel Aguirre', 'cargo' => 'Secretaria de Finanzas', 'foto_url' => asset('images/autoridades/mabel-aguirre.png')],
            (object) ['nombre' => 'Alejandra Ferreyra', 'cargo' => 'Secretaria Gremial', 'foto_url' => asset('images/autoridades/alejandra-ferreyra.png')],
        ]);
@endphp

@section('content')
    <section class="atsa-page-hero py-14 position-relative" style="background-image: url('{{ $sindicatoHeroImage }}'); min-height: 500px;">
        <div class="container-fluid text-center position-relative z-1">
            <span class="section-badge bg-white bg-opacity-10 text-white mb-3">
                <i class="ti ti-building"></i>
                {{ $sindicatoHeroLabel }}
            </span>
            <h1 class="fw-bolder display-4 text-white mb-4">{{ $sindicatoHeroTitle }}</h1>
            <p class="fs-5 text-white text-opacity-80 mb-0 col-lg-6 mx-auto">{{ $sindicatoHeroSubtitle }}</p>
        </div>
        <div class="position-absolute bottom-0 start-0 w-100">
            <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 80L60 72C120 64 240 48 360 40C480 32 600 32 720 36C840 40 960 48 1080 52C1200 56 1320 56 1380 56L1440 56V80H1380C1320 80 1200 80 1080 80C960 80 840 80 720 80C600 80 480 80 360 80C240 80 120 80 60 80H0Z" fill="white"/>
            </svg>
        </div>
    </section>

    <section class="py-10 py-lg-16">
        <div class="container-fluid">
            @include('sindicato.partials.historia-gremio')

            <div class="text-center mb-10" id="mision-valores">
                <div class="section-badge bg-primary-subtle text-primary mb-3">
                    <i class="ti ti-heart"></i>
                    MISIÓN Y VALORES
                </div>
                <h2 class="fw-bolder fs-9 mb-3">{{ $sindicatoMision?->title ?: 'Lo que guía nuestra tarea' }}</h2>
                <p class="text-muted fs-5 col-lg-6 mx-auto">{{ $sindicatoMision?->subtitle ?: 'Principios fundamentales que sostienen nuestro compromiso con los trabajadores' }}</p>
            </div>

            <div class="row g-4 mb-12">
                @foreach ([
                    ['icon' => 'ti-shield-check', 'title' => 'Representación', 'desc' => 'Defendemos los derechos laborales de cada trabajador del sector salud con compromiso y dedicación.', 'color' => 'primary'],
                    ['icon' => 'ti-school', 'title' => 'Formación', 'desc' => 'Impulsamos el desarrollo profesional a través del CENT N°74 y capacitaciones continuas.', 'color' => 'success'],
                    ['icon' => 'ti-users', 'title' => 'Solidaridad', 'desc' => 'Construimos comunidad entre trabajadores de la sanidad, fortaleciendo los lazos gremiales.', 'color' => 'warning']
                ] as $card)
                    <div class="col-lg-4">
                        <div class="feature-card bg-{{ $card['color'] }}-subtle p-7 text-center h-100">
                            <div class="feature-icon bg-{{ $card['color'] }} text-white mx-auto">
                                <i class="ti {{ $card['icon'] }}"></i>
                            </div>
                            <h4 class="fw-bold mb-3">{{ $card['title'] }}</h4>
                            <p class="text-muted fs-4 mb-0">{{ $card['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mb-10" id="autoridades">
                <div class="section-badge bg-primary-subtle text-primary mb-3">
                    <i class="ti ti-users"></i>
                    AUTORIDADES
                </div>
                <h2 class="fw-bolder fs-9 mb-3">Conducción institucional</h2>
                <p class="text-muted fs-5 col-lg-6 mx-auto">Las autoridades que representan a los trabajadores de la sanidad en Tucumán</p>
            </div>

            <div class="row g-4">
                @foreach ($autoridades as $person)
                    <div class="col-lg-3 col-md-6">
                        <div class="feature-card border-0 p-6 text-center h-100">
                            <div class="position-relative mx-auto mb-5" style="width: 140px; height: 140px;">
                                <img src="{{ $person->foto_url ?: 'https://ui-avatars.com/api/?name=' . urlencode($person->nombre) . '&size=200&background=1e3a5f&color=fff' }}"
                                     class="rounded-circle w-100 h-100 object-fit-cover border border-4 border-white shadow-lg"
                                     alt="{{ $person->nombre }}">
                                <div class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="ti ti-check"></i>
                                </div>
                            </div>
                            <h4 class="fw-bold mb-2">{{ $person->nombre }}</h4>
                            <span class="badge bg-primary-subtle text-primary fs-3 px-3 py-2">{{ $person->cargo }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ══════════ ORGANIGRAMA ══════════ --}}
    <section class="py-10 py-lg-16 bg-light" id="organigrama">
        <div class="container-fluid">
            <div class="text-center mb-10">
                <div class="section-badge bg-primary-subtle text-primary mb-3">
                    <i class="ti ti-sitemap"></i>
                    ESTRUCTURA
                </div>
                <h2 class="fw-bolder fs-9 mb-3">Organigrama institucional</h2>
                <p class="text-muted fs-5 col-lg-6 mx-auto">Estructura de conducción y comisiones de ATSA Tucumán</p>
            </div>

            @php
                $mesaDirectiva = [
                    ['Secretario General', 'Ramírez Edgar Reneé', 'ti-crown'],
                    ['Secretario General Adjunto', 'Ramírez Darío Reneé', 'ti-user-star'],
                    ['Secretaria de Finanzas', 'Aguirre Graciela Mabel', 'ti-cash-banknote'],
                    ['Prosecretaria de Finanzas', 'Ramírez Yvone Melisa', 'ti-report-money'],
                    ['Secretaria Gremial', 'Ferreyra Alejandra Judith', 'ti-scale'],
                    ['Prosecretaria Gremial', 'Rodríguez Myrian del Valle', 'ti-users'],
                ];

                $secretarias = [
                    ['Secretario de Prensa y Propaganda', 'Castro Daniel Agustín'],
                    ['Secretaria de Previsión y Acción Social', 'Arancibia Silvia Liliana'],
                    ['Secretario del Interior', 'Álvarez Arturo Bernardo'],
                    ['Secretario de Actas', 'Gutiérrez Rodolfo Oscar'],
                    ['Secretario de Turismo y Vivienda', 'Castro Aldo Fabián'],
                    ['Secretario de Deportes y Juventud', 'Rodríguez Andrés Carmelo'],
                    ['Prosecretario de Deportes y Juventud', 'Paz Claudio Alejandro'],
                    ['Sec. de Igualdad de Oportunidades y Género', 'Musa Sandra Beatriz'],
                    ['Sec. de Capacitación y Formación Profesional', 'Santillán Marcelo'],
                    ['Prosec. de Capacitación y Formación Profesional', 'Sultane José'],
                    ['Sec. de Org. y Relaciones Institucionales', 'Peña Dávila Silvio José Ariel'],
                    ['Sec. de Higiene, Seg. y Medicina del Trabajo', 'Avellaneda Sergio Ricardo'],
                ];

                $vocalesTitulares = [
                    ['Vocal Titular 1°', 'Juárez Emma Evangelina'],
                    ['Vocal Titular 2°', 'Gómez Silvina Raquel'],
                    ['Vocal Titular 3°', 'Aparicio Marcelo Alejandro'],
                    ['Vocal Titular 4°', 'Pérez Juan Faustino'],
                    ['Vocal Titular 5°', 'Bulacio Marcela Roxana'],
                    ['Vocal Titular 6°', 'Saavedra María Eugenia'],
                ];

                $vocalesSuplentes = [
                    ['Vocal Suplente 1°', 'Orellana José Luis'],
                    ['Vocal Suplente 2°', 'Di Santi Claudio José'],
                    ['Vocal Suplente 3°', 'Zalazar José Miguel'],
                    ['Vocal Suplente 4°', 'Ramírez Héctor Mario'],
                    ['Vocal Suplente 5°', 'Mancilla Rosa Isabel'],
                    ['Vocal Suplente 6°', 'Ávila Andrea Carolina'],
                ];

                $comisionRevisora = [
                    ['Miembro Titular 1°', 'Suárez Nora Gladys'],
                    ['Miembro Titular 2°', 'Moreira Antonia Hortencia'],
                    ['Miembro Titular 3°', 'Rodríguez Raúl Ernesto'],
                ];
            @endphp

            {{-- Comisión Directiva --}}
            <div class="card border-0 rounded-4 shadow-sm mb-6">
                <div class="card-body p-6">
                    <div class="text-center mb-5">
                        <span class="badge px-4 py-2 fw-bolder fs-4" style="background:#1e3a5f;color:#fff;border-radius:50px;">
                            COMISIÓN DIRECTIVA
                        </span>
                    </div>
                    <div class="row g-3 justify-content-center">
                        @foreach ($mesaDirectiva as [$cargo, $nombre, $icon])
                            <div class="col-xl-4 col-lg-6">
                                <div class="d-flex align-items-center gap-3 p-3 rounded-3 bg-light border h-100">
                                    <div style="width:42px;height:42px;border-radius:10px;background:#ecf2ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i class="ti {{ $icon }}" style="font-size:19px;color:#1e3a5f;"></i>
                                    </div>
                                    <div>
                                        <p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#49beff;margin-bottom:2px;">{{ $cargo }}</p>
                                        <p style="font-size:14px;font-weight:700;color:#1e293b;margin:0;">{{ $nombre }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-xl-7">
                    <div class="card border-0 rounded-4 shadow-sm h-100">
                        <div class="card-body p-5">
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <div class="feature-icon bg-primary-subtle text-primary">
                                    <i class="ti ti-building-community"></i>
                                </div>
                                <div>
                                    <span class="text-primary fw-bolder text-uppercase" style="font-size:12px;letter-spacing:.08em;">Secretarías</span>
                                    <h3 class="fw-bolder mb-0">Áreas de conducción</h3>
                                </div>
                            </div>
                            <div class="row g-3">
                                @foreach ($secretarias as [$cargo, $nombre])
                                    <div class="col-lg-6">
                                        <div class="p-3 rounded-3 border h-100" style="background:#f8fbff;">
                                            <p style="font-size:10.5px;font-weight:800;text-transform:uppercase;letter-spacing:.06em;color:#5d87ff;margin-bottom:4px;">{{ $cargo }}</p>
                                            <p class="fw-bold mb-0 text-dark">{{ $nombre }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-5">
                    <div class="card border-0 rounded-4 shadow-sm h-100">
                        <div class="card-body p-5">
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <div class="feature-icon bg-info-subtle text-info">
                                    <i class="ti ti-users-group"></i>
                                </div>
                                <div>
                                    <span class="text-info fw-bolder text-uppercase" style="font-size:12px;letter-spacing:.08em;">Vocalías</span>
                                    <h3 class="fw-bolder mb-0">Titulares y suplentes</h3>
                                </div>
                            </div>

                            <h5 class="fw-bolder mb-3">Vocales titulares</h5>
                            <div class="d-flex flex-column gap-2 mb-4">
                                @foreach ($vocalesTitulares as [$cargo, $nombre])
                                    <div class="d-flex justify-content-between gap-3 p-2 rounded-3" style="background:#f8fbff;">
                                        <span class="text-muted">{{ $cargo }}</span>
                                        <strong class="text-end">{{ $nombre }}</strong>
                                    </div>
                                @endforeach
                            </div>

                            <h5 class="fw-bolder mb-3">Vocales suplentes</h5>
                            <div class="d-flex flex-column gap-2">
                                @foreach ($vocalesSuplentes as [$cargo, $nombre])
                                    <div class="d-flex justify-content-between gap-3 p-2 rounded-3" style="background:#f8fbff;">
                                        <span class="text-muted">{{ $cargo }}</span>
                                        <strong class="text-end">{{ $nombre }}</strong>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 rounded-4 shadow-sm mt-4">
                <div class="card-body p-5">
                    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="feature-icon bg-warning-subtle text-warning">
                                <i class="ti ti-report-analytics"></i>
                            </div>
                            <div>
                                <span class="text-warning fw-bolder text-uppercase" style="font-size:12px;letter-spacing:.08em;">Control institucional</span>
                                <h3 class="fw-bolder mb-1">Comisión Revisora de Cuentas Titulares</h3>
                                <p class="text-muted mb-0">Órgano de revisión y control de la vida institucional del sindicato.</p>
                            </div>
                        </div>
                        <div class="row g-3 flex-grow-1">
                            @foreach ($comisionRevisora as [$cargo, $nombre])
                                <div class="col-md-4">
                                    <div class="p-3 rounded-3 border h-100" style="background:#fffaf0;">
                                        <p style="font-size:10.5px;font-weight:800;text-transform:uppercase;letter-spacing:.06em;color:#f59e0b;margin-bottom:4px;">{{ $cargo }}</p>
                                        <p class="fw-bold mb-0 text-dark">{{ $nombre }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════ INFRAESTRUCTURA ══════════ --}}
    <section class="py-10 py-lg-16" id="infraestructura">
        <div class="container-fluid">
            <div class="text-center mb-10">
                <div class="section-badge bg-primary-subtle text-primary mb-3">
                    <i class="ti ti-building-community"></i>
                    PATRIMONIO SINDICAL
                </div>
                <h2 class="fw-bolder fs-9 mb-3">Infraestructura al servicio de los afiliados</h2>
                <p class="text-muted fs-5 col-lg-6 mx-auto">Espacios propios construidos con el aporte de los trabajadores de la sanidad tucumana</p>
            </div>

            <div class="row g-4 mb-8">
                @foreach ([
                    [
                        'imagen'  => 'images/historia/ciudad-deportiva-atsa.jpg',
                        'titulo'  => 'Ciudad Deportiva ATSA Capital',
                        'sub'     => 'Ecuador y Thames, San Miguel de Tucumán',
                        'desc'    => 'El complejo social, deportivo y recreativo de referencia del sindicato. Incluye piletas, canchas, salones, playgrounds y la sede central gremial en Paraguay y Thames.',
                        'badges'  => ['Pileta olímpica', 'Canchas', 'Sede gremial', 'Área verde'],
                    ],
                    [
                        'imagen'  => 'images/turismo/hotel-atsa-termas-fachada.webp',
                        'titulo'  => 'Hotel ATSA — Termas de Río Hondo',
                        'sub'     => 'Termas de Río Hondo, Santiago del Estero',
                        'desc'    => 'Hotel propio inaugurado en 2013 para el turismo social de los afiliados. Ofrece habitaciones, spa, pileta termal y convenios con el complejo de termas.',
                        'badges'  => ['Hotel propio', 'Pileta termal', 'Spa', 'Convenio FATSA'],
                    ],
                    [
                        'imagen'  => 'images/historia/infraestructura-ciudad-deportiva.jpg',
                        'titulo'  => 'Filiales y escuelas CENT en toda la provincia',
                        'sub'     => '16 sedes en Tucumán',
                        'desc'    => 'ATSA cuenta con edificios propios en Capital, Este, Concepción, Famaillá, Aguilares, Amaicha del Valle y sedes educativas en 16 localidades del interior provincial.',
                        'badges'  => ['Capital', 'Concepción', 'Banda', '+ 13 sedes'],
                    ],
                ] as $infra)
                    <div class="col-lg-4">
                        <div class="feature-card border-0 h-100 overflow-hidden" style="box-shadow:0 12px 32px rgba(30,58,95,.10);">
                            <div style="height:220px;overflow:hidden;">
                                <img src="{{ asset($infra['imagen']) }}" alt="{{ $infra['titulo'] }}"
                                     class="w-100 h-100 object-fit-cover"
                                     style="transition:transform .4s ease;">
                            </div>
                            <div class="p-5">
                                <h3 class="fw-bolder fs-6 mb-1">{{ $infra['titulo'] }}</h3>
                                <p class="text-primary fw-semibold fs-3 mb-3">
                                    <i class="ti ti-map-pin me-1"></i>{{ $infra['sub'] }}
                                </p>
                                <p class="text-muted fs-4 mb-4">{{ $infra['desc'] }}</p>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach ($infra['badges'] as $badge)
                                        <span class="badge px-3 py-2 fw-semibold" style="background:#ecf2ff;color:#1e3a5f;font-size:12px;">{{ $badge }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Galería Ciudad Deportiva --}}
            <div class="row g-3 mb-8">
                <div class="col-12">
                    <p class="text-primary fw-bolder fs-4 mb-3"><i class="ti ti-camera me-2"></i>Galería — Ciudad Deportiva ATSA</p>
                </div>
                <div class="col-lg-8">
                    <div class="rounded-3 overflow-hidden" style="height:320px;">
                        <img src="{{ asset('images/turismo/ciudad-deportiva/pileta-general.jpeg') }}"
                             alt="Pileta Ciudad Deportiva" class="w-100 h-100 object-fit-cover"
                             style="transition:transform .4s ease;">
                    </div>
                </div>
                <div class="col-lg-4 d-flex flex-column gap-3">
                    <div class="rounded-3 overflow-hidden flex-fill">
                        <img src="{{ asset('images/turismo/ciudad-deportiva/pileta-panorama.jpeg') }}"
                             alt="Panorama pileta" class="w-100 h-100 object-fit-cover"
                             style="height:150px;transition:transform .4s ease;">
                    </div>
                    <div class="rounded-3 overflow-hidden flex-fill">
                        <img src="{{ asset('images/turismo/ciudad-deportiva/sector-recreativo.jpeg') }}"
                             alt="Sector recreativo" class="w-100 h-100 object-fit-cover"
                             style="height:150px;transition:transform .4s ease;">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="rounded-3 overflow-hidden" style="height:170px;">
                        <img src="{{ asset('images/turismo/ciudad-deportiva/cancha-cubierta.jpeg') }}"
                             alt="Cancha cubierta" class="w-100 h-100 object-fit-cover"
                             style="transition:transform .4s ease;">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="rounded-3 overflow-hidden" style="height:170px;">
                        <img src="{{ asset('images/turismo/ciudad-deportiva/futbol-infantil.jpeg') }}"
                             alt="Fútbol infantil" class="w-100 h-100 object-fit-cover"
                             style="transition:transform .4s ease;">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="rounded-3 overflow-hidden" style="height:170px;">
                        <img src="{{ asset('images/turismo/ciudad-deportiva/tenis-mesa.jpeg') }}"
                             alt="Tenis de mesa" class="w-100 h-100 object-fit-cover"
                             style="transition:transform .4s ease;">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="rounded-3 overflow-hidden" style="height:170px;">
                        <img src="{{ asset('images/turismo/ciudad-deportiva/ingreso-atsa.jpeg') }}"
                             alt="Ingreso Ciudad Deportiva" class="w-100 h-100 object-fit-cover"
                             style="transition:transform .4s ease;">
                    </div>
                </div>
            </div>

            {{-- Barrio ATSA + datos --}}
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="rounded-4 p-6 h-100" style="background:linear-gradient(135deg,#1e3a5f,#0f2236);">
                        <p class="fw-bold mb-2" style="font-size:11px;letter-spacing:.1em;color:#49beff;text-transform:uppercase;">VIVIENDA SOCIAL</p>
                        <h3 class="fw-bolder text-white mb-3" style="font-family:'Outfit',sans-serif;font-size:clamp(1.3rem,2.5vw,1.7rem);">
                            Barrio ATSA — Avenida Ejército del Norte
                        </h3>
                        <p style="color:rgba(255,255,255,.72);font-size:15px;line-height:1.65;margin-bottom:20px;">
                            En 2014 ATSA entregó 200 viviendas para afiliados en el Barrio ATSA sobre la Avenida Ejército del Norte.
                            También se realizaron entregas en los barrios Lomas del Tafí y Manantial Sur, ampliando el acceso a la
                            vivienda propia para trabajadores del sector salud tucumano.
                        </p>
                        <div class="row g-3">
                            @foreach ([['ti-home', '200 viviendas', 'Av. Ejército del Norte'], ['ti-map', 'Lomas del Tafí', 'Barrio afiliados'], ['ti-map', 'Manantial Sur', 'Barrio afiliados']] as [$icon, $titulo, $sub])
                                <div class="col-sm-4">
                                    <div class="p-3 rounded-3 d-flex align-items-center gap-2" style="background:rgba(255,255,255,.08);">
                                        <i class="ti {{ $icon }}" style="font-size:20px;color:#49beff;flex-shrink:0;"></i>
                                        <div>
                                            <p style="font-size:13px;font-weight:700;color:#fff;margin:0;">{{ $titulo }}</p>
                                            <p style="font-size:12px;color:rgba(255,255,255,.5);margin:0;">{{ $sub }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="rounded-4 p-5 h-100 d-flex flex-column justify-content-between" style="background:#ecf2ff;border:1px solid #d0dff5;">
                        <div>
                            <p class="fw-bold mb-2" style="font-size:11px;letter-spacing:.1em;color:#1e3a5f;text-transform:uppercase;">SIMULACIÓN CLÍNICA</p>
                            <h3 class="fw-bolder mb-3" style="font-family:'Outfit',sans-serif;color:#1e293b;font-size:1.2rem;">
                                Centro Regional de Simulación Clínica para Enfermería
                            </h3>
                            <p style="font-size:14px;color:#5a6a85;line-height:1.6;margin-bottom:20px;">
                                Inaugurado en 2022, este centro de alta tecnología permite la formación práctica de
                                enfermeros con simuladores clínicos de última generación, único en el NOA.
                            </p>
                        </div>
                        <a href="{{ route('filiales.index') }}" class="btn btn-primary fw-bold rounded-3">
                            <i class="ti ti-map-pin me-2"></i>Ver filiales y sedes
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════ CTA AFILIACIÓN ══════════ --}}
    <section class="py-10 py-lg-16 bg-light">
        <div class="container-fluid">
            <div class="overflow-hidden rounded-4 shadow-lg position-relative" style="background: linear-gradient(135deg, #1e3a5f 0%, #243b5a 55%, #2a4d73 100%);">
                <div class="position-absolute top-0 end-0 translate-middle rounded-circle" style="width: 260px; height: 260px; background: rgba(73, 190, 255, .12);"></div>
                <div class="position-absolute bottom-0 start-0 translate-middle rounded-circle" style="width: 220px; height: 220px; background: rgba(255, 255, 255, .06);"></div>

                <div class="position-relative p-7 p-lg-10">
                    <div class="row align-items-center g-5">
                        <div class="col-lg-7 text-white">
                            <span class="d-inline-flex align-items-center gap-2 rounded-pill px-4 py-2 mb-4 fw-bold" style="background: rgba(255,255,255,.12); color: #d9ecff;">
                                <i class="ti ti-users-group"></i>
                                AFILIACIÓN ATSA
                            </span>
                            <h2 class="fw-bolder mb-4" style="font-size: clamp(2rem, 3vw, 3.1rem); line-height: 1.08; color: #ffffff;">
                                Sumate a la familia de la sanidad tucumana
                            </h2>
                            <p class="fs-4 mb-5" style="max-width: 640px; color: rgba(255,255,255,.84); text-align: left;">
                                Forma parte del sindicato que defiende los derechos de los trabajadores de la salud en toda la provincia.
                                Accedé a acompañamiento gremial, beneficios, formación y una estructura institucional con presencia territorial real.
                            </p>
                            <div class="d-flex flex-wrap gap-3">
                                <a href="{{ route('afiliacion.create') }}" class="btn btn-light btn-lg px-10 py-7 fw-bold text-primary shadow-sm">
                                    <i class="ti ti-user-plus me-2"></i>Quiero afiliarme
                                </a>
                                <a href="{{ route('afiliados.index') }}" class="btn btn-outline-light btn-lg px-10 py-7 fw-bold">
                                    Ver beneficios
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="rounded-4 bg-white p-6 p-lg-7 shadow-sm">
                                <p class="text-primary fs-4 fw-bolder mb-3">POR QUÉ AFILIARTE</p>
                                <div class="d-grid gap-3">
                                    @foreach ([
                                        'Representación gremial y acompañamiento laboral',
                                        'Acceso a beneficios sociales y convenios',
                                        'Formación y capacitación mediante el CENT N°74',
                                        'Atención personalizada en filiales y sede central',
                                    ] as $item)
                                        <div class="d-flex align-items-start gap-3 rounded-3 border border-primary-subtle px-4 py-3 bg-primary-subtle bg-opacity-25">
                                            <span class="d-inline-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width: 38px; height: 38px; background: #e8f2ff; color: #1e3a5f;">
                                                <i class="ti ti-check fs-5"></i>
                                            </span>
                                            <p class="mb-0 fw-semibold text-dark" style="text-align: left;">{{ $item }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
