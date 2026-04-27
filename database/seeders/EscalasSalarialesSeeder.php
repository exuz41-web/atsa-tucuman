<?php

namespace Database\Seeders;

use App\Models\EscalaSalarial;
use Illuminate\Database\Seeder;

class EscalasSalarialesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EscalaSalarial::updateOrCreate(
            ['titulo' => 'Paritaria Salud Tucumán 2026'],
            [
                'descripcion' => 'Escala salarial de referencia para trabajadores de la sanidad. Reemplazar el PDF desde el panel admin cuando esté disponible el documento oficial.',
                'archivo' => 'escalas-salariales/paritaria-salud-tucuman-2026.pdf',
                'vigente_desde' => '2026-01-01',
                'vigente_hasta' => null,
                'activo' => true,
            ]
        );
    }
}
