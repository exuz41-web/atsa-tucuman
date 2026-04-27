@extends('layouts.afiliado')

@section('title', 'Beneficios')
@section('page_title', 'Beneficios')

@php
    use App\Models\Beneficio;

    $categoriaLabels = Beneficio::categorias();
    $categoriaColors = [
        'gremial' => '#1e3a5f',
        'accion_social' => '#e46a76',
        'turismo' => '#49beff',
        'formacion' => '#13deb9',
        'convenios' => '#ffae1f',
        'tramites' => '#5d87ff',
        'salud' => '#13deb9',
    ];

    $items = ($beneficios ?: collect());
    $destacados = ($beneficiosDestacados ?: collect())->count() ? $beneficiosDestacados : $items->take(4);
    $porCategoria = ($beneficiosPorCategoria ?: collect())->count() ? $beneficiosPorCategoria : $items->groupBy('categoria');
@endphp

@section('content')
    <div class="portal-card overflow-hidden mb-4 portal-benefits-hero">
        <div class="row align-items-center g-0">
            <div class="col-lg-8">
                <div class="p-4 p-lg-5">
                    <p class="text-primary fw-semibold fs-3 mb-1">BENEFICIOS PARA AFILIADOS</p>
                    <h2 class="fw-bolder mb-2">Una red de respaldo para vos y tu familia</h2>
                    <p class="text-muted mb-4">
                        Consultá tus beneficios vigentes, requisitos, documentación y accesos rápidos para solicitarlos desde el portal.
                    </p>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('afiliados.pedidos.nuevo') }}" class="btn btn-primary px-4 py-3 shadow-none">
                            <i class="ti ti-plus me-2"></i>Nueva solicitud
                        </a>
                        <a href="{{ route('afiliados.consultas') }}" class="btn btn-outline-primary px-4 py-3 shadow-none">
                            <i class="ti ti-message me-2"></i>Consultar beneficio
                        </a>
                        <a href="{{ route('turismo.index') }}" class="btn btn-light-primary px-4 py-3 shadow-none">
                            <i class="ti ti-beach me-2"></i>Turismo
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 d-none d-lg-block">
                <img src="{{ asset('images/turismo/ciudad-deportiva/pileta-familia.jpeg') }}" class="w-100 h-100 object-fit-cover" style="min-height: 260px;" alt="Beneficios ATSA">
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        @forelse ($destacados as $beneficio)
            @php
                $color = $categoriaColors[$beneficio->categoria] ?? '#1e3a5f';
                $link = route('afiliados.beneficios.solicitar', $beneficio);
            @endphp
            <div class="col-md-6 col-xl-3">
                <div class="portal-card p-4 h-100 portal-benefit-card">
                    <span class="portal-benefit-icon" style="--benefit-color: {{ $color }};">
                        <i class="ti {{ $beneficio->icono ?: 'ti-gift' }}"></i>
                    </span>
                    <span class="portal-benefit-label" style="--benefit-color: {{ $color }};">
                        {{ $categoriaLabels[$beneficio->categoria] ?? $beneficio->categoria }}
                    </span>
                    <h5 class="fw-bolder mt-3 mb-2">{{ $beneficio->titulo }}</h5>
                    <p class="text-muted mb-4">{{ $beneficio->descripcion_corta }}</p>
                    <a href="{{ $link }}" class="fw-bold text-primary">
                        Solicitar <i class="ti ti-arrow-right"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="portal-card p-4">
                    <p class="mb-0 text-muted">Todavía no hay beneficios cargados.</p>
                </div>
            </div>
        @endforelse
    </div>

    <div class="row g-4">
        <div class="col-xl-8">
            <div class="portal-card p-4 p-lg-5">
                <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap mb-4">
                    <div>
                        <p class="text-primary fw-semibold fs-3 mb-1">CATÁLOGO</p>
                        <h4 class="fw-bolder mb-0">Beneficios por categoría</h4>
                    </div>
                    <span class="badge bg-primary-subtle text-primary px-3 py-2">{{ $items->count() }} beneficios activos</span>
                </div>

                @if (($solicitudesBeneficios ?: collect())->count())
                    <div class="alert alert-primary rounded-3 border-0 mb-4">
                        <strong>Últimas solicitudes:</strong>
                        {{ $solicitudesBeneficios->map(fn ($solicitud) => $solicitud->beneficio?->titulo.' - '.(\App\Models\SolicitudBeneficio::estados()[$solicitud->estado] ?? $solicitud->estado).($solicitud->observacion_afiliado ? ' ('.$solicitud->observacion_afiliado.')' : ''))->join(' | ') }}
                    </div>
                @endif

                <div class="accordion portal-benefits-accordion" id="beneficiosAccordion">
                    @foreach ($porCategoria as $categoria => $grupo)
                        @php
                            $color = $categoriaColors[$categoria] ?? '#1e3a5f';
                            $collapseId = 'beneficio-'.$loop->index;
                        @endphp
                        <div class="accordion-item border-0 mb-3 rounded-3 overflow-hidden">
                            <h2 class="accordion-header">
                                <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }} fw-bolder" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}">
                                    <span class="category-dot me-3" style="background: {{ $color }};"></span>
                                    {{ $categoriaLabels[$categoria] ?? ucfirst($categoria) }}
                                </button>
                            </h2>
                            <div id="{{ $collapseId }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" data-bs-parent="#beneficiosAccordion">
                                <div class="accordion-body bg-white">
                                    <div class="d-flex flex-column gap-3">
                                        @foreach ($grupo as $beneficio)
                                            <div class="portal-benefit-row">
                                                <span class="portal-benefit-row-icon" style="--benefit-color: {{ $color }};">
                                                    <i class="ti {{ $beneficio->icono ?: 'ti-gift' }}"></i>
                                                </span>
                                                <div class="flex-grow-1">
                                                    <h5 class="fw-bolder mb-1">{{ $beneficio->titulo }}</h5>
                                                    <p class="text-muted mb-2">{{ $beneficio->descripcion_corta }}</p>
                                                    @if ($beneficio->requisitos)
                                                        <p class="fs-3 mb-1"><strong>Requisitos:</strong> {{ $beneficio->requisitos }}</p>
                                                    @endif
                                                    @if ($beneficio->documentacion)
                                                        <p class="fs-3 mb-0"><strong>Documentación:</strong> {{ $beneficio->documentacion }}</p>
                                                    @endif
                                                </div>
                                                <div class="text-end">
                                                    <a href="{{ route('afiliados.beneficios.solicitar', $beneficio) }}" class="btn btn-sm btn-outline-primary">Solicitar</a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="portal-card p-4 mb-4">
                <h4 class="fw-bolder mb-3">Cómo solicitar un beneficio</h4>
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex gap-3">
                        <span class="step-mini">1</span>
                        <p class="mb-0 text-muted">Elegí el beneficio y revisá requisitos.</p>
                    </div>
                    <div class="d-flex gap-3">
                        <span class="step-mini">2</span>
                        <p class="mb-0 text-muted">Prepará DNI, recibo y documentación respaldatoria.</p>
                    </div>
                    <div class="d-flex gap-3">
                        <span class="step-mini">3</span>
                        <p class="mb-0 text-muted">Cargá una solicitud o enviá una consulta desde el portal.</p>
                    </div>
                </div>
            </div>

            <div class="portal-card p-4">
                <h4 class="fw-bolder mb-3">Accesos rápidos</h4>
                <div class="d-grid gap-2">
                    <a href="{{ route('afiliados.pedidos.nuevo') }}" class="btn btn-primary shadow-none">
                        <i class="ti ti-file-plus me-2"></i>Nueva solicitud
                    </a>
                    <a href="{{ route('afiliados.descargas') }}" class="btn btn-outline-primary shadow-none">
                        <i class="ti ti-download me-2"></i>Descargas
                    </a>
                    <a href="{{ route('afiliado.carnet') }}" class="btn btn-outline-primary shadow-none">
                        <i class="ti ti-id me-2"></i>Mi carnet
                    </a>
                    <a href="{{ route('turismo.index') }}" class="btn btn-light-primary shadow-none">
                        <i class="ti ti-beach me-2"></i>Turismo y recreación
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .portal-benefits-hero {
        border: 1px solid rgba(30, 58, 95, .08);
    }
    .portal-benefit-card {
        transition: transform .25s ease, box-shadow .25s ease;
    }
    .portal-benefit-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 46px rgba(42, 53, 71, .12);
    }
    .portal-benefit-icon,
    .portal-benefit-row-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: color-mix(in srgb, var(--benefit-color) 14%, white);
        color: var(--benefit-color);
    }
    .portal-benefit-icon {
        width: 54px;
        height: 54px;
        font-size: 28px;
    }
    .portal-benefit-label {
        display: inline-flex;
        margin-left: 8px;
        border-radius: 999px;
        padding: 6px 11px;
        background: color-mix(in srgb, var(--benefit-color) 10%, white);
        color: var(--benefit-color);
        font-size: 12px;
        font-weight: 800;
    }
    .category-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-flex;
    }
    .portal-benefit-row {
        display: flex;
        gap: 14px;
        align-items: flex-start;
        padding: 16px;
        border-radius: 8px;
        border: 1px solid rgba(30, 58, 95, .08);
    }
    .portal-benefit-row-icon {
        width: 42px;
        height: 42px;
        flex: 0 0 auto;
        font-size: 22px;
    }
    .portal-benefits-accordion .accordion-button {
        box-shadow: none;
        background: #f6f8fb;
    }
    .step-mini {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: #1e3a5f;
        color: #fff;
        font-weight: 800;
        flex: 0 0 auto;
    }
</style>
@endpush
