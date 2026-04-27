<?php

namespace Database\Seeders;

use App\Models\CentConfiguracion;
use Illuminate\Database\Seeder;

class CentConfiguracionSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['contacto', 'nombre_institucion', 'CENT N°74', 'texto', 'Nombre público del instituto'],
            ['contacto', 'telefono', '0381 4332175', 'telefono', 'Teléfono principal del CENT'],
            ['contacto', 'email', 'cent74@atsatucuman.org', 'email', 'Correo institucional'],
            ['contacto', 'direccion_capital', 'Predio ATSA, Ecuador y Thames, San Miguel de Tucumán', 'texto', 'Dirección sede Capital'],
            ['contacto', 'horarios', 'Lunes a viernes de 8:00 a 16:00 hs', 'texto', 'Horario de atención'],
            ['redes', 'facebook', 'https://www.facebook.com/ATSATucuman', 'url', 'Facebook institucional'],
            ['redes', 'instagram', '', 'url', 'Instagram institucional'],
            ['redes', 'youtube', '', 'url', 'Canal de YouTube'],
            ['sitio', 'hero_titulo', 'Formación técnica para la salud tucumana', 'texto', 'Título principal del sitio CENT'],
            ['sitio', 'hero_subtitulo', 'Carreras terciarias con salida laboral, prácticas profesionalizantes y sedes en toda la provincia.', 'texto', 'Subtítulo principal del sitio CENT'],
            ['academico', 'ciclo_activo', (string) now()->year, 'numero', 'Ciclo lectivo activo'],
            ['academico', 'preinscripciones_abiertas', '1', 'booleano', 'Habilita el formulario público de preinscripción'],
            ['pagos', 'descuento_afiliado_atsa', '20', 'numero', 'Porcentaje de descuento sugerido para afiliados ATSA'],
            ['pagos', 'descuento_hijo_afiliado_atsa', '15', 'numero', 'Porcentaje de descuento sugerido para hijos o familiares de afiliados'],
            ['pagos', 'dias_alerta_vencimiento', '7', 'numero', 'Días previos para alertar vencimientos de cuotas'],
            ['smtp', 'smtp_host', '', 'texto', 'Servidor SMTP'],
            ['smtp', 'smtp_port', '587', 'numero', 'Puerto SMTP'],
            ['smtp', 'smtp_user', '', 'texto', 'Usuario SMTP'],
            ['smtp', 'smtp_password', '', 'texto', 'Contraseña SMTP'],
            ['smtp', 'smtp_encryption', 'tls', 'texto', 'Cifrado SMTP'],
            ['smtp', 'mail_from_address', '', 'email', 'Email remitente'],
            ['smtp', 'mail_from_name', 'CENT N°74', 'texto', 'Nombre remitente'],
        ];

        foreach ($items as [$grupo, $clave, $valor, $tipo, $descripcion]) {
            CentConfiguracion::updateOrCreate(
                ['clave' => $clave],
                compact('grupo', 'valor', 'tipo', 'descripcion')
            );
        }
    }
}
