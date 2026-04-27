<?php

namespace Tests\Feature;

use App\Models\Carrera;
use App\Models\CentSede;
use App\Models\PreinscripcionCent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CentPreinscripcionPublicTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_preinscripcion_requires_mandatory_documents(): void
    {
        [$carrera, $sede] = $this->catalogoBasico();

        $this->post(route('cent.preinscripcion.guardar'), [
            'carrera_id' => $carrera->id,
            'cent_sede_id' => $sede->id,
            'apellido_nombre' => 'Aspirante Test',
            'tipo_documento' => 'DNI',
            'dni' => '30000111',
            'email' => 'aspirante@example.com',
        ])->assertSessionHasErrors(['archivo_dni', 'archivo_titulo']);
    }

    public function test_public_preinscripcion_stores_request_and_files(): void
    {
        Storage::fake('public');
        [$carrera, $sede] = $this->catalogoBasico();

        $response = $this->post(route('cent.preinscripcion.guardar'), [
            'carrera_id' => $carrera->id,
            'cent_sede_id' => $sede->id,
            'apellido_nombre' => 'Aspirante Test',
            'tipo_documento' => 'DNI',
            'dni' => '30000112',
            'email' => 'aspirante2@example.com',
            'telefono' => '3811234567',
            'archivo_dni' => UploadedFile::fake()->image('dni.jpg'),
            'archivo_titulo' => UploadedFile::fake()->create('titulo.pdf', 128, 'application/pdf'),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('preinscripciones_cent', [
            'apellido_nombre' => 'Aspirante Test',
            'dni' => '30000112',
            'estado' => 'pendiente',
        ]);
    }

    public function test_applicant_can_check_status_and_update_documents(): void
    {
        Storage::fake('public');
        [$carrera, $sede] = $this->catalogoBasico();

        $preinscripcion = PreinscripcionCent::create([
            'codigo' => 'CENT-2026-CONSULTA',
            'carrera_id' => $carrera->id,
            'cent_sede_id' => $sede->id,
            'ciclo_lectivo' => 2026,
            'apellido_nombre' => 'Aspirante Consulta',
            'tipo_documento' => 'DNI',
            'dni' => '30000113',
            'email' => 'consulta@example.com',
            'estado' => 'en_revision',
            'observaciones_admin' => 'Falta constancia actualizada.',
        ]);

        $this->post(route('cent.preinscripcion.consultar'), [
            'codigo' => $preinscripcion->codigo,
            'dni' => $preinscripcion->dni,
        ])->assertOk()
            ->assertSee('Falta constancia actualizada.');

        $this->post(route('cent.preinscripcion.documentacion', $preinscripcion->codigo), [
            'dni' => $preinscripcion->dni,
            'archivo_titulo' => UploadedFile::fake()->create('constancia.pdf', 128, 'application/pdf'),
            'observaciones_alumno' => 'Adjunto constancia corregida.',
        ])->assertRedirect(route('cent.preinscripcion.consulta'));

        $this->assertDatabaseHas('preinscripciones_cent', [
            'codigo' => $preinscripcion->codigo,
            'estado' => 'en_revision',
        ]);

        $this->assertNotNull($preinscripcion->fresh()->archivo_titulo);
    }

    private function catalogoBasico(): array
    {
        $carrera = Carrera::create([
            'name' => 'Enfermería Profesional',
            'slug' => 'enfermeria-profesional',
            'duration' => '3 años',
            'title_granted' => 'Enfermero/a Profesional',
            'description' => 'Demo',
            'active' => true,
        ]);

        $sede = CentSede::create([
            'nombre' => 'CENT N°74 Capital',
            'slug' => 'cent-n74-capital',
            'ciudad' => 'San Miguel de Tucumán',
            'direccion' => 'Paraguay y Thames',
            'activa' => true,
            'orden' => 1,
        ]);

        return [$carrera, $sede];
    }
}
