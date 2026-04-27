@extends('layouts.app')

@section('title', 'Documentos institucionales | ATSA Tucumán')

@php
    use Illuminate\Support\Facades\Storage;
    $labels = [
        'estatuto'   => 'Estatuto',
        'balance'    => 'Balance',
        'reglamento' => 'Reglamento',
        'acta'       => 'Acta',
        'convenio'   => 'Convenio',
        'otro'       => 'Otro',
    ];
    $labelIcons = [
        'estatuto'   => 'ti-book',
        'balance'    => 'ti-calculator',
        'reglamento' => 'ti-clipboard-list',
        'acta'       => 'ti-writing',
        'convenio'   => 'ti-handshake',
        'otro'       => 'ti-paperclip',
    ];
@endphp

@push('styles')
<style>
    .doc-filter-btn {
        padding: 8px 20px;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 700;
        border: 1.5px solid #dce4ef;
        background: #fff;
        color: #5a6a85;
        cursor: pointer;
        transition: all .2s ease;
    }
    .doc-filter-btn:hover, .doc-filter-btn.active {
        background: #1e3a5f;
        border-color: #1e3a5f;
        color: #fff;
    }
    .doc-card {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 18px 20px;
        border-radius: 14px;
        background: #fff;
        border: 1px solid #e8eef6;
        box-shadow: 0 4px 12px rgba(30,58,95,.05);
        transition: border-color .2s, box-shadow .2s;
    }
    .doc-card:hover {
        border-color: #49beff;
        box-shadow: 0 8px 24px rgba(30,58,95,.10);
    }
    .doc-icon {
        width: 50px; height: 50px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }
    .doc-icon-pdf   { background: #fff0f0; color: #c0392b; }
    .doc-icon-other { background: #ecf2ff; color: #1e3a5f; }
    .doc-meta { flex: 1; min-width: 0; }
    .doc-title { font-size: 15px; font-weight: 700; color: #1e293b; margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .doc-badges { display: flex; flex-wrap: wrap; gap: 6px; }

    .transparency-item {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 16px;
        border-radius: 14px;
        background: #f8fbff;
        border: 1px solid #e8eef6;
    }
    .transparency-icon {
        width: 38px; height: 38px;
        border-radius: 10px;
        background: #ecf2ff;
        color: #1e3a5f;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px; flex-shrink: 0;
    }
</style>
@endpush

@section('content')

    {{-- HERO --}}
    <section class="atsa-page-hero py-14 position-relative"
             style="background-image: url('{{ asset('images/historia/ciudad-deportiva-atsa.jpg') }}'); min-height: 400px;">
        <div class="container-fluid position-relative z-1">
            <div class="col-lg-7">
                <span class="badge bg-white text-primary fs-3 fw-bolder px-3 py-2 mb-4">
                    <i class="ti ti-file-text me-1"></i>TRANSPARENCIA INSTITUCIONAL
                </span>
                <h1 class="fw-bolder display-4 text-white mb-3">Documentos institucionales</h1>
                <p class="fs-5 text-white text-opacity-75 mb-0 col-lg-9">
                    Estatutos, convenios colectivos, balances, reglamentos y actas de ATSA Tucumán
                    disponibles para todos los afiliados y el público en general.
                </p>
            </div>
        </div>
    </section>

    {{-- FRANJA DE VALORES: Transparencia --}}
    <section class="py-8" style="background: linear-gradient(135deg, #1e3a5f 0%, #163050 100%);">
        <div class="container-fluid">
            <div class="row g-4 align-items-center">
                <div class="col-lg-5">
                    <span style="font-size:11px;font-weight:800;letter-spacing:.12em;color:#49beff;text-transform:uppercase;">
                        NUESTRO COMPROMISO
                    </span>
                    <h2 class="fw-bolder text-white mt-2 mb-2" style="font-family:'Outfit',sans-serif;font-size:clamp(1.4rem,2.5vw,1.9rem);">
                        Gestión transparente y abierta
                    </h2>
                    <p style="color:rgba(255,255,255,.68);font-size:15px;line-height:1.6;margin:0;">
                        ATSA Tucumán pone a disposición sus documentos institucionales como parte del compromiso
                        con la transparencia gremial y el acceso a la información de todos los afiliados.
                    </p>
                </div>
                <div class="col-lg-7">
                    <div class="row g-3">
                        @foreach ([
                            ['ti-book', 'Estatuto social', 'El documento fundacional que rige el funcionamiento del sindicato y los derechos de los afiliados.'],
                            ['ti-calculator', 'Balances y rendiciones', 'Rendición de cuentas periódica para que los afiliados conozcan el estado patrimonial del sindicato.'],
                            ['ti-handshake', 'Convenios colectivos', 'Acuerdos paritarios y convenios que regulan las condiciones de trabajo del sector sanidad.'],
                            ['ti-clipboard-list', 'Reglamentos internos', 'Normativas que regulan el funcionamiento de las comisiones, delegados y órganos del sindicato.'],
                        ] as [$icon, $title, $desc])
                            <div class="col-md-6">
                                <div class="d-flex align-items-start gap-3 p-3 rounded-3" style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.08);">
                                    <div style="width:38px;height:38px;border-radius:9px;background:rgba(73,190,255,.15);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i class="ti {{ $icon }}" style="font-size:18px;color:#49beff;"></i>
                                    </div>
                                    <div>
                                        <p style="font-size:13px;font-weight:700;color:#fff;margin-bottom:3px;">{{ $title }}</p>
                                        <p style="font-size:12px;color:rgba(255,255,255,.55);margin:0;line-height:1.5;">{{ $desc }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- LISTADO DE DOCUMENTOS --}}
    <section class="py-10 py-md-14" style="background: #f4f7fb;">
        <div class="container-fluid">

            @if ($documentos->isNotEmpty())

                {{-- Filtros --}}
                <div class="d-flex flex-wrap gap-2 mb-7 align-items-center">
                    <span class="fw-bold text-muted me-2" style="font-size:13px;">Tipo:</span>
                    <button class="doc-filter-btn active" data-tipo="all">Todos</button>
                    @foreach ($labels as $key => $label)
                        <button class="doc-filter-btn" data-tipo="{{ $key }}">{{ $label }}</button>
                    @endforeach
                </div>

                <div class="row g-4">
                    @foreach ($documentos as $documento)
                        <div class="col-lg-6 documento-card" data-tipo="{{ $documento->tipo }}">
                            <div class="doc-card">
                                <div class="doc-icon doc-icon-pdf">
                                    <i class="ti ti-file-type-pdf"></i>
                                </div>
                                <div class="doc-meta">
                                    <p class="doc-title">{{ $documento->titulo }}</p>
                                    <div class="doc-badges">
                                        <span class="badge px-2 py-1 fw-bold" style="background:#ecf2ff;color:#1e3a5f;font-size:11px;">
                                            <i class="ti {{ $labelIcons[$documento->tipo] ?? 'ti-file' }} me-1"></i>
                                            {{ $labels[$documento->tipo] ?? ucfirst($documento->tipo) }}
                                        </span>
                                        @if ($documento->anio)
                                            <span class="badge bg-light text-muted px-2 py-1 fw-semibold" style="font-size:11px;">
                                                {{ $documento->anio }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ Storage::disk('public')->url($documento->archivo) }}"
                                   target="_blank"
                                   class="btn btn-outline-primary fw-bold rounded-3 flex-shrink-0"
                                   style="font-size:13px;padding:8px 18px;">
                                    <i class="ti ti-download me-1"></i>Descargar
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

            @else

                {{-- Estado vacío enriquecido --}}
                <div class="row g-5 align-items-center mb-10">
                    <div class="col-lg-5">
                        <div class="rounded-4 overflow-hidden" style="height: 320px;">
                            <img src="{{ asset('images/historia/ciudad-deportiva-atsa.jpg') }}"
                                 class="w-100 h-100 object-fit-cover" alt="ATSA Tucumán">
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <span style="font-size:11px;font-weight:800;letter-spacing:.12em;text-transform:uppercase;color:#49beff;">
                            EN PREPARACIÓN
                        </span>
                        <h2 class="fw-bolder mt-2 mb-3" style="font-family:'Outfit',sans-serif;font-size:clamp(1.5rem,3vw,2rem);">
                            Los documentos estarán disponibles próximamente
                        </h2>
                        <p class="text-body mb-5" style="color:#5a6a85;line-height:1.7;">
                            El equipo de ATSA está digitalizando y cargando los documentos institucionales
                            para que los afiliados y el público general puedan acceder de manera directa.
                            Incluirá el estatuto social actualizado, balances anuales, convenios colectivos
                            y acuerdos paritarios vigentes.
                        </p>
                        <div class="d-flex gap-3 flex-wrap">
                            <a href="{{ route('contacto.index') }}" class="btn btn-primary fw-bold py-8 px-9 rounded-3">
                                <i class="ti ti-mail me-2"></i>Solicitar documentos
                            </a>
                            <a href="https://wa.me/543814331665" target="_blank" rel="noopener"
                               class="btn btn-success fw-bold py-8 px-9 rounded-3">
                                <i class="ti ti-brand-whatsapp me-2"></i>WhatsApp
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Qué encontrarás próximamente --}}
                <div class="card rounded-4 border-0 shadow-sm">
                    <div class="card-body p-5 p-lg-7">
                        <h3 class="fw-bolder fs-6 mb-5">
                            <i class="ti ti-clock me-2 text-primary"></i>¿Qué documentos se publicarán?
                        </h3>
                        <div class="row g-4">
                            @foreach ([
                                ['ti-book', 'Estatuto social', 'El documento fundacional que establece los objetivos, estructura y funcionamiento de ATSA Tucumán, aprobado por la Secretaría de Trabajo.'],
                                ['ti-calculator', 'Balances anuales', 'Rendición de cuentas anual auditada, con el detalle del patrimonio, ingresos y egresos del sindicato para conocimiento de los afiliados.'],
                                ['ti-handshake', 'Convenio Colectivo de Trabajo', 'El CCT que regula las condiciones de trabajo, categorías, escalas salariales y derechos del sector sanidad en todo el país.'],
                                ['ti-writing', 'Actas de asamblea', 'Registros de las decisiones tomadas en asambleas generales de delegados y congresales de ATSA Tucumán.'],
                                ['ti-clipboard-list', 'Reglamentos internos', 'Normativas que regulan el funcionamiento de elecciones, subsidios, turismo y demás servicios del sindicato.'],
                                ['ti-file-certificate', 'Acuerdos paritarios', 'Documentos firmados en las negociaciones salariales colectivas entre FATSA, cámaras empleadoras y el Ministerio de Trabajo.'],
                            ] as [$icon, $title, $desc])
                                <div class="col-md-6 col-lg-4">
                                    <div class="transparency-item h-100">
                                        <div class="transparency-icon"><i class="ti {{ $icon }}"></i></div>
                                        <div>
                                            <p style="font-size:14px;font-weight:700;color:#1e293b;margin-bottom:4px;">{{ $title }}</p>
                                            <p style="font-size:13px;color:#64748b;line-height:1.5;margin:0;">{{ $desc }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            @endif

        </div>
    </section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.doc-filter-btn').forEach(function (button) {
        button.addEventListener('click', function () {
            document.querySelectorAll('.doc-filter-btn').forEach(b => b.classList.remove('active'));
            button.classList.add('active');
            const tipo = button.dataset.tipo;
            document.querySelectorAll('.documento-card').forEach(function (card) {
                card.style.display = tipo === 'all' || card.dataset.tipo === tipo ? '' : 'none';
            });
        });
    });
});
</script>
@endpush
