<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelModulesTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_exposes_operational_modules(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'active' => true,
        ]);

        $this->actingAs($admin)
            ->get('/admin')
            ->assertOk()
            ->assertSee('Prensa y web pública')
            ->assertSee('Recepción y atención')
            ->assertSee('Secretarías y beneficios')
            ->assertSee('Afiliación y padrón')
            ->assertSee('Configuración y seguridad');
    }

    public function test_critical_admin_pages_render_without_filament_errors(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'active' => true,
        ]);

        User::factory()->create([
            'role' => 'afiliado',
            'active' => true,
            'tipo_afiliado' => 'estatal',
            'estado_afiliado' => 'activo',
            'carnet_activo' => true,
        ]);

        foreach ([
            '/admin/users',
            '/admin/beneficios',
            '/admin/pedidos',
            '/admin/solicitudes-beneficios',
            '/admin/ordenes-prestacion',
            '/admin/prestadores',
            '/admin/posts',
            '/admin/configuracion',
            '/admin/gestion-carnets',
        ] as $path) {
            $this->actingAs($admin)
                ->get($path)
                ->assertOk();
        }
    }
}
