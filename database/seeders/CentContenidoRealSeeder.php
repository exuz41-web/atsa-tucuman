<?php

namespace Database\Seeders;

use App\Models\Carrera;
use App\Models\CentSede;
use App\Models\Comision;
use App\Models\Materia;
use App\Models\MatriculaCent;
use App\Models\PreinscripcionCent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CentContenidoRealSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $capital = CentSede::firstOrCreate(
                ['slug' => 'cent-n74-capital'],
                ['nombre' => 'CENT N°74 Capital', 'ciudad' => 'San Miguel de Tucumán']
            );

            $duplicate = CentSede::where('slug', 'cent-nro-74-capital')
                ->whereKeyNot($capital->id)
                ->first();

            if ($duplicate) {
                MatriculaCent::where('cent_sede_id', $duplicate->id)->update(['cent_sede_id' => $capital->id]);
                Comision::where('cent_sede_id', $duplicate->id)->update(['cent_sede_id' => $capital->id]);
                PreinscripcionCent::where('cent_sede_id', $duplicate->id)->update(['cent_sede_id' => $capital->id]);
                $duplicate->delete();
            }

            foreach ($this->sedes() as $sede) {
                CentSede::updateOrCreate(
                    ['slug' => $sede['slug']],
                    $sede
                );
            }

            $this->mergeCarreraDuplicates();

            foreach ($this->carreras() as $carrera) {
                Carrera::updateOrCreate(
                    ['slug' => $carrera['slug']],
                    $carrera
                );
            }

            $this->seedEnfermeriaPlan();
            $this->seedAgenteSocioSanitarioPlan();
            $this->seedDiagnosticoImagenesPlan();
            $this->seedFarmaciaPlan();
            $this->seedLaboratorioPlan();
            $this->seedEsterilizacionPlan();
        });
    }

    private function mergeCarreraDuplicates(): void
    {
        $pairs = [
            'agente-socio-sanitario' => 'tec-sup-en-agente-socio-sanitario',
            'diagnostico-por-imagenes' => 'tec-sup-en-diagnostico-por-imagenes',
            'farmacia' => 'tec-sup-en-farmacia',
            'laboratorio-de-analisis-clinicos' => 'tec-sup-en-laboratorio-de-analisis-clinicos',
            'esterilizacion' => 'tec-sup-en-esterilizacion',
        ];

        foreach ($pairs as $duplicateSlug => $targetSlug) {
            $duplicate = Carrera::where('slug', $duplicateSlug)->first();
            $target = Carrera::where('slug', $targetSlug)->first();

            if (! $duplicate || ! $target || $duplicate->is($target)) {
                continue;
            }

            DB::table('materias')->where('carrera_id', $duplicate->id)->update(['carrera_id' => $target->id]);
            DB::table('matriculas_cent')->where('carrera_id', $duplicate->id)->update(['carrera_id' => $target->id]);
            DB::table('preinscripciones_cent')->where('carrera_id', $duplicate->id)->update(['carrera_id' => $target->id]);
            $duplicate->delete();
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function sedes(): array
    {
        return [
            ['orden' => 1, 'slug' => 'cent-n74-capital', 'nombre' => 'CENT N°74 Capital', 'ciudad' => 'San Miguel de Tucumán', 'direccion' => 'Ciudad Deportiva ATSA, Paraguay y Thames', 'telefono' => '0381 4332175', 'whatsapp' => null, 'email' => null, 'horarios' => 'Consultar en sede', 'responsable' => null, 'imagen' => 'images/filiales/central-ciudad-deportiva.jpg', 'activa' => true],
            ['orden' => 2, 'slug' => 'cent-n74-trancas', 'nombre' => 'CENT N°74 Trancas', 'ciudad' => 'Trancas', 'direccion' => 'Hipólito Yrigoyen 161', 'telefono' => null, 'whatsapp' => null, 'email' => null, 'horarios' => 'Consultar en sede', 'responsable' => null, 'imagen' => null, 'activa' => true],
            ['orden' => 3, 'slug' => 'cent-n74-delfin-gallo', 'nombre' => 'CENT N°74 Delfín Gallo', 'ciudad' => 'Delfín Gallo', 'direccion' => 'Calle principal S/N', 'telefono' => null, 'whatsapp' => null, 'email' => null, 'horarios' => 'Consultar en sede', 'responsable' => null, 'imagen' => null, 'activa' => true],
            ['orden' => 4, 'slug' => 'cent-n74-banda-del-rio-sali', 'nombre' => 'CENT N°74 Banda del Río Salí', 'ciudad' => 'Banda del Río Salí', 'direccion' => 'Camino del Carmen 90', 'telefono' => null, 'whatsapp' => null, 'email' => null, 'horarios' => 'Consultar en sede', 'responsable' => null, 'imagen' => 'images/filiales/filial-este-banda.jpg', 'activa' => true],
            ['orden' => 5, 'slug' => 'cent-n74-concepcion', 'nombre' => 'CENT N°74 Concepción', 'ciudad' => 'Concepción', 'direccion' => 'Julio Argentino Roca 371', 'telefono' => null, 'whatsapp' => null, 'email' => null, 'horarios' => 'Consultar en sede', 'responsable' => null, 'imagen' => 'images/filiales/filial-sur-concepcion.jpg', 'activa' => true],
            ['orden' => 6, 'slug' => 'cent-n74-los-ralos', 'nombre' => 'CENT N°74 Los Ralos', 'ciudad' => 'Los Ralos', 'direccion' => 'Av. San Martín S/N', 'telefono' => null, 'whatsapp' => null, 'email' => null, 'horarios' => 'Consultar en sede', 'responsable' => null, 'imagen' => null, 'activa' => true],
            ['orden' => 7, 'slug' => 'cent-n74-simoca', 'nombre' => 'CENT N°74 Simoca', 'ciudad' => 'Simoca', 'direccion' => 'Belgrano S/N', 'telefono' => null, 'whatsapp' => null, 'email' => null, 'horarios' => 'Consultar en sede', 'responsable' => null, 'imagen' => null, 'activa' => true],
            ['orden' => 8, 'slug' => 'cent-n74-santa-rosa-de-leales', 'nombre' => 'CENT N°74 Santa Rosa de Leales', 'ciudad' => 'Santa Rosa de Leales', 'direccion' => 'J. L. Nougues 200', 'telefono' => null, 'whatsapp' => null, 'email' => null, 'horarios' => 'Consultar en sede', 'responsable' => null, 'imagen' => null, 'activa' => true],
            ['orden' => 9, 'slug' => 'cent-n74-tafi-viejo', 'nombre' => 'CENT N°74 Tafí Viejo', 'ciudad' => 'Tafí Viejo', 'direccion' => 'Perón 344', 'telefono' => null, 'whatsapp' => null, 'email' => null, 'horarios' => 'Consultar en sede', 'responsable' => null, 'imagen' => null, 'activa' => true],
            ['orden' => 10, 'slug' => 'cent-n74-lules', 'nombre' => 'CENT N°74 Lules', 'ciudad' => 'Lules', 'direccion' => 'Pje. Miguel Lillo 50', 'telefono' => null, 'whatsapp' => null, 'email' => null, 'horarios' => 'Consultar en sede', 'responsable' => null, 'imagen' => null, 'activa' => true],
            ['orden' => 11, 'slug' => 'cent-n74-graneros', 'nombre' => 'CENT N°74 Graneros', 'ciudad' => 'Graneros', 'direccion' => '25 de Mayo y Lavalle', 'telefono' => null, 'whatsapp' => null, 'email' => null, 'horarios' => 'Consultar en sede', 'responsable' => null, 'imagen' => null, 'activa' => true],
            ['orden' => 12, 'slug' => 'cent-n74-aguilares', 'nombre' => 'CENT N°74 Aguilares', 'ciudad' => 'Aguilares', 'direccion' => 'Av. Savio y Rvda. Pacheco', 'telefono' => null, 'whatsapp' => null, 'email' => null, 'horarios' => 'Consultar en sede', 'responsable' => null, 'imagen' => null, 'activa' => true],
            ['orden' => 13, 'slug' => 'cent-n74-la-ramada', 'nombre' => 'CENT N°74 La Ramada', 'ciudad' => 'La Ramada', 'direccion' => 'Av. Arturo Figueroa S/N', 'telefono' => null, 'whatsapp' => null, 'email' => null, 'horarios' => 'Consultar en sede', 'responsable' => null, 'imagen' => null, 'activa' => true],
            ['orden' => 14, 'slug' => 'cent-n74-amaicha-del-valle', 'nombre' => 'CENT N°74 Amaicha del Valle', 'ciudad' => 'Amaicha del Valle', 'direccion' => 'Ruta Provincial 337', 'telefono' => null, 'whatsapp' => null, 'email' => null, 'horarios' => 'Consultar en sede', 'responsable' => null, 'imagen' => null, 'activa' => true],
            ['orden' => 15, 'slug' => 'cent-n74-famailla', 'nombre' => 'CENT N°74 Famaillá', 'ciudad' => 'Famaillá', 'direccion' => 'Pje. Tiburcio Padilla 600', 'telefono' => null, 'whatsapp' => null, 'email' => null, 'horarios' => 'Consultar en sede', 'responsable' => null, 'imagen' => null, 'activa' => true],
            ['orden' => 16, 'slug' => 'cent-n74-monteros', 'nombre' => 'CENT N°74 Monteros', 'ciudad' => 'Monteros', 'direccion' => 'San Martín 145', 'telefono' => null, 'whatsapp' => null, 'email' => null, 'horarios' => 'Consultar en sede', 'responsable' => null, 'imagen' => null, 'activa' => true],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function carreras(): array
    {
        $requisitos = implode("\n", [
            'Curso propedéutico de nivelación obligatorio.',
            '80% de asistencia al curso de nivelación.',
            'Aprobación de cada área con nota mínima 6.',
            'Título secundario, constancia o documentación equivalente.',
            'DNI, ficha de inscripción, fotos 4x4, certificado de buena conducta, residencia, apto psicofísico y vacunas requeridas.',
        ]);

        return [
            ['name' => 'Enfermería Profesional', 'slug' => 'enfermeria-profesional', 'duration' => '3 años', 'title_granted' => 'Enfermero/a Profesional', 'description' => "Resolución 2322/5 (MEd.) 2015 y ampliatorias.\n\nModalidad: presencial. Duración: 3 años.\n\nPrácticas profesionalizantes: convenio con el SIPROSA.\n\nEl Enfermero Profesional es quien ha adquirido competencia científico-técnica para cuidar y ayudar a las personas sanas o enfermas: niño, adolescente, embarazada, adulto y adulto mayor, la familia y la comunidad en los tres niveles de atención.\n\nRealiza funciones asistenciales, administrativas, docentes e investigativas mediante una firme actitud humanística, ética, de responsabilidad legal y con conocimientos en las áreas biológicas, psicosociales y del entorno.\n\nEstá entrenado en las técnicas específicas del ejercicio de la profesión, sustentado en la lógica del método científico profesional de enfermería, acorde al desarrollo científico y tecnológico de las ciencias.", 'requirements' => "Título secundario\nCertificado de residencia\nCertificado de aptitud psicofísica\nDNI, fotocopia de 1° y 2° hoja\nActa de nacimiento\nFicha de inscripción\n2 fotos 4x4\nCarnet de vacuna Hepatitis B", 'active' => true],
            ['name' => 'Agente Socio Sanitario', 'slug' => 'tec-sup-en-agente-socio-sanitario', 'duration' => '3 años', 'title_granted' => 'Técnico/a Superior en Agente Socio Sanitario', 'description' => "Resolución 2355/5 (MEd.) 2015 y ampliatorias.\n\nModalidad: presencial. Duración: 3 años.\n\nPrácticas profesionalizantes: convenio con el SIPROSA.\n\nEl Agente Socio Sanitario es quien está en contacto directo con la comunidad, por lo tanto realiza tareas de prevención y promoción de la salud.\n\nPara ello utiliza la educación sanitaria y la consejería. Su rol es importante para mejorar la calidad de vida de las personas de la comunidad en la que se encuentra inmerso.\n\nLas actividades se realizan de acuerdo a lo requerido situacionalmente, llevando la impronta propia de cada agente sanitario y de las cualidades del caso en el que deba intervenir.", 'requirements' => "Título secundario\nCertificado de residencia\nCertificado de aptitud psicofísica\nDNI, fotocopia de 1° y 2° hoja\nActa de nacimiento\nFicha de inscripción\n2 fotos 4x4\nCarnet de vacuna Hepatitis B", 'active' => true],
            ['name' => 'Diagnóstico por Imágenes', 'slug' => 'tec-sup-en-diagnostico-por-imagenes', 'duration' => '3 años', 'title_granted' => 'Técnico/a Superior en Diagnóstico por Imágenes', 'description' => "Resolución 2341/5 (MEd.) 2015 y ampliatorias.\n\nModalidad: presencial. Duración: 3 años.\n\nPrácticas profesionalizantes: convenio con el SIPROSA.\n\nEl Técnico en Diagnóstico por Imágenes estará capacitado para realizar distintos exámenes según indicación médica, manejando equipos de mediana y alta complejidad en centros de atención públicos o privados.\n\nColabora en el diagnóstico médico acorde a su capacitación en cuanto al manejo de equipos y preparación del paciente.", 'requirements' => "Título secundario\nCertificado de residencia\nCertificado de aptitud psicofísica\nDNI, fotocopia de 1° y 2° hoja\nActa de nacimiento\nFicha de inscripción\n2 fotos 4x4\nCarnet de vacuna Hepatitis B", 'active' => true],
            ['name' => 'Farmacia', 'slug' => 'tec-sup-en-farmacia', 'duration' => '3 años', 'title_granted' => 'Técnico/a Superior en Farmacia', 'description' => "Resolución 2326/5 (MEd.) 2015 y ampliatorias.\n\nModalidad: presencial. Duración: 3 años.\n\nPrácticas profesionalizantes: convenio con el SIPROSA.\n\nLa carrera de Técnico Superior en Farmacia permite contar en el ámbito de la salud con técnicos altamente calificados y comprometidos en la promoción y el cuidado de la salud, para trabajar junto al profesional farmacéutico en áreas públicas y privadas del Sistema Provincial de Salud, asegurando la calidad de las actividades del cuidado de pacientes.\n\nForma profesionales técnicos con conocimientos y habilidades necesarias para asumir responsabilidades y actuar como miembros colaboradores del equipo de salud.\n\nÁmbitos de inserción laboral: hospitales, clínicas, sanatorios, laboratorios, centros de salud y áreas programáticas, empresas farmacéuticas, oficinas de farmacias privadas y públicas, droguerías y depósitos de insumo médico y odontológico.", 'requirements' => "Título secundario\nCertificado de residencia\nCertificado de aptitud psicofísica\nDNI, fotocopia de 1° y 2° hoja\nActa de nacimiento\nFicha de inscripción\n2 fotos 4x4\nCarnet de vacuna Hepatitis B", 'active' => true],
            ['name' => 'Laboratorio de Análisis Clínicos', 'slug' => 'tec-sup-en-laboratorio-de-analisis-clinicos', 'duration' => '3 años', 'title_granted' => 'Técnico/a Superior en Laboratorio de Análisis Clínicos', 'description' => "Resolución 496/5 (MEd.) 2014 y ampliatorias.\n\nModalidad: presencial. Duración: 3 años.\n\nPrácticas profesionalizantes: convenio con el SIPROSA.\n\nLa carrera de Técnico Superior en Laboratorio de Análisis Clínicos permite contar en el ámbito de salud con técnicos altamente calificados para realizar junto al bioquímico procesos técnicos específicos en el marco de las nuevas tecnologías, en permanente aprendizaje, capacitados para trabajar en redes y equipos interdisciplinarios y comprometidos con las necesidades de la población.\n\nEl Laboratorio de Análisis Clínicos es un servicio complejo que requiere personal técnico capacitado en la gestión de calidad de instrumentos, procesos, procedimientos y resultados. Brinda información acerca del estado de salud o enfermedad de las personas a través de análisis y exámenes que ayudan al médico a realizar un mejor diagnóstico. Por lo tanto, la formación y capacitación del personal técnico resulta un componente fundamental.", 'requirements' => "Título secundario\nCertificado de residencia\nCertificado de aptitud psicofísica\nDNI, fotocopia de 1° y 2° hoja\nActa de nacimiento\nFicha de inscripción\n2 fotos 4x4\nCarnet de vacuna Hepatitis B", 'active' => true],
            ['name' => 'Esterilización', 'slug' => 'tec-sup-en-esterilizacion', 'duration' => '3 años', 'title_granted' => 'Técnico/a Superior en Esterilización', 'description' => "Resolución 292/5 (MEd.) 2014 y ampliatorias.\n\nModalidad: presencial. Duración: 3 años.\n\nPrácticas profesionalizantes: convenio con el SIPROSA.\n\nEl Técnico Superior en Esterilización es el profesional de la salud que, desde una capacitación humanística, integra el equipo de salud desde el área de esterilización y otros ámbitos de su competencia, realizando procesos técnicos específicos en el marco legal vigente para el ejercicio de las profesiones en salud.\n\nPosee capacidad para resolver apropiadamente los problemas laborales que se le presentan, desarrollando su actividad en un marco ético de compromiso y responsabilidad.\n\nAplica las técnicas adecuadas para cada tipo de material bajo normas y protocolos vigentes. Asimismo, efectúa la comprobación del correcto funcionamiento de equipos y procesos de trabajo, y participa en el equipo de salud desde su competencia, con formación para capacitar a los empleados del área.", 'requirements' => "Título secundario\nCertificado de residencia\nCertificado de aptitud psicofísica\nDNI, fotocopia de 1° y 2° hoja\nActa de nacimiento\nFicha de inscripción\n2 fotos 4x4\nCarnet de vacuna Hepatitis B", 'active' => true],
        ];
    }

    private function seedFarmaciaPlan(): void
    {
        $farmacia = Carrera::where('slug', 'tec-sup-en-farmacia')->first();

        if (! $farmacia) {
            return;
        }

        Materia::where('carrera_id', $farmacia->id)->delete();

        foreach ($this->farmaciaMaterias() as $materia) {
            Materia::create([
                'carrera_id' => $farmacia->id,
                'name' => $materia['name'],
                'year' => $materia['year'],
                'semester' => null,
                'hours' => null,
                'correlatives' => $materia['correlatives'] ? null,
            ]);
        }
    }

    private function seedLaboratorioPlan(): void
    {
        $laboratorio = Carrera::where('slug', 'tec-sup-en-laboratorio-de-analisis-clinicos')->first();

        if (! $laboratorio) {
            return;
        }

        Materia::where('carrera_id', $laboratorio->id)->delete();

        foreach ($this->laboratorioMaterias() as $materia) {
            Materia::create([
                'carrera_id' => $laboratorio->id,
                'name' => $materia['name'],
                'year' => $materia['year'],
                'semester' => $materia['semester'] ? null,
                'hours' => null,
                'correlatives' => $materia['correlatives'] ? null,
            ]);
        }
    }

    private function seedEsterilizacionPlan(): void
    {
        $esterilizacion = Carrera::where('slug', 'tec-sup-en-esterilizacion')->first();

        if (! $esterilizacion) {
            return;
        }

        Materia::where('carrera_id', $esterilizacion->id)->delete();

        foreach ($this->esterilizacionMaterias() as $materia) {
            Materia::create([
                'carrera_id' => $esterilizacion->id,
                'name' => $materia['name'],
                'year' => $materia['year'],
                'semester' => null,
                'hours' => null,
                'correlatives' => $materia['correlatives'] ? null,
            ]);
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function esterilizacionMaterias(): array
    {
        return [
            ['year' => 1, 'name' => 'Física General'],
            ['year' => 1, 'name' => 'Química General e Inorgánica'],
            ['year' => 1, 'name' => 'Biología'],
            ['year' => 1, 'name' => 'Relaciones Humanas'],
            ['year' => 1, 'name' => 'Emergencias Médicas Básicas y Primeros Auxilios'],
            ['year' => 1, 'name' => 'Cultura, Comunicación y Trabajo'],
            ['year' => 1, 'name' => 'Esterilización I'],
            ['year' => 1, 'name' => 'Taller de Práctica de Laboratorio'],

            ['year' => 2, 'name' => 'Bioestadística'],
            ['year' => 2, 'name' => 'Organización y Gestión de Instituciones de Salud', 'correlatives' => ['Relaciones Humanas']],
            ['year' => 2, 'name' => 'Higiene y Bioseguridad', 'correlatives' => ['Biología']],
            ['year' => 2, 'name' => 'Esterilización II', 'correlatives' => ['Esterilización I']],
            ['year' => 2, 'name' => 'Inglés'],
            ['year' => 2, 'name' => 'Química Orgánica', 'correlatives' => ['Química General e Inorgánica']],
            ['year' => 2, 'name' => 'Metodología de la Investigación'],
            ['year' => 2, 'name' => 'Práctica Profesionalizante I', 'correlatives' => ['Taller de Práctica de Laboratorio']],

            ['year' => 3, 'name' => 'Ética y Deontología Profesional'],
            ['year' => 3, 'name' => 'Informática'],
            ['year' => 3, 'name' => 'Microbiología', 'correlatives' => ['Biología']],
            ['year' => 3, 'name' => 'Salud Pública'],
            ['year' => 3, 'name' => 'Esterilización III', 'correlatives' => ['Esterilización II']],
            ['year' => 3, 'name' => 'Práctica Profesionalizante II', 'correlatives' => ['Práctica Profesionalizante I']],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function laboratorioMaterias(): array
    {
        return [
            ['year' => 1, 'name' => 'Cultura, Comunicación y Trabajo'],
            ['year' => 1, 'name' => 'Física'],
            ['year' => 1, 'name' => 'Higiene y Bioseguridad'],
            ['year' => 1, 'name' => 'Biología e Histología'],
            ['year' => 1, 'name' => 'Química General e Inorgánica', 'semester' => 1],
            ['year' => 1, 'name' => 'Química Orgánica', 'semester' => 2, 'correlatives' => ['Química General e Inorgánica']],
            ['year' => 1, 'name' => 'Informática'],
            ['year' => 1, 'name' => 'Práctica Profesionalizante I'],

            ['year' => 2, 'name' => 'Química Analítica', 'correlatives' => ['Química Orgánica']],
            ['year' => 2, 'name' => 'Química Biológica', 'correlatives' => ['Química Orgánica']],
            ['year' => 2, 'name' => 'Metodología de la Investigación'],
            ['year' => 2, 'name' => 'Anatomía y Fisiología Humana', 'correlatives' => ['Biología e Histología']],
            ['year' => 2, 'name' => 'Estadística'],
            ['year' => 2, 'name' => 'Inglés'],
            ['year' => 2, 'name' => 'Relaciones Humanas', 'correlatives' => ['Cultura, Comunicación y Trabajo']],
            ['year' => 2, 'name' => 'Organización y Gestión de las Instituciones de Salud', 'correlatives' => ['Cultura, Comunicación y Trabajo']],
            ['year' => 2, 'name' => 'Práctica Profesionalizante II', 'correlatives' => ['Práctica Profesionalizante I']],

            ['year' => 3, 'name' => 'Ética y Deontología Profesional', 'correlatives' => ['Relaciones Humanas']],
            ['year' => 3, 'name' => 'Salud Pública', 'correlatives' => ['Organización y Gestión de las Instituciones de Salud']],
            ['year' => 3, 'name' => 'Bioquímica Clínica e Inmunología', 'correlatives' => ['Química Analítica', 'Química Biológica', 'Hematología y Hemostasia']],
            ['year' => 3, 'name' => 'Hematología y Hemostasia', 'correlatives' => ['Anatomía y Fisiología Humana']],
            ['year' => 3, 'name' => 'Microbiología', 'correlatives' => ['Anatomía y Fisiología Humana']],
            ['year' => 3, 'name' => 'Bioquímica Especial', 'correlatives' => ['Química Analítica', 'Química Biológica', 'Hematología y Hemostasia']],
            ['year' => 3, 'name' => 'Práctica Profesionalizante III'],
        ];
    }

    private function seedEnfermeriaPlan(): void
    {
        $enfermeria = Carrera::where('slug', 'enfermeria-profesional')->first();

        if (! $enfermeria) {
            return;
        }

        Materia::where('carrera_id', $enfermeria->id)->delete();

        foreach ($this->enfermeriaMaterias() as $materia) {
            Materia::create([
                'carrera_id' => $enfermeria->id,
                'name' => $materia['name'],
                'year' => $materia['year'],
                'semester' => null,
                'hours' => null,
                'correlatives' => $materia['correlatives'] ? null,
            ]);
        }
    }

    private function seedAgenteSocioSanitarioPlan(): void
    {
        $agente = Carrera::where('slug', 'tec-sup-en-agente-socio-sanitario')->first();

        if (! $agente) {
            return;
        }

        Materia::where('carrera_id', $agente->id)->delete();

        foreach ($this->agenteSocioSanitarioMaterias() as $materia) {
            Materia::create([
                'carrera_id' => $agente->id,
                'name' => $materia['name'],
                'year' => $materia['year'],
                'semester' => null,
                'hours' => null,
                'correlatives' => $materia['correlatives'] ? null,
            ]);
        }
    }

    private function seedDiagnosticoImagenesPlan(): void
    {
        $diagnostico = Carrera::where('slug', 'tec-sup-en-diagnostico-por-imagenes')->first();

        if (! $diagnostico) {
            return;
        }

        Materia::where('carrera_id', $diagnostico->id)->delete();

        foreach ($this->diagnosticoImagenesMaterias() as $materia) {
            Materia::create([
                'carrera_id' => $diagnostico->id,
                'name' => $materia['name'],
                'year' => $materia['year'],
                'semester' => null,
                'hours' => null,
                'correlatives' => $materia['correlatives'] ? null,
            ]);
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function diagnosticoImagenesMaterias(): array
    {
        return [
            ['year' => 1, 'name' => 'Comunicación y Producción de Textos'],
            ['year' => 1, 'name' => 'Tecnología Informática'],
            ['year' => 1, 'name' => 'Condiciones y Medio Ambiente de Trabajo'],
            ['year' => 1, 'name' => 'Salud Pública'],
            ['year' => 1, 'name' => 'Fundamentos de Esterilización'],
            ['year' => 1, 'name' => 'Físico-Química'],
            ['year' => 1, 'name' => 'Anatomía y Fisiología I'],
            ['year' => 1, 'name' => 'Técnicas de Diagnóstico por Imágenes I'],
            ['year' => 1, 'name' => 'Práctica Profesionalizante I'],

            ['year' => 2, 'name' => 'Psicología'],
            ['year' => 2, 'name' => 'Bioseguridad', 'correlatives' => ['Condiciones y Medio Ambiente de Trabajo']],
            ['year' => 2, 'name' => 'Farmacología', 'correlatives' => ['Anatomía y Fisiología I']],
            ['year' => 2, 'name' => 'Asistencias Médicas Básicas', 'correlatives' => ['Anatomía y Fisiología I']],
            ['year' => 2, 'name' => 'Biofísica', 'correlatives' => ['Físico-Química', 'Anatomía y Fisiología I']],
            ['year' => 2, 'name' => 'Anatomía y Fisiología II', 'correlatives' => ['Anatomía y Fisiología I']],
            ['year' => 2, 'name' => 'Técnicas de Diagnóstico por Imágenes II', 'correlatives' => ['Técnicas de Diagnóstico por Imágenes I']],
            ['year' => 2, 'name' => 'Práctica Profesionalizante II', 'correlatives' => ['Práctica Profesionalizante I']],

            ['year' => 3, 'name' => 'Inglés Técnico', 'correlatives' => ['Técnicas de Diagnóstico por Imágenes II']],
            ['year' => 3, 'name' => 'Ética y Deontología'],
            ['year' => 3, 'name' => 'Organización y Gestión de las Instituciones de Salud'],
            ['year' => 3, 'name' => 'Metodología de la Investigación y Bioestadística'],
            ['year' => 3, 'name' => 'Radioprotección', 'correlatives' => ['Bioseguridad', 'Farmacología', 'Anatomía y Fisiología II', 'Técnicas de Diagnóstico por Imágenes II']],
            ['year' => 3, 'name' => 'Embriología y Patología', 'correlatives' => ['Anatomía y Fisiología II', 'Práctica Profesionalizante II']],
            ['year' => 3, 'name' => 'Procedimientos Tecnológicos Especiales para el Diagnóstico por Imágenes', 'correlatives' => ['Técnicas de Diagnóstico por Imágenes II']],
            ['year' => 3, 'name' => 'Práctica Profesionalizante III', 'correlatives' => ['Práctica Profesionalizante II']],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function agenteSocioSanitarioMaterias(): array
    {
        return [
            ['year' => 1, 'name' => 'Morfofisiología'],
            ['year' => 1, 'name' => 'Higiene y Bioseguridad'],
            ['year' => 1, 'name' => 'Introducción a la Salud Pública'],
            ['year' => 1, 'name' => 'Introducción a la Psicología'],
            ['year' => 1, 'name' => 'Problemáticas Socioculturales Contemporáneas'],
            ['year' => 1, 'name' => 'Primeros Auxilios'],
            ['year' => 1, 'name' => 'Práctica Profesionalizante I'],

            ['year' => 2, 'name' => 'Nutrición', 'correlatives' => ['Morfofisiología']],
            ['year' => 2, 'name' => 'Epidemiología Aplicada', 'correlatives' => ['Introducción a la Salud Pública', 'Primeros Auxilios', 'Higiene y Bioseguridad']],
            ['year' => 2, 'name' => 'Salud Pública I', 'correlatives' => ['Morfofisiología', 'Introducción a la Salud Pública']],
            ['year' => 2, 'name' => 'Psicología Comunitaria', 'correlatives' => ['Introducción a la Psicología', 'Higiene y Bioseguridad']],
            ['year' => 2, 'name' => 'Ética y Deontología', 'correlatives' => ['Problemáticas Socioculturales Contemporáneas', 'Primeros Auxilios']],
            ['year' => 2, 'name' => 'Informática Aplicada', 'correlatives' => ['Nutrición']],
            ['year' => 2, 'name' => 'Proyecto de Intervención Sociocomunitaria I', 'correlatives' => ['Introducción a la Salud Pública', 'Problemáticas Socioculturales Contemporáneas']],
            ['year' => 2, 'name' => 'Salud Comunitaria y Grupos de Riesgo', 'correlatives' => ['Primeros Auxilios', 'Introducción a la Salud Pública', 'Higiene y Bioseguridad', 'Epidemiología Aplicada']],
            ['year' => 2, 'name' => 'Práctica Profesionalizante II'],

            ['year' => 3, 'name' => 'Antropología Cultural', 'correlatives' => ['Ética y Deontología', 'Epidemiología Aplicada']],
            ['year' => 3, 'name' => 'Educación para la Salud', 'correlatives' => ['Salud Pública I', 'Proyecto de Intervención Sociocomunitaria I']],
            ['year' => 3, 'name' => 'Estadística en Salud', 'correlatives' => ['Salud Comunitaria y Grupos de Riesgo', 'Salud Pública I']],
            ['year' => 3, 'name' => 'Organización y Gestión de las Instituciones en Salud', 'correlatives' => ['Epidemiología Aplicada', 'Salud Pública I', 'Salud Comunitaria y Grupos de Riesgo']],
            ['year' => 3, 'name' => 'Metodología de la Investigación', 'correlatives' => ['Epidemiología Aplicada', 'Salud Pública I', 'Psicología Comunitaria', 'Salud Comunitaria y Grupos de Riesgo']],
            ['year' => 3, 'name' => 'Salud Pública II', 'correlatives' => ['Salud Pública I', 'Ética y Deontología', 'Epidemiología Aplicada', 'Proyecto de Intervención Sociocomunitaria I']],
            ['year' => 3, 'name' => 'Lingüística Social', 'correlatives' => ['Salud Pública I', 'Salud Comunitaria y Grupos de Riesgo']],
            ['year' => 3, 'name' => 'Proyecto de Intervención Sociocomunitaria II', 'correlatives' => ['Nutrición', 'Epidemiología Aplicada', 'Salud Pública I', 'Psicología Comunitaria', 'Proyecto de Intervención Sociocomunitaria I']],
            ['year' => 3, 'name' => 'Práctica Profesionalizante III'],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function enfermeriaMaterias(): array
    {
        return [
            ['year' => 1, 'name' => 'Enfermería Básica'],
            ['year' => 1, 'name' => 'Enfermería Comunitaria', 'correlatives' => ['Enfermería Básica']],
            ['year' => 1, 'name' => 'Introducción a las Ciencias Psicosociales'],
            ['year' => 1, 'name' => 'Biofísico-Química'],
            ['year' => 1, 'name' => 'Microbiología'],
            ['year' => 1, 'name' => 'Anatomofisiología'],
            ['year' => 1, 'name' => 'Parasitología'],
            ['year' => 1, 'name' => 'Cultura, Comunicación y Trabajo'],
            ['year' => 1, 'name' => 'Higiene y Bioseguridad'],
            ['year' => 1, 'name' => 'Práctica Profesionalizante I'],

            ['year' => 2, 'name' => 'Enfermería Médica', 'correlatives' => ['Enfermería Comunitaria', 'Microbiología', 'Parasitología', 'Higiene y Bioseguridad']],
            ['year' => 2, 'name' => 'Enfermería Quirúrgica', 'correlatives' => ['Enfermería Médica', 'Biofísico-Química', 'Anatomofisiología']],
            ['year' => 2, 'name' => 'Psicología Clínica y Social', 'correlatives' => ['Introducción a las Ciencias Psicosociales']],
            ['year' => 2, 'name' => 'Farmacología General y Especial'],
            ['year' => 2, 'name' => 'Informática'],
            ['year' => 2, 'name' => 'Ética y Deontología'],
            ['year' => 2, 'name' => 'Nutrición y Dietoterapia'],
            ['year' => 2, 'name' => 'Salud Pública', 'correlatives' => ['Enfermería Comunitaria', 'Higiene y Bioseguridad']],
            ['year' => 2, 'name' => 'Práctica Profesionalizante II'],

            ['year' => 3, 'name' => 'Enfermería Materno Infantil', 'correlatives' => ['Enfermería Quirúrgica', 'Farmacología General y Especial', 'Nutrición y Dietoterapia', 'Salud Pública']],
            ['year' => 3, 'name' => 'Enfermería Infanto Juvenil', 'correlatives' => ['Enfermería Quirúrgica', 'Enfermería Materno Infantil', 'Farmacología General y Especial', 'Salud Pública']],
            ['year' => 3, 'name' => 'Enfermería Psiquiátrica y Salud Mental', 'correlatives' => ['Introducción a las Ciencias Psicosociales']],
            ['year' => 3, 'name' => 'Administración en Enfermería', 'correlatives' => ['Psicología Clínica y Social', 'Ética y Deontología', 'Estadística en Salud']],
            ['year' => 3, 'name' => 'Enfermería en Alto Riesgo I y II', 'correlatives' => ['Farmacología General y Especial', 'Enfermería Quirúrgica']],
            ['year' => 3, 'name' => 'Estadística en Salud', 'correlatives' => ['Salud Pública']],
            ['year' => 3, 'name' => 'Organización y Gestión de Instituciones de Salud', 'correlatives' => ['Salud Pública']],
            ['year' => 3, 'name' => 'Inglés'],
            ['year' => 3, 'name' => 'Práctica Profesionalizante III'],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function farmaciaMaterias(): array
    {
        return [
            ['year' => 1, 'name' => 'Física'],
            ['year' => 1, 'name' => 'Relaciones Humanas'],
            ['year' => 1, 'name' => 'Biología General y Principios de Anatomía Humana'],
            ['year' => 1, 'name' => 'Química General e Inorgánica'],
            ['year' => 1, 'name' => 'Higiene y Bioseguridad Aplicada'],
            ['year' => 1, 'name' => 'Emergencias Médicas Básicas y Primeros Auxilios'],
            ['year' => 1, 'name' => 'Taller de Práctica de Laboratorio'],

            ['year' => 2, 'name' => 'Química Orgánica y Biológica', 'correlatives' => ['Física', 'Biología General y Principios de Anatomía Humana', 'Química General e Inorgánica']],
            ['year' => 2, 'name' => 'Farmacia Hospitalaria I', 'correlatives' => ['Higiene y Bioseguridad Aplicada']],
            ['year' => 2, 'name' => 'Bioestadística'],
            ['year' => 2, 'name' => 'Organización y Gestión de las Instituciones de Salud', 'correlatives' => ['Higiene y Bioseguridad Aplicada']],
            ['year' => 2, 'name' => 'Farmacodinamia I', 'correlatives' => ['Biología General y Principios de Anatomía Humana', 'Química General e Inorgánica']],
            ['year' => 2, 'name' => 'Farmacotecnia I'],
            ['year' => 2, 'name' => 'Informática Aplicada'],
            ['year' => 2, 'name' => 'Práctica Profesionalizante I', 'correlatives' => ['Taller de Práctica de Laboratorio']],

            ['year' => 3, 'name' => 'Deontología Profesional y Legislación Farmacéutica', 'correlatives' => ['Farmacia Hospitalaria I', 'Organización y Gestión de las Instituciones de Salud']],
            ['year' => 3, 'name' => 'Salud Pública', 'correlatives' => ['Farmacia Hospitalaria I', 'Organización y Gestión de las Instituciones de Salud']],
            ['year' => 3, 'name' => 'Farmacodinamia II', 'correlatives' => ['Farmacodinamia I']],
            ['year' => 3, 'name' => 'Farmacotecnia II'],
            ['year' => 3, 'name' => 'Cultura, Comunicación y Trabajo'],
            ['year' => 3, 'name' => 'Práctica Profesionalizante II', 'correlatives' => ['Práctica Profesionalizante I']],
        ];
    }
}
