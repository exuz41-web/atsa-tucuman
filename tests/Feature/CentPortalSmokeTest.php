<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CentPortalSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_cent_alumno_portal_pages_render(): void
    {
        $alumno = User::factory()->create([
            'role' => 'alumno',
            'cent_role' => 'alumno',
            'active' => true,
        ]);

        foreach ([
            '/cent74/alumno/dashboard',
            '/cent74/alumno/mi-carrera',
            '/cent74/alumno/mis-notas',
            '/cent74/alumno/ficha-academica',
            '/cent74/alumno/mesas',
            '/cent74/alumno/legajo',
            '/cent74/alumno/cuotas',
            '/cent74/calendario',
            '/cent74/perfil',
            '/cent74/avisos',
        ] as $url) {
            $this->actingAs($alumno)->get($url)->assertOk();
        }
    }

    public function test_cent_docente_portal_pages_render(): void
    {
        $docente = User::factory()->create([
            'role' => 'docente',
            'cent_role' => 'docente',
            'active' => true,
        ]);

        foreach ([
            '/cent74/docente/dashboard',
            '/cent74/docente/comisiones',
            '/cent74/docente/mesas',
            '/cent74/calendario',
            '/cent74/perfil',
            '/cent74/avisos',
        ] as $url) {
            $this->actingAs($docente)->get($url)->assertOk();
        }
    }

    public function test_cent_directivo_portal_pages_render(): void
    {
        $directivo = User::factory()->create([
            'role' => 'admin',
            'cent_role' => 'directivo',
            'active' => true,
        ]);

        foreach ([
            '/cent74/directivo/dashboard',
            '/cent74/directivo/alumnos',
            '/cent74/directivo/docentes',
            '/cent74/directivo/comisiones',
            '/cent74/directivo/actas',
            '/cent74/directivo/actas-mesas',
            '/cent74/calendario',
            '/cent74/perfil',
            '/cent74/avisos',
            '/cent74/directivo/reportes',
        ] as $url) {
            $this->actingAs($directivo)->get($url)->assertOk();
        }
    }
}
