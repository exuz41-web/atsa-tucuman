@php
    $currentSlug = request()->segment(2);

    $editorPages = [
        [
            'slug' => 'editar-inicio',
            'label' => 'Inicio',
            'desc' => 'Banner, galería y turismo',
            'icon' => 'ti ti-home-2',
        ],
        [
            'slug' => 'editar-gremial',
            'label' => 'Gremial',
            'desc' => 'Sección gremial y derechos',
            'icon' => 'ti ti-scale',
        ],
        [
            'slug' => 'editar-sindicato',
            'label' => 'El Sindicato',
            'desc' => 'Historia e institucional',
            'icon' => 'ti ti-building',
        ],
        [
            'slug' => 'editar-turismo',
            'label' => 'Turismo',
            'desc' => 'Hotel y ciudad deportiva',
            'icon' => 'ti ti-sun',
        ],
        [
            'slug' => 'editar-contacto',
            'label' => 'Contacto',
            'desc' => 'Teléfonos y redes sociales',
            'icon' => 'ti ti-phone',
        ],
    ];

    $gestionItems = [
        [
            'slug' => 'posts',
            'label' => 'Noticias',
            'desc' => 'Artículos y novedades',
            'icon' => 'ti ti-news',
        ],
        [
            'slug' => 'efemerides',
            'label' => 'Efemérides',
            'desc' => 'Fechas y conmemoraciones',
            'icon' => 'ti ti-calendar-event',
        ],
        [
            'slug' => 'descargas',
            'label' => 'Descargas',
            'desc' => 'Archivos descargables',
            'icon' => 'ti ti-download',
        ],
        [
            'slug' => 'testimonios',
            'label' => 'Testimonios',
            'desc' => 'Reseñas de afiliados',
            'icon' => 'ti ti-message-circle',
        ],
        [
            'slug' => 'bloques-visuales',
            'label' => 'Bloques visuales',
            'desc' => 'Imágenes y galerías',
            'icon' => 'ti ti-photo',
        ],
        [
            'slug' => 'site-pages',
            'label' => 'Páginas del sitio',
            'desc' => 'Constructor de páginas',
            'icon' => 'ti ti-brush',
        ],
        [
            'slug' => 'secciones-sitio',
            'label' => 'Secciones',
            'desc' => 'Bloques de contenido',
            'icon' => 'ti ti-layout-rows',
        ],
    ];
@endphp

<aside class="atsa-editor-sidebar">
    <div class="atsa-editor-sidebar__header">
        <span class="atsa-editor-sidebar__header-icon">
            <i class="ti ti-world"></i>
        </span>
        <div>
            <strong>Sitio web ATSA</strong>
            <small>Editor y contenido</small>
        </div>
    </div>

    <div class="atsa-editor-sidebar__scroll">
        <div class="atsa-editor-sidebar__label">Páginas</div>

        <nav class="atsa-editor-sidebar__nav">
            @foreach ($editorPages as $page)
                @php $isActive = $currentSlug === $page['slug']; @endphp
                <a href="{{ url('/admin/' . $page['slug']) }}" wire:navigate class="atsa-editor-sidebar__link {{ $isActive ? 'is-active' : '' }}">
                    <span class="atsa-editor-sidebar__icon">
                        <i class="{{ $page['icon'] }}"></i>
                    </span>
                    <span class="atsa-editor-sidebar__copy">
                        <strong>{{ $page['label'] }}</strong>
                        <small>{{ $page['desc'] }}</small>
                    </span>
                    @if ($isActive)
                        <i class="ti ti-chevron-right atsa-editor-sidebar__chevron"></i>
                    @endif
                </a>
            @endforeach
        </nav>

        <div class="atsa-editor-sidebar__divider"></div>
        <div class="atsa-editor-sidebar__label">Gestión</div>

        <nav class="atsa-editor-sidebar__nav">
            @foreach ($gestionItems as $item)
                @php $isActive = $currentSlug === $item['slug']; @endphp
                <a href="{{ url('/admin/' . $item['slug']) }}" class="atsa-editor-sidebar__link {{ $isActive ? 'is-active' : '' }}">
                    <span class="atsa-editor-sidebar__icon">
                        <i class="{{ $item['icon'] }}"></i>
                    </span>
                    <span class="atsa-editor-sidebar__copy">
                        <strong>{{ $item['label'] }}</strong>
                        <small>{{ $item['desc'] }}</small>
                    </span>
                    @if ($isActive)
                        <i class="ti ti-chevron-right atsa-editor-sidebar__chevron"></i>
                    @endif
                </a>
            @endforeach
        </nav>
    </div>

    <div class="atsa-editor-sidebar__footer">
        <a href="{{ url('/') }}" target="_blank">
            <i class="ti ti-external-link"></i>
            <span>Ver sitio público</span>
        </a>
    </div>
</aside>
