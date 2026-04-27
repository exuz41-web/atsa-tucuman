<?php

namespace Database\Seeders;

use App\Models\AsistenciaCent;
use App\Models\Carrera;
use App\Models\CentClase;
use App\Models\CentCuota;
use App\Models\CentDescarga;
use App\Models\CentEvento;
use App\Models\CentHorario;
use App\Models\CentLegajoDocumento;
use App\Models\CentMaterial;
use App\Models\CentPermisoExamen;
use App\Models\CentSede;
use App\Models\CentTrabajoPractico;
use App\Models\Comision;
use App\Models\Inscripcion;
use App\Models\MatriculaCent;
use App\Models\Materia;
use App\Models\MesaExamenCent;
use App\Models\Nota;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CentPremiumDemoSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('Cent1234!');

        $sedes = $this->sedes();
        $carreras = Carrera::where('active', true)->get()->keyBy('slug');

        $this->sincronizarSedesCarreras($carreras, $sedes);

        $afiliados = collect(range(1, 5))->map(function ($i) use ($password) {
            return User::updateOrCreate(
                ['email' => "afiliado.demo{$i}@atsa.com"],
                [
                    'name' => ['Marcela Gómez', 'Carlos Medina', 'Lucía Fernández', 'Jorge Salvatierra', 'Ana Robles'][$i - 1],
                    'password' => $password,
                    'role' => 'afiliado',
                    'cent_role' => null,
                    'dni' => '30'.str_pad((string) (100000 + $i), 6, '0', STR_PAD_LEFT),
                    'phone' => '38150000'.$i,
                    'numero_afiliado' => 'ATSA2026-'.str_pad((string) $i, 5, '0', STR_PAD_LEFT),
                    'active' => true,
                ]
            );
        });

        $docentes = collect(range(1, 5))->map(function ($i) use ($password, $sedes) {
            return User::updateOrCreate(
                ['email' => "docente.cent{$i}@atsa.com"],
                [
                    'name' => ['Prof. Laura Romano', 'Prof. Diego Ledesma', 'Prof. Patricia Soria', 'Prof. Martín Alderete', 'Prof. Claudia Pérez'][$i - 1],
                    'password' => $password,
                    'role' => 'docente',
                    'cent_role' => 'docente',
                    'dni' => '28'.str_pad((string) (300000 + $i), 6, '0', STR_PAD_LEFT),
                    'phone' => '38151000'.$i,
                    'cent_sede_id' => $sedes->values()->get(($i - 1) % max($sedes->count(), 1))?->id,
                    'active' => true,
                ]
            );
        });

        foreach (range(1, 2) as $i) {
            User::updateOrCreate(
                ['email' => "directivo.cent{$i}@atsa.com"],
                [
                    'name' => ['Directora Académica CENT', 'Coordinador Sede Sur'][$i - 1],
                    'password' => $password,
                    'role' => 'directivo',
                    'cent_role' => $i === 1 ? 'directivo' : 'coordinador',
                    'dni' => '24'.str_pad((string) (500000 + $i), 6, '0', STR_PAD_LEFT),
                    'cent_sede_id' => $i === 1 ? null : $sedes->firstWhere('slug', 'cent-n74-concepcion')?->id,
                    'active' => true,
                ]
            );
        }

        $alumnos = collect([
            ['name' => 'Sofía Molina', 'email' => 'alumno.demo1@cent.com', 'carrera' => 'enfermeria-profesional', 'estado' => 'cursando', 'year' => 2, 'afiliado' => $afiliados[0]],
            ['name' => 'Nicolás Paz', 'email' => 'alumno.demo2@cent.com', 'carrera' => 'tec-sup-en-farmacia', 'estado' => 'inscripto', 'year' => 1, 'afiliado' => null],
            ['name' => 'Camila Núñez', 'email' => 'alumno.demo3@cent.com', 'carrera' => 'tec-sup-en-laboratorio-de-analisis-clinicos', 'estado' => 'cursando', 'year' => 2, 'afiliado' => $afiliados[1]],
            ['name' => 'Matías Herrera', 'email' => 'alumno.demo4@cent.com', 'carrera' => 'tec-sup-en-diagnostico-por-imagenes', 'estado' => 'regular', 'year' => 3, 'afiliado' => null],
            ['name' => 'Valentina Díaz', 'email' => 'alumno.demo5@cent.com', 'carrera' => 'tec-sup-en-agente-socio-sanitario', 'estado' => 'cursando', 'year' => 3, 'afiliado' => $afiliados[2]],
        ])->map(function ($row, $index) use ($password, $sedes, $carreras, $docentes) {
            $carrera = $carreras[$row['carrera']] ? $carreras->first();
            $sede = $carrera?->centSedes()->first() ? $sedes->first();
            $alumno = User::updateOrCreate(
                ['email' => $row['email']],
                [
                    'name' => $row['name'],
                    'password' => $password,
                    'role' => 'alumno',
                    'cent_role' => 'alumno',
                    'dni' => '40'.str_pad((string) (700000 + $index), 6, '0', STR_PAD_LEFT),
                    'phone' => '38152000'.$index,
                    'cent_sede_id' => $sede?->id,
                    'active' => true,
                    'address' => 'San Miguel de Tucumán',
                ]
            );

            $matricula = MatriculaCent::updateOrCreate(
                ['user_id' => $alumno->id, 'carrera_id' => $carrera?->id, 'ciclo_lectivo' => 2026],
                [
                    'cent_sede_id' => $sede?->id,
                    'legajo' => 'CENT-2026-'.str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT),
                    'estado' => $row['estado'],
                    'fecha_ingreso' => now()->subMonths(10 - $index),
                    'regularidad_vencimiento' => now()->addYear(),
                ]
            );

            $materias = Materia::where('carrera_id', $carrera?->id)->where('year', '<=', $row['year'])->take(4)->get();

            foreach ($materias as $mIndex => $materia) {
                $comision = Comision::firstOrCreate(
                    ['materia_id' => $materia->id, 'cent_sede_id' => $sede?->id, 'year_cycle' => 2026],
                    [
                        'docente_id' => $docentes->values()->get($mIndex % $docentes->count())->id,
                        'filial_id' => null,
                        'schedule' => 'Lunes y miércoles 18:00 a 21:00',
                    ]
                );

                Inscripcion::updateOrCreate(
                    ['alumno_id' => $alumno->id, 'comision_id' => $comision->id],
                    ['status' => 'aprobada']
                );

                if ($mIndex < 2 && $row['year'] > 1) {
                    Nota::updateOrCreate(
                        ['alumno_id' => $alumno->id, 'comision_id' => $comision->id, 'type' => 'final'],
                        ['grade' => 7 + ($mIndex % 3), 'status' => 'aprobado', 'loaded_by' => $comision->docente_id]
                    );
                }

                AsistenciaCent::updateOrCreate(
                    ['alumno_id' => $alumno->id, 'comision_id' => $comision->id, 'fecha' => now()->subDays($mIndex + 1)->toDateString()],
                    ['estado' => $mIndex === 3 ? 'tarde' : 'presente', 'cargado_por' => $comision->docente_id]
                );

                $clase = CentClase::firstOrCreate(
                    ['comision_id' => $comision->id, 'titulo' => 'Clase introductoria - '.$materia->name],
                    [
                        'descripcion' => 'Presentación de contenidos, criterios de evaluación y bibliografía.',
                        'fecha_inicio' => now()->addDays($mIndex + 1)->setTime(18, 0),
                        'fecha_fin' => now()->addDays($mIndex + 1)->setTime(21, 0),
                        'modalidad' => 'presencial',
                        'aula' => 'Aula '.($mIndex + 1),
                        'publicada' => true,
                        'creado_por' => $comision->docente_id,
                    ]
                );

                CentMaterial::firstOrCreate(
                    ['comision_id' => $comision->id, 'titulo' => 'Apunte base - '.$materia->name],
                    ['clase_id' => $clase->id, 'descripcion' => 'Material inicial de lectura.', 'tipo' => 'apunte', 'publicado' => true, 'creado_por' => $comision->docente_id]
                );

                $tp = CentTrabajoPractico::firstOrCreate(
                    ['comision_id' => $comision->id, 'titulo' => 'TP N°1 - '.$materia->name],
                    [
                        'consigna' => 'Resolver la guía de actividades y adjuntar el desarrollo en PDF.',
                        'fecha_publicacion' => now(),
                        'fecha_entrega' => now()->addDays(10),
                        'puntaje_maximo' => 10,
                        'acepta_entregas' => true,
                        'publicado' => true,
                        'creado_por' => $comision->docente_id,
                    ]
                );

                if ($index === 0 && $mIndex === 0) {
                    \App\Models\CentEntregaTrabajo::updateOrCreate(
                        ['trabajo_practico_id' => $tp->id, 'alumno_id' => $alumno->id],
                        ['comentario' => 'Entrega demo del alumno.', 'estado' => 'aprobado', 'calificacion' => 8, 'entregado_at' => now()->subDays(2), 'corregido_at' => now()->subDay(), 'corregido_por' => $comision->docente_id]
                    );
                }
            }

            CentLegajoDocumento::updateOrCreate(
                ['user_id' => $alumno->id, 'tipo' => 'dni'],
                ['estado' => $index < 3 ? 'aprobado' : 'pendiente', 'observaciones' => 'Documento demo', 'validado_at' => $index < 3 ? now() : null]
            );

            $cuota = CentCuota::updateOrCreate(
                ['alumno_id' => $alumno->id, 'concepto' => 'Cuota mensual', 'periodo' => 'Abril 2026'],
                [
                    'matricula_cent_id' => $matricula->id,
                    'monto' => 25000,
                    'descuento_tipo' => $row['afiliado'] ? 'hijo_afiliado_atsa' : 'ninguno',
                    'descuento_porcentaje' => $row['afiliado'] ? 20 : 0,
                    'afiliado_descuento_id' => $row['afiliado']?->id,
                    'vencimiento' => $index % 2 === 0 ? now()->subDays(3) : now()->addDays(10),
                    'estado' => $index % 2 === 0 ? 'vencida' : 'pendiente',
                    'creado_por' => 1,
                ]
            );

            if ($index >= 2) {
                $mesaMateria = $materias->last();
                if ($mesaMateria) {
                    $mesa = MesaExamenCent::firstOrCreate(
                        ['materia_id' => $mesaMateria->id, 'cent_sede_id' => $sede?->id, 'fecha' => now()->addDays(25)->toDateString()],
                        ['docente_id' => $docentes->first()->id, 'hora' => '18:00', 'turno' => 'Julio', 'aula' => 'Aula magna', 'cupo' => 40, 'estado' => 'abierta', 'creada_por' => 1]
                    );

                    \App\Models\InscripcionMesaCent::updateOrCreate(
                        ['alumno_id' => $alumno->id, 'mesa_examen_cent_id' => $mesa->id],
                        ['estado' => 'inscripto']
                    );

                    CentPermisoExamen::updateOrCreate(
                        ['alumno_id' => $alumno->id, 'mesa_examen_cent_id' => $mesa->id],
                        ['cent_cuota_id' => $cuota->id, 'estado' => $index === 4 ? 'habilitado' : 'pendiente_pago', 'monto' => 0, 'qr_token' => (string) Str::uuid(), 'habilitado_at' => $index === 4 ? now() : null]
                    );
                }
            }

            return $alumno;
        });

        CentEvento::firstOrCreate(
            ['titulo' => 'Inicio de cursado 2026'],
            ['tipo' => 'evento', 'fecha_inicio' => now()->addDays(7), 'descripcion' => 'Inicio formal de actividades académicas.', 'rol_destino' => 'todos', 'activo' => true]
        );
        $this->seedHorariosYDescargas($carreras, $sedes);
    }

    private function seedHorariosYDescargas($carreras, $sedes): void
    {
        $capital = $sedes['cent-n74-capital'] ? CentSede::where('slug', 'cent-n74-capital')->first();
        $concepcion = $sedes['cent-n74-concepcion'] ? CentSede::where('slug', 'cent-n74-concepcion')->first();
        $enfermeria = $carreras['enfermeria-profesional'] ? Carrera::where('slug', 'enfermeria-profesional')->first();
        $farmacia = $carreras['tec-sup-en-farmacia'] ? Carrera::where('slug', 'tec-sup-en-farmacia')->first();

        CentHorario::updateOrCreate(
            ['titulo' => 'Primer año - Enfermería Profesional - Turno tarde'],
            [
                'cent_sede_id' => $capital?->id,
                'carrera_id' => $enfermeria?->id,
                'ciclo_lectivo' => '2026',
                'descripcion' => 'Cursado presencial de lunes a jueves de 18:00 a 22:00. Los horarios pueden ajustarse por prácticas profesionalizantes.',
                'activo' => true,
                'orden' => 1,
            ]
        );

        CentHorario::updateOrCreate(
            ['titulo' => 'Segundo año - Farmacia - Turno tarde'],
            [
                'cent_sede_id' => $capital?->id,
                'carrera_id' => $farmacia?->id,
                'ciclo_lectivo' => '2026',
                'descripcion' => 'Cursado presencial con materias teóricas y espacios de práctica en laboratorio institucional.',
                'activo' => true,
                'orden' => 2,
            ]
        );

        CentHorario::updateOrCreate(
            ['titulo' => 'Tercer año - Enfermería Profesional - Sede Concepción'],
            [
                'cent_sede_id' => $concepcion?->id,
                'carrera_id' => $enfermeria?->id,
                'ciclo_lectivo' => '2026',
                'descripcion' => 'Organización de cursado y prácticas profesionalizantes sujeta a coordinación de sede.',
                'activo' => true,
                'orden' => 3,
            ]
        );

        foreach ([
            [
                'titulo' => 'Ficha de inscripción aspirantes 2026',
                'categoria' => 'inscripciones',
                'descripcion' => 'Formulario institucional para completar y presentar en la sede elegida.',
                'url_externa' => 'https://cent74atsatucuman.ar/FICHA%20DE%20INSCRIPCION%20ASPIRANTES%202026.pdf',
                'orden' => 1,
            ],
            [
                'titulo' => 'Requisitos generales de ingreso',
                'categoria' => 'formularios',
                'descripcion' => 'Listado de documentación requerida para iniciar el proceso de inscripción.',
                'orden' => 2,
            ],
            [
                'titulo' => 'Plan de estudio - Enfermería Profesional',
                'categoria' => 'planes_estudio',
                'descripcion' => 'Resumen académico por año de cursado y correlatividades principales.',
                'carrera_id' => $enfermeria?->id,
                'orden' => 3,
            ],
            [
                'titulo' => 'Reglamento académico del alumno',
                'categoria' => 'reglamentos',
                'descripcion' => 'Normas generales de cursado, asistencia, evaluación y regularidad.',
                'orden' => 4,
            ],
        ] as $row) {
            CentDescarga::updateOrCreate(
                ['titulo' => $row['titulo']],
                $row + ['activo' => true]
            );
        }
    }

    private function sedes()
    {
        $data = [
            ['nombre' => 'CENT N°74 Capital', 'slug' => 'cent-n74-capital', 'ciudad' => 'San Miguel de Tucumán', 'direccion' => 'Predio ATSA, Ecuador y Thames'],
            ['nombre' => 'CENT N°74 Concepción', 'slug' => 'cent-n74-concepcion', 'ciudad' => 'Concepción', 'direccion' => 'Julio Argentino Roca 371'],
            ['nombre' => 'CENT N°74 Banda del Río Salí', 'slug' => 'cent-n74-banda-del-rio-sali', 'ciudad' => 'Banda del Río Salí', 'direccion' => 'Camino del Carmen 90'],
            ['nombre' => 'CENT N°74 Famaillá', 'slug' => 'cent-n74-famailla', 'ciudad' => 'Famaillá', 'direccion' => 'Pje. Tiburcio Padilla 600'],
            ['nombre' => 'CENT N°74 Tafí Viejo', 'slug' => 'cent-n74-tafi-viejo', 'ciudad' => 'Tafí Viejo', 'direccion' => 'Perón 344'],
            ['nombre' => 'CENT N°74 Lules', 'slug' => 'cent-n74-lules', 'ciudad' => 'Lules', 'direccion' => 'Pje. Miguel Lillo 50'],
            ['nombre' => 'CENT N°74 Aguilares', 'slug' => 'cent-n74-aguilares', 'ciudad' => 'Aguilares', 'direccion' => 'Av. Savio Pacheco'],
        ];

        foreach ($data as $index => $row) {
            CentSede::updateOrCreate(['slug' => $row['slug']], $row + ['activa' => true, 'orden' => $index + 1]);
        }

        return CentSede::whereIn('slug', collect($data)->pluck('slug'))->get()->keyBy('slug');
    }

    private function sincronizarSedesCarreras($carreras, $sedes): void
    {
        $map = [
            'enfermeria-profesional' => ['cent-n74-capital', 'cent-n74-concepcion', 'cent-n74-banda-del-rio-sali', 'cent-n74-famailla', 'cent-n74-tafi-viejo'],
            'tec-sup-en-agente-socio-sanitario' => ['cent-n74-capital', 'cent-n74-concepcion', 'cent-n74-lules'],
            'tec-sup-en-diagnostico-por-imagenes' => ['cent-n74-capital', 'cent-n74-aguilares', 'cent-n74-lules'],
            'tec-sup-en-farmacia' => ['cent-n74-capital', 'cent-n74-tafi-viejo'],
            'tec-sup-en-laboratorio-de-analisis-clinicos' => ['cent-n74-capital', 'cent-n74-famailla', 'cent-n74-aguilares'],
            'tec-sup-en-esterilizacion' => ['cent-n74-capital', 'cent-n74-concepcion'],
        ];

        foreach ($map as $slug => $sedeSlugs) {
            $carrera = $carreras[$slug] ? null;
            if (! $carrera) {
                continue;
            }

            $carrera->centSedes()->syncWithoutDetaching(
                collect($sedeSlugs)->map(fn ($sedeSlug) => $sedes[$sedeSlug]->id ? null)->filter()->all()
            );
        }
    }
}
