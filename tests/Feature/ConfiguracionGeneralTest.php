<?php

namespace Tests\Feature;

use App\Console\Commands\RunBackups;
use App\Filament\Pages\Configuracion;
use App\Models\Configuracion as ConfiguracionModel;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ConfiguracionGeneralTest extends TestCase
{
    use RefreshDatabase;

    public function test_configuration_page_updates_site_settings_and_operational_keys(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'active' => true,
        ]);

        $this->actingAs($admin);

        Livewire::test(Configuracion::class)
            ->set('data.site_name', 'ATSA Tucumán renovado')
            ->set('data.institutional_text', 'Texto institucional actualizado')
            ->set('data.footer_text', 'Pie institucional')
            ->set('data.telefono_principal', '0381 4000000')
            ->set('data.telefono_cent', '0381 4111111')
            ->set('data.direccion', 'Paraguay 100')
            ->set('data.email_contacto', 'contacto@atsa.test')
            ->set('data.whatsapp', '543814000000')
            ->set('data.horarios', 'Lunes a viernes')
            ->set('data.facebook', 'https://facebook.com/atsa')
            ->set('data.instagram', 'https://instagram.com/atsa')
            ->set('data.youtube', 'https://youtube.com/@atsa')
            ->set('data.tiktok', 'https://tiktok.com/@atsa')
            ->set('data.twitter', 'https://x.com/atsa')
            ->set('data.nombre_sitio', 'ATSA Tucumán')
            ->set('data.descripcion_sitio', 'Descripción pública')
            ->set('data.secretario_general', 'Secretaría General')
            ->set('data.anio_fundacion', '1950')
            ->set('data.google_analytics_id', 'G-TEST123')
            ->set('data.public_site_enabled', true)
            ->set('data.maintenance_message', 'Sin novedades')
            ->set('data.backup_enabled', false)
            ->set('data.backup_schedule', '03:15')
            ->set('data.backup_keep_days', '30')
            ->set('data.smtp_enabled', true)
            ->set('data.smtp_host', 'smtp.atsa.test')
            ->set('data.smtp_port', '587')
            ->set('data.smtp_username', 'mailer')
            ->set('data.smtp_password', 'secret')
            ->set('data.smtp_encryption', 'tls')
            ->set('data.smtp_from_address', 'no-reply@atsa.test')
            ->set('data.smtp_from_name', 'ATSA')
            ->call('save')
            ->assertHasNoErrors();

        $settings = SiteSetting::current();

        $this->assertSame('ATSA Tucumán renovado', $settings->site_name);
        $this->assertSame('https://youtube.com/@atsa', $settings->youtube_url);
        $this->assertSame('G-TEST123', $settings->google_analytics_id);
        $this->assertSame('no-reply@atsa.test', $settings->mail_from_address);
        $this->assertSame('0', ConfiguracionModel::get('backup_enabled'));
        $this->assertSame('30', ConfiguracionModel::get('backup_keep_days'));
        $this->assertSame('1', ConfiguracionModel::get('smtp_enabled'));
        $this->assertNotSame('secret', ConfiguracionModel::get('smtp_password'));
    }

    public function test_backup_command_respects_database_configuration(): void
    {
        ConfiguracionModel::create([
            'clave' => 'backup_enabled',
            'valor' => '0',
            'tipo' => 'numero',
            'descripcion' => 'Backups automáticos activos',
            'grupo' => 'backups',
        ]);

        $this->artisan(RunBackups::class)
            ->expectsOutput('Los backups automáticos están desactivados por configuración.')
            ->assertSuccessful();
    }
}
