@extends('layouts.app')

@section('title', 'Escalas salariales | ATSA Tucumán')
@section('meta_description', 'Escalas salariales y acuerdos paritarios del sector salud de Tucumán. ATSA Tucumán negocia salarios para los trabajadores de la sanidad.')
@section('og_image', asset('images/historia/movilizacion-atsa-sanidad.jpg'))

@php use Illuminate\Support\Facades\Storage; @endphp

@section('content')

    {{-- HERO --}}
    <section class="atsa-page-hero py-14 position-relative"
             style="background-image: url('{{ asset('images/historia/movilizacion-atsa-sanidad.jpg') }}'); min-height: 440px;">
        <div class="container-fluid position-relative z-1">
            <div class="col-lg-8">
                <span class="badge bg-white text-primary fs-3 fw-bolder px-3 py-2 mb-4">
                    <i class="ti ti-cash me-1"></i>PARITARIAS
                </span>
                <h1 class="fw-bolder display-4 text-white mb-3">Escalas salariales</h1>
                <p class="fs-5 text-white text-opacity-75 mb-5 col-lg-9">
                    Acuerdos vigentes e historial de negociaciones salariales del sector salud de Tucumán.
                    ATSA negocia para mejorar el salario y las condiciones laborales de cada trabajador.
                </p>
                <a href="{{ route('gremial.index') }}#paritarias" class="btn btn-outline-light fw-bold py-7 px-9">
                    <i class="ti ti-scale me-2"></i>Actividad gremial
                </a>
            </div>
        </div>
    </section>

    {{-- INFO BANNER --}}
    <section class="py-8 bg-primary-subtle">
        <div class="container-fluid">
            <div class="row g-4 align-items-center">
                <div class="col-lg-7">
                    <p class="text-primary fw-bolder fs-4 mb-1">¿QUÉ SON LAS PARITARIAS?</p>
                    <p class="fs-4 text-body mb-0">
                        Las negociaciones paritarias son el proceso en el que ATSA Tucumán —junto a los demás
                        sindicatos del sector salud— se sienta con el Gobierno Provincial y empleadores para
                        acordar actualizaciones salariales, condiciones laborales y beneficios colectivos.
                    </p>
                </div>
                <div class="col-lg-5">
                    <div class="row g-3 text-center">
                        @foreach ([
                            ['ti-users-group', 'Representación', 'Conducción electa por los afiliados'],
                            ['ti-handshake',   'Negociación',    'Mesas con Ministerio de Salud'],
                            ['ti-file-certificate', 'Acuerdo',  'Firmado y publicado para todos'],
                        ] as $s)
                            <div class="col-4">
                                <div class="bg-white rounded-3 p-3 h-100 shadow-sm">
                                    <i class="ti {{ $s[0] }} text-primary fs-7 mb-2 d-block"></i>
                                    <p class="fw-bolder mb-1 fs-4">{{ $s[1] }}</p>
                                    <p class="text-muted mb-0 fs-2">{{ $s[2] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ACUERDO VIGENTE BANNER --}}
    <section class="py-7" style="background: linear-gradient(135deg, #1e3a5f 0%, #0f2236 100%);">
        <div class="container-fluid">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <span class="badge rounded-pill px-3 py-2 fw-bold fs-2" style="background:#1D9E75;color:#fff;">
                            <i class="ti ti-circle-check me-1"></i>Vigente 2026
                        </span>
                        <h3 class="text-white fw-bolder mb-0 fs-6">
                            Acuerdo paritario firmado el 6 de marzo de 2026
                        </h3>
                    </div>
                    <p class="text-white text-opacity-75 fs-4 mb-0 mt-2">
                        <strong class="text-white">11% de aumento</strong> sobre el básico bonificable ·
                        <strong class="text-white">Carrera Sanitaria al 60%</strong> (+8 puntos) ·
                        Piso salarial <strong class="text-white">$940.000</strong>
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('gremial.index') }}#paritarias-publico"
                       class="btn btn-outline-light fw-bold py-7 px-8">
                        <i class="ti ti-scale me-2"></i>Ver detalle del acuerdo
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- CONTENIDO PRINCIPAL --}}
    <section class="py-10 py-lg-14">
        <div class="container-fluid">

            {{-- Documentos PDF cargados desde admin --}}
            @if ($escalas->count())
                <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-8">
                    <div>
                        <p class="text-primary fs-4 fw-bolder mb-2">DOCUMENTOS OFICIALES</p>
                        <h2 class="fw-bolder fs-9 mb-0">Acuerdos salariales publicados</h2>
                    </div>
                    <a href="{{ route('contacto.index') }}" class="btn btn-outline-primary fw-bold">
                        <i class="ti ti-mail me-2"></i>Consultar al gremio
                    </a>
                </div>
                @foreach ($escalas as $escala)
                    <div class="card border-0 rounded-3 mb-4 escala-card overflow-hidden">
                        <div class="card-body p-0">
                            <div class="row g-0 align-items-stretch">
                                <div class="col-lg-1 col-md-2 d-flex align-items-center justify-content-center"
                                     style="background: linear-gradient(180deg, #1e3a5f, #2a4d73); min-height: 100px;">
                                    <i class="ti ti-file-certificate text-white" style="font-size: 36px;"></i>
                                </div>
                                <div class="col p-6">
                                    <div class="d-flex flex-wrap align-items-start justify-content-between gap-4">
                                        <div>
                                            <span class="badge bg-primary-subtle text-primary mb-2 px-3 py-2">
                                                <i class="ti ti-calendar me-1"></i>
                                                Vigente desde {{ $escala->vigente_desde->format('d/m/Y') }}
                                            </span>
                                            <h2 class="fw-bolder fs-7 mb-2">{{ $escala->titulo }}</h2>
                                            @if ($escala->descripcion)
                                                <p class="fs-4 text-body mb-0 col-lg-9">{{ $escala->descripcion }}</p>
                                            @endif
                                        </div>
                                        @if ($escala->archivo)
                                            <a href="{{ Storage::disk('public')->url($escala->archivo) }}"
                                               target="_blank"
                                               class="btn btn-primary py-7 px-9 fw-bold flex-shrink-0">
                                                <i class="ti ti-download me-2"></i>Descargar PDF
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <hr class="my-8">
            @endif

            {{-- ESCALA CARRERA SANITARIA 2026 --}}
            <div class="mb-5">
                <div class="d-flex align-items-end gap-3 flex-wrap mb-6">
                    <div>
                        <p class="text-primary fs-4 fw-bolder mb-1">SECTOR PÚBLICO — TUCUMÁN</p>
                        <h2 class="fw-bolder fs-9 mb-0">Escala salarial 2026 — Carrera Sanitaria</h2>
                    </div>
                    <span class="badge rounded-pill px-3 py-2 fs-2 fw-bold mb-1" style="background:#1D9E75;color:#fff;">
                        Vigente desde marzo 2026
                    </span>
                </div>

                {{-- Tabla de categorías --}}
                <div class="card border-0 rounded-4 shadow-sm overflow-hidden mb-5">
                    <div class="px-6 py-4 d-flex align-items-center gap-3"
                         style="background: linear-gradient(135deg, #1e3a5f 0%, #0f2236 100%);">
                        <i class="ti ti-table text-white fs-6"></i>
                        <div>
                            <h5 class="text-white fw-bolder mb-0">Básicos por categoría — Carrera Sanitaria provincial</h5>
                            <small class="text-white text-opacity-75">Valores mensuales brutos. Incluyen 11% de aumento + 60% de Carrera Sanitaria. Vigentes desde el 6 de marzo de 2026.</small>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="font-size:14px;">
                            <thead style="background:#f0f4f8;">
                                <tr>
                                    <th class="px-5 py-4">Categoría</th>
                                    <th class="px-4 py-4">Perfil / Ejemplos</th>
                                    <th class="px-4 py-4 text-end">Básico bruto</th>
                                    <th class="px-4 py-4 text-end">Con CS 60%</th>
                                    <th class="px-4 py-4 text-center">Nivel</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ([
                                    ['A — Profesional universitario',       'Médicos, odontólogos, bioquímicos, kinesiólogos, nutricionistas, farmacéuticos', '$1.380.000', '$2.208.000', 'primary'],
                                    ['B — Técnico superior universitario',   'Enfermeros universitarios, trabajadores sociales, psicólogos, fonoaudiólogos',    '$1.220.000', '$1.952.000', 'info'],
                                    ['C — Técnico de nivel medio',           'Enfermeros técnicos, técnicos en imágenes, técnicos de laboratorio',               '$1.090.000', '$1.744.000', 'info'],
                                    ['D — Auxiliar especializado',           'Auxiliares de enfermería, administrativos sanitarios, instrumentadores',           '$1.010.000', '$1.616.000', 'warning'],
                                    ['E — Auxiliar general / Administrativo','Administrativos generales, recepcionistas, archivistas, maestranza',               '$940.000',   '$1.504.000', 'warning'],
                                    ['F — Servicios generales',              'Personal de limpieza, camilleros, porteros, vigilancia',                           '$940.000',   '$1.504.000', 'secondary'],
                                ] as $cat)
                                    <tr>
                                        <td class="px-5 py-4 fw-bolder">{{ $cat[0] }}</td>
                                        <td class="px-4 py-4 text-muted fs-3">{{ $cat[1] }}</td>
                                        <td class="px-4 py-4 text-end fw-semibold">{{ $cat[2] }}</td>
                                        <td class="px-4 py-4 text-end fw-bolder text-success">{{ $cat[3] }}</td>
                                        <td class="px-4 py-4 text-center">
                                            <span class="badge bg-{{ $cat[4] }}-subtle text-{{ $cat[4] }} rounded-pill px-3">{{ substr($cat[0], 0, 1) }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 d-flex align-items-start gap-2" style="background:#f8fafc; border-top:1px solid #e8edf2;">
                        <i class="ti ti-alert-circle text-warning fs-5 flex-shrink-0 mt-1"></i>
                        <p class="fs-3 text-muted mb-0">
                            Los valores son <strong>orientativos</strong>. El salario real incluye además:
                            <strong>antigüedad</strong> (1% por año de servicio), <strong>presentismo</strong>,
                            <strong>zona desfavorable</strong> para trabajadores no asistenciales de áreas alejadas,
                            horas extras, guardia activa y demás asignaciones particulares.
                            La escala oficial se publica mediante resolución del Ministerio de Salud Pública.
                        </p>
                    </div>
                </div>

                {{-- Composición del salario + Carrera Sanitaria --}}
                <div class="row g-4 mb-5">
                    <div class="col-lg-8">
                        <div class="card border-0 rounded-4 shadow-sm h-100">
                            <div class="card-body p-6">
                                <h5 class="fw-bolder mb-4 d-flex align-items-center gap-2">
                                    <i class="ti ti-calculator text-primary fs-6"></i>
                                    ¿Qué componentes integran el salario?
                                </h5>
                                <div class="row g-3">
                                    @foreach ([
                                        ['primary',   'ti-coin',              'Básico bonificable',       'El valor de tabla según tu categoría. Sobre este se calculan todos los porcentajes.'],
                                        ['success',   'ti-heart-rate-monitor','Carrera Sanitaria',        'Actualmente en el 60% del básico. El objetivo paritario es llegar al 100%.'],
                                        ['info',      'ti-calendar-stats',    'Antigüedad',               '1% del básico por cada año de servicio. Sin límite de acumulación.'],
                                        ['warning',   'ti-star',              'Presentismo',              'Adicional por asistencia perfecta mensual. Variable según establecimiento.'],
                                        ['danger',    'ti-map-pin',           'Zona desfavorable',        'Para trabajadores en áreas alejadas o de difícil acceso en la provincia.'],
                                        ['secondary', 'ti-clock',             'Horas extras / guardia',   'Horas adicionales y guardia activa se liquidan aparte según convenio.'],
                                    ] as $comp)
                                        <div class="col-md-6">
                                            <div class="d-flex gap-3 rounded-3 p-4 h-100"
                                                 style="background:#f8fafc; border:1px solid #e8edf2;">
                                                <span class="badge bg-{{ $comp[0] }}-subtle text-{{ $comp[0] }} rounded-2 p-2 align-self-start flex-shrink-0">
                                                    <i class="ti {{ $comp[1] }} fs-5"></i>
                                                </span>
                                                <div>
                                                    <strong class="fs-4 text-dark d-block mb-1">{{ $comp[2] }}</strong>
                                                    <p class="fs-3 text-muted mb-0">{{ $comp[3] }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card border-0 rounded-4 h-100 overflow-hidden shadow-sm">
                            <div class="px-6 py-5 d-flex align-items-center gap-3"
                                 style="background: linear-gradient(135deg, #ECF2FF, #dde8ff); border-bottom:1px solid #c5d8ff;">
                                <i class="ti ti-heart-rate-monitor text-primary fs-6"></i>
                                <h5 class="fw-bolder mb-0 text-primary">Carrera Sanitaria</h5>
                            </div>
                            <div class="card-body p-6">
                                <p class="fs-4 text-muted mb-4">
                                    La Carrera Sanitaria es un plus porcentual sobre el básico que ATSA viene
                                    conquistando paritaria a paritaria. El objetivo es llegar al 100%.
                                </p>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fs-3 text-muted">Alcanzado</span>
                                    <strong class="text-success fs-5">60%</strong>
                                </div>
                                <div class="rounded-pill overflow-hidden mb-3" style="height:12px;background:#e2f5ec;">
                                    <div style="width:60%;height:100%;background:#1D9E75;border-radius:999px;"></div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <span class="fs-3 text-muted">Objetivo final</span>
                                    <strong class="text-primary fs-4">100%</strong>
                                </div>
                                <div class="d-flex flex-column gap-1">
                                    @foreach ([
                                        ['2022', '44%'],
                                        ['2023', '50%'],
                                        ['2024', '52%'],
                                        ['2025', '52%'],
                                        ['2026', '60%'],
                                    ] as $cs)
                                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-light">
                                            <span class="fs-3 text-muted">{{ $cs[0] }}</span>
                                            <span class="badge bg-{{ $cs[0] === '2026' ? 'success' : 'primary' }}-subtle text-{{ $cs[0] === '2026' ? 'success' : 'primary' }} fw-bold px-3">{{ $cs[1] }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- HISTORIAL DE ACUERDOS --}}
            <div class="mb-8">
                <div class="text-center mb-7">
                    <p class="text-primary fs-4 fw-bolder mb-2">HISTORIAL</p>
                    <h2 class="fw-bolder fs-9 mb-0">Acuerdos paritarios 2022–2026</h2>
                </div>
                <div class="card border-0 rounded-4 shadow-sm overflow-hidden">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="font-size:14px;">
                            <thead style="background:#f0f4f8;">
                                <tr>
                                    <th class="px-5 py-4">Año</th>
                                    <th class="px-4 py-4">Período</th>
                                    <th class="px-4 py-4">Aumento salarial</th>
                                    <th class="px-4 py-4">Carrera Sanitaria</th>
                                    <th class="px-4 py-4">Logros destacados</th>
                                    <th class="px-4 py-4 text-center">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ([
                                    ['2026', 'Mar 2026 – Ene 2027', '11% sobre básico bonificable',             '60% (+8 puntos)', '500+ trabajadores a planta transitoria · Zona desfavorable actualizada · 100% CS para próximos a jubilarse', 'success', 'Vigente'],
                                    ['2025', 'Feb 2025 – Feb 2026', '10% en 3 etapas + revisión jun +5%',       '52% (sin cambio)', '100% CS para trabajadores a menos de 10 años de jubilarse · Pase planta transitoria', 'info', 'Histórico'],
                                    ['2024', 'Feb 2024 – Ene 2025', 'Actualizaciones trimestrales',             '52% (+2 puntos)', 'Incorporación de nuevos rubros a la base de cálculo', 'secondary', 'Histórico'],
                                    ['2023', 'Feb 2023 – Ene 2024', 'Aumentos bimestrales por inflación',       '50% (+6 puntos)', 'Suma fija $50.000 para categorías E y F · Activación cláusula gatillo', 'secondary', 'Histórico'],
                                    ['2022', 'Feb 2022 – Ene 2023', 'Pauta del 60% anual en tramos',            '44% (+4 puntos)', 'Incorporación de 300 trabajadores bajo convenio', 'secondary', 'Histórico'],
                                ] as $row)
                                    <tr>
                                        <td class="px-5 py-4 fw-bolder fs-5">{{ $row[0] }}</td>
                                        <td class="px-4 py-4 fs-3 text-muted">{{ $row[1] }}</td>
                                        <td class="px-4 py-4 fw-semibold">{{ $row[2] }}</td>
                                        <td class="px-4 py-4">
                                            <span class="badge bg-success-subtle text-success fw-bold px-3">{{ $row[3] }}</span>
                                        </td>
                                        <td class="px-4 py-4 fs-3 text-muted">{{ $row[4] }}</td>
                                        <td class="px-4 py-4 text-center">
                                            <span class="badge bg-{{ $row[5] }}-subtle text-{{ $row[5] }} rounded-pill px-3">{{ $row[6] }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- CTAs finales --}}
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="rounded-4 p-6 p-lg-8 h-100 d-flex flex-column flex-lg-row align-items-center justify-content-between gap-4"
                         style="background: linear-gradient(135deg, #1e3a5f 0%, #0d1f35 100%);">
                        <div class="text-white">
                            <h5 class="fw-bolder mb-1">¿Tenés dudas sobre tu recibo de sueldo?</h5>
                            <p class="mb-0 text-white text-opacity-75 fs-4">
                                Acercate a cualquier filial de ATSA o escribinos por WhatsApp.
                                La asesoría gremial es gratuita para todos los afiliados.
                            </p>
                        </div>
                        <a href="https://wa.me/543814331665" target="_blank" rel="noopener"
                           class="btn btn-success fw-bold py-7 px-8 flex-shrink-0">
                            <i class="ti ti-brand-whatsapp me-2"></i>Consultar por WhatsApp
                        </a>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card border-0 rounded-4 h-100 shadow-sm">
                        <div class="card-body p-5 d-flex flex-column justify-content-center">
                            <p class="fs-3 fw-bolder mb-2 text-muted">
                                <i class="ti ti-building-bank me-2 text-primary"></i>Fuente oficial
                            </p>
                            <p class="fs-4 text-muted mb-3">
                                Actas y comunicados del Ministerio de Salud Pública de Tucumán.
                            </p>
                            <a href="https://msptucuman.gov.ar/se-realizo-la-firma-de-acta-acuerdo-por-paritarias-en-salud-2026/"
                               target="_blank" rel="noopener"
                               class="btn btn-outline-primary fw-bold">
                                <i class="ti ti-external-link me-2"></i>Ver acta 2026
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

@endsection

@push('styles')
<style>
    .escala-card {
        box-shadow: 0 12px 32px rgba(42,53,71,.08);
        transition: transform .25s ease, box-shadow .25s ease;
    }
    .escala-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 20px 44px rgba(42,53,71,.13);
    }
</style>
@endpush
