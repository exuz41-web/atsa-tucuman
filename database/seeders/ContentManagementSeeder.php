<?php

namespace Database\Seeders;

use App\Models\Autoridad;
use App\Models\PageSection;
use App\Models\VisualBlock;
use Illuminate\Database\Seeder;

class ContentManagementSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedAutoridades();
        $this->seedPageSections();
        $this->seedVisualBlocks();
    }

    private function seedAutoridades(): void
    {
        $autoridades = [
            [
                'nombre' => 'Reneé Ramírez',
                'cargo' => 'Secretario General',
                'foto' => 'images/autoridades/renee-ramirez.jpg',
                'descripcion' => 'Conduce la representación gremial de los trabajadores de la sanidad tucumana.',
                'orden' => 1,
            ],
            [
                'nombre' => 'Darío Ramírez',
                'cargo' => 'Secretario Adjunto',
                'foto' => 'images/autoridades/dario-ramirez.jpeg',
                'descripcion' => 'Acompaña la conducción institucional y territorial de ATSA Tucumán.',
                'orden' => 2,
            ],
            [
                'nombre' => 'Mabel Aguirre',
                'cargo' => 'Secretaria de Finanzas',
                'foto' => 'images/autoridades/mabel-aguirre.png',
                'descripcion' => 'Responsable de la administración y gestión financiera institucional.',
                'orden' => 3,
            ],
            [
                'nombre' => 'Alejandra Ferreyra',
                'cargo' => 'Secretaria Gremial',
                'foto' => 'images/autoridades/alejandra-ferreyra.png',
                'descripcion' => 'Coordina la acción gremial y el acompañamiento a los trabajadores.',
                'orden' => 4,
            ],
        ];

        foreach ($autoridades as $autoridad) {
            Autoridad::updateOrCreate(
                ['nombre' => $autoridad['nombre']],
                $autoridad + ['activo' => true]
            );
        }
    }

    private function seedPageSections(): void
    {
        $sections = [
            [
                'page' => 'home',
                'key' => 'hero',
                'label' => 'Sindicato de trabajadores de la sanidad',
                'title' => 'Representamos a los trabajadores de la salud de Tucumán',
                'subtitle' => 'ATSA Tucumán defiende los derechos laborales del sector sanitario desde hace más de 100 años.',
                'image_path' => 'images/home/hero-atsa-movilizacion.jpeg',
                'button_text' => 'Conocé tus derechos',
                'button_url' => '/gremial',
                'secondary_button_text' => 'Quiero afiliarme',
                'secondary_button_url' => '/afiliados',
                'orden' => 1,
            ],
            [
                'page' => 'sindicato',
                'key' => 'hero',
                'label' => 'Institucional',
                'title' => 'El Sindicato',
                'subtitle' => '100 años defendiendo a la sanidad tucumana',
                'image_path' => 'images/historia/movilizacion-atsa-sanidad.jpg',
                'orden' => 1,
            ],
        ];

        foreach ($sections as $section) {
            PageSection::updateOrCreate(
                ['page' => $section['page'], 'key' => $section['key']],
                $section + ['active' => true]
            );
        }
    }

    private function seedVisualBlocks(): void
    {
        $blocks = [
            [
                'page' => 'sindicato',
                'section' => 'historia',
                'title' => 'Sede de ATSA Tucumán',
                'subtitle' => 'Infraestructura',
                'description' => 'Espacios propios al servicio de los afiliados y de la familia de la sanidad.',
                'image_path' => 'images/historia/ciudad-deportiva-atsa.jpg',
                'size' => 'large',
                'position' => 'center',
                'orden' => 1,
            ],
            [
                'page' => 'sindicato',
                'section' => 'historia',
                'title' => 'CENT N°74',
                'subtitle' => 'Formación',
                'description' => 'La educación sanitaria como eje de crecimiento institucional.',
                'image_path' => 'images/historia/formacion-cent-74.jpg',
                'link_url' => '/filiales',
                'link_text' => 'Ver filiales',
                'size' => 'medium',
                'position' => 'center',
                'orden' => 2,
            ],
            [
                'page' => 'sindicato',
                'section' => 'historia',
                'title' => 'Ciudad Deportiva',
                'subtitle' => 'Infraestructura',
                'description' => 'Un espacio social, deportivo y recreativo para la familia de la sanidad.',
                'image_path' => 'images/historia/infraestructura-ciudad-deportiva.jpg',
                'link_url' => '/turismo',
                'link_text' => 'Ver turismo',
                'size' => 'medium',
                'position' => 'center',
                'orden' => 3,
            ],
        ];

        foreach ($blocks as $block) {
            VisualBlock::updateOrCreate(
                [
                    'page' => $block['page'],
                    'section' => $block['section'],
                    'title' => $block['title'],
                ],
                $block + ['active' => true]
            );
        }
    }
}
