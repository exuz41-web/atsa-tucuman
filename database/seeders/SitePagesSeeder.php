<?php

namespace Database\Seeders;

use App\Models\SitePage;
use Illuminate\Database\Seeder;

class SitePagesSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [

            // ── INICIO ────────────────────────────────────────────────────────
            [
                'slug'   => 'home',
                'label'  => 'Inicio',
                'icon'   => 'heroicon-o-home',
                'blocks' => [
                    [
                        'type' => 'hero',
                        'data' => [
                            'visible'    => true,
                            'badge'      => 'Sindicato de trabajadores de la sanidad',
                            'badge_color'=> 'primary',
                            'title'      => 'Representamos a los trabajadores de la salud de Tucumán',
                            'subtitle'   => 'ATSA Tucumán defiende los derechos laborales del sector sanitario desde hace más de 100 años.',
                            'image'      => null,
                            'overlay'    => 'medium',
                            'btn1_text'  => 'Conocé tus derechos',
                            'btn1_url'   => '/gremial',
                            'btn2_text'  => 'Quiero afiliarme',
                            'btn2_url'   => '/afiliacion',
                        ],
                    ],
                    [
                        'type' => 'stats_bar',
                        'data' => [
                            'visible' => true,
                            'items'   => [
                                ['number' => '100+', 'label' => 'Años de historia', 'icon' => 'ti ti-award'],
                                ['number' => '12K+', 'label' => 'Afiliados activos', 'icon' => 'ti ti-users'],
                                ['number' => '8',    'label' => 'Filiales en Tucumán', 'icon' => 'ti ti-map-pin'],
                                ['number' => '24/7', 'label' => 'Atención y defensa', 'icon' => 'ti ti-shield-check'],
                            ],
                        ],
                    ],
                    [
                        'type' => 'cards_section',
                        'data' => [
                            'visible'  => true,
                            'badge'    => 'Lo que hacemos',
                            'title'    => 'Tu sindicato, tu respaldo',
                            'subtitle' => 'Trabajamos cada día para que los trabajadores de la salud tengan mejores condiciones laborales, acceso a beneficios y representación digna.',
                            'columns'  => '3',
                            'style'    => 'icon_top',
                            'items'    => [
                                ['icon' => 'ti ti-shield-check', 'icon_color' => '#1e3a5f', 'title' => 'Defensa gremial', 'description' => 'Representación en paritarias, conflictos laborales y negociaciones colectivas con empleadores del sector salud.', 'btn_text' => 'Ver más', 'btn_url' => '/gremial'],
                                ['icon' => 'ti ti-heart', 'icon_color' => '#e74c3c', 'title' => 'Beneficios para afiliados', 'description' => 'Turismo, hotelería, descuentos, préstamos y convenios exclusivos para vos y tu familia.', 'btn_text' => 'Ver beneficios', 'btn_url' => '/turismo'],
                                ['icon' => 'ti ti-school', 'icon_color' => '#27ae60', 'title' => 'Formación continua', 'description' => 'El CENT N°74 ofrece carreras técnicas y cursos para el crecimiento profesional de los trabajadores de la salud.', 'btn_text' => 'Conocer el CENT', 'btn_url' => '/cent74'],
                            ],
                        ],
                    ],
                    [
                        'type' => 'news_section',
                        'data' => [
                            'visible'   => true,
                            'badge'     => 'Novedades',
                            'title'     => 'Últimas noticias',
                            'subtitle'  => 'Enterate de todo lo que pasa en ATSA Tucumán y el sector salud.',
                            'categoria' => '',
                            'cantidad'  => '3',
                            'btn_text'  => 'Ver todas las noticias',
                            'btn_url'   => '/novedades',
                        ],
                    ],
                    [
                        'type' => 'gallery_section',
                        'data' => [
                            'visible'  => true,
                            'title'    => 'Ciudad Deportiva y sede gremial',
                            'subtitle' => 'Un espacio propio para el deporte, la recreación y la vida familiar de nuestros afiliados.',
                            'columns'  => '3',
                            'images'   => [],
                        ],
                    ],
                    [
                        'type' => 'cta_section',
                        'data' => [
                            'visible'   => true,
                            'style'     => 'image_bg',
                            'align'     => 'left_right',
                            'title'     => 'Descanso, deporte y convenios para afiliados',
                            'subtitle'  => 'Conocé la Ciudad Deportiva ATSA, el Hotel ATSA en Termas de Río Hondo y la red de hoteles y espacios recreativos vinculados a FATSA.',
                            'bg_image'  => null,
                            'btn1_text' => 'Ver beneficios de turismo',
                            'btn1_url'  => '/turismo',
                            'btn2_text' => '',
                            'btn2_url'  => '',
                        ],
                    ],
                ],
            ],

            // ── SINDICATO ─────────────────────────────────────────────────────
            [
                'slug'   => 'sindicato',
                'label'  => 'El Sindicato',
                'icon'   => 'heroicon-o-building-office',
                'blocks' => [
                    [
                        'type' => 'hero',
                        'data' => [
                            'visible'   => true,
                            'badge'     => 'Conocenos',
                            'title'     => 'Más de 100 años representando a los trabajadores de la salud',
                            'subtitle'  => 'ATSA Tucumán nació para defender los derechos de quienes cuidan la salud de todos los tucumanos.',
                            'image'     => null,
                            'overlay'   => 'medium',
                            'btn1_text' => 'Nuestra historia',
                            'btn1_url'  => '#historia',
                            'btn2_text' => 'Ver autoridades',
                            'btn2_url'  => '#autoridades',
                        ],
                    ],
                    [
                        'type' => 'timeline_section',
                        'data' => [
                            'visible'  => true,
                            'badge'    => 'Historia',
                            'title'    => 'Nuestra historia',
                            'subtitle' => 'Más de un siglo de lucha, organización y representación de los trabajadores de la sanidad tucumana.',
                            'items'    => [
                                ['year' => '1923', 'title' => 'Fundación de ATSA', 'description' => 'Nace la Asociación de Trabajadores de la Sanidad Argentina en Tucumán, con el objetivo de defender los derechos de los trabajadores del sector salud.'],
                                ['year' => '1945', 'title' => 'Primeras conquistas laborales', 'description' => 'Logros históricos en condiciones laborales y salariales para los trabajadores de hospitales y clínicas de la provincia.'],
                                ['year' => '1970', 'title' => 'Ciudad Deportiva ATSA', 'description' => 'Inauguración de la Ciudad Deportiva, un espacio de recreación y deporte para los afiliados y sus familias.'],
                                ['year' => '2000', 'title' => 'CENT N°74', 'description' => 'Creación del Centro Educativo para la Formación Técnica, ofreciendo carreras técnicas a los trabajadores de la salud.'],
                            ],
                        ],
                    ],
                    [
                        'type' => 'text_image',
                        'data' => [
                            'visible'    => true,
                            'badge'      => 'Misión y Visión',
                            'image_side' => 'right',
                            'title'      => 'Nuestra misión',
                            'body'       => '<p>ATSA Tucumán tiene como misión <strong>representar y defender los intereses de los trabajadores de la sanidad</strong> de la provincia, promoviendo mejores condiciones laborales, salariales y de vida para sus afiliados y sus familias.</p><p>Trabajamos por un sector salud más justo, con trabajadores dignamente remunerados y con acceso a formación continua.</p>',
                            'image'      => null,
                            'btn_text'   => 'Conocer nuestros valores',
                            'btn_url'    => '#valores',
                        ],
                    ],
                    [
                        'type' => 'team_section',
                        'data' => [
                            'visible'  => true,
                            'badge'    => 'Conducción',
                            'title'    => 'Nuestra conducción',
                            'subtitle' => 'Las autoridades elegidas democráticamente para representar a todos los afiliados.',
                        ],
                    ],
                    [
                        'type' => 'cards_section',
                        'data' => [
                            'visible'  => true,
                            'badge'    => 'Infraestructura',
                            'title'    => 'Nuestras instalaciones',
                            'subtitle' => 'ATSA Tucumán cuenta con espacios propios para brindar los mejores servicios a sus afiliados.',
                            'columns'  => '3',
                            'style'    => 'icon_top',
                            'items'    => [
                                ['icon' => 'ti ti-building', 'icon_color' => '#1e3a5f', 'title' => 'Sede central', 'description' => 'Oficinas administrativas y atención a afiliados en el centro de San Miguel de Tucumán.', 'btn_text' => '', 'btn_url' => ''],
                                ['icon' => 'ti ti-swimming', 'icon_color' => '#3498db', 'title' => 'Ciudad Deportiva', 'description' => 'Pileta, canchas de fútbol, tenis, vóley y espacios verdes para el esparcimiento familiar.', 'btn_text' => '', 'btn_url' => ''],
                                ['icon' => 'ti ti-school', 'icon_color' => '#27ae60', 'title' => 'CENT N°74', 'description' => 'Centro educativo con aulas equipadas y laboratorios para la formación técnica de los trabajadores.', 'btn_text' => '', 'btn_url' => ''],
                            ],
                        ],
                    ],
                ],
            ],

            // ── GREMIAL ───────────────────────────────────────────────────────
            [
                'slug'   => 'gremial',
                'label'  => 'Gremial',
                'icon'   => 'heroicon-o-shield-check',
                'blocks' => [
                    [
                        'type' => 'hero',
                        'data' => [
                            'visible'   => true,
                            'badge'     => 'Actividad gremial',
                            'title'     => 'Defendemos tus derechos laborales',
                            'subtitle'  => 'ATSA Tucumán negocia, representa y defiende los intereses de todos los trabajadores de la salud en paritarias y conflictos laborales.',
                            'image'     => null,
                            'overlay'   => 'medium',
                            'btn1_text' => 'Consultar por un caso',
                            'btn1_url'  => '/contacto',
                            'btn2_text' => 'Escala salarial vigente',
                            'btn2_url'  => '/escalas-salariales',
                        ],
                    ],
                    [
                        'type' => 'cards_section',
                        'data' => [
                            'visible'  => true,
                            'badge'    => 'Nuestros servicios',
                            'title'    => 'Todo lo que hacemos por vos',
                            'subtitle' => 'La actividad gremial de ATSA abarca desde la negociación salarial hasta la asistencia jurídica en casos individuales.',
                            'columns'  => '3',
                            'style'    => 'icon_top',
                            'items'    => [
                                ['icon' => 'ti ti-gavel', 'icon_color' => '#1e3a5f', 'title' => 'Asesoría legal laboral', 'description' => 'Asistencia jurídica gratuita para afiliados en conflictos laborales, despidos y reclamos.', 'btn_text' => '', 'btn_url' => ''],
                                ['icon' => 'ti ti-cash', 'icon_color' => '#27ae60', 'title' => 'Negociación paritaria', 'description' => 'Participamos activamente en las discusiones salariales para lograr los mejores acuerdos para los trabajadores.', 'btn_text' => '', 'btn_url' => ''],
                                ['icon' => 'ti ti-users', 'icon_color' => '#e67e22', 'title' => 'Representación sindical', 'description' => 'Delegados en los principales establecimientos de salud para defender tus derechos en el lugar de trabajo.', 'btn_text' => '/delegados', 'btn_url' => '/delegados'],
                                ['icon' => 'ti ti-first-aid-kit', 'icon_color' => '#e74c3c', 'title' => 'Obra social', 'description' => 'Cobertura de salud para afiliados y su grupo familiar a través de OSECAC.', 'btn_text' => '', 'btn_url' => ''],
                                ['icon' => 'ti ti-certificate', 'icon_color' => '#9b59b6', 'title' => 'Capacitación y formación', 'description' => 'Cursos, talleres y carreras técnicas a través del CENT N°74 para tu crecimiento profesional.', 'btn_text' => 'Ver el CENT', 'btn_url' => '/cent74'],
                                ['icon' => 'ti ti-phone-call', 'icon_color' => '#3498db', 'title' => 'Atención permanente', 'description' => 'Nuestro equipo está disponible para responder tus consultas y acompañarte en cada situación.', 'btn_text' => 'Contactarnos', 'btn_url' => '/contacto'],
                            ],
                        ],
                    ],
                    [
                        'type' => 'news_section',
                        'data' => [
                            'visible'   => true,
                            'badge'     => 'Actividad',
                            'title'     => 'Últimas novedades gremiales',
                            'subtitle'  => '',
                            'categoria' => 'gremial',
                            'cantidad'  => '3',
                            'btn_text'  => 'Ver todas',
                            'btn_url'   => '/novedades',
                        ],
                    ],
                    [
                        'type' => 'cta_section',
                        'data' => [
                            'visible'   => true,
                            'style'     => 'primary',
                            'align'     => 'left_right',
                            'title'     => '¿Todavía no estás afiliado?',
                            'subtitle'  => 'Sumate a ATSA y accedé a todos los beneficios gremiales, legales y sociales que tenemos para vos.',
                            'bg_image'  => null,
                            'btn1_text' => 'Afiliarme ahora',
                            'btn1_url'  => '/afiliacion',
                            'btn2_text' => 'Conocer beneficios',
                            'btn2_url'  => '/turismo',
                        ],
                    ],
                ],
            ],

            // ── TURISMO ───────────────────────────────────────────────────────
            [
                'slug'   => 'turismo',
                'label'  => 'Turismo y Beneficios',
                'icon'   => 'heroicon-o-sun',
                'blocks' => [
                    [
                        'type' => 'hero',
                        'data' => [
                            'visible'   => true,
                            'badge'     => 'Beneficios para afiliados',
                            'title'     => 'Turismo, deporte y convenios exclusivos',
                            'subtitle'  => 'Aprovechá todos los beneficios de ser afiliado a ATSA: hoteles, recreación, descuentos y mucho más.',
                            'image'     => null,
                            'overlay'   => 'medium',
                            'btn1_text' => 'Consultar disponibilidad',
                            'btn1_url'  => '#consulta',
                            'btn2_text' => 'Quiero afiliarme',
                            'btn2_url'  => '/afiliacion',
                        ],
                    ],
                    [
                        'type' => 'cards_section',
                        'data' => [
                            'visible'  => true,
                            'badge'    => 'Destinos',
                            'title'    => 'Espacios recreativos ATSA',
                            'subtitle' => 'Como afiliado tenés acceso prioritario a nuestras instalaciones recreativas y turísticas.',
                            'columns'  => '3',
                            'style'    => 'icon_top',
                            'items'    => [
                                ['icon' => 'ti ti-swimming', 'icon_color' => '#3498db', 'title' => 'Ciudad Deportiva ATSA', 'description' => 'Pileta olímpica, canchas deportivas, quinchos y espacios verdes en San Miguel de Tucumán.', 'btn_text' => 'Consultar', 'btn_url' => '/contacto'],
                                ['icon' => 'ti ti-building-castle', 'icon_color' => '#e67e22', 'title' => 'Hotel ATSA — Termas', 'description' => 'Alojamiento con piletas termales en Río Hondo. Descuentos especiales para afiliados y su grupo familiar.', 'btn_text' => 'Consultar', 'btn_url' => '/contacto'],
                                ['icon' => 'ti ti-map', 'icon_color' => '#27ae60', 'title' => 'Red FATSA', 'description' => 'Accedé a la red de hoteles, campings y espacios recreativos de FATSA en todo el país.', 'btn_text' => 'Ver destinos', 'btn_url' => '/contacto'],
                            ],
                        ],
                    ],
                    [
                        'type' => 'cta_section',
                        'data' => [
                            'visible'   => true,
                            'style'     => 'primary',
                            'align'     => 'left_right',
                            'title'     => '¿Querés hacer una consulta o reserva?',
                            'subtitle'  => 'Contactá a ATSA para información sobre disponibilidad, precios y condiciones para afiliados.',
                            'bg_image'  => null,
                            'btn1_text' => 'Hacer una consulta',
                            'btn1_url'  => '/contacto',
                            'btn2_text' => '',
                            'btn2_url'  => '',
                        ],
                    ],
                ],
            ],

            // ── CONTACTO ──────────────────────────────────────────────────────
            [
                'slug'   => 'contacto',
                'label'  => 'Contacto',
                'icon'   => 'heroicon-o-envelope',
                'blocks' => [
                    [
                        'type' => 'hero',
                        'data' => [
                            'visible'   => true,
                            'badge'     => 'Estamos para ayudarte',
                            'title'     => 'Contactate con ATSA Tucumán',
                            'subtitle'  => 'Encontrá la sede más cercana, escribinos o llamanos. Nuestro equipo está disponible para atenderte.',
                            'image'     => null,
                            'overlay'   => 'light',
                            'btn1_text' => '',
                            'btn1_url'  => '',
                            'btn2_text' => '',
                            'btn2_url'  => '',
                        ],
                    ],
                    [
                        'type' => 'contact_info',
                        'data' => [
                            'visible'         => true,
                            'title'           => '¿Cómo contactarnos?',
                            'badge'           => 'Contacto',
                            'show_map'        => true,
                            'show_form'       => true,
                            'show_branches'   => true,
                        ],
                    ],
                ],
            ],

            // ── AFILIADOS ─────────────────────────────────────────────────────
            [
                'slug'   => 'afiliados',
                'label'  => 'Afiliados',
                'icon'   => 'heroicon-o-users',
                'blocks' => [
                    [
                        'type' => 'hero',
                        'data' => [
                            'visible'   => true,
                            'badge'     => 'Para afiliados',
                            'title'     => 'Beneficios exclusivos para afiliados a ATSA',
                            'subtitle'  => 'Como afiliado tenés acceso a obra social, asesoría legal, turismo, formación y mucho más.',
                            'image'     => null,
                            'overlay'   => 'medium',
                            'btn1_text' => 'Afiliarme',
                            'btn1_url'  => '/afiliacion',
                            'btn2_text' => 'Portal de afiliados',
                            'btn2_url'  => '/afiliados/login',
                        ],
                    ],
                    [
                        'type' => 'cards_section',
                        'data' => [
                            'visible'  => true,
                            'badge'    => 'Beneficios',
                            'title'    => '¿Por qué afiliarte a ATSA?',
                            'subtitle' => 'Ser parte de ATSA Tucumán significa tener respaldo gremial, beneficios sociales y acceso a servicios exclusivos.',
                            'columns'  => '3',
                            'style'    => 'icon_top',
                            'items'    => [
                                ['icon' => 'ti ti-shield-check', 'icon_color' => '#1e3a5f', 'title' => 'Respaldo legal', 'description' => 'Asesoría jurídica gratuita en conflictos laborales y defensa de tus derechos como trabajador.', 'btn_text' => '', 'btn_url' => ''],
                                ['icon' => 'ti ti-first-aid-kit', 'icon_color' => '#e74c3c', 'title' => 'Obra social', 'description' => 'Cobertura médica para vos y tu familia a través de OSECAC.', 'btn_text' => '', 'btn_url' => ''],
                                ['icon' => 'ti ti-beach', 'icon_color' => '#f39c12', 'title' => 'Turismo y recreación', 'description' => 'Descuentos en hoteles, campings y espacios deportivos de la red ATSA-FATSA.', 'btn_text' => 'Ver beneficios', 'btn_url' => '/turismo'],
                            ],
                        ],
                    ],
                    [
                        'type' => 'cta_section',
                        'data' => [
                            'visible'   => true,
                            'style'     => 'primary',
                            'align'     => 'left_right',
                            'title'     => '¡Afiliarte es fácil y rápido!',
                            'subtitle'  => 'Completá el formulario de preinscripción y un representante de ATSA se va a comunicar con vos.',
                            'bg_image'  => null,
                            'btn1_text' => 'Comenzar ahora',
                            'btn1_url'  => '/afiliacion',
                            'btn2_text' => '',
                            'btn2_url'  => '',
                        ],
                    ],
                ],
            ],

            // ── FILIALES ──────────────────────────────────────────────────────
            [
                'slug'   => 'filiales',
                'label'  => 'Filiales',
                'icon'   => 'heroicon-o-map-pin',
                'blocks' => [
                    [
                        'type' => 'hero',
                        'data' => [
                            'visible'   => true,
                            'badge'     => 'Presencia provincial',
                            'title'     => 'Filiales de ATSA en toda la provincia',
                            'subtitle'  => 'Tenemos presencia en los principales centros urbanos de Tucumán para estar cerca de todos los trabajadores de la salud.',
                            'image'     => null,
                            'overlay'   => 'medium',
                            'btn1_text' => 'Contactar sede',
                            'btn1_url'  => '/contacto',
                            'btn2_text' => '',
                            'btn2_url'  => '',
                        ],
                    ],
                    [
                        'type' => 'branches_section',
                        'data' => [
                            'visible'  => true,
                            'badge'    => 'Filiales',
                            'title'    => 'Encontrá tu filial más cercana',
                            'subtitle' => 'Cada filial tiene sus propias autoridades y representa a los afiliados de su zona.',
                        ],
                    ],
                ],
            ],

            // ── DELEGADOS ─────────────────────────────────────────────────────
            [
                'slug'   => 'delegados',
                'label'  => 'Delegados',
                'icon'   => 'heroicon-o-identification',
                'blocks' => [
                    [
                        'type' => 'hero',
                        'data' => [
                            'visible'   => true,
                            'badge'     => 'Representación en el lugar de trabajo',
                            'title'     => 'Delegados gremiales de ATSA Tucumán',
                            'subtitle'  => 'Los delegados son el puente entre los trabajadores y el sindicato, presentes en cada establecimiento de salud.',
                            'image'     => null,
                            'overlay'   => 'medium',
                            'btn1_text' => '',
                            'btn1_url'  => '',
                            'btn2_text' => '',
                            'btn2_url'  => '',
                        ],
                    ],
                    [
                        'type' => 'text_image',
                        'data' => [
                            'visible'    => true,
                            'badge'      => '¿Qué hace un delegado?',
                            'image_side' => 'right',
                            'title'      => 'Tu voz en el lugar de trabajo',
                            'body'       => '<p>El <strong>delegado gremial</strong> es el representante de ATSA en cada establecimiento de salud. Sus funciones incluyen:</p><ul><li>Recibir y canalizar reclamos de los trabajadores</li><li>Controlar el cumplimiento del convenio colectivo</li><li>Informar sobre novedades gremiales y paritarias</li><li>Articular con la conducción del sindicato ante conflictos</li></ul><p>Si necesitás contactar al delegado de tu lugar de trabajo, escribinos y te ponemos en contacto.</p>',
                            'image'      => null,
                            'btn_text'   => 'Contactar ATSA',
                            'btn_url'    => '/contacto',
                        ],
                    ],
                ],
            ],

            // ── DOCUMENTOS ────────────────────────────────────────────────────
            [
                'slug'   => 'documentos',
                'label'  => 'Documentos',
                'icon'   => 'heroicon-o-document-text',
                'blocks' => [
                    [
                        'type' => 'hero',
                        'data' => [
                            'visible'   => true,
                            'badge'     => 'Documentación institucional',
                            'title'     => 'Documentos de ATSA Tucumán',
                            'subtitle'  => 'Accedé a convenios colectivos, estatutos, resoluciones y documentos institucionales del sindicato.',
                            'image'     => null,
                            'overlay'   => 'medium',
                            'btn1_text' => '',
                            'btn1_url'  => '',
                            'btn2_text' => '',
                            'btn2_url'  => '',
                        ],
                    ],
                    [
                        'type' => 'downloads_section',
                        'data' => [
                            'visible'  => true,
                            'title'    => 'Documentos disponibles',
                            'cantidad' => '12',
                        ],
                    ],
                ],
            ],
        ];

        foreach ($pages as $page) {
            SitePage::updateOrCreate(
                ['slug' => $page['slug']],
                [
                    'label'  => $page['label'],
                    'icon'   => $page['icon'],
                    'blocks' => $page['blocks'],
                    'active' => true,
                ]
            );
        }

        $this->command->info('✅ SitePages seeder completado — '.count($pages).' páginas cargadas.');
    }
}
