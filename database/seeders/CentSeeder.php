<?php

namespace Database\Seeders;

use App\Models\Carrera;
use App\Models\CentSede;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CentSeeder extends Seeder
{
    public function run(): void
    {
        $sedes = [
            ['nombre' => 'CENT N°74 Capital', 'ciudad' => 'San Miguel de Tucumán', 'direccion' => 'Ciudad Deportiva ATSA, Paraguay y Thames', 'telefono' => '0381 4332175', 'orden' => 1],
            ['nombre' => 'CENT N°74 Trancas', 'ciudad' => 'Trancas', 'orden' => 2],
            ['nombre' => 'CENT N°74 Delfín Gallo', 'ciudad' => 'Delfín Gallo', 'orden' => 3],
            ['nombre' => 'CENT N°74 Banda del Río Salí', 'ciudad' => 'Banda del Río Salí', 'direccion' => 'Cam. del Carmen 90', 'orden' => 4],
            ['nombre' => 'CENT N°74 Concepción', 'ciudad' => 'Concepción', 'direccion' => 'Julio Argentino Roca 371', 'orden' => 5],
            ['nombre' => 'CENT N°74 Los Ralos', 'ciudad' => 'Los Ralos', 'orden' => 6],
            ['nombre' => 'CENT N°74 Simoca', 'ciudad' => 'Simoca', 'orden' => 7],
            ['nombre' => 'CENT N°74 Santa Rosa de Leales', 'ciudad' => 'Santa Rosa de Leales', 'orden' => 8],
            ['nombre' => 'CENT N°74 Tafí Viejo', 'ciudad' => 'Tafí Viejo', 'orden' => 9],
            ['nombre' => 'CENT N°74 Lules', 'ciudad' => 'Lules', 'orden' => 10],
            ['nombre' => 'CENT N°74 Graneros', 'ciudad' => 'Graneros', 'orden' => 11],
            ['nombre' => 'CENT N°74 Aguilares', 'ciudad' => 'Aguilares', 'orden' => 12],
            ['nombre' => 'CENT N°74 La Ramada', 'ciudad' => 'La Ramada', 'orden' => 13],
            ['nombre' => 'CENT N°74 Amaicha del Valle', 'ciudad' => 'Amaicha del Valle', 'orden' => 14],
            ['nombre' => 'CENT N°74 Famaillá', 'ciudad' => 'Famaillá', 'orden' => 15],
            ['nombre' => 'CENT N°74 Monteros', 'ciudad' => 'Monteros', 'orden' => 16],
        ];

        foreach ($sedes as $sede) {
            CentSede::updateOrCreate(
                ['slug' => Str::slug($sede['nombre'])],
                array_merge([
                    'horarios' => 'Consultar días y horarios en la sede.',
                    'activa' => true,
                ], $sede)
            );
        }

        $carreras = [
            ['name' => 'Enfermería Profesional', 'duration' => '3 años', 'title_granted' => 'Enfermero/a Profesional'],
            ['name' => 'Tec. Sup. en Agente Socio Sanitario', 'duration' => '3 años', 'title_granted' => 'Técnico/a Superior en Agente Socio Sanitario'],
            ['name' => 'Tec. Sup. en Diagnóstico por Imágenes', 'duration' => '3 años', 'title_granted' => 'Técnico/a Superior en Diagnóstico por Imágenes'],
            ['name' => 'Tec. Sup. en Farmacia', 'duration' => '3 años', 'title_granted' => 'Técnico/a Superior en Farmacia'],
            ['name' => 'Tec. Sup. en Laboratorio de Análisis Clínicos', 'duration' => '3 años', 'title_granted' => 'Técnico/a Superior en Laboratorio de Análisis Clínicos'],
            ['name' => 'Tec. Sup. en Esterilización', 'duration' => '3 años', 'title_granted' => 'Técnico/a Superior en Esterilización'],
        ];

        foreach ($carreras as $carrera) {
            Carrera::updateOrCreate(
                ['slug' => Str::slug($carrera['name'])],
                array_merge($carrera, [
                    'description' => 'Carrera terciaria orientada a la formación profesional en ciencias de la salud, con acompañamiento académico del CENT N°74 de ATSA Tucumán.',
                    'requirements' => "DNI.\nTítulo secundario o constancia correspondiente.\nDocumentación personal solicitada por la sede.",
                    'active' => true,
                ])
            );
        }
    }
}
