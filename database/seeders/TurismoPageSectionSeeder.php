<?php

namespace Database\Seeders;

use App\Models\PageSection;
use Illuminate\Database\Seeder;

class TurismoPageSectionSeeder extends Seeder
{
    public function run(): void
    {
        PageSection::updateOrCreate(
            ['page' => 'turismo', 'key' => 'condiciones'],
            [
                'label' => 'Reglamento turismo',
                'title' => 'Condiciones del beneficio',
                'subtitle' => 'Reglamento general para turismo y recreación',
                'body' => 'Los beneficios turísticos están sujetos a disponibilidad, condición de afiliado activo, reglamento vigente y confirmación previa por parte de ATSA Tucumán. Podrá solicitarse documentación respaldatoria para validar el acceso al beneficio. Las reservas de Ciudad Deportiva se consultan en el predio y los convenios hoteleros se informan según disponibilidad de temporada.',
                'orden' => 1,
                'active' => true,
            ]
        );
    }
}
