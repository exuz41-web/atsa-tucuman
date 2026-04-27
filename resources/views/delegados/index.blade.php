@extends('layouts.app')

@section('title', 'Delegados | ATSA Tucumán')

@php use Illuminate\Support\Facades\Storage; @endphp

@push('styles')
<style>
    .delegado-card-inner {
        border-radius: 20px;
        background: #fff;
        border: 1px solid #e8eef6;
        box-shadow: 0 8px 24px rgba(30,58,95,.07);
        transition: transform .25s ease, box-shadow .25s ease;
        overflow: hidden;
    }
    .delegado-card-inner:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 40px rgba(30,58,95,.13);
    }
    .delegado-card-header {
        padding: 28px 24px 20px;
        text-align: center;
    }
    .delegado-avatar {
        width: 88px; height: 88px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #ecf2ff;
        box-shadow: 0 6px 16px rgba(30,58,95,.15);
        margin-bottom: 14px;
    }
    .delegado-card-body {
        padding: 0 24px 24px;
        text-align: center;
    }
    .delegado-filter-btn {
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
    .delegado-filter-btn:hover,
    .delegado-filter-btn.active {
        background: #1e3a5f;
        border-color: #1e3a5f;
        color: #fff;
    }

    .que-hace-item {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 16px;
        border-radius: 14px;
        background: #f8fbff;
        border: 1px solid #e8eef6;
        transition: border-color .2s;
    }
    .que-hace-item:hover { border-color: #49beff; }
    .que-hace-icon {
        width: 40px; height: 40px;
        border-radius: 10px;
        background: #ecf2ff;
        display: flex; align-items: center; justify-content: center;
        font-size: 19px; color: #1e3a5f; flex-shrink: 0;
    }
</style>
@endpush

@section('content')

    {{-- HERO --}}
    <section class="atsa-page-hero py-14 position-relative"
             style="background-image: url('{{ asset('images/historia/movilizacion-atsa-sanidad.jpg') }}'); min-height: 440px;">
        <div class="container-fluid position-relative z-1">
            <div class="col-lg-7">
                <span class="badge bg-white text-primary fs-3 fw-bolder px-3 py-2 mb-4">
                    <i class="ti ti-users me-1"></i>REPRESENTACIÓN
                </span>
                <h1 class="fw-bolder display-4 text-white mb-3">Delegados gremiales</h1>
                <p class="fs-5 text-white text-opacity-75 mb-0 col-lg-9">
                    Referentes gremiales en cada sector de trabajo. Los delegados de ATSA acompañan
                    al afiliado en clínicas, sanatorios, laboratorios y establecimientos de salud de toda Tucumán.
                </p>
            </div>
        </div>
    </section>

    {{-- QUÉ HACE UN DELEGADO --}}
    <section class="py-10 py-lg-14" style="background: #f4f7fb;">
        <div class="container-fluid">
            <div class="row g-6 align-items-center">
                <div class="col-lg-5">
                    <span style="font-size:11px;font-weight:800;letter-spacing:.12em;text-transform:uppercase;color:#49beff;">
                        ROL GREMIAL
                    </span>
                    <h2 class="fw-bolder mt-2 mb-3" style="font-family:'Outfit',sans-serif;font-size:clamp(1.6rem,3vw,2.2rem);">
                        ¿Qué hace un delegado de ATSA?
                    </h2>
                    <p class="text-body mb-5" style="color:#5a6a85;line-height:1.7;">
                        Los delegados son la voz del afiliado en el lugar de trabajo. Son elegidos por sus compañeros
                        y actúan como nexo directo entre los trabajadores y la conducción del sindicato.
                    </p>
                    <a href="{{ route('afiliacion.create') }}" class="btn btn-primary fw-bold py-8 px-9 rounded-3 me-2">
                        <i class="ti ti-user-plus me-2"></i>Afiliarme
                    </a>
                    <a href="{{ route('contacto.index') }}" class="btn btn-outline-primary fw-bold py-8 px-9 rounded-3">
                        <i class="ti ti-mail me-2"></i>Consultar
                    </a>
                </div>
                <div class="col-lg-7">
                    <div class="row g-3">
                        @foreach ([
                            ['ti-scale', 'Gestión de conflictos', 'Interviene en situaciones de conflicto laboral, despidos, sanciones y reclamos salariales junto a la asesoría legal de ATSA.'],
                            ['ti-headset', 'Orientación al afiliado', 'Asesora al trabajador sobre sus derechos, procedimientos gremiales y cómo acceder a los beneficios del sindicato.'],
                            ['ti-shield-check', 'Velar por las condiciones', 'Controla que se respeten las condiciones de trabajo, los convenios colectivos y las escalas salariales vigentes.'],
                            ['ti-users-group', 'Participación sindical', 'Representa a sus compañeros en asambleas, congresos y reuniones de la organización gremial.'],
                            ['ti-megaphone', 'Comunicación interna', 'Transmite las novedades del sindicato, comunica acuerdos paritarios y mantiene informado al sector.'],
                            ['ti-calendar-event', 'Acción social', 'Facilita el acceso a subsidios, pedidos de ayuda social y actividades recreativas de ATSA para la familia.'],
                        ] as [$icon, $title, $desc])
                            <div class="col-md-6">
                                <div class="que-hace-item">
                                    <div class="que-hace-icon"><i class="ti {{ $icon }}"></i></div>
                                    <div>
                                        <p style="font-size:13px;font-weight:700;color:#1e293b;margin-bottom:4px;">{{ $title }}</p>
                                        <p style="font-size:12px;color:#64748b;line-height:1.5;margin:0;">{{ $desc }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- GRILLA DE DELEGADOS --}}
    <section class="py-10 py-md-14">
        <div class="container-fluid">

            @if ($delegados->isNotEmpty())
                {{-- Filtros --}}
                <div class="d-flex flex-wrap gap-2 mb-8 align-items-center">
                    <span class="fw-bold text-muted me-2" style="font-size:13px;">Filtrar por filial:</span>
                    <button class="delegado-filter-btn active" data-filial="all">Todas</button>
                    @foreach ($filiales as $filial)
                        <button class="delegado-filter-btn" data-filial="{{ $filial->id }}">{{ $filial->name }}</button>
                    @endforeach
                </div>

                <div class="row" id="delegadosGrid">
                    @foreach ($delegados as $delegado)
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-6 delegado-card" data-filial="{{ $delegado->filial_id }}">
                            <div class="delegado-card-inner h-100">
                                <div class="delegado-card-header">
                                    <img src="{{ $delegado->foto ? Storage::disk('public')->url($delegado->foto) : 'https://ui-avatars.com/api/?name='.urlencode($delegado->nombre).'&size=160&background=1e3a5f&color=fff' }}"
                                         class="delegado-avatar" alt="{{ $delegado->nombre }}">
                                    <h3 class="fw-bolder mb-1" style="font-size:16px;">{{ $delegado->nombre }}</h3>
                                    <span class="badge px-3 py-2 fw-bold" style="background:#ecf2ff;color:#1e3a5f;font-size:12px;">
                                        {{ $delegado->sector }}
                                    </span>
                                </div>
                                <div class="delegado-card-body">
                                    <p class="text-muted mb-3" style="font-size:13px;">
                                        <i class="ti ti-map-pin me-1"></i>{{ $delegado->filial->name ?: 'Sin filial asignada' }}
                                    </p>
                                    @if ($delegado->telefono)
                                        <a href="https://wa.me/{{ preg_replace('/\D+/', '', $delegado->telefono) }}"
                                           target="_blank" rel="noopener"
                                           class="btn btn-success w-100 fw-bold rounded-3 py-6" style="font-size:13px;">
                                            <i class="ti ti-brand-whatsapp me-2"></i>{{ $delegado->telefono }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            @else
                {{-- Estado vacío mejorado --}}
                <div class="row g-5 align-items-center mb-12">
                    <div class="col-lg-6">
                        <span style="font-size:11px;font-weight:800;letter-spacing:.12em;text-transform:uppercase;color:#1e3a5f;">
                            DIRECTORIO EN ACTUALIZACIÓN
                        </span>
                        <h2 class="fw-bolder mt-2 mb-3" style="font-family:'Outfit',sans-serif;font-size:clamp(1.5rem,3vw,2rem);">
                            El listado de delegados se está actualizando
                        </h2>
                        <p class="text-body mb-5" style="color:#5a6a85;line-height:1.7;">
                            Estamos incorporando el directorio completo de delegados de todas las filiales y sectores.
                            Mientras tanto, podés contactar directamente a la sede central o a tu filial más cercana
                            para recibir orientación gremial.
                        </p>
                        <div class="d-flex gap-3 flex-wrap">
                            <a href="https://wa.me/543814331665" target="_blank" rel="noopener"
                               class="btn btn-success fw-bold py-8 px-9 rounded-3">
                                <i class="ti ti-brand-whatsapp me-2"></i>Consultar por WhatsApp
                            </a>
                            <a href="{{ route('contacto.index') }}" class="btn btn-outline-primary fw-bold py-8 px-9 rounded-3">
                                <i class="ti ti-phone me-2"></i>Contactar ATSA
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="row g-3">
                            @foreach ([
                                ['0381 4331665', 'Sede Central', 'Paraguay y Thames, San Miguel de Tucumán', 'ti-building'],
                                ['03865 421030', 'Filial del Sur', 'J. A. Roca 371, Concepción', 'ti-map-pin'],
                                ['381 5677170', 'Filial Este', 'Cam. del Carmen 90, Banda del Río Salí', 'ti-map-pin'],
                            ] as [$tel, $nombre, $dir, $icon])
                                <div class="col-12">
                                    <div class="d-flex align-items-start gap-3 p-4 rounded-3 bg-light border">
                                        <div style="width:42px;height:42px;border-radius:10px;background:#ecf2ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                            <i class="ti {{ $icon }}" style="color:#1e3a5f;font-size:19px;"></i>
                                        </div>
                                        <div>
                                            <p style="font-size:14px;font-weight:700;color:#1e293b;margin-bottom:2px;">{{ $nombre }}</p>
                                            <p style="font-size:13px;color:#64748b;margin-bottom:0;">{{ $dir }}</p>
                                            @if ($tel)
                                                <a href="tel:{{ preg_replace('/\D+/', '', $tel) }}"
                                                   style="font-size:13px;font-weight:700;color:#1e3a5f;text-decoration:none;">
                                                    <i class="ti ti-phone me-1"></i>{{ $tel }}
                                                </a>
                                            @endif
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

    {{-- CTA: ¿Querés ser delegado? --}}
    <section class="py-10 py-lg-14" style="background: #f4f7fb;">
        <div class="container-fluid">
            <div class="rounded-4 overflow-hidden" style="background: linear-gradient(135deg, #1e3a5f 0%, #0f2236 100%);">
                <div class="row g-0 align-items-center">
                    <div class="col-lg-8 p-7 p-lg-10">
                        <span style="font-size:11px;font-weight:800;letter-spacing:.1em;color:#49beff;text-transform:uppercase;">
                            PARTICIPACIÓN GREMIAL
                        </span>
                        <h2 class="fw-bolder text-white mt-2 mb-3" style="font-family:'Outfit',sans-serif;font-size:clamp(1.5rem,3vw,2rem);">
                            ¿Querés ser delegado en tu sector?
                        </h2>
                        <p class="mb-5" style="color:rgba(255,255,255,.72);font-size:15px;line-height:1.65;">
                            Los delegados son elegidos por sus compañeros y representan al sector ante el sindicato.
                            Si te interesa participar activamente en la vida gremial de ATSA Tucumán, contactanos para
                            conocer los requisitos y el proceso.
                        </p>
                        <div class="d-flex gap-3 flex-wrap">
                            <a href="{{ route('contacto.index') }}" class="btn btn-light fw-bold py-8 px-10" style="color:#1e3a5f;border-radius:12px;">
                                <i class="ti ti-mail me-2"></i>Solicitar información
                            </a>
                            <a href="https://wa.me/543814331665" target="_blank" rel="noopener" class="btn btn-success fw-bold py-8 px-10" style="border-radius:12px;">
                                <i class="ti ti-brand-whatsapp me-2"></i>WhatsApp
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-4 d-none d-lg-flex align-items-center justify-content-center p-10">
                        <div style="width:120px;height:120px;border-radius:28px;background:rgba(73,190,255,.12);border:2px solid rgba(73,190,255,.2);display:flex;align-items:center;justify-content:center;">
                            <i class="ti ti-user-star" style="font-size:56px;color:#49beff;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.delegado-filter-btn').forEach(function (button) {
        button.addEventListener('click', function () {
            document.querySelectorAll('.delegado-filter-btn').forEach(b => b.classList.remove('active'));
            button.classList.add('active');
            const filial = button.dataset.filial;
            document.querySelectorAll('.delegado-card').forEach(function (card) {
                card.style.display = filial === 'all' || card.dataset.filial === filial ? '' : 'none';
            });
        });
    });
});
</script>
@endpush
