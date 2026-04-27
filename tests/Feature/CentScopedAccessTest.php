<?php

namespace Tests\Feature;

use App\Models\Carrera;
use App\Models\CentSede;
use App\Models\Comision;
use App\Models\Inscripcion;
use App\Models\InscripcionMesaCent;
use App\Models\Materia;
use App\Models\MatriculaCent;
use App\Models\MesaExamenCent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CentScopedAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_scoped_directivo_cannot_access_other_sede_comision_or_student_documents(): void
    {
        [$sedePropia, $sedeAjena, $carrera, $materia, $docente, $alumnoPropio, $alumnoAjeno] = $this->crearEscenarioBase();

        $directivo = User::factory()->create([
            'role' => 'admin',
            'cent_role' => 'directivo',
            'cent_sede_id' => $sedePropia->id,
            'active' => true,
        ]);

        $comisionPropia = Comision::create([
            'materia_id' => $materia->id,
            'cent_sede_id' => $sedePropia->id,
            'docente_id' => $docente->id,
            'year_cycle' => now()->year,
            'schedule' => 'Lunes 18:00',
        ]);

        $comisionAjena = Comision::create([
            'materia_id' => $materia->id,
            'cent_sede_id' => $sedeAjena->id,
            'docente_id' => $docente->id,
            'year_cycle' => now()->year,
            'schedule' => 'Martes 18:00',
        ]);

        $this->actingAs($directivo)
            ->get(route('cent.directivo.comisiones.editar', $comisionPropia))
            ->assertOk();

        $this->actingAs($directivo)
            ->get(route('cent.directivo.comisiones.editar', $comisionAjena))
            ->assertForbidden();

        $this->actingAs($directivo)
            ->get(route('cent.directivo.alumnos.constancia', $alumnoAjeno))
            ->assertForbidden();

        $this->actingAs($directivo)
            ->get(route('cent.docente.alumnos.ficha-pdf', $alumnoAjeno))
            ->assertForbidden();
    }

    public function test_scoped_directivo_cannot_manage_other_sede_mesas_or_cross_sede_enrollments(): void
    {
        [$sedePropia, $sedeAjena, $carrera, $materia, $docente, $alumnoPropio, $alumnoAjeno] = $this->crearEscenarioBase();

        $directivo = User::factory()->create([
            'role' => 'admin',
            'cent_role' => 'directivo',
            'cent_sede_id' => $sedePropia->id,
            'active' => true,
        ]);

        $comisionPropia = Comision::create([
            'materia_id' => $materia->id,
            'cent_sede_id' => $sedePropia->id,
            'docente_id' => $docente->id,
            'year_cycle' => now()->year,
            'schedule' => 'Lunes 18:00',
        ]);

        $mesaAjena = MesaExamenCent::create([
            'materia_id' => $materia->id,
            'cent_sede_id' => $sedeAjena->id,
            'docente_id' => $docente->id,
            'fecha' => now()->addMonth()->toDateString(),
            'estado' => 'abierta',
            'acta_estado' => 'cerrada',
        ]);

        $inscripcionAjena = InscripcionMesaCent::create([
            'mesa_examen_cent_id' => $mesaAjena->id,
            'alumno_id' => $alumnoAjeno->id,
            'estado' => 'inscripto',
        ]);

        $this->actingAs($directivo)
            ->post(route('cent.directivo.comisiones.inscribir', $comisionPropia), [
                'alumno_id' => $alumnoAjeno->id,
                'status' => 'aprobada',
            ])
            ->assertForbidden();

        $this->assertDatabaseMissing('inscripciones', [
            'alumno_id' => $alumnoAjeno->id,
            'comision_id' => $comisionPropia->id,
        ]);

        $this->actingAs($directivo)
            ->get(route('cent.directivo.actas-mesas.pdf', $mesaAjena))
            ->assertForbidden();

        $this->actingAs($directivo)
            ->get(route('cent.alumno.mesas.comprobante', $inscripcionAjena))
            ->assertForbidden();
    }

    private function crearEscenarioBase(): array
    {
        $sedePropia = CentSede::create([
            'nombre' => 'Capital',
            'slug' => 'capital-'.Str::random(6),
            'ciudad' => 'San Miguel de Tucumán',
            'activa' => true,
        ]);

        $sedeAjena = CentSede::create([
            'nombre' => 'Concepción',
            'slug' => 'concepcion-'.Str::random(6),
            'ciudad' => 'Concepción',
            'activa' => true,
        ]);

        $carrera = Carrera::create([
            'name' => 'Enfermería Profesional',
            'slug' => 'enfermeria-profesional-'.Str::random(6),
            'duration' => '3 años',
            'title_granted' => 'Enfermero Profesional',
            'description' => 'Carrera de prueba.',
            'active' => true,
        ]);

        $materia = Materia::create([
            'carrera_id' => $carrera->id,
            'name' => 'Enfermería Básica',
            'year' => 1,
            'semester' => 1,
        ]);

        $docente = User::factory()->create([
            'role' => 'docente',
            'cent_role' => 'docente',
            'active' => true,
        ]);

        $alumnoPropio = User::factory()->create([
            'role' => 'alumno',
            'cent_role' => 'alumno',
            'active' => true,
        ]);

        $alumnoAjeno = User::factory()->create([
            'role' => 'alumno',
            'cent_role' => 'alumno',
            'active' => true,
        ]);

        MatriculaCent::create([
            'user_id' => $alumnoPropio->id,
            'carrera_id' => $carrera->id,
            'cent_sede_id' => $sedePropia->id,
            'legajo' => 'LEG-'.Str::random(8),
            'ciclo_lectivo' => now()->year,
            'estado' => 'cursando',
        ]);

        MatriculaCent::create([
            'user_id' => $alumnoAjeno->id,
            'carrera_id' => $carrera->id,
            'cent_sede_id' => $sedeAjena->id,
            'legajo' => 'LEG-'.Str::random(8),
            'ciclo_lectivo' => now()->year,
            'estado' => 'cursando',
        ]);

        return [$sedePropia, $sedeAjena, $carrera, $materia, $docente, $alumnoPropio, $alumnoAjeno];
    }
}
