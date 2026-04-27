<section class="mb-12" id="historia">
    <style>
        .atsa-history-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 12px 24px -4px rgba(145, 158, 171, .12);
        }

        .atsa-history-content p {
            font-size: 1rem;
            line-height: 1.85;
            color: #5a6a85;
            margin-bottom: 1rem;
            text-align: justify;
            text-justify: inter-word;
        }

        .atsa-history-content strong {
            color: #2a3547;
        }

        .atsa-history-visual-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.25fr) minmax(260px, .75fr);
            gap: 20px;
        }

        .atsa-history-visual-card {
            overflow: hidden;
            border: 1px solid #e5eaef;
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 12px 24px -4px rgba(145, 158, 171, .12);
        }

        .atsa-history-visual-large img {
            display: block;
            width: 100%;
            height: 420px;
            object-fit: cover;
        }

        .atsa-history-side-photo {
            overflow: hidden;
            border-radius: 16px;
            border: 1px solid #e5eaef;
            background: #f6f8fb;
            box-shadow: 0 12px 24px rgba(42, 53, 71, .10);
        }

        .atsa-history-side-photo img {
            display: block;
            width: 100%;
            height: 230px;
            object-fit: cover;
        }

        .atsa-history-side-photo figcaption {
            padding: 14px 16px;
            color: #5a6a85;
            font-size: 13px;
            font-weight: 700;
            line-height: 1.45;
        }

        .atsa-history-photo {
            position: relative;
            display: flex;
            min-height: 198px;
            align-items: end;
            overflow: hidden;
            border-radius: 16px;
            background-position: center;
            background-size: cover;
            color: #fff;
            text-decoration: none;
        }

        .atsa-history-photo::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(15, 34, 54, .05), rgba(15, 34, 54, .82));
        }

        .atsa-history-photo-body {
            position: relative;
            z-index: 1;
            padding: 20px;
        }

        .atsa-history-photo-body span {
            display: inline-flex;
            margin-bottom: 8px;
            border-radius: 999px;
            background: rgba(255, 255, 255, .16);
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        .atsa-history-photo-body h4 {
            margin: 0 0 4px;
            color: #fff;
            font-weight: 900;
        }

        .atsa-history-photo-body p {
            margin: 0;
            color: rgba(255, 255, 255, .82);
            line-height: 1.5;
        }

        .atsa-history-timeline {
            position: relative;
            display: grid;
            gap: 18px;
            padding-left: 28px;
        }

        .atsa-history-timeline::before {
            content: "";
            position: absolute;
            left: 8px;
            top: 8px;
            bottom: 8px;
            width: 2px;
            background: #dfe5ef;
        }

        .atsa-history-item {
            position: relative;
            border: 1px solid #e5eaef;
            border-radius: 12px;
            padding: 18px;
            background: #f8fbff;
        }

        .atsa-history-item p {
            text-align: justify;
            text-justify: inter-word;
        }

        .atsa-history-item::before {
            content: "";
            position: absolute;
            left: -27px;
            top: 22px;
            width: 14px;
            height: 14px;
            border: 3px solid #5d87ff;
            border-radius: 999px;
            background: #fff;
        }

        .atsa-history-item h4 {
            color: #1e3a5f;
            font-weight: 800;
            margin-bottom: 8px;
        }

        @media (max-width: 991.98px) {
            .atsa-history-visual-grid {
                grid-template-columns: 1fr;
            }

            .atsa-history-visual-large img {
                min-height: 250px;
                height: 300px;
            }
        }
    </style>

    @php
        use App\Models\PageSection;
        use App\Models\VisualBlock;

        $historiaSection      = PageSection::get('sindicato', 'historia');
        $historiaTitleDb      = $historiaSection?->title;
        $historiaBodyDb       = $historiaSection?->body;

        $historiaImagenPrincipal = asset('images/historia/ciudad-deportiva-atsa.jpg');
        $historiaImagenMovilizacion = asset('images/historia/movilizacion-atsa-sanidad.jpg');
        $historiaImagenFormacion = asset('images/historia/formacion-cent-74.jpg');
        $historiaImagenInfraestructura = asset('images/historia/infraestructura-ciudad-deportiva.jpg');

        $visualBlocksDb = VisualBlock::activeFor('sindicato', 'historia');
        $visualBlocks = $visualBlocksDb->isNotEmpty()
            ? $visualBlocksDb->map(fn (VisualBlock $block) => (object) [
                'title' => $block->title,
                'subtitle' => $block->subtitle,
                'description' => $block->description,
                'image_url' => $block->imageUrl(),
                'link_url' => $block->link_url,
                'size' => $block->size,
            ])
            : collect([
                (object) [
                    'title' => 'Sede de ATSA Tucumán',
                    'subtitle' => 'Infraestructura',
                    'description' => 'Espacios propios al servicio de los afiliados y de la familia de la sanidad.',
                    'image_url' => $historiaImagenPrincipal,
                    'link_url' => null,
                    'size' => 'large',
                ],
                (object) [
                    'title' => 'CENT N°74',
                    'subtitle' => 'Formación',
                    'description' => 'La educación sanitaria como eje de crecimiento institucional.',
                    'image_url' => $historiaImagenFormacion,
                    'link_url' => route('filiales.index'),
                    'size' => 'medium',
                ],
                (object) [
                    'title' => 'Ciudad Deportiva',
                    'subtitle' => 'Infraestructura',
                    'description' => 'Un espacio social, deportivo y recreativo para la familia de la sanidad.',
                    'image_url' => $historiaImagenInfraestructura,
                    'link_url' => route('turismo.index'),
                    'size' => 'medium',
                ],
            ]);

        $mainVisual = $visualBlocks->values()->first();
        $sideVisuals = $visualBlocks->values()->slice(1)->take(2);
    @endphp

    <div class="atsa-history-card p-5 p-lg-7">
        <div class="row g-5">
            <div class="col-lg-4">
                <p class="text-primary fs-4 fw-bolder mb-2">HISTORIA - ATSA TUCUMÁN</p>
                <h2 class="fw-bolder fs-9 mb-4">{{ $historiaTitleDb ?: '100 años de lucha, organización y crecimiento' }}</h2>
                <p class="fs-4 text-body mb-4">Nuestro sindicato marc&oacute; el rumbo del sindicalismo tucumano con hombres y mujeres que construyeron la familia de la sanidad.</p>
                <div class="rounded-3 bg-primary-subtle p-4">
                    <h3 class="fw-bolder text-primary mb-1">1925 - 2025</h3>
                    <p class="mb-0 fw-semibold text-dark">Un siglo defendiendo derechos laborales y jerarquizando al trabajador de la salud.</p>
                </div>

                <figure class="atsa-history-side-photo mt-4 mb-0">
                    <img src="{{ $historiaImagenMovilizacion }}" alt="Movilizaci&oacute;n gremial de ATSA Tucum&aacute;n" loading="lazy">
                    <figcaption>La organizaci&oacute;n gremial en la calle, defendiendo los derechos laborales de la sanidad tucumana.</figcaption>
                </figure>
            </div>

            <div class="col-lg-8 atsa-history-content">
                @if($historiaBodyDb)
                    {!! $historiaBodyDb !!}
                @else
                    <p>Nuestro sindicato cumple 100 años defendiendo a los trabajadores de la salud tucumana, siempre luchando por los derechos laborales de cada compañero. Desde sus orígenes, ATSA Tucumán marcó el rumbo en el sindicalismo de la provincia con hombres y mujeres que constituyeron la familia de la sanidad.</p>
                    <p>Esta entidad gremial inició sus actividades humildemente y fue creciendo de manera inigualable. En todo este proceso hubo dirigentes que marcaron una etapa de lucha, siempre buscando el bienestar de nuestros compañeros. Su historia estuvo enmarcada en grandes acontecimientos que sentaron las bases de lo que hoy es ATSA Tucumán.</p>
                    <p>El 18 de septiembre de 1925 se reunieron enfermeros y enfermeras y decidieron conformar un sindicato como <strong>Centro Unión de Enfermeras y Enfermeros de Tucumán</strong>, que se constituyó el 3 de octubre de 1925.</p>
                    <p>El 10 de agosto de 1939 se reorganiza con el apoyo de más de doscientos afiliados. Ese año también es creada la Escuela de Enfermeras y Enfermeros de Tucumán, bajo la dirección del Dr. Roberto Pérez de Nucci.</p>
                    <p>Durante las décadas del 40 y 50 el sindicato es conducido por Juan Agustín Blasetti. El 1 de septiembre de 1955 ATSA Tucumán obtiene la personería gremial número 394, logrando el carácter de entidad gremial de primer grado.</p>
                @endif
            </div>
        </div>

        <div class="mt-7">
            <div class="d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3 mb-4">
                <div>
                    <p class="text-primary fs-3 fw-bolder mb-1">ARCHIVO VISUAL</p>
                    <h3 class="fw-bolder text-dark mb-0">Im&aacute;genes de nuestra historia</h3>
                </div>
                <a href="{{ $mainVisual->image_url }}" target="_blank" rel="noopener" class="btn btn-primary">Ver imagen ampliada</a>
            </div>

            <div class="atsa-history-visual-grid">
                <div class="atsa-history-visual-card atsa-history-visual-large">
                    <img src="{{ $mainVisual->image_url }}" alt="{{ $mainVisual->title }}" loading="lazy">
                </div>

                <div class="d-grid gap-3">
                    @foreach ($sideVisuals as $block)
                        <a href="{{ $block->link_url ? url($block->link_url) : $block->image_url }}" class="atsa-history-photo" style="background-image: url('{{ $block->image_url }}');">
                            <div class="atsa-history-photo-body">
                                <span>{{ $block->subtitle }}</span>
                                <h4>{{ $block->title }}</h4>
                                <p>{{ $block->description }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="mt-7 atsa-history-timeline">
            <article class="atsa-history-item">
                <h4>D&eacute;cadas de 1960, 1970 y 1980</h4>
                <p>En los a&ntilde;os 60 Alfredo Acosta condujo los destinos de la asociaci&oacute;n. Durante los a&ntilde;os 70 y 80 la conducci&oacute;n estuvo a cargo de Alberto del Carmen Padilla, acompa&ntilde;ado por dirigentes que consolidaron una etapa de crecimiento. En esta gesti&oacute;n ATSA adquiere los terrenos de Suipacha 553 para constituir su sede.</p>
            </article>

            <article class="atsa-history-item">
                <h4>1985 - 2000: retorno de la democracia</h4>
                <p>Con el retorno de la democracia, ATSA ser&aacute; conducida por Ram&oacute;n Roberto Bulacio, logrando un gran avance en la estructura pol&iacute;tico-sindical. En 1992 es creado el CENT N&deg;74, ofreciendo carreras terciarias en ciencias de la salud.</p>
            </article>

            <article class="atsa-history-item">
                <h4>2003 - 2006: normalizaci&oacute;n y nueva etapa</h4>
                <p>En 2003 se normaliza la entidad, asumiendo nuevas autoridades. Luego de un breve interinato, en 2006 Rene&eacute; Ram&iacute;rez es electo Secretario General con la visi&oacute;n de jerarquizar al trabajador de la sanidad.</p>
            </article>

            <article class="atsa-history-item">
                <h4>2006: predio en El Cadillal</h4>
                <p>ATSA Tucum&aacute;n toma posesi&oacute;n de un predio en El Cadillal, donde se proyecta un complejo social y deportivo para la familia de la sanidad.</p>
            </article>

            <article class="atsa-history-item">
                <h4>2013: hotel propio en Termas de R&iacute;o Hondo</h4>
                <p>ATSA inaugura su primer hotel propio en Termas de R&iacute;o Hondo, fortaleciendo el turismo social para los afiliados. En este per&iacute;odo tambi&eacute;n se entregan viviendas en Lomas del Taf&iacute; y Manantial Sur.</p>
            </article>

            <article class="atsa-history-item">
                <h4>2014: Barrio ATSA</h4>
                <p>Se construye el Barrio ATSA sobre avenida Ej&eacute;rcito del Norte, compuesto por 200 viviendas para afiliados. Ese mismo a&ntilde;o es reelegida la Comisi&oacute;n Directiva encabezada por Rene&eacute; Ram&iacute;rez.</p>
            </article>

            <article class="atsa-history-item">
                <h4>2015: Ciudad Deportiva, Filial Este y Escuela de Famaill&aacute;</h4>
                <p>ATSA inaugura la Ciudad Deportiva - ATSA Capital, construye la Filial Este en Banda del R&iacute;o Sal&iacute; y la Escuela de Famaill&aacute;. Ese a&ntilde;o, Rene&eacute; Ram&iacute;rez es electo Legislador Provincial y presidente de la Comisi&oacute;n de Salud.</p>
            </article>

            <article class="atsa-history-item">
                <h4>2018: Torre Concepci&oacute;n</h4>
                <p>El sindicato inaugura la Torre Concepci&oacute;n, un nuevo establecimiento educativo de referencia para el sur de la provincia. Rene&eacute; Ram&iacute;rez es reelecto Secretario General y luego Legislador Provincial.</p>
            </article>

            <article class="atsa-history-item">
                <h4>2020: Covid-19</h4>
                <p>Durante la pandemia, ATSA Tucum&aacute;n trabaj&oacute; junto al Ministerio de Salud y el Gobierno de la Provincia, acompa&ntilde;ando a los trabajadores de la sanidad en uno de los momentos m&aacute;s dif&iacute;ciles de su historia.</p>
            </article>

            <article class="atsa-history-item">
                <h4>2020 - 2021: obras en Concepci&oacute;n y Amaicha del Valle</h4>
                <p>Se inician obras en el Complejo Social y Deportivo Concepci&oacute;n y comienza la construcci&oacute;n del complejo y nueva escuela en Amaicha del Valle, de gran importancia para los Valles Calchaqu&iacute;es.</p>
            </article>

            <article class="atsa-history-item">
                <h4>2022 - 2024: nuevos logros educativos</h4>
                <p>Rene&eacute; Ram&iacute;rez es electo por quinta vez Secretario General. Se inaugura el Centro Regional de Simulaci&oacute;n Cl&iacute;nica para Enfermer&iacute;a y se lanza la Tecnicatura Universitaria en Emergencias M&eacute;dicas junto al Ministerio de Salud y la Universidad San Pablo T.</p>
            </article>

            <article class="atsa-history-item">
                <h4>2025: a&ntilde;o del centenario</h4>
                <p>En el a&ntilde;o del centenario, ATSA construye en Amaicha del Valle el primer establecimiento educativo de Nivel Terciario en Ciencias de la Salud. El broche de oro es la inauguraci&oacute;n de la nueva sede ubicada en la Ciudad Deportiva.</p>
            </article>
        </div>

        <div class="row mt-7 g-4 atsa-history-content">
            <div class="col-lg-6">
                <div class="rounded-3 bg-light p-5 h-100">
                    <h3 class="fw-bolder text-dark mb-3">Anexo institucional</h3>
                    <p>El CENT N&deg;74 es la escuela educativa en Ciencias de la Salud m&aacute;s importante de la regi&oacute;n. Cuenta con carreras terciarias como Enfermer&iacute;a, Agente Socio Sanitario, Diagn&oacute;stico por Im&aacute;genes, Farmacia, Laboratorio de An&aacute;lisis Cl&iacute;nicos y Esterilizaci&oacute;n.</p>
                    <p>La entidad tiene escuelas propias en Capital, Este, Famaill&aacute;, Concepci&oacute;n, Aguilares y Amaicha del Valle. Adem&aacute;s, cuenta con presencia educativa en Trancas, La Ramada, Taf&iacute; Viejo, Lules, Monteros, Delf&iacute;n Gallo, Los Ralos, Simoca, Santa Rosa de Leales y Graneros.</p>
                    <p>ATSA cuenta con el Centro Regional de Simulaci&oacute;n Cl&iacute;nica para Enfermer&iacute;a, convenios educativos y cursos de capacitaci&oacute;n y formaci&oacute;n profesional.</p>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="rounded-3 bg-primary-subtle p-5 h-100">
                    <h3 class="fw-bolder text-primary mb-3">Conclusiones</h3>
                    <p>ATSA Tucum&aacute;n es referente en el Noroeste Argentino. Tiene un marcado rol sindical, social y educativo, priorizando la jerarquizaci&oacute;n del trabajador y de sus familias.</p>
                    <p>El sindicato cuenta con miles de alumnos, complejos polideportivos, presencia territorial, delegados, congresales y una Comisi&oacute;n Directiva comprometida con el crecimiento permanente.</p>
                    <p>ATSA Tucum&aacute;n no solo es un sindicato: es una entidad educativa, social y recreativa con un rol vital en toda la provincia. Somos la familia de la sanidad.</p>
                </div>
            </div>
        </div>

        <div class="mt-7 rounded-3 bg-atsa-blue p-5 p-lg-7 text-white">
            <h3 class="fw-bolder text-white mb-3">Agradecimientos</h3>
            <p class="fs-4 text-white text-opacity-75">En estos 100 a&ntilde;os agradecemos a todos los compa&ntilde;eros que hicieron su aporte para que ATSA Tucum&aacute;n llegue a cumplir un siglo de vida sindical; a los trabajadores de la sanidad; a nuestros afiliados; delegados; congresales; miembros de Comisi&oacute;n Directiva y familias.</p>
            <p class="fs-4 text-white text-opacity-75 mb-0">Nuestro deber es proteger y defender al trabajador de la salud con responsabilidad, honestidad, empat&iacute;a y dedicaci&oacute;n. Seguiremos luchando por cada compa&ntilde;ero de la sanidad.</p>
        </div>
    </div>
</section>
