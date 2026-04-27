<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CentAdminSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_cent_admin_pages_render_for_cent_admin(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin CENT',
            'email' => 'centadmin@atsa.com',
            'role' => 'admin',
            'cent_role' => 'directivo',
            'active' => true,
        ]);

        foreach ([
            '/cent-admin/carreras',
            '/cent-admin/materias',
            '/cent-admin/cent-sedes',
            '/cent-admin/preinscripciones-cent',
            '/cent-admin/matriculas-cent',
            '/cent-admin/usuarios',
            '/cent-admin/comisiones',
            '/cent-admin/inscripciones-academicas',
            '/cent-admin/notas',
            '/cent-admin/avisos-cent',
            '/cent-admin/mesas-examen',
            '/cent-admin/calendario',
            '/cent-admin/legajos',
            '/cent-admin/cuotas',
            '/cent-admin/recibos',
            '/cent-admin/notificaciones',
            '/cent-admin/equivalencias',
            '/cent-admin/auditoria',
            '/cent-admin/configuracion-cent',
        ] as $url) {
            $this->actingAs($admin)->get($url)->assertOk();
        }
    }
}
