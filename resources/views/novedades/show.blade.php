@extends('layouts.app')

@section('title', $post->title . ' | ATSA Tucumán')

@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;

    $categoryImages = [
        'gremial' => asset('images/historia/movilizacion-atsa-sanidad.jpg'),
        'institucional' => asset('images/historia/ciudad-deportiva-atsa.jpg'),
        'formacion' => asset('images/historia/formacion-cent-74.jpg'),
        'filiales' => asset('images/filiales/filial-este-banda.jpg'),
        'eventos' => asset('images/historia/infraestructura-ciudad-deportiva.jpg'),
        'beneficios' => asset('images/turismo/hotel-atsa-termas-fachada.webp'),
    ];

    $categoryLabels = [
        'institucional' => 'Institucional',
        'gremial' => 'Gremial',
        'formacion' => 'Formación',
        'filiales' => 'Filiales',
        'eventos' => 'Eventos',
        'beneficios' => 'Beneficios',
    ];

    $image = $post->image
        ? Storage::disk('public')->url($post->image)
        : ($categoryImages[$post->category] ?? $categoryImages['institucional']);
    $related = \App\Models\Post::query()
        ->where('id', '!=', $post->id)
        ->latest('published_at')
        ->take(3)
        ->get();
    $wordCount = str_word_count(strip_tags($post->body ?? ''));
    $readingTime = max(1, (int) ceil($wordCount / 200));
    $excerpt = $post->excerpt ?: Str::limit(strip_tags($post->body ?? ''), 160);
    $authorName = $post->author->name ?? 'ATSA Tucumán';
    $pubDate = optional($post->published_at)->format('d/m/Y') ?: $post->created_at->format('d/m/Y');
    $authorInitials = collect(explode(' ', $authorName))
        ->filter()
        ->take(2)
        ->map(fn ($p) => mb_strtoupper(mb_substr($p, 0, 1)))
        ->implode('');
    $heroImagePosition = $post->slug === 'atsa-tucuman-firmo-un-convenio-con-la-municipalidad-de-banda-del-rio-sali'
        ? 'center 12%'
        : 'center center';
    $approvedComments = $post->comments ?? collect();
    if ($approvedComments instanceof \Illuminate\Database\Eloquent\Collection || $approvedComments instanceof \Illuminate\Support\Collection) {
        $approvedComments = $approvedComments->filter(fn ($comment) => (bool) ($comment->approved_at ?? true))->values();
    } else {
        $approvedComments = collect();
    }
@endphp

@section('meta_description', $excerpt)
@section('og_image', $image)
@section('og_type', 'article')

@push('head')
    <meta property="article:published_time" content="{{ optional($post->published_at)->toIso8601String() }}" />
    <meta property="article:author" content="{{ $authorName }}" />
@endpush

@section('content')
    <div id="reading-progress" style="position:fixed;top:0;left:0;height:4px;background:var(--atsa-blue);width:0%;z-index:10000;transition:width .1s linear;border-radius:0 2px 2px 0;"></div>

    <section class="bg-primary-subtle pt-lg-14 py-lg-0 py-5">
        <div class="container-fluid">
            <div class="text-center">
                <div class="d-flex justify-content-center mb-3">
                    <span class="badge bg-primary text-white fs-2 fw-bolder px-3 py-2">
                        {{ $categoryLabels[$post->category] ?? ucfirst($post->category) }}
                    </span>
                </div>
                <h1 class="text-dark fw-bolder px-xl-12 my-5" style="font-size: clamp(1.6rem, 4vw, 2.8rem); line-height: 1.25;">{{ $post->title }}</h1>
                <div class="d-flex justify-content-center align-items-center gap-7 flex-wrap mb-5">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white" style="width:36px;height:36px;background:#1e3a5f;font-size:13px;flex-shrink:0;">
                            {{ $authorInitials }}
                        </div>
                        <p class="mb-0 fs-4 fw-semibold text-dark">{{ $authorName }}</p>
                    </div>
                    <div class="d-flex align-items-center gap-2 text-muted">
                        <i class="ti ti-calendar fs-4"></i>
                        <p class="mb-0 fs-4">{{ $pubDate }}</p>
                    </div>
                    <div class="d-flex align-items-center gap-2 text-muted">
                        <i class="ti ti-clock fs-4"></i>
                        <p class="mb-0 fs-4">{{ $readingTime }} min de lectura</p>
                    </div>
                </div>
            </div>
            <div class="mt-5">
                <img src="{{ $image }}" alt="{{ $post->title }}" class="img-fluid rounded-3 mb-n11 w-100" style="max-height:520px;object-fit:cover;object-position:{{ $heroImagePosition }};" loading="eager">
            </div>
        </div>
    </section>

    <section class="mt-11 pb-md-5 pb-lg-12 pt-lg-14">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4 order-lg-1 order-2">
                    <div class="d-flex flex-column gap-4 sticky-top" style="top: 90px;">
                        <div class="card border-0 shadow-sm rounded-3 p-5">
                            <div class="d-flex gap-3 align-items-center mb-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white flex-shrink-0" style="width:52px;height:52px;background:#1e3a5f;font-size:18px;">
                                    {{ $authorInitials }}
                                </div>
                                <div>
                                    <p class="mb-0 text-dark fs-4 fw-semibold">{{ $authorName }}</p>
                                    <p class="mb-0 fs-3 text-muted">Comunicación institucional</p>
                                </div>
                            </div>
                            <p class="fs-3 text-muted mb-0">ATSA Tucumán - información gremial para los trabajadores de la sanidad.</p>
                        </div>

                        <div class="card border-0 shadow-sm rounded-3 p-5">
                            <h5 class="fs-3 fw-bold text-uppercase text-muted mb-4 d-flex align-items-center gap-2">
                                <i class="ti ti-bookmark text-primary"></i> Relacionadas
                            </h5>
                            @forelse ($related as $item)
                                @php
                                    $rImg = $item->image
                                        ? Storage::disk('public')->url($item->image)
                                        : ($categoryImages[$item->category] ?? $categoryImages['institucional']);
                                @endphp
                                <a href="{{ route('novedades.show', $item->slug) }}" class="d-flex gap-3 align-items-start text-decoration-none mb-4 pb-4 border-bottom">
                                    <img src="{{ $rImg }}" alt="{{ $item->title }}" class="rounded-2 flex-shrink-0" style="width:60px;height:50px;object-fit:cover;" loading="lazy">
                                    <span class="text-dark fs-3 fw-semibold link-primary line-clamp-2">{{ Str::limit($item->title, 62) }}</span>
                                </a>
                            @empty
                                <p class="fs-4 text-muted mb-0">No hay noticias relacionadas todavía.</p>
                            @endforelse
                        </div>

                        <div class="card border-0 shadow-sm rounded-3 p-5">
                            <h5 class="text-uppercase fs-3 fw-bold text-muted mb-4 d-flex align-items-center gap-2">
                                <i class="ti ti-share text-primary"></i> Compartir
                            </h5>
                            <div class="d-flex gap-3 flex-wrap">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm py-4 px-5 d-flex align-items-center gap-2">
                                    <i class="ti ti-brand-facebook"></i> Facebook
                                </a>
                                <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . url()->current()) }}" target="_blank" rel="noopener" class="btn btn-success btn-sm py-4 px-5 d-flex align-items-center gap-2">
                                    <i class="ti ti-brand-whatsapp"></i> WhatsApp
                                </a>
                                <button id="share-native-btn" class="btn btn-outline-secondary btn-sm py-4 px-5 d-none align-items-center gap-2">
                                    <i class="ti ti-share"></i> Compartir
                                </button>
                                <button type="button" id="copy-link-btn" class="btn btn-outline-secondary btn-sm py-4 px-5 d-flex align-items-center gap-2" title="Copiar enlace">
                                    <i class="ti ti-copy"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8 order-lg-2 order-1 mb-lg-0 mb-7">
                    <article id="article-content" class="prose-atsa fs-4" style="line-height:1.85;">
                        {!! $post->body !!}
                    </article>

                    @if ($post->video_url)
                        @php
                            $embedUrl = null;
                            if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $post->video_url, $m)) {
                                $embedUrl = 'https://www.youtube.com/embed/' . $m[1];
                            } elseif (preg_match('/vimeo\.com\/(\d+)/', $post->video_url, $m)) {
                                $embedUrl = 'https://player.vimeo.com/video/' . $m[1];
                            }
                        @endphp
                        @if ($embedUrl)
                            <div class="mt-7">
                                <h4 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                                    <i class="ti ti-player-play text-primary"></i> Video
                                </h4>
                                <div style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;border-radius:12px;">
                                    <iframe src="{{ $embedUrl }}" style="position:absolute;top:0;left:0;width:100%;height:100%;border:0;" allowfullscreen loading="lazy"></iframe>
                                </div>
                            </div>
                        @endif
                    @endif

                    @if (!empty($post->gallery) && count($post->gallery) > 0)
                        <div class="mt-7">
                            <h4 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                                <i class="ti ti-photo text-primary"></i> Galería de fotos
                            </h4>
                            <div class="row g-3" id="post-gallery">
                                @foreach ($post->gallery as $idx => $photo)
                                    <div class="col-6 col-md-4">
                                        <a href="{{ Storage::disk('public')->url($photo) }}" data-gallery="post-gallery" data-index="{{ $idx }}">
                                            <img src="{{ Storage::disk('public')->url($photo) }}" alt="Foto {{ $idx + 1 }}" class="img-fluid rounded-3 w-100 gallery-thumb" style="height:180px;object-fit:cover;cursor:zoom-in;transition:opacity .2s;" loading="lazy">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if ($post->fuente || $post->fuente_url)
                        <div class="mt-7 p-4 bg-light rounded-3 d-flex align-items-start gap-3">
                            <i class="ti ti-link text-muted mt-1 flex-shrink-0"></i>
                            <div>
                                <span class="text-muted fs-3 fw-semibold text-uppercase">Fuente: </span>
                                @if ($post->fuente_url)
                                    <a href="{{ $post->fuente_url }}" target="_blank" rel="noopener" class="fs-3 text-primary">
                                        {{ $post->fuente ?: $post->fuente_url }}
                                    </a>
                                @else
                                    <span class="fs-3 text-dark">{{ $post->fuente }}</span>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="mt-7 pt-5 border-top d-flex align-items-center gap-3 flex-wrap">
                        <span class="text-muted fs-4">Categoría:</span>
                        <a href="{{ route('novedades.index', ['category' => $post->category]) }}" class="badge bg-primary-subtle text-primary fs-3 px-3 py-2">
                            {{ $categoryLabels[$post->category] ?? ucfirst($post->category) }}
                        </a>
                        @if (!empty($post->tags) && count($post->tags) > 0)
                            <span class="text-muted fs-4 ms-2">Tags:</span>
                            @foreach ($post->tags as $tag)
                                <span class="badge bg-secondary-subtle text-secondary fs-3 px-3 py-2">{{ $tag }}</span>
                            @endforeach
                        @endif
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('novedades.index') }}" class="btn btn-outline-primary py-4 px-6 d-inline-flex align-items-center gap-2 novedad-back-link">
                            <span class="novedad-back-link__arrow" aria-hidden="true">←</span>
                            <span>Volver a novedades</span>
                        </a>
                    </div>

                    <section class="mt-7 pt-5 border-top">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-5">
                            <div>
                                <p class="text-primary fw-bold text-uppercase mb-2">Comunidad</p>
                                <h3 class="fw-bolder mb-0">Comentarios</h3>
                            </div>
                            <span class="badge bg-primary-subtle text-primary fs-3 px-3 py-2">{{ $approvedComments->count() }} publicados</span>
                        </div>

                        <div class="card border-0 shadow-sm rounded-4 p-4 p-lg-5 mb-5">
                            <h4 class="fw-bolder mb-2">Dejá tu comentario</h4>
                            <p class="text-muted fs-4 mb-4">Los comentarios se publican una vez aprobados por el equipo de ATSA.</p>
                            <form action="{{ route('novedades.comentar', $post->slug) }}" method="POST" class="row g-3">
                                @csrf
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nombre</label>
                                    <input type="text" name="name" class="form-control form-control-lg" value="{{ auth()->check() ? auth()->user()->name : old('name') }}" @if(auth()->check()) readonly @endif required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Email</label>
                                    <input type="email" name="email" class="form-control form-control-lg" value="{{ auth()->check() ? auth()->user()->email : old('email') }}" @if(auth()->check()) readonly @endif>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Comentario</label>
                                    <textarea name="content" rows="5" class="form-control form-control-lg" placeholder="Escribí tu opinión sobre esta novedad..." required>{{ old('content') }}</textarea>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-primary py-3 px-5">
                                        <i class="ti ti-message-circle me-2"></i>Enviar comentario
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="d-flex flex-column gap-4">
                            @forelse($approvedComments as $comment)
                                @php
                                    $commentInitials = collect(explode(' ', $comment->name))
                                        ->filter()
                                        ->take(2)
                                        ->map(fn ($p) => mb_strtoupper(mb_substr($p, 0, 1)))
                                        ->implode('');
                                @endphp
                                <div class="card border-0 shadow-sm rounded-4 p-4 p-lg-5">
                                    <div class="d-flex gap-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white flex-shrink-0" style="width:52px;height:52px;background:#1e3a5f;font-size:18px;">
                                            {{ $commentInitials }}
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                                                <div>
                                                    <h5 class="fw-bolder mb-0">{{ $comment->name }}</h5>
                                                    <p class="text-muted mb-0 fs-3">{{ optional($comment->approved_at ?: $comment->created_at)->format('d/m/Y H:i') }}</p>
                                                </div>
                                                <span class="badge bg-success-subtle text-success fs-3 px-3 py-2">Aprobado</span>
                                            </div>
                                            <p class="mb-0 fs-4 text-dark" style="line-height:1.75;">{{ $comment->content }}</p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="card border-0 bg-light rounded-4 p-5 text-center">
                                    <i class="ti ti-message-circle fs-10 text-primary mb-3"></i>
                                    <h5 class="fw-bolder mb-2">Todavía no hay comentarios</h5>
                                    <p class="text-muted mb-0">Sé el primero en dejar una opinión sobre esta novedad.</p>
                                </div>
                            @endforelse
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    .prose-atsa h2, .prose-atsa h3, .prose-atsa h4 {
        font-weight: 700;
        color: #1e3a5f;
        margin-top: 2rem;
        margin-bottom: .75rem;
    }
    .prose-atsa p { margin-bottom: 1.25rem; }
    .prose-atsa img { border-radius: 12px; max-width: 100%; height: auto; }
    .prose-atsa blockquote {
        border-left: 4px solid #1e3a5f;
        padding: 12px 20px;
        background: #ecf2ff;
        border-radius: 0 12px 12px 0;
        font-style: italic;
        margin: 1.5rem 0;
    }
    .prose-atsa a { color: #1e3a5f; font-weight: 600; }
    .prose-atsa ul, .prose-atsa ol { padding-left: 1.5rem; margin-bottom: 1.25rem; }
    .prose-atsa li { margin-bottom: .4rem; }
    .novedad-back-link {
        font-size: 1rem;
        line-height: 1;
        white-space: nowrap;
    }
    .novedad-back-link__arrow {
        font-size: 1rem;
        line-height: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
    }
    .gallery-thumb:hover { opacity: .85; }
    #gallery-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,.92);
        z-index: 99999;
        align-items: center;
        justify-content: center;
    }
    #gallery-overlay-inner {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        max-width: 92vw;
        max-height: 92vh;
    }
    #gallery-img {
        max-width: 90vw;
        max-height: 80vh;
        border-radius: 10px;
        object-fit: contain;
    }
    #gallery-close {
        position: absolute;
        top: -42px;
        right: 0;
        background: none;
        border: none;
        color: #fff;
        font-size: 2.2rem;
        line-height: 1;
        cursor: pointer;
        opacity: .8;
    }
    #gallery-close:hover { opacity: 1; }
    #gallery-prev, #gallery-next {
        position: fixed;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255,255,255,.15);
        border: none;
        color: #fff;
        font-size: 2.5rem;
        line-height: 1;
        cursor: pointer;
        padding: 10px 18px;
        border-radius: 8px;
        opacity: .8;
        transition: opacity .2s, background .2s;
    }
    #gallery-prev:hover, #gallery-next:hover { opacity: 1; background: rgba(255,255,255,.28); }
    #gallery-prev { left: 12px; }
    #gallery-next { right: 12px; }
    #gallery-counter { color: rgba(255,255,255,.6); font-size: 13px; margin-top: 10px; }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var thumbs = document.querySelectorAll('[data-gallery="post-gallery"]');
        if (thumbs.length) {
            var overlay = document.createElement('div');
            overlay.id = 'gallery-overlay';
            overlay.innerHTML = '<div id="gallery-overlay-inner"><button id="gallery-close" aria-label="Cerrar">&times;</button><button id="gallery-prev" aria-label="Anterior">&#8249;</button><img id="gallery-img" src="" alt=""><button id="gallery-next" aria-label="Siguiente">&#8250;</button><p id="gallery-counter"></p></div>';
            document.body.appendChild(overlay);

            var photos = Array.from(thumbs).map(function (a) { return a.getAttribute('href'); });
            var current = 0;

            function openGallery(idx) {
                current = idx;
                document.getElementById('gallery-img').src = photos[current];
                document.getElementById('gallery-counter').textContent = (current + 1) + ' / ' + photos.length;
                overlay.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }

            function closeGallery() {
                overlay.style.display = 'none';
                document.body.style.overflow = '';
            }

            thumbs.forEach(function (a) {
                a.addEventListener('click', function (e) {
                    e.preventDefault();
                    openGallery(parseInt(a.dataset.index, 10));
                });
            });

            document.getElementById('gallery-close').addEventListener('click', closeGallery);
            document.getElementById('gallery-prev').addEventListener('click', function () {
                openGallery((current - 1 + photos.length) % photos.length);
            });
            document.getElementById('gallery-next').addEventListener('click', function () {
                openGallery((current + 1) % photos.length);
            });
            overlay.addEventListener('click', function (e) {
                if (e.target === overlay) {
                    closeGallery();
                }
            });
            document.addEventListener('keydown', function (e) {
                if (overlay.style.display !== 'flex') {
                    return;
                }
                if (e.key === 'Escape') closeGallery();
                if (e.key === 'ArrowLeft') openGallery((current - 1 + photos.length) % photos.length);
                if (e.key === 'ArrowRight') openGallery((current + 1) % photos.length);
            });
        }

        var article = document.getElementById('article-content');
        var bar = document.getElementById('reading-progress');
        if (article && bar) {
            window.addEventListener('scroll', function () {
                var rect = article.getBoundingClientRect();
                var total = article.offsetHeight;
                var scrolled = -rect.top;
                var pct = Math.min(100, Math.max(0, (scrolled / total) * 100));
                bar.style.width = pct + '%';
            }, { passive: true });
        }

        var nativeBtn = document.getElementById('share-native-btn');
        if (navigator.share && nativeBtn) {
            nativeBtn.classList.remove('d-none');
            nativeBtn.classList.add('d-flex');
            nativeBtn.addEventListener('click', function () {
                navigator.share({
                    title: @js($post->title),
                    text: @js($excerpt),
                    url: window.location.href,
                });
            });
        }

        var copyBtn = document.getElementById('copy-link-btn');
        if (copyBtn) {
            copyBtn.addEventListener('click', function () {
                navigator.clipboard.writeText(window.location.href).then(() => {
                    var original = copyBtn.innerHTML;
                    copyBtn.innerHTML = '<i class="ti ti-check"></i>';
                    setTimeout(function () {
                        copyBtn.innerHTML = original;
                    }, 2000);
                });
            });
        }
    });
</script>
@endpush
