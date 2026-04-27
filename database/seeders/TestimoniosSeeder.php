<?php

namespace Database\Seeders;

use App\Models\Testimonio;
use Illuminate\Database\Seeder;

class TestimoniosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['nombre' => 'María González', 'cargo' => 'Enfermera, Hospital Padilla', 'filial' => 'San Miguel', 'texto' => 'ATSA nos acompaña en cada reclamo y nos acerca capacitación para crecer en el trabajo.', 'orden' => 1],
            ['nombre' => 'Carlos Medina', 'cargo' => 'Técnico de laboratorio', 'filial' => 'Concepción', 'texto' => 'La filial está cerca cuando necesitamos asesoramiento. Eso hace una diferencia enorme.', 'orden' => 2],
            ['nombre' => 'Lucía Fernández', 'cargo' => 'Administrativa de clínica', 'filial' => 'Monteros', 'texto' => 'Ser afiliada me dio respaldo, información y acceso a beneficios para mi familia.', 'orden' => 3],
        ];

        foreach ($items as $item) {
            Testimonio::updateOrCreate(['nombre' => $item['nombre']], $item + ['activo' => true, 'estado' => 'aprobado']);
        }
    }
}
