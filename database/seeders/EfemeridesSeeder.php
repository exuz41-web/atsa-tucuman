<?php

namespace Database\Seeders;

use App\Models\Efemeride;
use Illuminate\Database\Seeder;

class EfemeridesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [12, 5, 'Día Internacional de la Enfermería', 'Reconocimiento a la labor esencial de enfermería.'],
            [21, 5, 'Día del Trabajador de la Salud', 'Jornada para valorar al sector sanitario.'],
            [3, 7, 'Día del Médico Rural', 'Homenaje al trabajo sanitario en comunidades rurales.'],
            [29, 7, 'Día del Médico', 'Fecha de reconocimiento profesional.'],
            [20, 10, 'Día del Pediatra', 'Saludo a quienes cuidan la salud infantil.'],
            [21, 11, 'Día Nacional de la Enfermería', 'Efeméride nacional del sector.'],
            [3, 12, 'Día del Médico', 'Reconocimiento a médicos y médicas.'],
            [1, 5, 'Día Internacional del Trabajo', 'Reconocimiento a la organización de trabajadores.'],
            [10, 10, 'Día Mundial de la Salud Mental', 'Concientización sobre salud mental.'],
            [14, 6, 'Día Mundial del Donante de Sangre', 'Promoción de la donación voluntaria.'],
        ];

        foreach ($items as [$dia, $mes, $titulo, $descripcion]) {
            Efemeride::updateOrCreate(
                ['dia' => $dia, 'mes' => $mes, 'titulo' => $titulo],
                ['descripcion' => $descripcion, 'activo' => true]
            );
        }
    }
}
