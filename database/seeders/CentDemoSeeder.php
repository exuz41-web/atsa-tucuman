<?php

namespace Database\Seeders;

use App\Models\Carrera;
use App\Models\CentSede;
use App\Models\Comision;
use App\Models\Inscripcion;
use App\Models\Materia;
use App\Models\MatriculaCent;
use App\Models\Nota;
use App\Models\PreinscripcionCent;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CentDemoSeeder extends Seeder
{
    public function run(): void
    {
        $password = 'Cent1234!';
        $year = now()->year;

        $sedeCapital = $this->sedeCapital();
        $sedeConcepcion = $this->sedeConcepcion();
        $carrera = $this->carreraEnfermeria();
        $materias = $this->materiasDemo($carrera);

        $directivo = $this->usuario(
            email: 'centadmin@atsa.com',
            name: 'Admin CENT Demo',
            dni: '99000001',
            phone: '3810000001',
            role: 'admin',
            centRole: 'directivo',
            password: $password,
        );

        $docente = $this->usuario(
            email: 'docente.cent@atsa.com',
            name: 'Docente CENT Demo',
            dni: '99000002',
            phone: '3810000002',
            role: 'docente',
            centRole: 'docente',
            password: $password,
        );

        $alumno = $this->usuario(
            email: 'alumno.cent@atsa.com',
            name: 'Alumno CENT Demo',
            dni: '99000003',
            phone: '3810000003',
            role: 'alumno',
            centRole: 'alumno',
            password: $password,
        );

        $alumna = $this->usuario(
            email: 'alumna.cent@atsa.com',
            name: 'María Alumna Demo',
            dni: '99000004',
            phone: '3810000004',
            role: 'alumno',
            centRole: 'alumno',
            password: $password,
        );

        $this->matricular($alumno, $carrera, $sedeCapital, 'CENT'.$year.'-00001', $year);
        $this->matricular($alumna, $carrera, $sedeConcepcion, 'CENT'.$year.'-00002', $year);

        $comisiones = [
            $this->comision($materias['basica'], $sedeCapital, $docente, 'Lunes y miércoles 18:00 a 21:00', $year),
            $this->comision($materias['comunitaria'], $sedeCapital, $docente, 'Martes y jueves 18:00 a 21:00', $year),
            $this->comision($materias['bioseguridad'], $sedeConcepcion, $docente, 'Viernes 17:00 a 21:00', $year),
        ];

        foreach ($comisiones as $comision) {
            $this->inscribir($alumno, $comision);
            $this->inscribir($alumna, $comision);
        }

        $this->nota($alumno, $comisiones[0], 'parcial1', 8.50, 'aprobado', $docente);
        $this->nota($alumno, $comisiones[0], 'parcial2', 9.00, 'aprobado', $docente);
        $this->nota($alumno, $comisiones[1], 'parcial1', 7.50, 'aprobado', $docente);
        $this->nota($alumna, $comisiones[0], 'parcial1', 6.50, 'aprobado', $docente);
        $this->nota($alumna, $comisiones[1], 'parcial1', 5.00, 'desaprobado', $docente);

        $this->preinscripcionesDemo($carrera, $sedeCapital, $sedeConcepcion, $directivo, $year);

        $this->command?->info('Demo CENT creado correctamente. Password para todos: '.$password);
        $this->command?->line('Admin/directivo: centadmin@atsa.com');
        $this->command?->line('Docente: docente.cent@atsa.com');
        $this->command?->line('Alumno: alumno.cent@atsa.com / DNI 99000003 / Legajo CENT'.$year.'-00001');
        $this->command?->line('Alumna: alumna.cent@atsa.com / DNI 99000004 / Legajo CENT'.$year.'-00002');
    }

    private function sedeCapital(): CentSede
    {
        return CentSede::updateOrCreate(
            ['slug' => 'cent-n74-capital'],
            [
                'nombre' => 'CENT N°74 Capital',
                'ciudad' => 'San Miguel de Tucumán',
                'direccion' => 'Ciudad Deportiva ATSA, Paraguay y Thames',
                'telefono' => '0381 4332175',
                'horarios' => 'Lunes a viernes de 8:00 a 16:00 hs',
                'activa' => true,
                'orden' => 1,
            ]
        );
    }

    private function sedeConcepcion(): CentSede
    {
        return CentSede::updateOrCreate(
            ['slug' => 'cent-n74-concepcion'],
            [
                'nombre' => 'CENT N°74 Concepción',
                'ciudad' => 'Concepción',
                'direccion' => 'Julio Argentino Roca 371',
                'horarios' => 'Consultar en sede',
                'activa' => true,
                'orden' => 5,
            ]
        );
    }

    private function carreraEnfermeria(): Carrera
    {
        return Carrera::updateOrCreate(
            ['slug' => 'enfermeria-profesional'],
            [
                'name' => 'Enfermería Profesional',
                'duration' => '3 años',
                'title_granted' => 'Enfermero/a Profesional',
                'description' => 'Carrera terciaria orientada a la formación profesional en ciencias de la salud, con prácticas profesionalizantes y acompañamiento académico del CENT N°74.',
                'requirements' => "Título secundario\nCertificado de residencia\nCertificado de aptitud psicofísica\nDNI\nActa de nacimiento\nFicha de inscripción\n2 fotos 4x4\nCarnet de vacuna Hepatitis B",
                'active' => true,
            ]
        );
    }

    /**
     * @return array<string, Materia>
     */
    private function materiasDemo(Carrera $carrera): array
    {
        return [
            'basica' => Materia::updateOrCreate(
                ['carrera_id' => $carrera->id, 'name' => 'Enfermería Básica'],
                ['year' => 1, 'semester' => 1, 'hours' => 96, 'correlatives' => []]
            ),
            'comunitaria' => Materia::updateOrCreate(
                ['carrera_id' => $carrera->id, 'name' => 'Enfermería Comunitaria'],
                ['year' => 1, 'semester' => 2, 'hours' => 96, 'correlatives' => ['Enfermería Básica']]
            ),
            'bioseguridad' => Materia::updateOrCreate(
                ['carrera_id' => $carrera->id, 'name' => 'Higiene y Bioseguridad'],
                ['year' => 1, 'semester' => 2, 'hours' => 64, 'correlatives' => []]
            ),
        ];
    }

    private function usuario(string $email, string $name, string $dni, string $phone, string $role, string $centRole, string $password): User
    {
        return User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'dni' => $dni,
                'phone' => $phone,
                'password' => Hash::make($password),
                'role' => $role,
                'cent_role' => $centRole,
                'active' => true,
            ]
        );
    }

    private function matricular(User $alumno, Carrera $carrera, CentSede $sede, string $legajo, int $year): MatriculaCent
    {
        return MatriculaCent::updateOrCreate(
            [
                'user_id' => $alumno->id,
                'carrera_id' => $carrera->id,
                'ciclo_lectivo' => $year,
            ],
            [
                'cent_sede_id' => $sede->id,
                'legajo' => $legajo,
                'estado' => 'cursando',
                'fecha_ingreso' => now()->startOfYear(),
                'observaciones' => 'Matrícula demo creada para pruebas del portal CENT.',
            ]
        );
    }

    private function comision(Materia $materia, CentSede $sede, User $docente, string $schedule, int $year): Comision
    {
        return Comision::updateOrCreate(
            [
                'materia_id' => $materia->id,
                'cent_sede_id' => $sede->id,
                'docente_id' => $docente->id,
                'year_cycle' => $year,
            ],
            [
                'filial_id' => null,
                'schedule' => $schedule,
                'acta_estado' => 'abierta',
            ]
        );
    }

    private function inscribir(User $alumno, Comision $comision): void
    {
        Inscripcion::updateOrCreate(
            [
                'alumno_id' => $alumno->id,
                'comision_id' => $comision->id,
            ],
            [
                'status' => 'aprobada',
            ]
        );
    }

    private function nota(User $alumno, Comision $comision, string $type, float $grade, string $status, User $docente): void
    {
        Nota::updateOrCreate(
            [
                'alumno_id' => $alumno->id,
                'comision_id' => $comision->id,
                'type' => $type,
            ],
            [
                'grade' => $grade,
                'status' => $status,
                'loaded_by' => $docente->id,
            ]
        );
    }

    private function preinscripcionesDemo(Carrera $carrera, CentSede $capital, CentSede $concepcion, User $directivo, int $year): void
    {
        $items = [
            [
                'codigo' => 'CENT-'.$year.'-DEMO-001',
                'sede' => $capital,
                'apellido_nombre' => 'Aspirante Capital Demo',
                'dni' => '99100001',
                'email' => 'aspirante.capital@example.com',
                'telefono' => '3811110001',
                'estado' => 'pendiente',
                'observaciones_admin' => null,
            ],
            [
                'codigo' => 'CENT-'.$year.'-DEMO-002',
                'sede' => $concepcion,
                'apellido_nombre' => 'Aspirante Concepción Demo',
                'dni' => '99100002',
                'email' => 'aspirante.concepcion@example.com',
                'telefono' => '3811110002',
                'estado' => 'aprobada',
                'observaciones_admin' => 'Documentación revisada para demo.',
            ],
        ];

        foreach ($items as $item) {
            PreinscripcionCent::updateOrCreate(
                ['codigo' => $item['codigo']],
                [
                    'carrera_id' => $carrera->id,
                    'cent_sede_id' => $item['sede']->id,
                    'ciclo_lectivo' => $year,
                    'apellido_nombre' => $item['apellido_nombre'],
                    'fecha_nacimiento' => now()->subYears(22)->toDateString(),
                    'nacionalidad' => 'Argentina',
                    'estado_civil' => 'Soltero/a',
                    'tipo_documento' => 'DNI',
                    'dni' => $item['dni'],
                    'email' => $item['email'],
                    'telefono' => $item['telefono'],
                    'domicilio' => 'Domicilio demo',
                    'localidad' => $item['sede']->ciudad,
                    'nivel_estudios' => 'Secundario completo',
                    'titulo_secundario' => 'En trámite',
                    'observaciones_alumno' => 'Preinscripción demo para validar el circuito.',
                    'estado' => $item['estado'],
                    'observaciones_admin' => $item['observaciones_admin'],
                    'aprobado_por' => $item['estado'] === 'aprobada' ? $directivo->id : null,
                    'aprobado_at' => $item['estado'] === 'aprobada' ? now() : null,
                ]
            );
        }
    }
}
