@extends('layouts.app')

@section('title', 'Turismo y Recreación | ATSA Tucumán')
@section('meta_description', 'Beneficios turísticos de ATSA Tucumán: Hotel ATSA en Termas de Río Hondo, convenios FATSA y Ciudad Deportiva para afiliados.')
@section('og_image', asset('images/turismo/ciudad-deportiva/ingreso-atsa.jpeg'))

@php
    use App\Models\PageSection;

    $turismoHero     = PageSection::get('turismo', 'hero');
    $turismoHeroImg  = $turismoHero?->imageUrl(asset('images/turismo/ciudad-deportiva/ingreso-atsa.jpeg'))
                       ?? asset('images/turismo/ciudad-deportiva/ingreso-atsa.jpeg');
    $turismoHeroTitle    = $turismoHero?->title    ?: 'Beneficios turísticos para la familia de la sanidad';
    $turismoHeroSubtitle = $turismoHero?->subtitle ?: 'Ciudad Deportiva ATSA, Hotel ATSA en Termas de Río Hondo y convenios hoteleros nacionales a través de FATSA.';

    $ciudadInfo = PageSection::get('turismo', 'ciudad_info');
    $ciudadTitle    = $ciudadInfo?->title    ?: 'Un predio para afiliados y sus familias';
    $ciudadSubtitle = $ciudadInfo?->subtitle ?: 'La Ciudad Deportiva ATSA cuenta con piletas, canchas, quinchos, asadores y salones. Durante la temporada de verano se convierte en uno de los espacios más importantes de encuentro y recreación para la familia de la sanidad.';
    $ciudadHorarios = $ciudadInfo?->body     ?: 'Informes y reservas: lunes a viernes de 8:00 a 13:00 y de 15:00 a 18:00.';

    $hotelInfo = PageSection::get('turismo', 'hotel_info');
    $hotelTitle     = $hotelInfo?->title       ?: 'Hotel ATSA Tucumán en Termas de Río Hondo';
    $hotelSubtitle  = $hotelInfo?->subtitle    ?: 'Un espacio pensado para que los afiliados puedan descansar, disfrutar de las aguas termales y compartir una escapada familiar en Santiago del Estero.';
    $hotelDireccion = $hotelInfo?->button_text ?: 'M. Güemes, Termas de Río Hondo, Santiago del Estero';
    $hotelTelefono  = $hotelInfo?->button_url  ?: '+54 3858 42-2005';

    $hotelImages = [
        asset('images/turismo/hotel-atsa-termas-fachada.webp'),
        asset('images/turismo/hotel-atsa-termas-comedor.webp'),
        asset('images/turismo/hotel-atsa-termas-hall.webp'),
        asset('images/turismo/hotel-atsa-termas-living.webp'),
    ];

    $hotelServices = [
        ['icon' => 'ti-swimming', 'label' => 'Piscina'],
        ['icon' => 'ti-tools-kitchen-2', 'label' => 'Restaurante'],
        ['icon' => 'ti-glass-full', 'label' => 'Bar'],
        ['icon' => 'ti-wifi', 'label' => 'Wi-Fi gratuito'],
        ['icon' => 'ti-car', 'label' => 'Estacionamiento'],
        ['icon' => 'ti-shirt', 'label' => 'Lavandería'],
        ['icon' => 'ti-bell', 'label' => 'Servicio de habitaciones'],
        ['icon' => 'ti-map-pin', 'label' => 'Ubicación céntrica'],
    ];

    $ciudadServices = [
        ['icon' => 'ti-swimming', 'label' => 'Piletas de natación'],
        ['icon' => 'ti-ball-football', 'label' => 'Canchas de fútbol'],
        ['icon' => 'ti-ball-basketball', 'label' => 'Básquet y vóley'],
        ['icon' => 'ti-grill', 'label' => 'Quinchos y asadores'],
        ['icon' => 'ti-building-community', 'label' => 'Salones de fiesta'],
        ['icon' => 'ti-chess', 'label' => 'Ajedrez y tenis de mesa'],
    ];

    $ciudadImages = [
        ['image' => asset('images/turismo/ciudad-deportiva/ingreso-atsa.jpeg'), 'title' => 'Ingreso principal'],
        ['image' => asset('images/turismo/ciudad-deportiva/pileta-familia.jpeg'), 'title' => 'Piletas de natación'],
        ['image' => asset('images/turismo/ciudad-deportiva/cancha-cubierta.jpeg'), 'title' => 'Cancha cubierta'],
        ['image' => asset('images/turismo/ciudad-deportiva/tenis-mesa.jpeg'), 'title' => 'Tenis de mesa'],
        ['image' => asset('images/turismo/ciudad-deportiva/futbol-infantil.jpeg'), 'title' => 'Fútbol infantil'],
        ['image' => asset('images/turismo/ciudad-deportiva/sector-recreativo.jpeg'), 'title' => 'Sector recreativo'],
    ];

    $complejosSociales = [
        [
            'title' => 'Complejo Social y Deportivo Concepción',
            'location' => 'Concepción, Tucumán',
            'description' => 'Espacio proyectado para recreación, encuentro familiar y actividades sociales de los afiliados del sur provincial.',
            'image' => asset('images/filiales/filial-sur-concepcion.jpg'),
            'tag' => 'Sur',
            'map' => 'https://www.google.com/maps/search/?api=1&query=ATSA%20Concepcion%20Tucuman',
        ],
        [
            'title' => 'Complejo Social y Deportivo Amaicha del Valle',
            'location' => 'Amaicha del Valle, Tucumán',
            'description' => 'Infraestructura social y educativa para acompañar a los trabajadores y estudiantes de los Valles Calchaquíes.',
            'image' => asset('images/turismo/ciudad-deportiva/pileta-panorama.jpeg'),
            'tag' => 'Valles',
            'map' => 'https://www.google.com/maps/search/?api=1&query=ATSA%20Amaicha%20del%20Valle',
        ],
        [
            'title' => 'Complejo Social y Deportivo Banda del Río Salí',
            'location' => 'Banda del Río Salí, Tucumán',
            'description' => 'Predio del este tucumano vinculado a la presencia territorial de ATSA y a la atención de la familia de la sanidad.',
            'image' => asset('images/filiales/filial-este-banda.jpg'),
            'tag' => 'Este',
            'map' => 'https://www.google.com/maps/search/?api=1&query=Camino%20del%20Carmen%2090%20Banda%20del%20Rio%20Sali',
        ],
        [
            'title' => 'Predio Social y Deportivo El Cadillal',
            'location' => 'El Cadillal, Tucumán',
            'description' => 'Un espacio histórico de recreación y turismo social para fortalecer el descanso de los afiliados y sus familias.',
            'image' => asset('images/turismo/complejos/predio-el-cadillal.jpeg'),
            'tag' => 'Turismo social',
            'map' => 'https://www.google.com/maps/search/?api=1&query=El%20Cadillal%20Tucuman',
        ],
    ];

    $fatsaDestinos = [
        'Capital Federal',
        'Mar del Plata',
        'San Bernardo',
        'La Falda',
        'Villa Giardino',
        'Paso de la Patria',
        'Cafayate',
    ];

    $fallbackHotelImages = [
        'Villa Gesell'  => asset('images/turismo/convenios/hotel-sanidad-mar-del-plata.webp'),
        'Tigre'         => asset('images/turismo/convenios/hotel-fatsa-san-bernardo.webp'),
        'Pontevedra'    => asset('images/turismo/convenios/apart-hotel-21-septiembre.webp'),
        'Mar del Plata' => asset('images/turismo/convenios/hotel-sanidad-mar-del-plata.webp'),
        'La Falda'      => asset('images/turismo/convenios/hotel-otto-calace-la-falda.webp'),
        'San Bernardo'  => asset('images/turismo/convenios/hotel-fatsa-san-bernardo.webp'),
        'Paso de la Patria' => asset('images/turismo/convenios/hosteria-fatsa-paso-de-la-patria.webp'),
        'La Plata'      => asset('images/turismo/convenios/complejo-atsa-la-plata-los-hornos.webp'),
        'Necochea'      => asset('images/turismo/convenios/hotel-sanidad-mar-del-plata.webp'),
    ];

    $condicionesSection = PageSection::get('turismo', 'condiciones');
    $condicionesTitulo  = $condicionesSection?->title ?: ($condiciones?->title ?: 'Condiciones del beneficio');
    $condicionesTexto   = $condicionesSection?->body  ?: ($condiciones?->body  ?: 'Los beneficios turísticos están sujetos a disponibilidad, condición de afiliado activo, reglamento vigente y confirmación previa por parte de ATSA Tucumán. Podrá solicitarse documentación respaldatoria para validar el acceso al beneficio.');
@endphp

@section('content')
    <section class="atsa-page-hero turismo-hero py-14" style="background-image: url('{{ $turismoHeroImg }}');">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <span class="badge bg-white text-primary fs-3 fw-bolder px-3 py-2 mb-4">TURISMO Y RECREACIÓN</span>
                    <h1 class="fw-bolder fs-12 text-white mb-4">{{ $turismoHeroTitle }}</h1>
                    <p class="fs-5 text-white text-opacity-75 mb-5">{{ $turismoHeroSubtitle }}</p>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="#ciudad-deportiva" class="btn btn-light text-primary fw-bold py-8 px-9">
                            <i class="ti ti-ball-football me-2"></i>Ciudad Deportiva
                        </a>
                        <a href="#complejos-sociales" class="btn btn-outline-light fw-bold py-8 px-9">
                            <i class="ti ti-building-community me-2"></i>Complejos
                        </a>
                        <a href="#hotel-atsa" class="btn btn-outline-light fw-bold py-8 px-9">
                            <i class="ti ti-building-skyscraper me-2"></i>Hotel ATSA Termas
                        </a>
                        <a href="#consulta-turismo" class="btn btn-outline-light fw-bold py-8 px-9">
                            <i class="ti ti-message-circle me-2"></i>Consultar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 py-md-14" id="ciudad-deportiva">
        <div class="container-fluid">
            <div class="row align-items-stretch g-4 mb-12">
                <div class="col-lg-5">
                    <div class="h-100 rounded-3 overflow-hidden position-relative turismo-photo-card" style="background-image: url('{{ asset('images/turismo/ciudad-deportiva/pileta-familia.jpeg') }}');">
                        <div class="position-absolute bottom-0 start-0 end-0 p-6 text-white">
                            <span class="badge bg-white text-primary mb-3">RECREACIÓN LOCAL</span>
                            <h2 class="fw-bolder text-white fs-9 mb-2">Ciudad Deportiva ATSA</h2>
                            <p class="mb-0 text-white text-opacity-75">Ecuador y Thames, San Miguel de Tucumán.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="card rounded-3 h-100 border-0 data-shadow">
                        <div class="card-body p-7">
                            <p class="text-primary fs-4 fw-bolder mb-2">COMPLEJO RECREATIVO Y DEPORTIVO</p>
                            <h2 class="fw-bolder fs-8 mb-3">{{ $ciudadTitle }}</h2>
                            <p class="fs-4 text-body mb-5">{{ $ciudadSubtitle }}</p>
                            <div class="row g-3">
                                @foreach ($ciudadServices as $service)
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center gap-3 bg-light rounded-3 p-3 h-100">
                                            <span class="round-40 rounded-circle hstack justify-content-center bg-white text-primary">
                                                <i class="ti {{ $service['icon'] }}"></i>
                                            </span>
                                            <strong>{{ $service['label'] }}</strong>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="bg-primary-subtle rounded-3 p-4 mt-5">
                                <strong class="d-block text-primary mb-1">Informes y reservas en el predio</strong>
                                <p class="mb-0 fs-3">{{ $ciudadHorarios }}</p>
                            </div>
                            <a href="https://www.google.com/maps/search/?api=1&query=Ecuador%20y%20Thames%2C%20San%20Miguel%20de%20Tucuman" target="_blank" rel="noopener" class="btn btn-outline-primary mt-4">
                                <i class="ti ti-map-2 me-2"></i>Cómo llegar a Ciudad Deportiva
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-12">
                <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-5">
                    <div>
                        <p class="text-primary fs-4 fw-bolder mb-2">GALERÍA</p>
                        <h2 class="fw-bolder fs-8 mb-0">Espacios de la Ciudad Deportiva</h2>
                    </div>
                    <a href="#como-consultar" class="btn btn-outline-primary">Consultar disponibilidad</a>
                </div>
                <div class="row g-4">
                    @foreach ($ciudadImages as $item)
                        <div class="col-lg-4 col-md-6">
                            <figure class="turismo-gallery-card rounded-3 overflow-hidden mb-0">
                                <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" class="w-100 object-fit-cover">
                                <figcaption>{{ $item['title'] }}</figcaption>
                            </figure>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mb-12" id="complejos-sociales">
                <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-5">
                    <div>
                        <p class="text-primary fs-4 fw-bolder mb-2">COMPLEJOS SOCIALES Y DEPORTIVOS</p>
                        <h2 class="fw-bolder fs-8 mb-0">Presencia recreativa en toda la provincia</h2>
                    </div>
                    <a href="#consulta-turismo" class="btn btn-outline-primary">Consultar información</a>
                </div>
                <p class="fs-4 text-body mb-5">ATSA Tucumán también cuenta con espacios sociales, deportivos y recreativos vinculados a Concepción, Amaicha del Valle, Banda del Río Salí y El Cadillal.</p>
                <div class="row g-4">
                    @foreach ($complejosSociales as $complejo)
                        <div class="col-xl-3 col-md-6">
                            <article class="card border-0 rounded-3 overflow-hidden h-100 complejo-social-card">
                                <div class="position-relative">
                                    <img src="{{ $complejo['image'] }}" alt="{{ $complejo['title'] }}" class="w-100 object-fit-cover" style="height: 190px;">
                                    <span class="badge bg-white text-primary position-absolute top-0 start-0 m-3 px-3 py-2">{{ $complejo['tag'] }}</span>
                                </div>
                                <div class="card-body p-5 d-flex flex-column">
                                    <h3 class="fw-bolder fs-5 mb-2">{{ $complejo['title'] }}</h3>
                                    <p class="text-primary fs-3 fw-semibold mb-3">
                                        <i class="ti ti-map-pin me-1"></i>{{ $complejo['location'] }}
                                    </p>
                                    <p class="fs-3 text-body flex-grow-1">{{ $complejo['description'] }}</p>
                                    <div class="d-flex flex-wrap gap-2 mt-3">
                                        <a href="#consulta-turismo" class="btn btn-primary btn-sm">Consultar</a>
                                        <a href="{{ $complejo['map'] }}" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm">Mapa</a>
                                    </div>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="row align-items-center g-5 mb-12" id="hotel-atsa">
                <div class="col-lg-6">
                    <p class="text-primary fs-4 fw-bolder mb-2">HOTEL PROPIO</p>
                    <h2 class="fw-bolder fs-10 mb-4">{{ $hotelTitle }}</h2>
                    <p class="fs-4 text-body mb-4">{{ $hotelSubtitle }}</p>
                    <div class="row g-3 mb-5">
                        <div class="col-sm-6">
                            <div class="bg-primary-subtle rounded-3 p-4 h-100">
                                <i class="ti ti-map-pin text-primary fs-7"></i>
                                <h5 class="fw-bolder mt-3 mb-1">Dirección</h5>
                                <p class="mb-0 fs-3">{{ $hotelDireccion }}</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="bg-primary-subtle rounded-3 p-4 h-100">
                                <i class="ti ti-phone text-primary fs-7"></i>
                                <h5 class="fw-bolder mt-3 mb-1">Teléfono</h5>
                                <p class="mb-0 fs-3">{{ $hotelTelefono }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <span class="badge bg-success-subtle text-success fs-3 px-3 py-2">
                            <i class="ti ti-star-filled me-1"></i>Promedio 4 estrellas
                        </span>
                        <span class="badge bg-light text-dark fs-3 px-3 py-2">78 reseñas en Google Maps</span>
                    </div>
                    <a href="https://www.google.com/maps/search/?api=1&query=G%C3%BCemes%20386%2C%20Termas%20de%20R%C3%ADo%20Hondo" target="_blank" rel="noopener" class="btn btn-outline-primary mt-4">
                        <i class="ti ti-map-2 me-2"></i>Cómo llegar al Hotel ATSA
                    </a>
                </div>
                <div class="col-lg-6">
                    <div class="row g-3">
                        <div class="col-12">
                            <img src="{{ $hotelImages[0] }}" alt="Fachada Hotel ATSA Termas" class="w-100 rounded-3 object-fit-cover data-shadow" style="height: 340px;">
                        </div>
                        @foreach (array_slice($hotelImages, 1) as $image)
                            <div class="col-4">
                                <img src="{{ $image }}" alt="Interior Hotel ATSA Termas" class="w-100 rounded-3 object-fit-cover" style="height: 120px;">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-12">
                @foreach ($hotelServices as $service)
                    <div class="col-xl-3 col-lg-4 col-sm-6">
                        <div class="card border-0 rounded-3 h-100 turismo-service-card">
                            <div class="card-body p-4 d-flex align-items-center gap-3">
                                <span class="turismo-service-icon rounded-circle hstack justify-content-center bg-primary-subtle text-primary flex-shrink-0">
                                    <i class="ti {{ $service['icon'] }} fs-5"></i>
                                </span>
                                <h4 class="fw-bolder fs-4 mb-0">{{ $service['label'] }}</h4>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mb-12" id="convenios-fatsa">
                <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-5">
                    <div>
                        <p class="text-primary fs-4 fw-bolder mb-2">CONVENIOS FATSA</p>
                        <h2 class="fw-bolder fs-8 mb-0">Hoteles y espacios de la red Sanidad</h2>
                    </div>
                    <a href="#consulta-turismo" class="btn btn-outline-primary">Consultar disponibilidad</a>
                </div>
                <p class="fs-4 text-body mb-5">Además del Hotel ATSA Tucumán en Termas, los afiliados pueden consultar disponibilidad en la red de hoteles y beneficios turísticos vinculados a FATSA.</p>
                <div class="row g-4">
                    @forelse ($hotelesConvenio as $hotel)
                        @php
                            $hotelImage = $hotel->imagen_url ?: ($fallbackHotelImages[$hotel->localidad] ?? asset('images/turismo/hotel-atsa-termas-fachada.webp'));
                        @endphp
                        <div class="col-lg-4 col-md-6">
                            <article class="card border-0 rounded-3 overflow-hidden h-100 hotel-convenio-card">
                                <img src="{{ $hotelImage }}" alt="{{ $hotel->nombre }}" class="w-100 object-fit-cover" style="height: 220px;">
                                <div class="card-body p-5 d-flex flex-column">
                                    <div class="d-flex align-items-center justify-content-between gap-3 mb-3">
                                        <span class="badge bg-primary-subtle text-primary px-3 py-2">{{ \App\Filament\Resources\HotelConvenioResource::tipos()[$hotel->tipo] ?? ucfirst($hotel->tipo) }}</span>
                                        <span class="text-muted fs-2">Convenio FATSA</span>
                                    </div>
                                    <h3 class="fw-bolder fs-6 mb-2">{{ $hotel->nombre }}</h3>
                                    <p class="text-body fs-3 mb-3">
                                        <i class="ti ti-map-pin text-primary me-1"></i>{{ $hotel->localidad }}, {{ $hotel->provincia }}
                                    </p>
                                    <p class="text-muted fs-3 mb-3 d-flex gap-2">
                                        <i class="ti ti-current-location text-primary mt-1"></i>
                                        <span>{{ $hotel->direccion ?: 'Dirección a confirmar con ATSA/FATSA' }}</span>
                                    </p>
                                    <p class="fs-3 text-body flex-grow-1">{{ $hotel->descripcion ?: 'Beneficio turístico sujeto a disponibilidad y reglamento vigente.' }}</p>
                                    <div class="d-flex gap-2 flex-wrap mt-3">
                                        <a href="#consulta-turismo" class="btn btn-primary btn-sm">Consultar</a>
                                        @if ($hotel->web_url)
                                            <a href="{{ $hotel->web_url }}" target="_blank" rel="noopener" class="btn btn-light-primary btn-sm">Ver referencia</a>
                                        @endif
                                        @if ($hotel->mapa_url)
                                            <a href="{{ $hotel->mapa_url }}" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm">Mapa</a>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        </div>
                    @empty
                        @foreach ($fatsaDestinos as $destino)
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="d-flex align-items-center gap-3 bg-light rounded-3 p-3 h-100">
                                    <span class="round-40 rounded-circle hstack justify-content-center bg-white text-primary">
                                        <i class="ti ti-map-pin"></i>
                                    </span>
                                    <strong>{{ $destino }}</strong>
                                </div>
                            </div>
                        @endforeach
                    @endforelse
                </div>
                <p class="fs-2 text-muted mt-4 mb-0">Los beneficios están sujetos a disponibilidad, condición de afiliado y reglamento vigente.</p>
            </div>

            <div class="row g-4 mb-12" id="consulta-turismo">
                <div class="col-lg-5">
                    <div class="card border-0 rounded-3 h-100 data-shadow">
                        <div class="card-body p-7">
                            <p class="text-primary fs-4 fw-bolder mb-2">REGLAMENTO</p>
                            <h2 class="fw-bolder fs-8 mb-4">{{ $condicionesTitulo }}</h2>
                            <p class="fs-4 text-body mb-5">{{ $condicionesTexto }}</p>
                            <div class=”bg-primary-subtle rounded-3 p-4”>
                                <strong class=”text-primary d-block mb-2”><i class=”ti ti-info-circle me-1”></i>Importante</strong>
                                <p class=”mb-0 fs-3”>Los beneficios turísticos están sujetos a disponibilidad, condición de afiliado activo y reglamento vigente. Consultá en tu filial o por WhatsApp para más información.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="card border-0 rounded-3 h-100 data-shadow">
                        <div class="card-body p-7">
                            <p class="text-primary fs-4 fw-bolder mb-2">CONSULTA TURÍSTICA</p>
                            <h2 class="fw-bolder fs-8 mb-4">Solicitá información del beneficio</h2>

                            @if (session('turismo_success'))
                                <div class="alert alert-success rounded-3">{{ session('turismo_success') }}</div>
                            @endif

                            <form action="{{ route('turismo.consulta') }}" method="POST" class="row g-4">
                                @csrf
                                <input type="text" name="website" class="d-none" tabindex="-1" autocomplete="off">

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nombre completo *</label>
                                    <input type="text" name="nombre" value="{{ old('nombre') }}" class="form-control @error('nombre') is-invalid @enderror" required>
                                    @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Teléfono *</label>
                                    <input type="text" name="telefono" value="{{ old('telefono') }}" class="form-control @error('telefono') is-invalid @enderror" required>
                                    @error('telefono') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Email</label>
                                    <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror">
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">DNI</label>
                                    <input type="text" name="dni" value="{{ old('dni') }}" class="form-control @error('dni') is-invalid @enderror">
                                    @error('dni') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">N° afiliado</label>
                                    <input type="text" name="numero_afiliado" value="{{ old('numero_afiliado') }}" class="form-control @error('numero_afiliado') is-invalid @enderror">
                                    @error('numero_afiliado') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Beneficio *</label>
                                    <select name="beneficio" class="form-select @error('beneficio') is-invalid @enderror" required>
                                        <option value="ciudad_deportiva" @selected(old('beneficio') === 'ciudad_deportiva')>Ciudad Deportiva</option>
                                        <option value="hotel_termas" @selected(old('beneficio') === 'hotel_termas')>Hotel ATSA Termas</option>
                                        <option value="convenios_fatsa" @selected(old('beneficio') === 'convenios_fatsa')>Convenios FATSA</option>
                                        <option value="otro" @selected(old('beneficio') === 'otro')>Otro</option>
                                    </select>
                                    @error('beneficio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Fecha estimada</label>
                                    <input type="date" name="fecha_estimada" value="{{ old('fecha_estimada') }}" class="form-control @error('fecha_estimada') is-invalid @enderror">
                                    @error('fecha_estimada') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Mensaje *</label>
                                    <textarea name="mensaje" rows="5" class="form-control @error('mensaje') is-invalid @enderror" required>{{ old('mensaje') }}</textarea>
                                    @error('mensaje') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary py-8 px-9 fw-bold">
                                        <i class="ti ti-send me-2"></i>Enviar consulta
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-3 overflow-hidden turismo-consulta" id="como-consultar">
                <div class="row g-0 align-items-center">
                    <div class="col-lg-7">
                        <div class="p-7 p-lg-10">
                            <p class="text-white fs-4 fw-bolder mb-2">CÓMO CONSULTAR</p>
                            <h2 class="text-white fw-bolder fs-9 mb-4">Reservas e informes para afiliados</h2>
                            <p class="text-white text-opacity-75 fs-4 mb-5">Consultá en la sede central actual de ATSA Tucumán, ubicada en Paraguay y Thames, San Miguel de Tucumán. Para Ciudad Deportiva, los informes y reservas se realizan en el predio de Ecuador y Thames. También podés escribir por WhatsApp para recibir orientación.</p>
                            <div class="d-flex flex-wrap gap-3">
                                <a href="https://wa.me/543814331665" target="_blank" rel="noopener" class="btn btn-success fw-bold py-7 px-9">
                                    <i class="ti ti-brand-whatsapp me-2"></i>Consultar por WhatsApp
                                </a>
                                <a href="{{ route('afiliados.index') }}#beneficios" class="btn btn-light text-primary fw-bold py-7 px-9">
                                    Ver beneficios
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="bg-white p-7 p-lg-8 m-4 rounded-3">
                            <h3 class="fw-bolder mb-4">Datos útiles</h3>
                            <div class="d-flex gap-3 mb-4">
                                <i class="ti ti-building text-primary fs-7"></i>
                                <div>
                                    <strong>Sede central ATSA Tucumán</strong>
                                    <p class="mb-0 text-body">Paraguay y Thames, San Miguel de Tucumán</p>
                                </div>
                            </div>
                            <div class="d-flex gap-3 mb-4">
                                <i class="ti ti-map-pin text-primary fs-7"></i>
                                <div>
                                    <strong>Ciudad Deportiva</strong>
                                    <p class="mb-0 text-body">Ecuador y Thames, San Miguel de Tucumán</p>
                                </div>
                            </div>
                            <div class="d-flex gap-3 mb-4">
                                <i class="ti ti-hotel-service text-primary fs-7"></i>
                                <div>
                                    <strong>Hotel ATSA Termas</strong>
                                    <p class="mb-0 text-body">M. Güemes, Termas de Río Hondo</p>
                                </div>
                            </div>
                            <div class="d-flex gap-3">
                                <i class="ti ti-phone text-primary fs-7"></i>
                                <div>
                                    <strong>Teléfono hotel</strong>
                                    <p class="mb-0 text-body">+54 3858 42-2005</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    .turismo-hero::before {
        background: linear-gradient(90deg, rgba(15, 34, 54, .94), rgba(30, 58, 95, .72));
    }
    .turismo-service-card {
        min-height: 92px;
        box-shadow: 0 10px 24px rgba(42, 53, 71, .06);
        transition: transform .25s ease, box-shadow .25s ease;
    }
    .turismo-service-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 14px 32px rgba(42, 53, 71, .1);
    }
    .turismo-service-icon {
        width: 42px;
        height: 42px;
    }
    .turismo-photo-card {
        min-height: 560px;
        background-size: cover;
        background-position: center;
    }
    .turismo-photo-card::before {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(15, 34, 54, .05), rgba(15, 34, 54, .9));
    }
    .turismo-photo-card > * {
        z-index: 1;
    }
    .turismo-gallery-card {
        position: relative;
        box-shadow: 0 14px 32px rgba(42, 53, 71, .09);
        background: #fff;
    }
    .turismo-gallery-card img {
        height: 230px;
        transition: transform .35s ease;
    }
    .turismo-gallery-card:hover img {
        transform: scale(1.04);
    }
    .turismo-gallery-card figcaption {
        position: absolute;
        left: 16px;
        right: 16px;
        bottom: 16px;
        border-radius: 8px;
        background: rgba(255, 255, 255, .94);
        color: #1e3a5f;
        font-weight: 800;
        padding: 10px 14px;
    }
    .complejo-social-card {
        box-shadow: 0 14px 32px rgba(42, 53, 71, .08);
        transition: transform .25s ease, box-shadow .25s ease;
    }
    .complejo-social-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 46px rgba(42, 53, 71, .14);
    }
    .complejo-social-card img {
        transition: transform .35s ease;
    }
    .complejo-social-card:hover img {
        transform: scale(1.04);
    }
    .hotel-convenio-card {
        box-shadow: 0 14px 32px rgba(42, 53, 71, .08);
        transition: transform .25s ease, box-shadow .25s ease;
    }
    .hotel-convenio-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 46px rgba(42, 53, 71, .14);
    }
    .turismo-consulta {
        background: linear-gradient(135deg, #1e3a5f 0%, #0f2236 100%);
    }
</style>
@endpush
