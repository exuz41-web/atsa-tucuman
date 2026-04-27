@extends('layouts.cent-public')

@section('title', $aviso->titulo.' — CENT N°74')
@section('meta_description', Str::limit(strip_tags($aviso->contenido), 160))

@push('styles')
<style>
    [data-aos] { opacity: 0; transform: translateY(24px); transition: opacity .5s ease, transform .5s ease; }
    [data-aos].aos-animate { opacity: 1; transform: none; }
    .gallery-item { cursor: pointer; position: relative; overflow: hidden; border-radius: 8px; aspect-ratio: 1; }
    .gallery-item img { width: 100%; height: 100%; object-fit: cover; transition: transform .3s ease; }
    .gallery-item:hover img { transform: scale(1.05); }
    .lightbox { display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, .9); animation: fadeIn .3s ease; }
    .lightbox.active { display: flex; align-items: center; justify-content: center; }
    .lightbox-content { position: relative; max-width: 90%; max-height: 90vh; }
    .lightbox-img { max-width: 100%; max-height: 85vh; object-fit: contain; }
    .lightbox-close { position: absolute; top: 20px; right: 20px; color: white; font-size: 32px; cursor: pointer; background: rgba(0, 0, 0, .5); border: none; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
    .lightbox-close:hover { background: rgba(0, 0, 0, .8); }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    .article-body { line-height: 1.8; }
    .article-body h2, .article-body h3 { margin-top: 1.5em; margin-bottom: .5em; font-weight: 700; }
    .article-body p { margin-bottom: 1em; }
    .article-body ul, .article-body ol { margin-left: 2em; margin-bottom: 1em; }
    .article-body li { margin-bottom: .5em; }
    .aviso-metadata { background: var(--cent-soft); padding: 1.5rem; border-radius: 8px; }
    .aviso-sidebar { position: sticky; top: 20px; }
    .related-card { transition: all .3s ease; }
    .related-card:hover { box-shadow: 0 8px 16px rgba(0, 0, 0, .1); transform: translateY(-4px); }
</style>
@endpush

@section('content')

{{-- Hero Section --}}
<section class="py-5 py-lg-8">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8" data-aos>
                {{-- Breadcrumb & Metadata --}}
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb" style="font-size: 13px;">
                        <li class="breadcrumb-item"><a href="{{ route('cent.index') }}">CENT N°74</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('cent.novedades') }}">Novedades</a></li>
                        <li class="breadcrumb-item active">{{ $aviso->titulo }}</li>
                    </ol>
                </nav>

                {{-- Title & Metadata Row --}}
                <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
                    @php $cfg = $aviso->tipo_config; @endphp
                    <span class="badge bg-{{ $cfg['color'] }}-subtle text-{{ $cfg['color'] }} rounded-pill" style="font-size: 12px;">
                        <i class="ti {{ $cfg['icon'] }} me-1"></i>{{ $cfg['label'] }}
                    </span>
                    <small class="text-muted">
                        <i class="ti ti-calendar me-1"></i>{{ $aviso->created_at->format('d/m/Y') }}
                    </small>
                    @if($aviso->carrera)
                        <small class="text-muted">
                            <i class="ti ti-book me-1"></i>{{ $aviso->carrera->name }}
                        </small>
                    @endif
                </div>

                <h1 class="display-5 fw-bolder mb-4">{{ $aviso->titulo }}</h1>

                {{-- Main Image or Placeholder --}}
                @if($aviso->imagen_url)
                    <img src="{{ $aviso->imagen_url }}" alt="{{ $aviso->titulo }}" class="img-fluid rounded-4 mb-5" style="max-height: 500px; object-fit: cover; width: 100%;">
                @else
                    <div class="rounded-4 mb-5 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, var(--bs-{{ $cfg['color'] }}-subtle) 0%, var(--bs-{{ $cfg['color'] }}-subtle) 100%); min-height: 300px;">
                        <div class="text-center">
                            <i class="ti {{ $cfg['icon'] }} text-{{ $cfg['color'] }}" style="font-size: 80px; opacity: .4;"></i>
                            <p class="text-{{ $cfg['color'] }} mt-3 fw-semibold">{{ $cfg['label'] }}</p>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sidebar (sticky) --}}
            <div class="col-lg-4" data-aos>
                <div class="aviso-sidebar">
                    <div class="aviso-metadata mb-4">
                        <h3 class="fw-bold mb-3" style="font-size: 15px;">Información del aviso</h3>

                        {{-- Tipo --}}
                        <div class="mb-3 pb-3 border-bottom">
                            <small class="text-muted d-block mb-1">Tipo</small>
                            <span class="badge bg-{{ $cfg['color'] }}-subtle text-{{ $cfg['color'] }}">{{ $cfg['label'] }}</span>
                        </div>

                        {{-- Fecha --}}
                        <div class="mb-3 pb-3 border-bottom">
                            <small class="text-muted d-block mb-1">Publicado</small>
                            <strong class="d-block">{{ $aviso->created_at->format('d/m/Y') }}</strong>
                            @if($aviso->publicado_hasta)
                                <small class="text-muted">Válido hasta {{ $aviso->publicado_hasta->format('d/m/Y') }}</small>
                            @endif
                        </div>

                        {{-- Carrera / Sede --}}
                        @if($aviso->carrera || $aviso->sede)
                            <div class="mb-3 pb-3 border-bottom">
                                <small class="text-muted d-block mb-1">Destino</small>
                                @if($aviso->carrera)
                                    <strong class="d-block">{{ $aviso->carrera->name }}</strong>
                                @endif
                                @if($aviso->sede)
                                    <small class="text-muted d-block">
                                        <i class="ti ti-map-pin me-1"></i>{{ $aviso->sede->nombre }}
                                        @if($aviso->sede->ciudad)
                                            ({{ $aviso->sede->ciudad }})
                                        @endif
                                    </small>
                                @endif
                            </div>
                        @endif

                        {{-- Rol Destino --}}
                        <div class="mb-0">
                            <small class="text-muted d-block mb-1">Para</small>
                            @php
                                $rolesDestino = [
                                    'todos' => 'Todos',
                                    'alumno' => 'Alumnos',
                                    'docente' => 'Docentes',
                                    'directivo' => 'Directivos',
                                ];
                            @endphp
                            <strong class="d-block">{{ $rolesDestino[$aviso->rol_destino] ?? $aviso->rol_destino }}</strong>
                        </div>
                    </div>

                    {{-- Download Button (if attachment) --}}
                    @if($aviso->adjunto_path)
                        <a href="{{ asset('storage/'.$aviso->adjunto_path) }}" class="btn btn-cent btn-sm w-100 mb-4" download>
                            <i class="ti ti-download me-2"></i>Descargar archivo
                        </a>
                    @endif

                    {{-- Back Link --}}
                    <a href="{{ route('cent.novedades') }}" class="btn btn-outline-primary btn-sm w-100">
                        <i class="ti ti-arrow-left me-2"></i>Volver a novedades
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Article Content --}}
<section class="py-5 border-bottom">
    <div class="container">
        <div class="row">
            <div class="col-lg-8" data-aos>
                <article class="article-body">
                    {!! $aviso->contenido !!}
                </article>
            </div>
        </div>
    </div>
</section>

{{-- Gallery --}}
@if($aviso->gallery && count($aviso->gallery) > 0)
<section class="py-5 border-bottom">
    <div class="container">
        <h2 class="h5 fw-bold mb-4 d-flex align-items-center gap-2">
            <i class="ti ti-photo text-info"></i>Galería de fotos
        </h2>
        <div class="row g-3">
            @foreach($aviso->gallery as $photo)
                <div class="col-md-6 col-lg-4" data-aos>
                    <div class="gallery-item" onclick="openLightbox(this)">
                        <img src="{{ asset('storage/'.$photo) }}" alt="Galería" loading="lazy">
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Lightbox --}}
<div class="lightbox" id="lightbox">
    <div class="lightbox-content">
        <button class="lightbox-close" onclick="closeLightbox()">&times;</button>
        <img id="lightbox-img" class="lightbox-img" src="" alt="Foto amplificada">
    </div>
</div>
@endif

{{-- Video --}}
@if($aviso->video_url)
<section class="py-5 border-bottom">
    <div class="container">
        <h2 class="h5 fw-bold mb-4 d-flex align-items-center gap-2">
            <i class="ti ti-player-play text-danger"></i>Video relacionado
        </h2>
        <div class="row">
            <div class="col-lg-8" data-aos>
                <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 8px;">
                    @php
                        $embedUrl = null;
                        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $aviso->video_url, $m)) {
                            $embedUrl = "https://www.youtube.com/embed/{$m[1]}";
                        } elseif (preg_match('/vimeo\.com\/(\d+)/', $aviso->video_url, $m)) {
                            $embedUrl = "https://player.vimeo.com/video/{$m[1]}";
                        }
                    @endphp
                    @if($embedUrl)
                        <iframe src="{{ $embedUrl }}" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;" allowfullscreen></iframe>
                    @else
                        <p class="text-danger"><i class="ti ti-alert-triangle me-2"></i>URL de video no válida. Use YouTube o Vimeo.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endif

{{-- Related Avisos --}}
@if($relacionados->isNotEmpty())
<section class="py-5">
    <div class="container">
        <h2 class="h5 fw-bold mb-4 d-flex align-items-center gap-2">
            <i class="ti ti-link text-secondary"></i>Avisos relacionados
        </h2>
        <div class="row g-4">
            @foreach($relacionados as $relacion)
                @php $cfg = $relacion->tipo_config; @endphp
                <div class="col-md-6 col-lg-4" data-aos>
                    <a href="{{ route('cent.novedades.show', $relacion) }}" class="text-decoration-none">
                        <article class="card cent-card h-100 related-card">
                            @if($relacion->imagen_url)
                                <img src="{{ $relacion->imagen_url }}" alt="{{ $relacion->titulo }}" class="card-img-top" style="height: 180px; object-fit: cover;">
                            @else
                                <div class="card-img-top d-flex align-items-center justify-content-center" style="height: 180px; background: var(--cent-soft);">
                                    <i class="ti {{ $cfg['icon'] }} text-{{ $cfg['color'] }}" style="font-size: 48px; opacity: .5;"></i>
                                </div>
                            @endif
                            <div class="card-body">
                                <div class="mb-2">
                                    <span class="badge bg-{{ $cfg['color'] }}-subtle text-{{ $cfg['color'] }} rounded-pill" style="font-size: 11px;">
                                        <i class="ti {{ $cfg['icon'] }} me-1"></i>{{ $cfg['label'] }}
                                    </span>
                                </div>
                                <h3 class="fw-bold mb-2" style="font-size: 15px; color: inherit;">{{ $relacion->titulo }}</h3>
                                <p class="text-muted mb-0" style="font-size: 13px; line-height: 1.6;">
                                    {{ Str::limit($relacion->contenido, 100) }}
                                </p>
                            </div>
                            <div class="card-footer bg-white border-top py-3">
                                <small class="text-muted d-flex justify-content-between">
                                    <span><i class="ti ti-calendar me-1"></i>{{ $relacion->created_at->format('d/m/Y') }}</span>
                                    <span class="text-primary fw-semibold">Leer más →</span>
                                </small>
                            </div>
                        </article>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection

@push('scripts')
<script>
    function openLightbox(element) {
        const src = element.querySelector('img').src;
        document.getElementById('lightbox-img').src = src;
        document.getElementById('lightbox').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        document.getElementById('lightbox').classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    document.getElementById('lightbox')?.addEventListener('click', function(e) {
        if (e.target === this) closeLightbox();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeLightbox();
    });

    // AOS animation
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
