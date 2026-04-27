@extends('layouts.cent-public')

@section('title', 'Novedades — CENT N°74')
@section('meta_description', 'Novedades institucionales del CENT N°74: avisos para alumnos, docentes, mesas de examen, inscripciones y comunicados.')

@push('styles')
<style>
    [data-aos] { opacity: 0; transform: translateY(24px); transition: opacity .5s ease, transform .5s ease; }
    [data-aos].aos-animate { opacity: 1; transform: none; }
    .novedad-img { height: 180px; object-fit: cover; width: 100%; }
</style>
@endpush

@section('content')

{{-- Hero --}}
<section class="py-5 bg-light">
    <div class="container py-lg-4">
        <div class="row align-items-end g-4">
            <div class="col-lg-8" data-aos>
                <span class="section-badge bg-primary-subtle text-primary"><i class="ti ti-news me-1"></i>Novedades institucionales</span>
                <h1 class="display-5 fw-bolder mb-2">Novedades del CENT N°74</h1>
                <p class="fs-5 text-muted mb-0">Avisos, fechas importantes, mesas de examen, inscripciones y comunicados para alumnos, docentes y la comunidad.</p>
            </div>
            <div class="col-lg-4" data-aos>
                <div class="card cent-card">
                    <div class="card-body p-4">
                        <div class="d-flex gap-3 align-items-center">
                            <span class="feature-icon bg-info-subtle text-info flex-shrink-0"><i class="ti ti-bell"></i></span>
                            <div>
                                <strong class="d-block">Portal académico</strong>
                                <p class="text-muted mb-0" style="font-size:13px;">Para alumnos: notas, asistencia y avisos personalizados.</p>
                                <a href="{{ route('cent.login') }}" class="fw-bold text-primary small">Ingresar →</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Destacadas --}}
@if($destacadas->isNotEmpty())
<section class="py-5 border-bottom">
    <div class="container">
        <h2 class="h5 fw-bold mb-4 d-flex align-items-center gap-2">
            <span class="rounded-circle bg-warning-subtle text-warning d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;"><i class="ti ti-star"></i></span>
            Destacadas
        </h2>
        <div class="row g-4">
            @foreach($destacadas as $nov)
            @php $cfg = $nov->tipo_config; @endphp
            <div class="col-md-6 col-lg-4" data-aos>
                <a href="{{ route('cent.novedades.show', $nov) }}" class="text-decoration-none">
                    <article class="card cent-card h-100 border-2" style="border: 2px solid var(--bs-{{ $cfg['color'] }}-border-subtle) !important;">
                        @if($nov->imagen_url)
                            <img src="{{ $nov->imagen_url }}" alt="{{ $nov->titulo }}" class="novedad-img rounded-top-4">
                        @else
                            <div class="novedad-img rounded-top-4 d-flex align-items-center justify-content-center bg-{{ $cfg['color'] }}-subtle">
                                <i class="ti {{ $cfg['icon'] }} text-{{ $cfg['color'] }}" style="font-size:52px;opacity:.6;"></i>
                            </div>
                        @endif
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="badge bg-{{ $cfg['color'] }}-subtle text-{{ $cfg['color'] }} rounded-pill">
                                    <i class="ti {{ $cfg['icon'] }} me-1"></i>{{ $cfg['label'] }}
                                </span>
                                <span class="badge bg-warning text-dark rounded-pill"><i class="ti ti-star me-1"></i>Destacado</span>
                            </div>
                            <h3 class="fw-bold mb-2" style="font-size:16px;">{{ $nov->titulo }}</h3>
                            <p class="text-muted flex-grow-1 mb-3" style="font-size:13.5px;line-height:1.6;">{{ Str::limit($nov->contenido, 140) }}</p>
                            <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-auto">
                                <small class="text-muted">
                                    <i class="ti ti-calendar me-1"></i>{{ $nov->created_at->format('d/m/Y') }}
                                </small>
                                <small class="text-primary fw-semibold">Leer más →</small>
                            </div>
                        </div>
                    </article>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Todas las novedades --}}
<section class="py-5 py-lg-10">
    <div class="container">
        @if($novedades->isEmpty())
            <div class="text-center py-5" data-aos>
                <div class="feature-icon bg-light text-muted mx-auto mb-4"><i class="ti ti-inbox"></i></div>
                <h2 class="fw-bold mb-2">No hay novedades publicadas</h2>
                <p class="text-muted mb-4">Cuando la institución publique avisos o comunicados van a aparecer acá.</p>
                <a href="{{ route('cent.login') }}" class="btn btn-cent">
                    <i class="ti ti-login me-2"></i>Acceder al portal académico
                </a>
            </div>
        @else
            <div class="row g-4">
                @foreach($novedades as $nov)
                @php $cfg = $nov->tipo_config; @endphp
                <div class="col-md-6 col-lg-4" data-aos>
                    <a href="{{ route('cent.novedades.show', $nov) }}" class="text-decoration-none">
                        <article class="card cent-card h-100 {{ $nov->destacado ? 'border border-warning border-2' : '' }}">
                            @if($nov->imagen_url)
                                <img src="{{ $nov->imagen_url }}" alt="{{ $nov->titulo }}" class="novedad-img rounded-top-4">
                            @else
                                <div class="novedad-img rounded-top-4 d-flex align-items-center justify-content-center" style="background: var(--cent-soft);">
                                    <i class="ti {{ $cfg['icon'] }} text-{{ $cfg['color'] }}" style="font-size:48px;opacity:.5;"></i>
                                </div>
                            @endif
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="badge bg-{{ $cfg['color'] }}-subtle text-{{ $cfg['color'] }} rounded-pill" style="font-size:11px;">
                                        <i class="ti {{ $cfg['icon'] }} me-1"></i>{{ $cfg['label'] }}
                                    </span>
                                    @if($nov->destacado)
                                        <span class="badge bg-warning text-dark rounded-pill" style="font-size:10px;"><i class="ti ti-star me-1"></i>Destacado</span>
                                    @endif
                                </div>
                                <h3 class="fw-bold mb-2" style="font-size:15px;">{{ $nov->titulo }}</h3>
                                <p class="text-muted flex-grow-1 mb-3" style="font-size:13.5px;line-height:1.6;">
                                    {{ Str::limit($nov->contenido, 180) }}
                                </p>
                                <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-auto">
                                    <small class="text-muted">
                                        <i class="ti ti-calendar me-1"></i>{{ $nov->created_at->format('d/m/Y') }}
                                    </small>
                                    <small class="text-primary fw-semibold">Leer más →</small>
                                </div>
                            </div>
                        </article>
                    </a>
                </div>
                @endforeach
            </div>

            {{-- Paginación --}}
            @if($novedades->hasPages())
            <div class="d-flex justify-content-center mt-5">
                {{ $novedades->links('pagination::bootstrap-5') }}
            </div>
            @endif
        @endif
    </div>
</section>

{{-- CTA portal --}}
<section class="py-5 bg-light border-top">
    <div class="container">
        <div class="card cent-card">
            <div class="card-body p-4 p-lg-5">
                <div class="row align-items-center g-4">
                    <div class="col-lg-8">
                        <h3 class="fw-bold">¿Sos alumno o docente del CENT?</h3>
                        <p class="text-muted mb-0">En el portal académico encontrás notas, asistencia, comisiones y avisos personalizados para tu carrera y sede.</p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <a href="{{ route('cent.login') }}" class="btn btn-cent btn-lg">
                            <i class="ti ti-login me-2"></i>Acceder al portal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    (function () {
        const els = document.querySelectorAll('[data-aos]');
        if (!els.length) return;
        const io = new IntersectionObserver((entries) => {
            entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('aos-animate'); io.unobserve(e.target); } });
        }, { threshold: 0.1 });
        els.forEach(el => io.observe(el));
    })();
</script>
@endpush
