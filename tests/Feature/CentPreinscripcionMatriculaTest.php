<?php

namespace Tests\Feature;

use App\Models\Carrera;
use App\Models\CentSede;
use App\Models\Comision;
use App\Models\Materia;
use App\Models\MatriculaCent;
use App\Models\PreinscripcionCent;
use App\Models\User;
use App\Services\Cent\InscribirMatriculaAComisiones;
use App\Services\Cent\MatricularPreinscripcion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CentPreinscripcionMatriculaTest extends TestCase
{
    use RefreshDatabase;

    public function test_preinscripcion_can_be_converted_to_student_matricula_and_commission_enrollment(): void
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

        $materia = Materia::create([
            'carrera_id' => $carrera->id,
            'name' => 'Enfermería Básica',
            'year' => 1,
        ]);

        $docente = User::factory()->create([
            'role' => 'docente',
            'cent_role' => 'docente',
            'active' => true,
        ]);

        $comision = Comision::create([
            'materia_id' => $materia->id,
            'cent_sede_id' => $sede->id,
            'docente_id' => $docente->id,
            'year_cycle' => 2026,
            'schedule' => 'Lunes 18:00',
        ]);

        $preinscripcion = PreinscripcionCent::create([
            'codigo' => 'CENT-2026-TEST',
            'carrera_id' => $carrera->id,
            'cent_sede_id' => $sede->id,
            'ciclo_lectivo' => 2026,
            'apellido_nombre' => 'Alumno Test',
            'tipo_documento' => 'DNI',
            'dni' => '30999111',
            'email' => 'alumno.test.cent@example.com',
            'telefono' => '3811234567',
            'estado' => 'aprobada',
        ]);

        $matricula = app(MatricularPreinscripcion::class)->ejecutar($preinscripcion, 'Cent1234!', 'cursando');
        $creadas = app(InscribirMatriculaAComisiones::class)->ejecutar($matricula, [$comision->id], 'aprobada');

        $this->assertSame(1, $creadas);
        $this->assertDatabaseHas('users', [
            'email' => 'alumno.test.cent@example.com',
            'dni' => '30999111',
            'role' => 'alumno',
            'cent_role' => 'alumno',
        ]);
        $this->assertDatabaseHas('matriculas_cent', [
            'id' => $matricula->id,
            'estado' => 'cursando',
            'legajo' => 'CENT2026-00001',
        ]);
        $this->assertDatabaseHas('preinscripciones_cent', [
            'codigo' => 'CENT-2026-TEST',
            'estado' => 'inscripta',
            'user_id' => $matricula->user_id,
        ]);
        $this->assertDatabaseHas('inscripciones', [
            'alumno_id' => $matricula->user_id,
            'comision_id' => $comision->id,
            'status' => 'aprobada',
        ]);
        $this->assertSame(1, MatriculaCent::count());
    }
}
