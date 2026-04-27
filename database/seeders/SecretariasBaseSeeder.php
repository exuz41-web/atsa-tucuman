<?php

namespace Database\Seeders;

use App\Models\Secretaria;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SecretariasBaseSeeder extends Seeder
{
    public function run(): void
    {
        $secretarias = [
            ['nombre' => 'Secretaría General', 'responsable' => 'Ramírez Edgar Reneé'],
            ['nombre' => 'Secretaría General Adjunta', 'responsable' => 'Ramírez Darío Reneé'],
            ['nombre' => 'Secretaría de Finanzas', 'responsable' => 'Aguirre Graciela Mabel'],
            ['nombre' => 'Prosecretaría de Finanzas', 'responsable' => 'Ramírez Yvone Melisa'],
            ['nombre' => 'Secretaría Gremial', 'responsable' => 'Ferreyra Alejandra Judith'],
            ['nombre' => 'Prosecretaría Gremial', 'responsable' => 'Rodríguez Myrian del Valle'],
            ['nombre' => 'Secretaría de Prensa y Propaganda', 'responsable' => 'Castro Daniel Agustín'],
            ['nombre' => 'Secretaría de Previsión y Acción Social', 'responsable' => 'Arancibia Silvia Liliana'],
            ['nombre' => 'Secretaría del Interior', 'responsable' => 'Álvarez Arturo Bernardo'],
            ['nombre' => 'Secretaría de Actas', 'responsable' => 'Gutiérrez Rodolfo Oscar'],
            ['nombre' => 'Secretaría de Turismo y Vivienda', 'responsable' => 'Castro Aldo Fabián'],
            ['nombre' => 'Secretaría de Deportes y Juventud', 'responsable' => 'Rodríguez Andrés Carmelo'],
            ['nombre' => 'Prosecretaría de Deportes y Juventud', 'responsable' => 'Paz Claudio Alejandro'],
            ['nombre' => 'Secretaría de Igualdad de Oportunidades y Género', 'responsable' => 'Musa Sandra Beatriz'],
            ['nombre' => 'Secretaría de Capacitación y Formación Profesional', 'responsable' => 'Santillán Marcelo'],
            ['nombre' => 'Prosecretaría de Capacitación y Formación Profesional', 'responsable' => 'Sultane Jose'],
            ['nombre' => 'Secretaría de Organización y Relaciones Institucionales', 'responsable' => 'Peña Dávila Silvio Jose Ariel'],
            ['nombre' => 'Secretaría de Higiene, Seguridad y Medicina del Trabajo', 'responsable' => 'Avellaneda Sergio Ricardo'],
        ];

        foreach ($secretarias as $index => $secretaria) {
            Secretaria::updateOrCreate(
                ['slug' => Str::slug($secretaria['nombre'])],
                [
                    'nombre' => $secretaria['nombre'],
                    'slug' => Str::slug($secretaria['nombre']),
                    'responsable' => $secretaria['responsable'],
                    'orden' => $index + 1,
                    'activa' => true,
                ]
            );
        }
    }
}
