<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostsSectorPrivadoSeeder extends Seeder
{
    public function run(): void
    {
        $authorId = User::orderBy('id')->value('id') ? 1;

        $posts = [

            [
                'title'            => 'Acuerdo paritario 2026 para trabajadores de clínicas y sanatorios privados',
                'slug'             => 'paritarias-privado-2026-cct-122-75',
                'category'         => 'gremial',
                'published_at'     => '2026-03-20 10:00:00',
                'destacado'        => true,
                'fuente'           => 'FATSA / sanidad.org.ar',
                'fuente_url'       => 'https://www.sanidad.org.ar/acciongremial/cct/c122.aspx',
                'excerpt'          => 'FATSA cerró el acuerdo salarial 2026 para el CCT 122/75: tres tramos de aumento acumulado del 5,19% y sumas no remunerativas mensuales de hasta $90.000.',
                'meta_description' => 'Paritarias 2026 sector privado sanidad CCT 122/75: aumentos por tramos y suma no remunerativa para trabajadores de clínicas y sanatorios de Tucumán.',
                'body'             => <<<'BODY'
La Federación de Asociaciones de Trabajadores de la Sanidad Argentina (FATSA), de la que ATSA Tucumán forma parte, cerró en marzo de 2026 el acuerdo salarial para los trabajadores encuadrados en el Convenio Colectivo de Trabajo 122/75, que regula las condiciones laborales en clínicas, sanatorios, hospitales privados y geriátricos de todo el país.

**¿Qué establece el acuerdo?**

El acuerdo fija un incremento salarial en tres tramos acumulativos sobre el básico convencional:

- **Febrero 2026:** +1,8% + suma no remunerativa mensual de $80.000
- **Marzo 2026:** +1,7% + suma no remunerativa mensual de $85.000
- **Abril 2026:** +1,6% + suma no remunerativa mensual de $90.000

El efecto acumulativo del aumento en los tres tramos equivale al **5,19%** sobre el básico de enero 2026. Las partes acordaron reunirse en mayo de 2026 para revisar las escalas en función de la evolución del índice de precios.

**Bono por el Día del Trabajador de la Sanidad**

El acuerdo incorpora el pago de una asignación especial de **$63.369,91** por el Día del Trabajador de la Sanidad (21 de septiembre) para todos los trabajadores comprendidos en el CCT 122/75, independientemente de su categoría y antigüedad.

**Escala de referencia — Básicos de abril 2026**

Los valores del básico convencional vigentes desde el 1° de abril de 2026 van desde **$1.372.188** para profesionales (bioquímicos, kinesiólogos, nutricionistas) hasta **$981.730** para personal de maestranza y mucamas. Estos valores no incluyen antigüedad, presentismo ni demás adiciones particulares de cada establecimiento.

**¿A quiénes aplica en Tucumán?**

El CCT 122/75 abarca a todos los trabajadores de clínicas privadas, sanatorios, hospitales privados, geriátricos y neuropsiquiátricos. En Tucumán, los principales establecimientos comprendidos incluyen el Sanatorio 9 de Julio, Sanatorio del Norte, Sanatorio Modelo, Sanatorio Rivadavia, Sanatorio Sur, Clínica Mayo, Galeno, Cimsa, Sama, Parque y otros centros de salud privados.

ATSA Tucumán acompaña a los trabajadores del sector privado en la verificación del correcto encuadre y liquidación salarial. Si tenés dudas sobre tu recibo de sueldo, acercate a la sede gremial o a tu delegado.
BODY,
            ],

            [
                'title'            => 'CCT 108/75: acuerdo 2026 para trabajadores de diagnóstico e imagen médica',
                'slug'             => 'paritarias-privado-2026-cct-108-75-diagnostico',
                'category'         => 'gremial',
                'published_at'     => '2026-03-21 09:00:00',
                'destacado'        => false,
                'fuente'           => 'FATSA / sanidad.org.ar',
                'fuente_url'       => 'https://www.sanidad.org.ar/acciongremial/cct/c108.aspx',
                'excerpt'          => 'Los trabajadores de laboratorios, centros de diagnóstico e imagen médica recibieron el mismo aumento que el CCT 122/75 con vigencia desde febrero de 2026.',
                'meta_description' => 'Paritarias 2026 CCT 108/75 sanidad: acuerdo para trabajadores de diagnóstico, imagen médica y laboratorios privados.',
                'body'             => <<<'BODY'
En simultáneo con el acuerdo del CCT 122/75, FATSA firmó el convenio salarial 2026 para el **Convenio Colectivo de Trabajo 108/75**, que regula las condiciones laborales del personal técnico y administrativo de centros de diagnóstico por imágenes y laboratorios de análisis clínicos privados (CADIME / CEDIM).

**Idéntica estructura de aumento**

El acuerdo establece los mismos tres tramos que el CCT 122/75:

- **Febrero:** +1,8% + $80.000 no remunerativo mensual
- **Marzo:** +1,7% + $85.000 no remunerativo mensual
- **Abril:** +1,6% + $90.000 no remunerativo mensual

También incluye el bono especial por el Día del Trabajador de la Sanidad (21 de septiembre) de **$63.369,91**.

**¿Quiénes están comprendidos?**

El CCT 108/75 abarca a los trabajadores de:

- Centros de diagnóstico por imágenes (radiología, resonancia magnética, tomografía, ecografías)
- Laboratorios de análisis clínicos privados
- Centros de medicina nuclear
- Establecimientos de anatomía patológica

**¿Trabajás en diagnóstico y no sabés si estás bien encuadrado?**

Un error frecuente es que trabajadores de centros de diagnóstico sean liquidados bajo el CCT 122/75 en lugar del 108/75, o viceversa. ATSA Tucumán asesora gratuitamente sobre el encuadre correcto y los derechos que corresponden a cada convenio. Consultá con tu delegado o acercate a la sede gremial.
BODY,
            ],

            [
                'title'            => 'ATSA protestó en clínicas y sanatorios por incumplimientos salariales',
                'slug'             => 'atsa-protesta-clinicas-sanatorios-tucuman-incumplimientos',
                'category'         => 'gremial',
                'published_at'     => '2025-05-14 11:00:00',
                'destacado'        => false,
                'fuente'           => 'LV12 / Diario Panorama',
                'fuente_url'       => 'https://www.lv12.com.ar/sanatorios/atsa-protesto-clinicas-y-sanatorios-tucuman-n98662',
                'excerpt'          => 'ATSA llevó adelante paros parciales de cuatro horas por turno en los principales establecimientos privados de salud de Tucumán en reclamo por actualizaciones salariales adeudadas.',
                'meta_description' => 'ATSA Tucumán realizó paros parciales en clínicas y sanatorios privados por incumplimientos salariales.',
                'body'             => <<<'BODY'
ATSA Tucumán llevó adelante medidas de fuerza en las principales clínicas y sanatorios privados de la provincia en el marco de un plan de lucha nacional coordinado por FATSA ante el incumplimiento de acuerdos salariales por parte de los empleadores del sector.

**La medida de fuerza**

La acción consistió en **paros parciales de cuatro horas por turno** en todos los establecimientos de salud privada comprendidos en los CCT 122/75 y 108/75. El secretario general de ATSA Tucumán, **Reneé Ramírez**, explicó que la medida fue adoptada ante la falta de pago de las actualizaciones salariales acordadas en paritarias.

Los establecimientos donde se realizaron las protestas incluyeron: Sanatorio 9 de Julio, Sanatorio del Norte, Sanatorio Modelo, Sanatorio Rivadavia, Sanatorio Sur, Sanatorio Parque, Galeno, Central, Cimsa, Sama, Regional y Clínica Mayo.

**Servicios de emergencia garantizados**

Ramírez aclaró que se garantizó la atención de urgencias y emergencias, las guardias hospitalarias, los internados en cuidados intensivos y los pacientes con cirugías programadas de urgencia, en cumplimiento del régimen de servicios mínimos establecido por ley.

**La posición del gremio**

"Los trabajadores del sector privado tienen los mismos derechos que cualquier otro trabajador. El convenio colectivo existe para ser cumplido, y ATSA va a estar siempre presente para que así sea", afirmó Ramírez. El dirigente también solicitó la conformación de una mesa de trabajo conjunta entre el Estado provincial, empleadores y sindicatos para ordenar el funcionamiento del sector privado de salud.

**Resultado**

Tras las medidas de fuerza, los empleadores afectados acordaron en actas de conciliación el pago escalonado de las diferencias salariales adeudadas, con seguimiento por parte de la comisión directiva de ATSA.
BODY,
            ],

            [
                'title'            => '750 nuevos delegados asumieron en clínicas, sanatorios y hospitales de Tucumán',
                'slug'             => 'nuevos-delegados-atsa-sector-privado-tucuman-2024',
                'category'         => 'gremial',
                'published_at'     => '2024-11-08 09:00:00',
                'destacado'        => false,
                'fuente'           => 'Ministerio de Salud Pública de Tucumán',
                'fuente_url'       => 'https://msptucuman.gov.ar/asumieron-los-nuevos-delegados-de-atsa/',
                'excerpt'          => 'ATSA Tucumán normalizó la representación en todos los establecimientos de salud de la provincia: 750 delegados electos tomaron posesión de sus cargos para el período 2024-2026.',
                'meta_description' => 'ATSA Tucumán renovó sus 750 delegados en establecimientos públicos y privados de salud para el período 2024-2026.',
                'body'             => <<<'BODY'
ATSA Tucumán completó el proceso de normalización sindical en todos los establecimientos de salud de la provincia con la asunción de **750 nuevos delegados** electos por los trabajadores tanto del sector público como del privado para el período 2024–2026.

**El acto de asunción**

El secretario general **Reneé Ramírez** tomó el juramento a los delegados electos en un acto que contó con la presencia de autoridades del Ministerio de Salud Pública de la Provincia. Ramírez destacó la importancia de contar con representantes genuinos en cada lugar de trabajo: "El delegado es la voz del trabajador ante cualquier problema con la dirección, con la administración, con el dueño del sanatorio o la clínica".

**Funciones del delegado en el sector privado**

Los delegados en establecimientos privados tienen, entre otras, las siguientes responsabilidades:

- Recibir y gestionar los reclamos de los trabajadores ante la conducción del establecimiento
- Verificar el cumplimiento del CCT 122/75 o 108/75 según el tipo de institución
- Participar en reuniones periódicas con la comisión directiva de ATSA
- Notificar al gremio ante cualquier irregularidad en liquidaciones, despidos o condiciones de trabajo
- Promover la afiliación y la formación gremial entre sus compañeros

**Cobertura territorial**

Los delegados electos representan a trabajadores en clínicas, sanatorios, geriátricos, laboratorios y centros de diagnóstico de toda la provincia, incluyendo establecimientos del Gran Tucumán y del interior provincial como Concepción, Monteros, Aguilares, Famaillá y Banda del Río Salí.

**Un mensaje a los trabajadores del privado**

"Sé que muchos trabajadores del sector privado no saben que tienen un convenio que los protege, que tienen un gremio que los respalda. Por eso tenemos delegados: para que nadie quede solo ante una injusticia", expresó Ramírez durante el acto.
BODY,
            ],

            [
                'title'            => 'Derechos del trabajador de la sanidad privada: lo que dice el CCT 122/75',
                'slug'             => 'derechos-trabajador-sanidad-privada-cct-122-75-tucuman',
                'category'         => 'gremial',
                'published_at'     => '2025-03-10 08:00:00',
                'destacado'        => false,
                'fuente'           => 'ATSA Tucumán / sanidad.org.ar',
                'fuente_url'       => 'https://www.sanidad.org.ar/acciongremial/cct/c122.aspx',
                'excerpt'          => 'Muchos trabajadores de clínicas y sanatorios privados desconocen los derechos que les garantiza el Convenio Colectivo 122/75. ATSA Tucumán los detalla para que nadie trabaje sin saber lo que le corresponde.',
                'meta_description' => 'Derechos laborales del CCT 122/75 para trabajadores de sanidad privada en Tucumán: licencias, vacaciones, categorías y más.',
                'body'             => <<<'BODY'
El Convenio Colectivo de Trabajo 122/75 es el instrumento legal que regula los derechos y obligaciones de los trabajadores y empleadores del sector salud privado en Argentina. ATSA Tucumán acerca a sus afiliados y trabajadores del sector un resumen de los derechos más importantes que establece este convenio.

**Licencias especiales**

El CCT 122/75 establece licencias pagas por causas personales que van más allá del esquema básico de la Ley de Contrato de Trabajo:

- **Casamiento:** 14 días corridos desde el día de la ceremonia
- **Nacimiento o adopción de hijo/a:** 3 días corridos
- **Fallecimiento de cónyuge o hijos:** 7 días corridos
- **Fallecimiento de padres o hermanos:** 7 días corridos
- **Fallecimiento de abuelos o nietos:** 2 días corridos
- **Personal expuesto a radiaciones (radiología):** 14 días adicionales de licencia anual

**Vacaciones según antigüedad**

- Hasta 5 años de servicio: **14 días hábiles**
- Entre 5 y 10 años: **21 días hábiles**
- Entre 10 y 20 años: **28 días hábiles**
- Más de 20 años: **35 días hábiles**

**Examen médico preventivo**

Los trabajadores tienen derecho a ausentarse el tiempo necesario para realizarse controles médicos preventivos sin descuento de presentismo ni descontar del período de vacaciones.

**Categorías y encuadre**

El convenio establece categorías que van desde el Personal Auxiliar hasta el Profesional Universitario. Un error frecuente es la categorización incorrecta, que resulta en salarios por debajo de lo que corresponde. ATSA Tucumán verifica el encuadre correcto sin costo para el trabajador.

**¿Cómo reclamar ante un incumplimiento?**

Si tu empleador no respeta el CCT, el primer paso es consultar con tu delegado o con la sede de ATSA. El gremio puede actuar de forma individual o colectiva para hacer cumplir el convenio, incluyendo la interposición de denuncias ante el Ministerio de Trabajo de la Provincia.

Recordá: estar afiliado te da acceso a asesoramiento jurídico gratuito y representación en cualquier conflicto laboral.
BODY,
            ],

        ];

        foreach ($posts as $data) {
            $data['slug']      = Str::slug($data['slug']);
            $data['tags']      = ['sector privado', 'paritarias', 'CCT 122/75'];
            $data['author_id'] = $authorId;

            Post::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }

        $this->command->info('✅ ' . count($posts) . ' posts del sector privado insertados/actualizados.');
    }
}
