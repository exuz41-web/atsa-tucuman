<?php

namespace Database\Seeders;

use App\Models\SitePage;
use Illuminate\Database\Seeder;

class CentSitePagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Página de inicio del CENT
        SitePage::updateOrCreate(['slug' => 'cent_home'], [
            'label'  => 'Inicio CENT',
            'blocks' => [
                [
                    'type' => 'hero',
                    'data' => [
                        'visible'           => true,
                        'title'             => 'Centro de Educación y Formación',
                        'subtitle'          => 'Programas de enfermería y salud para profesionales',
                        'background_image'  => null,
                        'cta_text'          => 'Conocer carreras',
                        'cta_url'           => '/cent74/carreras',
                    ],
                ],
                [
                    'type' => 'stats_bar',
                    'data' => [
                        'visible' => true,
                        'items'   => [
                            ['label' => 'Años de trayectoria', 'value' => '15+', 'icon' => 'ti ti-award'],
                            ['label' => 'Carreras activas', 'value' => '3', 'icon' => 'ti ti-book'],
                            ['label' => 'Alumnos egresados', 'value' => '250+', 'icon' => 'ti ti-users'],
                        ],
                    ],
                ],
                [
                    'type' => 'cards_section',
                    'data' => [
                        'visible'  => true,
                        'title'    => 'Nuestras fortalezas',
                        'subtitle' => 'Formación integral en salud con docentes especializados',
                        'items'    => [
                            [
                                'icon'        => 'ti ti-books',
                                'title'       => 'Formación teórico-práctica',
                                'description' => 'Combinamos aprendizaje en aula con experiencia en campo.',
                            ],
                            [
                                'icon'        => 'ti ti-users-group',
                                'title'       => 'Docentes especializados',
                                'description' => 'Profesionales con experiencia en el sector salud.',
                            ],
                            [
                                'icon'        => 'ti ti-certificate',
                                'title'       => 'Títulos reconocidos',
                                'description' => 'Certificados válidos a nivel provincial y nacional.',
                            ],
                        ],
                    ],
                ],
                [
                    'type' => 'cta_section',
                    'data' => [
                        'visible'   => true,
                        'title'     => 'Inicia tu carrera en salud',
                        'subtitle'  => 'Preinscríbete ahora y sé parte de nuestra comunidad educativa',
                        'cta_text'  => 'Preinscribirse',
                        'cta_url'   => '/cent74/preinscripcion',
                    ],
                ],
            ],
            'active' => true,
        ]);

        // Página de carreras
        SitePage::updateOrCreate(['slug' => 'cent_carreras'], [
            'label'  => 'Carreras',
            'blocks' => [
                [
                    'type' => 'hero',
                    'data' => [
                        'visible'           => true,
                        'title'             => 'Nuestras Carreras',
                        'subtitle'          => 'Programas de formación en enfermería y salud',
                        'background_image'  => null,
                        'cta_text'          => null,
                        'cta_url'           => null,
                    ],
                ],
                [
                    'type' => 'accordion_section',
                    'data' => [
                        'visible' => true,
                        'title'   => 'Preguntas frecuentes sobre las carreras',
                        'items'   => [
                            [
                                'question' => 'Cuál es la duración de las carreras?',
                                'answer'   => 'Las carreras tienen una duración entre 2 y 3 años dependiendo de la modalidad y especialización elegida.',
                            ],
                            [
                                'question' => 'Cuáles son los requisitos de ingreso?',
                                'answer'   => 'Se requiere tener título de educación secundaria completo. Se realiza una evaluación diagnóstica de conocimientos.',
                            ],
                            [
                                'question' => 'Se otorga título reconocido?',
                                'answer'   => 'Sí, los títulos emitidos por el CENT N°74 son válidos a nivel provincial y nacional en el sector salud.',
                            ],
                        ],
                    ],
                ],
            ],
            'active' => true,
        ]);

        // Página de sedes
        SitePage::updateOrCreate(['slug' => 'cent_sedes'], [
            'label'  => 'Sedes',
            'blocks' => [
                [
                    'type' => 'hero',
                    'data' => [
                        'visible'           => true,
                        'title'             => 'Nuestras Sedes',
                        'subtitle'          => 'Ubicaciones en la provincia donde podés estudiar',
                        'background_image'  => null,
                        'cta_text'          => null,
                        'cta_url'           => null,
                    ],
                ],
                [
                    'type' => 'contact_info',
                    'data' => [
                        'visible'  => true,
                        'title'    => 'Contactar una sede',
                        'address'  => null,
                        'phone'    => null,
                        'email'    => null,
                    ],
                ],
            ],
            'active' => true,
        ]);

        // Página de preguntas frecuentes
        SitePage::updateOrCreate(['slug' => 'cent_faq'], [
            'label'  => 'Preguntas frecuentes',
            'blocks' => [
                [
                    'type' => 'hero',
                    'data' => [
                        'visible'           => true,
                        'title'             => 'Preguntas Frecuentes',
                        'subtitle'          => 'Resuelve tus dudas sobre inscripción, carreras y requisitos',
                        'background_image'  => null,
                        'cta_text'          => null,
                        'cta_url'           => null,
                    ],
                ],
                [
                    'type' => 'accordion_section',
                    'data' => [
                        'visible' => true,
                        'title'   => 'Preguntas y respuestas',
                        'items'   => [
                            [
                                'question' => 'Cómo me inscribo?',
                                'answer'   => 'Podés hacer tu preinscripción en línea a través de nuestro sitio web. Luego deberás completar la documentación y asistir a una entrevista.',
                            ],
                            [
                                'question' => 'Cuál es el costo de los estudios?',
                                'answer'   => 'Consult con nuestra área administrativa sobre aranceles y formas de pago. Contamos con planes de financiación flexibles.',
                            ],
                            [
                                'question' => 'Se puede trabajar mientras se estudia?',
                                'answer'   => 'Sí, muchos de nuestros alumnos compatibilizan el estudio con el trabajo. Oferemos horarios que facilitan esta combinación.',
                            ],
                            [
                                'question' => 'Qué salida laboral tiene la carrera?',
                                'answer'   => 'Los egresados de nuestras carreras tienen amplia demanda en hospitales, clínicas privadas, centros de salud y organizaciones de atención a la comunidad.',
                            ],
                        ],
                    ],
                ],
            ],
            'active' => true,
        ]);

        // Página de contacto
        SitePage::updateOrCreate(['slug' => 'cent_contacto'], [
            'label'  => 'Contacto',
            'blocks' => [
                [
                    'type' => 'hero',
                    'data' => [
                        'visible'           => true,
                        'title'             => 'Contactanos',
                        'subtitle'          => 'Estamos aquí para responder tus preguntas sobre nuestras carreras',
                        'background_image'  => null,
                        'cta_text'          => null,
                        'cta_url'           => null,
                    ],
                ],
                [
                    'type' => 'contact_info',
                    'data' => [
                        'visible'  => true,
                        'title'    => 'Información de contacto',
                        'address'  => null,
                        'phone'    => null,
                        'email'    => null,
                    ],
                ],
            ],
            'active' => true,
        ]);
    }
}
