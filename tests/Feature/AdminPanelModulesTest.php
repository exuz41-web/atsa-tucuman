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
}
