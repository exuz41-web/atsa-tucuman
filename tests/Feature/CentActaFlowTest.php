<?php

namespace Tests\Feature;

use App\Models\Carrera;
use App\Models\CentSede;
use App\Models\Comision;
use App\Models\Inscripcion;
use App\Models\MatriculaCent;
use App\Models\Materia;
use App\Models\MesaExamenCent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CentActaFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_docente_carga_notas_y_direccion_aprueba_acta(): void
    {
        [$docente, $directivo, $alumno, $comision] = $this->crearComisionConAlumno();

        $this->actingAs($docente)
            ->post("/cent74/docente/comisiones/{$comision->id}/planilla", [
                'notas' => [
                    $alumno->id => [
                        'parcial1' => ['grade' => 8.50, 'status' => 'aprobado'],
                        'final' => ['grade' => 9, 'status' => 'aprobado'],
                    ],
                ],
            ])
            ->assertRedirect("/cent74/docente/comisiones/{$comision->id}/planilla");

        $this->assertDatabaseHas('notas', [
            'alumno_id' => $alumno->id,
            'comision_id' => $comision->id,
            'type' => 'final',
            'status' => 'aprobado',
            'loaded_by' => $docente->id,
        ]);

        $this->actingAs($docente)
            ->post("/cent74/docente/comisiones/{$comision->id}/planilla/cerrar", [
                'acta_observaciones' => 'Carga finalizada.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('comisiones', [
            'id' => $comision->id,
            'acta_estado' => 'cerrada',
            'acta_cerrada_por' => $docente->id,
        ]);

        $this->actingAs($directivo)
            ->post("/cent74/docente/comisiones/{$comision->id}/planilla/aprobar")
            ->assertRedirect();

        $this->assertDatabaseHas('comisiones', [
            'id' => $comision->id,
            'acta_estado' => 'aprobada',
            'acta_aprobada_por' => $directivo->id,
        ]);
    }

    public function test_directivo_puede_reabrir_acta_aprobada(): void
    {
        [, $directivo, , $comision] = $this->crearComisionConAlumno([
            'acta_estado' => 'aprobada',
            'acta_aprobada_at' => now(),
        ]);

        $this->actingAs($directivo)
            ->post("/cent74/docente/comisiones/{$comision->id}/planilla/reabrir", [
                'acta_observaciones' => 'Corrección solicitada por dirección.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('comisiones', [
            'id' => $comision->id,
            'acta_estado' => 'abierta',
            'acta_observaciones' => 'Corrección solicitada por dirección.',
        ]);
    }

    public function test_directivo_gestiona_comision_inscribe_alumno_y_emite_documentos(): void
    {
        [$docente, $directivo, $alumno, $carrera, $materia, $sede] = $this->crearBaseAcademica();

        MatriculaCent::create([
            'user_id' => $alumno->id,
            'carrera_id' => $carrera->id,
            'cent_sede_id' => $sede->id,
            'legajo' => 'CENT-TEST-001',
            'ciclo_lectivo' => now()->year,
            'estado' => 'cursando',
            'fecha_ingreso' => now(),
        ]);

        $this->actingAs($directivo)->get('/cent74/directivo/comisiones')->assertOk();
        $this->actingAs($directivo)->get('/cent74/directivo/comisiones/crear')->assertOk();

        $response = $this->actingAs($directivo)
            ->post('/cent74/directivo/comisiones', [
                'materia_id' => $materia->id,
                'cent_sede_id' => $sede->id,
                'docente_id' => $docente->id,
                'year_cycle' => now()->year,
                'schedule' => 'Martes 19:00',
            ]);

        $comision = Comision::latest('id')->first();

        $response->assertRedirect("/cent74/directivo/comisiones/{$comision->id}/editar");

        $this->actingAs($directivo)
            ->post("/cent74/directivo/comisiones/{$comision->id}/inscribir", [
                'alumno_id' => $alumno->id,
                'status' => 'aprobada',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('inscripciones', [
            'alumno_id' => $alumno->id,
            'comision_id' => $comision->id,
            'status' => 'aprobada',
        ]);

        $this->actingAs($directivo)->get("/cent74/directivo/alumnos/{$alumno->id}/constancia")->assertOk();
        $this->actingAs($directivo)->get("/cent74/directivo/alumnos/{$alumno->id}/ficha-pdf")->assertOk();
    }

    public function test_docente_carga_asistencia_y_alumno_descarga_documentos(): void
    {
        [$docente, , $alumno, $comision] = $this->crearComisionConAlumno();

        MatriculaCent::create([
            'user_id' => $alumno->id,
            'carrera_id' => $comision->materia->carrera_id,
            'cent_sede_id' => $comision->cent_sede_id,
            'legajo' => 'CENT-TEST-002',
            'ciclo_lectivo' => now()->year,
            'estado' => 'cursando',
            'fecha_ingreso' => now(),
        ]);

        $this->actingAs($docente)
            ->get("/cent74/docente/comisiones/{$comision->id}/asistencia")
            ->assertOk();

        $this->actingAs($docente)
            ->post("/cent74/docente/comisiones/{$comision->id}/asistencia", [
                'fecha' => now()->toDateString(),
                'asistencias' => [
                    $alumno->id => [
                        'estado' => 'presente',
                        'observaciones' => 'Participó en clase.',
                    ],
                ],
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('asistencia_cents', [
            'comision_id' => $comision->id,
            'alumno_id' => $alumno->id,
            'estado' => 'presente',
            'cargado_por' => $docente->id,
        ]);

        $this->actingAs($alumno)->get('/cent74/alumno/constancia')->assertOk();
        $this->actingAs($alumno)->get('/cent74/alumno/ficha-academica/pdf')->assertOk();
    }

    public function test_alumno_se_inscribe_a_mesa_y_docente_carga_resultado(): void
    {
        [$docente, , $alumno, $carrera, $materia, $sede] = $this->crearBaseAcademica();

        MatriculaCent::create([
            'user_id' => $alumno->id,
            'carrera_id' => $carrera->id,
            'cent_sede_id' => $sede->id,
            'legajo' => 'CENT-TEST-003',
            'ciclo_lectivo' => now()->year,
            'estado' => 'cursando',
            'fecha_ingreso' => now(),
        ]);

        $mesa = MesaExamenCent::create([
            'materia_id' => $materia->id,
            'cent_sede_id' => $sede->id,
            'docente_id' => $docente->id,
            'fecha' => now()->addDays(10)->toDateString(),
            'hora' => '18:00',
            'turno' => 'Tarde',
            'aula' => 'Aula 1',
            'cupo' => 20,
            'estado' => 'abierta',
        ]);

        $this->actingAs($alumno)->get('/cent74/alumno/mesas')->assertOk();

        $this->actingAs($alumno)
            ->post("/cent74/alumno/mesas/{$mesa->id}/inscribir")
            ->assertRedirect();

        $this->assertDatabaseHas('inscripcion_mesa_cents', [
            'mesa_examen_cent_id' => $mesa->id,
            'alumno_id' => $alumno->id,
            'estado' => 'inscripto',
        ]);

        $inscripcion = \App\Models\InscripcionMesaCent::first();

        $this->actingAs($alumno)
            ->get("/cent74/alumno/mesas/inscripcion/{$inscripcion->id}/comprobante")
            ->assertOk();

        $this->actingAs($docente)->get("/cent74/docente/mesas/{$mesa->id}")->assertOk();

        $this->actingAs($docente)
            ->post("/cent74/docente/mesas/{$mesa->id}/resultados", [
                'cerrar_mesa' => true,
                'resultados' => [
                    $inscripcion->id => [
                        'estado' => 'aprobado',
                        'nota' => 8,
                        'observaciones' => 'Aprobado en mesa final.',
                    ],
                ],
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('inscripcion_mesa_cents', [
            'id' => $inscripcion->id,
            'estado' => 'aprobado',
            'nota' => 8,
            'cargado_por' => $docente->id,
        ]);

        $this->assertDatabaseHas('mesa_examen_cents', [
            'id' => $mesa->id,
            'estado' => 'finalizada',
            'acta_estado' => 'cerrada',
            'acta_cerrada_por' => $docente->id,
        ]);

        $directivo = User::where('cent_role', 'directivo')->first();

        $this->actingAs($directivo)
            ->get("/cent74/directivo/actas-mesas/{$mesa->id}/pdf")
            ->assertOk();

        $this->actingAs($directivo)
            ->post("/cent74/directivo/actas-mesas/{$mesa->id}/aprobar", [
                'acta_libro' => '1',
                'acta_folio' => '12',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('mesa_examen_cents', [
            'id' => $mesa->id,
            'acta_estado' => 'aprobada',
            'acta_aprobada_por' => $directivo->id,
            'acta_libro' => '1',
            'acta_folio' => '12',
        ]);
    }

    private function crearComisionConAlumno(array $comisionOverrides = []): array
    {
        [$docente, $directivo, $alumno, , $materia, $sede] = $this->crearBaseAcademica();

        $comision = Comision::create(array_merge([
            'materia_id' => $materia->id,
            'cent_sede_id' => $sede->id,
            'docente_id' => $docente->id,
            'year_cycle' => now()->year,
            'schedule' => 'Lunes 18:00',
            'acta_estado' => 'abierta',
        ], $comisionOverrides));

        Inscripcion::create([
            'alumno_id' => $alumno->id,
            'comision_id' => $comision->id,
            'status' => 'aprobada',
        ]);

        return [$docente, $directivo, $alumno, $comision];
    }

    private function crearBaseAcademica(): array
    {
        $docente = User::factory()->create([
            'role' => 'docente',
            'cent_role' => 'docente',
            'active' => true,
        ]);

        $directivo = User::factory()->create([
            'role' => 'admin',
            'cent_role' => 'directivo',
            'active' => true,
        ]);

        $alumno = User::factory()->create([
            'role' => 'alumno',
            'cent_role' => 'alumno',
            'active' => true,
            'dni' => '12345678',
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

        $sede = CentSede::create([
            'nombre' => 'Capital',
            'slug' => 'capital-'.Str::random(6),
            'ciudad' => 'San Miguel de Tucumán',
            'activa' => true,
        ]);

        return [$docente, $directivo, $alumno, $carrera, $materia, $sede];
    }
}
