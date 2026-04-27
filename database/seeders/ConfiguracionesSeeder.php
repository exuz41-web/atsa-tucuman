<?php

namespace Database\Seeders;

use App\Models\Configuracion;
use Illuminate\Database\Seeder;

class ConfiguracionesSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['contacto', 'telefono_principal', '0381 4331665', 'telefono', 'Telefono principal'],
            ['contacto', 'telefono_cent', '0381 4332175', 'telefono', 'Telefono CENT N°74'],
            ['contacto', 'direccion', 'Paraguay y Thames, San Miguel de Tucumán', 'texto', 'Direccion institucional'],
            ['contacto', 'email_contacto', 'info@atsatucuman.org', 'email', 'Email de contacto'],
            ['contacto', 'whatsapp', '5493814331665', 'telefono', 'WhatsApp institucional'],
            ['contacto', 'horarios', 'Lunes a Viernes 8:00 a 16:00 hs', 'texto', 'Horarios de atencion'],
            ['redes_sociales', 'facebook', 'https://www.facebook.com/ATSATucuman', 'url', 'Facebook oficial'],
            ['redes_sociales', 'instagram', '', 'url', 'Instagram oficial'],
            ['redes_sociales', 'twitter', '', 'url', 'Twitter / X oficial'],
            ['sitio', 'nombre_sitio', 'ATSA Tucumán', 'texto', 'Nombre del sitio'],
            ['sitio', 'descripcion_sitio', 'Asociación de Trabajadores de la Sanidad Argentina - Tucumán', 'texto', 'Descripcion institucional'],
            ['sitio', 'secretario_general', 'René Ramírez', 'texto', 'Secretario General'],
            ['sitio', 'anio_fundacion', '1990', 'numero', 'Ano de fundacion'],
        ];

        foreach ($items as [$grupo, $clave, $valor, $tipo, $descripcion]) {
            Configuracion::updateOrCreate(
                ['clave' => $clave],
                compact('grupo', 'valor', 'tipo', 'descripcion')
            );
        }
    }
}
