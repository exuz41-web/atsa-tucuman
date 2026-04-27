@extends('layouts.app')

@section('title', 'Novedades | ATSA Tucumán')

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

    $fallbackPosts = collect([
        (object) [
            'title' => 'Paritarias Salud 2026: acuerdo para trabajadores sanitarios',
            'slug' => 'paritarias-salud-2026',
            'body' => 'ATSA Tucumán acompaña cada negociación salarial para defender los derechos de los trabajadores de la sanidad.',
            'category' => 'gremial',
            'published_at' => \Carbon\Carbon::parse('2026-03-06'),
            'image' => null,
            'author' => (object) ['name' => 'ATSA Tucumán'],
        ],
        (object) [
            'title' => 'ATSA inauguró su nueva sede en el marco del centenario',
            'slug' => 'nueva-sede-atsa-centenario-2025',
            'body' => 'En el año del centenario, ATSA Tucumán inauguró su nueva sede gremial en el predio de Ciudad Deportiva.',
            'category' => 'institucional',
            'published_at' => \Carbon\Carbon::parse('2025-10-03'),
            'image' => null,
            'author' => (object) ['name' => 'ATSA Tucumán'],
        ],
        (object) [
            'title' => 'El CENT N°74 fortalece la formación sanitaria',
            'slug' => 'cent-74-formacion-sanitaria',
            'body' => 'La formación profesional es uno de los ejes históricos de crecimiento institucional de ATSA Tucumán.',
            'category' => 'formacion',
            'published_at' => \Carbon\Carbon::parse('2025-09-15'),
            'image' => null,
            'author' => (object) ['name' => 'ATSA Tucumán'],
        ],
    ]);

    $items = ($posts ?? collect())->count() ? $posts : $fallbackPosts;
    $filterCategories = count($categories ?? []) ? $categories : ['institucional', 'gremial', 'formacion', 'filiales', 'eventos', 'beneficios'];
    $resultsLabel = method_exists($posts ?? null, 'total')
        ? $posts->total() . ' novedades'
        : $items->count() . ' novedades';
@endphp

@push('styles')
    <style>
        .novedades-pill {
            display: inline-flex;
            align-items: center;
            padding: 8px 18px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 700;
            color: rgba(255, 255, 255, .86);
            background: rgba(255, 255, 255, .14);
            border: 1px solid rgba(255, 255, 255, .22);
            text-decoration: none;
            transition: all .2s ease;
            backdrop-filter: blur(4px);
        }

        .novedades-pill:hover,
        .novedades-pill--active {
            background: #fff;
            color: #1e3a5f;
            border-color: #fff;
        }

        .news-shop-shell {
            overflow: hidden;
            border: 1px solid #e8eef8;
            background: #fff;
            box-shadow: 0 14px 34px rgba(30, 58, 95, .07);
        }

        .news-filter-link {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 16px;
            border-radius: 8px;
            color: #2a3547;
            font-weight: 700;
            text-decoration: none;
            transition: all .2s ease;
        }

        .news-filter-link:hover,
        .news-filter-link.is-active {
            background: #ecf2ff;
            color: #5d87ff;
        }

        .news-view-switcher {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px;
            border: 1px solid #e8eef8;
            border-radius: 8px;
            background: #f6f9ff;
        }

        .news-view-btn {
            min-width: 150px;
            height: 46px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 0 18px;
            border: 1px solid #dce7fb;
            border-radius: 8px;
            color: #5d6f8f;
            background: #fff;
            font-weight: 800;
            transition: all .2s ease;
        }

        .news-view-btn:hover,
        .news-view-btn.is-active {
            background: #5d87ff;
            border-color: #5d87ff;
            color: #fff;
        }

        .news-card {
            border: 1px solid #edf2fa;
            border-radius: 8px;
            box-shadow: 0 14px 34px rgba(30, 58, 95, .06);
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .news-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 18px 42px rgba(30, 58, 95, .1);
        }

        .news-card-img {
            height: 230px;
            object-fit: cover;
        }

        .news-list-item {
            display: none;
        }

        .news-list-card {
            display: grid;
            grid-template-columns: 220px minmax(0, 1fr);
            gap: 22px;
            border: 1px solid #edf2fa;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 14px 34px rgba(30, 58, 95, .06);
            overflow: hidden;
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .news-list-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 18px 42px rgba(30, 58, 95, .1);
        }

        .news-list-card img {
            width: 100%;
            height: 100%;
            min-height: 188px;
            object-fit: cover;
        }

        .news-list-meta-dot {
            width: 7px;
            height: 7px;
            display: inline-block;
            border-radius: 50%;
            background: #13deb9;
        }

        .news-results.is-list .news-grid-item {
            display: none;
        }

        .news-results.is-list .news-list-item {
            display: block;
        }

        .line-clamp-2,
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .line-clamp-2 {
            -webkit-line-clamp: 2;
        }

        .line-clamp-3 {
            -webkit-line-clamp: 3;
        }

        .atsa-pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            padding: 18px 20px;
            border: 1px solid #e8eef8;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 14px 34px rgba(30, 58, 95, .07);
        }

        .atsa-pagination__info {
            color: #5d6f8f;
            font-size: 14px;
            font-weight: 600;
        }

        .atsa-pagination__links {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px;
        }

        .atsa-page-btn {
            display: inline-flex;
            min-width: 40px;
            height: 40px;
            align-items: center;
            justify-content: center;
            padding: 0 14px;
            border: 1px solid #dce7fb;
            border-radius: 8px;
            color: #1e3a5f;
            background: #fff;
            font-size: 14px;
            font-weight: 700;
            line-height: 1;
            text-decoration: none;
            transition: all .2s ease;
        }

        .atsa-page-btn:hover,
        .atsa-page-btn.is-active {
            border-color: #5d87ff;
            background: #5d87ff;
            color: #fff;
        }

        .atsa-page-btn.is-disabled {
            cursor: not-allowed;
            opacity: .48;
            background: #f5f8fc;
        }

        @media (max-width: 991.98px) {
            .news-list-card {
                grid-template-columns: 1fr;
            }

            .news-list-card img {
                height: 240px;
            }
        }

        @media (max-width: 767.98px) {
            .news-card-img {
                height: 210px;
            }

            .news-view-switcher,
            .news-view-btn {
                width: 100%;
            }

            .atsa-pagination {
                align-items: stretch;
                flex-direction: column;
            }

            .atsa-pagination__links {
                justify-content: center;
            }
        }
    </style>
@endpush

@section('content')
    <section class="atsa-page-hero py-14 position-relative" style="background-image: url('{{ asset('images/historia/movilizacion-atsa-sanidad.jpg') }}'); min-height: 440px;">
        <div class="container-fluid position-relative z-1">
            <div class="col-lg-8">
                <span class="badge bg-white text-primary fs-3 fw-bolder px-3 py-2 mb-4">
                    <i class="ti ti-news me-1"></i>NOVEDADES
                </span>
                <h1 class="fw-bolder display-4 text-white mb-3">Noticias y comunicados</h1>
                <p class="fs-5 text-white text-opacity-75 mb-5 col-lg-9">
                    Información gremial, institucional, educativa y de filiales para los trabajadores
                    de la sanidad de Tucumán.
                </p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('novedades.index') }}" class="novedades-pill {{ empty($category) ? 'novedades-pill--active' : '' }}">Todas</a>
                    @foreach ($filterCategories as $cat)
                        <a href="{{ route('novedades.index', ['category' => $cat]) }}" class="novedades-pill {{ ($category ?? '') === $cat ? 'novedades-pill--active' : '' }}">
                            {{ $categoryLabels[$cat] ?? ucfirst($cat) }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="pt-8 pt-md-12 pb-4 pb-md-14">
        <div class="container-fluid">
            <div class="card news-shop-shell rounded-3">
                <div class="d-lg-flex w-100">
                    <aside class="flex-shrink-0 border-end d-none d-lg-block" style="width: 260px;">
                        <div class="p-4 border-bottom">
                            <h6 class="fw-semibold mb-3">Filtrar por categoría</h6>
                            <a href="{{ route('novedades.index') }}" class="news-filter-link {{ empty($category) ? 'is-active' : '' }}">
                                <i class="ti ti-circles fs-5"></i>
                                Todas
                            </a>
                            @foreach ($filterCategories as $cat)
                                <a href="{{ route('novedades.index', ['category' => $cat]) }}" class="news-filter-link {{ ($category ?? '') === $cat ? 'is-active' : '' }}">
                                    <i class="ti {{ match ($cat) {
                                        'gremial' => 'ti-scale',
                                        'formacion' => 'ti-school',
                                        'filiales' => 'ti-map-pin',
                                        'eventos' => 'ti-calendar-event',
                                        'beneficios' => 'ti-gift',
                                        default => 'ti-building-community',
                                    } }} fs-5"></i>
                                    {{ $categoryLabels[$cat] ?? ucfirst($cat) }}
                                </a>
                            @endforeach
                        </div>

                        <div class="p-4">
                            <h6 class="fw-semibold mb-3">Accesos rápidos</h6>
                            <a href="{{ route('afiliados.index') }}" class="btn btn-primary w-100 mb-3">Área afiliados</a>
                            <a href="{{ route('contacto.index') }}" class="btn btn-outline-primary w-100">Contactar ATSA</a>
                        </div>
                    </aside>

                    <div class="card-body p-4 p-md-5 flex-grow-1">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4 mb-5">
                            <div>
                                <h5 class="fs-5 fw-bolder mb-1">Novedades</h5>
                                <p class="mb-0 text-muted fw-semibold">{{ $resultsLabel }}</p>
                            </div>

                            <div class="d-flex flex-wrap align-items-center gap-2">
                                <button type="button" class="btn btn-primary d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#novedadesFilters" aria-controls="novedadesFilters">
                                    <i class="ti ti-filter me-1"></i>Filtros
                                </button>
                                <div class="news-view-switcher" role="group" aria-label="Cambiar vista de novedades">
                                    <button type="button" class="news-view-btn is-active" data-news-view="grid" aria-label="Vista en grilla">
                                        <i class="ti ti-layout-grid fs-5"></i>
                                        <span>Vista tarjetas</span>
                                    </button>
                                    <button type="button" class="news-view-btn" data-news-view="list" aria-label="Vista en lista">
                                        <i class="ti ti-list-details fs-5"></i>
                                        <span>Vista lista</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="news-results" data-news-results>
                            <div class="row">
                                @foreach ($items as $post)
                                    @php
                                        if ($post->image) {
                                            $image = Str::startsWith($post->image, ['http://', 'https://', '/'])
                                                ? $post->image
                                                : Storage::disk('public')->url($post->image);
                                        } else {
                                            $image = $categoryImages[$post->category] ?? $categoryImages['institucional'];
                                        }

                                        $date = $post->published_at ? $post->published_at->format('d/m/Y') : now()->format('d/m/Y');
                                        $postUrl = isset($post->id) ? route('novedades.show', $post->slug) : route('novedades.index');
                                        $authorName = $post->author->name ?? 'ATSA Tucumán';
                                        $authorInitials = collect(explode(' ', $authorName))
                                            ->filter()
                                            ->take(2)
                                            ->map(fn ($piece) => Str::upper(Str::substr($piece, 0, 1)))
                                            ->implode('');
                                        $categoryText = $categoryLabels[$post->category] ?? ucfirst($post->category);
                                        $excerpt = Str::limit(strip_tags($post->body), 150);
                                    @endphp

                                    <div class="col-md-6 col-xl-4 mb-4 news-grid-item">
                                        <article class="card news-card hover-img overflow-hidden h-100">
                                            <a href="{{ $postUrl }}" class="position-relative d-block">
                                                <img src="{{ $image }}" alt="{{ $post->title }}" class="w-100 news-card-img">
                                                <span class="position-absolute top-0 start-0 m-3 badge text-bg-light text-primary fw-bolder px-3 py-2">
                                                    {{ $categoryText }}
                                                </span>
                                                <span class="position-absolute bottom-0 end-0 me-3 mb-n3 text-bg-primary rounded-circle p-2 text-white d-inline-flex">
                                                    <i class="ti ti-arrow-up-right fs-4"></i>
                                                </span>
                                            </a>
                                            <div class="card-body p-4 d-flex flex-column">
                                                <div class="d-flex align-items-center gap-2 mb-3 text-muted fs-2 fw-semibold">
                                                    <i class="ti ti-calendar"></i>
                                                    {{ $date }}
                                                </div>
                                                <a href="{{ $postUrl }}" class="fs-5 fw-bolder text-dark line-clamp-2 mb-3">
                                                    {{ $post->title }}
                                                </a>
                                                <p class="text-muted line-clamp-3 mb-4">{{ $excerpt }}</p>
                                                <div class="d-flex align-items-center justify-content-between mt-auto">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white" style="width:40px;height:40px;background:#1e3a5f;font-size:13px;">
                                                            {{ $authorInitials ?: 'AT' }}
                                                        </span>
                                                        <span class="fs-2 fw-semibold text-dark">{{ $authorName }}</span>
                                                    </div>
                                                    <a href="{{ $postUrl }}" class="text-primary fw-bold" aria-label="Leer {{ $post->title }}">Leer</a>
                                                </div>
                                            </div>
                                        </article>
                                    </div>

                                    <div class="col-12 mb-4 news-list-item">
                                        <article class="news-list-card">
                                            <a href="{{ $postUrl }}" class="d-block">
                                                <img src="{{ $image }}" alt="{{ $post->title }}">
                                            </a>
                                            <div class="p-4 p-md-5">
                                                <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
                                                    <span class="badge bg-primary-subtle text-primary fw-bolder px-3 py-2">{{ $categoryText }}</span>
                                                    <span class="d-inline-flex align-items-center gap-2 text-muted fs-2 fw-semibold">
                                                        <span class="news-list-meta-dot"></span>
                                                        {{ $date }}
                                                    </span>
                                                    <span class="d-inline-flex align-items-center gap-2 text-muted fs-2 fw-semibold">
                                                        <i class="ti ti-user-circle"></i>
                                                        {{ $authorName }}
                                                    </span>
                                                </div>
                                                <a href="{{ $postUrl }}" class="fs-6 fw-bolder text-dark line-clamp-2 mb-3 d-block">
                                                    {{ $post->title }}
                                                </a>
                                                <p class="text-muted fs-4 line-clamp-3 mb-4">{{ $excerpt }}</p>
                                                <a href="{{ $postUrl }}" class="btn btn-outline-primary">
                                                    Leer más <i class="ti ti-arrow-right ms-1"></i>
                                                </a>
                                            </div>
                                        </article>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        @if (method_exists($posts ?? null, 'hasPages') && $posts->hasPages())
                            <nav class="atsa-pagination mt-4" aria-label="Paginación de novedades">
                                <div class="atsa-pagination__info">
                                    Mostrando {{ $posts->firstItem() }} a {{ $posts->lastItem() }} de {{ $posts->total() }} novedades
                                </div>
                                <div class="atsa-pagination__links">
                                    @if ($posts->onFirstPage())
                                        <span class="atsa-page-btn is-disabled">Anterior</span>
                                    @else
                                        <a class="atsa-page-btn" href="{{ $posts->previousPageUrl() }}" rel="prev">Anterior</a>
                                    @endif

                                    @foreach ($posts->getUrlRange(1, $posts->lastPage()) as $page => $url)
                                        @if ($page === $posts->currentPage())
                                            <span class="atsa-page-btn is-active">{{ $page }}</span>
                                        @else
                                            <a class="atsa-page-btn" href="{{ $url }}">{{ $page }}</a>
                                        @endif
                                    @endforeach

                                    @if ($posts->hasMorePages())
                                        <a class="atsa-page-btn" href="{{ $posts->nextPageUrl() }}" rel="next">Siguiente</a>
                                    @else
                                        <span class="atsa-page-btn is-disabled">Siguiente</span>
                                    @endif
                                </div>
                            </nav>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="novedadesFilters" aria-labelledby="novedadesFiltersLabel">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title fw-bolder" id="novedadesFiltersLabel">Filtros</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
        </div>
        <div class="offcanvas-body">
            <a href="{{ route('novedades.index') }}" class="news-filter-link {{ empty($category) ? 'is-active' : '' }}">
                <i class="ti ti-circles fs-5"></i>
                Todas
            </a>
            @foreach ($filterCategories as $cat)
                <a href="{{ route('novedades.index', ['category' => $cat]) }}" class="news-filter-link {{ ($category ?? '') === $cat ? 'is-active' : '' }}">
                    <i class="ti {{ match ($cat) {
                        'gremial' => 'ti-scale',
                        'formacion' => 'ti-school',
                        'filiales' => 'ti-map-pin',
                        'eventos' => 'ti-calendar-event',
                        'beneficios' => 'ti-gift',
                        default => 'ti-building-community',
                    } }} fs-5"></i>
                    {{ $categoryLabels[$cat] ?? ucfirst($cat) }}
                </a>
            @endforeach
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (() => {
            const results = document.querySelector('[data-news-results]');
            const buttons = document.querySelectorAll('[data-news-view]');

            if (!results || !buttons.length) {
                return;
            }

            const setView = (view) => {
                const isList = view === 'list';

                results.classList.toggle('is-list', isList);
                buttons.forEach((button) => {
                    button.classList.toggle('is-active', button.dataset.newsView === view);
                    button.setAttribute('aria-pressed', button.dataset.newsView === view ? 'true' : 'false');
                });

                localStorage.setItem('atsa-news-view', view);
            };

            buttons.forEach((button) => {
                button.addEventListener('click', () => setView(button.dataset.newsView));
            });

            setView(localStorage.getItem('atsa-news-view') || 'grid');
        })();
    </script>
@endpush
