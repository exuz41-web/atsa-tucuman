<?php

namespace Database\Seeders;

use App\Models\Beneficio;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BeneficiosSeeder extends Seeder
{
    public function run(): void
    {
        $beneficios = [
            [
                'titulo' => 'Asesoramiento jurídico laboral',
                'categoria' => 'gremial',
                'descripcion_corta' => 'Orientación y acompañamiento ante conflictos laborales, sanciones, despidos y reclamos salariales.',
                'descripcion_larga' => 'ATSA Tucumán acompaña a cada trabajador de la sanidad con asesoramiento gremial y jurídico para defender sus derechos laborales.',
                'icono' => 'ti-scale',
                'requisitos' => 'Ser afiliado activo o trabajador encuadrado en la actividad de sanidad.',
                'documentacion' => 'DNI, recibo de sueldo, telegramas o documentación del reclamo si corresponde.',
                'link' => '/contacto',
                'publico' => true,
                'solo_afiliados' => false,
                'destacado' => true,
                'orden' => 1,
            ],
            [
                'titulo' => 'Representación gremial y paritarias',
                'categoria' => 'gremial',
                'descripcion_corta' => 'Defensa colectiva en paritarias, condiciones laborales y reclamos del sector salud.',
                'descripcion_larga' => 'La organización gremial sostiene la negociación colectiva y acompaña los reclamos de los trabajadores en toda la provincia.',
                'icono' => 'ti-shield-check',
                'link' => '/gremial',
                'publico' => true,
                'solo_afiliados' => false,
                'destacado' => true,
                'orden' => 2,
            ],
            [
                'titulo' => 'Formación profesional CENT N°74',
                'categoria' => 'formacion',
                'descripcion_corta' => 'Carreras técnicas, cursos y capacitación para jerarquizar a los trabajadores de la salud.',
                'descripcion_larga' => 'El CENT N°74 es uno de los pilares educativos de ATSA Tucumán, con presencia en distintas localidades y formación orientada al sector sanitario.',
                'imagen' => 'images/historia/formacion-cent-74.jpg',
                'icono' => 'ti-school',
                'link' => 'https://cent74atsatucuman.ar',
                'publico' => true,
                'solo_afiliados' => false,
                'destacado' => true,
                'orden' => 3,
            ],
            [
                'titulo' => 'Turismo y recreación',
                'categoria' => 'turismo',
                'descripcion_corta' => 'Ciudad Deportiva, Hotel ATSA Termas y convenios turísticos vinculados a FATSA.',
                'descripcion_larga' => 'Los afiliados pueden consultar beneficios recreativos para disfrutar con sus familias en espacios propios y convenios nacionales.',
                'imagen' => 'images/turismo/ciudad-deportiva/ingreso-atsa.jpeg',
                'icono' => 'ti-beach',
                'requisitos' => 'Ser afiliado activo y consultar disponibilidad según temporada.',
                'documentacion' => 'Carnet o número de afiliado. Puede solicitarse documentación adicional para reservas.',
                'link' => '/turismo',
                'publico' => true,
                'solo_afiliados' => false,
                'destacado' => true,
                'orden' => 4,
            ],
            [
                'titulo' => 'Acción social',
                'categoria' => 'accion_social',
                'descripcion_corta' => 'Acompañamiento ante necesidades familiares, situaciones de emergencia y pedidos especiales.',
                'descripcion_larga' => 'El área social permite canalizar solicitudes y acompañar al afiliado cuando atraviesa una necesidad concreta.',
                'icono' => 'ti-heart-handshake',
                'requisitos' => 'Ser afiliado activo y presentar la documentación respaldatoria del pedido.',
                'documentacion' => 'DNI, recibo de sueldo y documentación específica según el tipo de solicitud.',
                'link' => '/afiliados/login',
                'publico' => true,
                'solo_afiliados' => true,
                'destacado' => true,
                'orden' => 5,
            ],
            [
                'titulo' => 'Convenios y descuentos',
                'categoria' => 'convenios',
                'descripcion_corta' => 'Beneficios en comercios, servicios y propuestas para afiliados y sus familias.',
                'descripcion_larga' => 'ATSA puede publicar convenios vigentes, descuentos y propuestas especiales para la comunidad afiliada.',
                'icono' => 'ti-discount-2',
                'link' => '/afiliados/beneficios',
                'publico' => true,
                'solo_afiliados' => true,
                'destacado' => false,
                'orden' => 6,
            ],
            [
                'titulo' => 'Trámites y documentación',
                'categoria' => 'tramites',
                'descripcion_corta' => 'Gestión de constancias, descargas, pedidos y documentación útil desde el portal del afiliado.',
                'descripcion_larga' => 'El área privada permite centralizar consultas, pedidos y documentación para agilizar la atención.',
                'icono' => 'ti-file-check',
                'link' => '/afiliados/descargas',
                'publico' => true,
                'solo_afiliados' => true,
                'destacado' => false,
                'orden' => 7,
            ],
            [
                'titulo' => 'Atención en filiales',
                'categoria' => 'gremial',
                'descripcion_corta' => 'Presencia territorial para asesoramiento, consultas y acompañamiento cercano.',
                'descripcion_larga' => 'ATSA Tucumán sostiene atención gremial en sus filiales para estar cerca de cada compañero de la sanidad.',
                'imagen' => 'images/filiales/central-ciudad-deportiva.jpg',
                'icono' => 'ti-map-pin',
                'link' => '/filiales',
                'publico' => true,
                'solo_afiliados' => false,
                'destacado' => false,
                'orden' => 8,
            ],
        ];

        foreach ($beneficios as $beneficio) {
            Beneficio::updateOrCreate(
                ['slug' => Str::slug($beneficio['titulo'])],
                $beneficio + ['activo' => true]
            );
        }
    }
}
