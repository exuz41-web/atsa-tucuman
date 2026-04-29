<?php

namespace Tests\Feature;

use App\Filament\Pages\Configuracion;
use App\Filament\Resources\AutoridadResource;
use App\Filament\Resources\CentActivityLogResource;
use App\Filament\Resources\CentCuotaResource;
use App\Filament\Resources\CentDescargaResource;
use App\Filament\Resources\CentHorarioResource;
use App\Filament\Resources\CentLegajoDocumentoResource;
use App\Filament\Resources\CentSedeResource;
use App\Filament\Resources\UserResource;
use App\Models\Carrera;
use App\Models\CentCuota;
use App\Models\CentEntregaTrabajo;
use App\Models\CentLegajoDocumento;
use App\Models\CentMaterial;
use App\Models\CentSede;
use App\Models\CentTrabajoPractico;
use App\Models\Comision;
use App\Models\Inscripcion;
use App\Models\Materia;
use App\Models\PreinscripcionCent;
use App\Models\SolicitudAfiliacion;
use App\Models\User;
use App\Support\BackupSupport;
use App\Support\CarnetSupport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class SecurityAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_afiliacion_pdf_requires_public_token_not_id_or_dni(): void
    {
        $solicitud = SolicitudAfiliacion::create([
            'estado' => 'pendiente',
            'apellido_nombre' => 'Persona Afiliada',
            'tipo_documento' => 'DNI',
            'numero_documento' => '30111222',
            'telefono' => '3815550000',
            'email' => 'persona@example.com',
            'acepta_declaracion' => true,
        ]);

        $this->get(route('afiliacion.pdf', $solicitud->id))->assertNotFound();
        $this->get(route('afiliacion.pdf', $solicitud->numero_documento))->assertNotFound();
        $this->get(route('afiliacion.pdf', $solicitud->public_token))->assertOk();
    }

    public function test_cent_carnet_verification_requires_public_token_not_user_id(): void
    {
        $alumno = User::factory()->create([
            'role' => 'alumno',
            'cent_role' => 'alumno',
            'active' => true,
        ]);

        $this->get(route('cent.carnet.verificar', $alumno->id))
            ->assertOk()
            ->assertSee('Estudiante no encontrado');

        $this->get(route('cent.carnet.verificar', $alumno->cent_public_token))
            ->assertOk()
            ->assertSee('Estudiante verificado');
    }

    public function test_affiliate_carnet_qr_uses_public_token_while_legacy_number_still_works(): void
    {
        $afiliado = User::factory()->create([
            'role' => 'afiliado',
            'active' => true,
            'estado_afiliado' => 'activo',
            'numero_afiliado' => 'A-900',
            'carnet_activo' => true,
            'carnet_vencimiento' => now()->addMonth(),
        ]);

        $this->assertNotNull($afiliado->afiliado_public_token);
        $this->assertSame(route('carnet.verificar', $afiliado->afiliado_public_token), CarnetSupport::verificationUrl($afiliado));

        $this->get(route('carnet.verificar', $afiliado->id))->assertOk()->assertSee('Carnet no encontrado');
        $this->get(route('carnet.verificar', $afiliado->numero_afiliado))->assertOk()->assertSee($afiliado->name);
        $this->get(route('carnet.verificar', $afiliado->afiliado_public_token))
            ->assertOk()
            ->assertSee($afiliado->name)
            ->assertSee('QR del carnet')
            ->assertSee('Ver QR grande para prestador');

        $this->get(route('carnet.qr', $afiliado->afiliado_public_token))
            ->assertOk()
            ->assertSee($afiliado->name)
            ->assertSee('QR grande');
    }

    public function test_cent_preinscripcion_files_are_private_and_pdf_uses_public_token(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        $carrera = Carrera::create([
            'name' => 'Enfermería Profesional',
            'slug' => 'enfermeria-profesional-'.Str::random(6),
            'duration' => '3 años',
            'title_granted' => 'Enfermero Profesional',
            'description' => 'Carrera de prueba.',
            'active' => true,
        ]);

        $sede = CentSede::create([
            'nombre' => 'Capital',
            'slug' => 'capital-'.Str::random(6),
            'ciudad' => 'San Miguel de Tucumán',
            'activa' => true,
        ]);

        $response = $this->post(route('cent.preinscripcion.guardar'), [
            'carrera_id' => $carrera->id,
            'cent_sede_id' => $sede->id,
            'apellido_nombre' => 'Aspirante CENT',
            'tipo_documento' => 'DNI',
            'dni' => '33222111',
            'email' => 'aspirante@example.com',
            'archivo_dni' => UploadedFile::fake()->image('dni.jpg'),
            'archivo_titulo' => UploadedFile::fake()->create('titulo.pdf', 100, 'application/pdf'),
        ]);

        $preinscripcion = PreinscripcionCent::firstOrFail();

        $response->assertRedirect(route('cent.preinscripcion.gracias', $preinscripcion->public_token));
        $this->assertNotSame($preinscripcion->codigo, $preinscripcion->public_token);

        Storage::disk('local')->assertExists($preinscripcion->archivo_dni);
        Storage::disk('local')->assertExists($preinscripcion->archivo_titulo);
        Storage::disk('public')->assertMissing($preinscripcion->archivo_dni);
        Storage::disk('public')->assertMissing($preinscripcion->archivo_titulo);

        $this->get(route('cent.preinscripcion.ficha', $preinscripcion->codigo))->assertNotFound();
        $this->get(route('cent.preinscripcion.ficha', $preinscripcion->public_token))->assertOk();
    }

    public function test_private_cent_files_require_login_and_ownership_or_privileged_role(): void
    {
        [$alumno, $otroAlumno, $docente, $directivo, $comision] = $this->crearComisionConUsuarios();

        $legajo = CentLegajoDocumento::create([
            'user_id' => $alumno->id,
            'tipo' => 'dni',
            'archivo' => 'cent/legajos/test-dni.pdf',
            'estado' => 'pendiente',
        ]);

        $cuota = CentCuota::create([
            'alumno_id' => $alumno->id,
            'concepto' => 'Cuota abril',
            'periodo' => 'Abril 2026',
            'monto' => 1000,
            'estado' => 'pendiente',
            'comprobante' => 'cent/cuotas/test-comprobante.pdf',
        ]);

        $material = CentMaterial::create([
            'comision_id' => $comision->id,
            'titulo' => 'Material privado',
            'tipo' => 'apunte',
            'archivo' => 'cent/materiales/test-material.pdf',
            'publicado' => true,
            'creado_por' => $docente->id,
        ]);

        $trabajo = CentTrabajoPractico::create([
            'comision_id' => $comision->id,
            'titulo' => 'TP privado',
            'consigna' => 'Resolver actividad.',
            'archivo_consigna' => 'cent/trabajos/test-consigna.pdf',
            'publicado' => true,
            'acepta_entregas' => true,
            'creado_por' => $docente->id,
        ]);

        $entrega = CentEntregaTrabajo::create([
            'trabajo_practico_id' => $trabajo->id,
            'alumno_id' => $alumno->id,
            'archivo' => 'cent/entregas/test-entrega.pdf',
            'estado' => 'entregado',
            'entregado_at' => now(),
        ]);

        foreach ([
            $legajo->archivo,
            $cuota->comprobante,
            $material->archivo,
            $trabajo->archivo_consigna,
            $entrega->archivo,
        ] as $path) {
            Storage::disk('local')->put($path, 'archivo privado');
        }

        $this->get(route('cent.archivos.legajo', $legajo))->assertRedirect(route('login'));
        $this->actingAs($otroAlumno)->get(route('cent.archivos.legajo', $legajo))->assertForbidden();
        $this->actingAs($alumno)->get(route('cent.archivos.legajo', $legajo))->assertOk();
        $this->actingAs($directivo)->get(route('cent.archivos.legajo', $legajo))->assertOk();

        $this->actingAs($otroAlumno)->get(route('cent.archivos.cuotas.comprobante', $cuota))->assertForbidden();
        $this->actingAs($alumno)->get(route('cent.archivos.cuotas.comprobante', $cuota))->assertOk();

        $this->actingAs($otroAlumno)->get(route('cent.archivos.materiales', $material))->assertForbidden();
        $this->actingAs($alumno)->get(route('cent.archivos.materiales', $material))->assertOk();
        $this->actingAs($docente)->get(route('cent.archivos.materiales', $material))->assertOk();

        $this->actingAs($otroAlumno)->get(route('cent.archivos.trabajos.consigna', $trabajo))->assertForbidden();
        $this->actingAs($alumno)->get(route('cent.archivos.trabajos.consigna', $trabajo))->assertOk();

        $this->actingAs($otroAlumno)->get(route('cent.archivos.entregas', $entrega))->assertForbidden();
        $this->actingAs($alumno)->get(route('cent.archivos.entregas', $entrega))->assertOk();
        $this->actingAs($docente)->get(route('cent.archivos.entregas', $entrega))->assertOk();
    }

    public function test_backup_download_requires_backup_permission(): void
    {
        File::ensureDirectoryExists(BackupSupport::backupDirectory());
        File::put(BackupSupport::pathFor('test-backup.tar'), 'backup');

        $recepcion = User::factory()->create([
            'role' => 'afiliado',
            'perfil_interno' => 'recepcion',
            'active' => true,
        ]);

        $admin = User::factory()->create([
            'role' => 'admin',
            'active' => true,
        ]);

        $this->actingAs($recepcion)
            ->get(route('panel.backups.download', ['filename' => 'test-backup.tar']))
            ->assertForbidden();

        $this->actingAs($admin)
            ->get(route('panel.backups.download', ['filename' => 'test-backup.tar']))
            ->assertOk();
    }

    public function test_permission_map_limits_key_resource_access(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'active' => true]);
        $recepcion = User::factory()->create(['role' => 'afiliado', 'perfil_interno' => 'recepcion', 'active' => true]);
        $padron = User::factory()->create(['role' => 'afiliado', 'perfil_interno' => 'padron', 'active' => true]);
        $docente = User::factory()->create(['role' => 'docente', 'cent_role' => 'docente', 'active' => true]);
        $directivo = User::factory()->create(['role' => 'admin', 'cent_role' => 'directivo', 'active' => true]);
        $secretaria = User::factory()->create(['role' => 'afiliado', 'perfil_interno' => 'secretaria', 'active' => true]);
        $alumno = User::factory()->create(['role' => 'alumno', 'cent_role' => 'alumno', 'active' => true]);

        $this->assertTrue($admin->hasPermission('admin.backups.manage'));
        $this->assertTrue($recepcion->hasPermission('admin.afiliacion.manage'));
        $this->assertFalse($recepcion->hasPagePermission(Configuracion::class, 'admin'));
        $this->assertTrue($padron->hasResourcePermission(UserResource::class, 'admin'));
        $this->assertTrue($secretaria->hasResourcePermission(AutoridadResource::class, 'admin'));
        $this->assertFalse($docente->hasResourcePermission(CentCuotaResource::class, 'cent'));
        $this->assertFalse($docente->hasResourcePermission(CentSedeResource::class, 'cent'));
        $this->assertFalse($alumno->hasResourcePermission(CentDescargaResource::class, 'cent'));
        $this->assertFalse($alumno->hasResourcePermission(CentHorarioResource::class, 'cent'));
        $this->assertTrue($directivo->hasResourcePermission(CentLegajoDocumentoResource::class, 'cent'));
        $this->assertTrue($directivo->hasResourcePermission(CentSedeResource::class, 'cent'));
        $this->assertTrue($directivo->hasResourcePermission(CentActivityLogResource::class, 'cent'));
        $this->assertTrue($directivo->hasPermission('cent.reportes.manage'));
    }

    private function crearComisionConUsuarios(): array
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
        ]);

        $otroAlumno = User::factory()->create([
            'role' => 'alumno',
            'cent_role' => 'alumno',
            'active' => true,
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

        $comision = Comision::create([
            'materia_id' => $materia->id,
            'cent_sede_id' => $sede->id,
            'docente_id' => $docente->id,
            'year_cycle' => now()->year,
            'schedule' => 'Lunes 18:00',
        ]);

        Inscripcion::create([
            'alumno_id' => $alumno->id,
            'comision_id' => $comision->id,
            'status' => 'aprobada',
        ]);

        return [$alumno, $otroAlumno, $docente, $directivo, $comision];
    }
}
