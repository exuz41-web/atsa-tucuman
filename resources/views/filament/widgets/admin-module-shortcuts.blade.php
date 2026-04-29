@php
    $modules = [
        [
            'label' => 'Prensa y web pública',
            'description' => 'Inicio, novedades, efemérides, descargas y contenido visible.',
            'icon' => 'ti-news',
            'color' => 'blue',
            'href' => \App\Filament\Pages\EditarInicio::getUrl(),
            'links' => [
                ['label' => 'Noticias', 'href' => \App\Filament\Resources\PostResource::getUrl()],
                ['label' => 'Efemérides', 'href' => \App\Filament\Resources\EfemerideResource::getUrl()],
                ['label' => 'Secciones', 'href' => \App\Filament\Resources\PageSectionResource::getUrl()],
            ],
        ],
        [
            'label' => 'Recepción y atención',
            'description' => 'Bandeja diaria para pedidos, consultas y trámites.',
            'icon' => 'ti-inbox',
            'color' => 'green',
            'href' => \App\Filament\Resources\PedidoResource::getUrl(),
            'links' => [
                ['label' => 'Pedidos', 'href' => \App\Filament\Resources\PedidoResource::getUrl()],
                ['label' => 'Consultas', 'href' => \App\Filament\Resources\ConsultaResource::getUrl()],
                ['label' => 'Trámites', 'href' => \App\Filament\Resources\TramiteResource::getUrl()],
            ],
        ],
        [
            'label' => 'Secretarías y beneficios',
            'description' => 'Beneficios, prestadores, órdenes y control por sector.',
            'icon' => 'ti-heart-handshake',
            'color' => 'orange',
            'href' => \App\Filament\Resources\SolicitudBeneficioResource::getUrl(),
            'links' => [
                ['label' => 'Solicitudes', 'href' => \App\Filament\Resources\SolicitudBeneficioResource::getUrl()],
                ['label' => 'Prestadores', 'href' => \App\Filament\Resources\PrestadorResource::getUrl()],
                ['label' => 'Órdenes', 'href' => \App\Filament\Resources\OrdenPrestacionResource::getUrl()],
            ],
        ],
        [
            'label' => 'Afiliación y padrón',
            'description' => 'Afiliados, altas, carnets, filiales y establecimientos.',
            'icon' => 'ti-address-book',
            'color' => 'purple',
            'href' => \App\Filament\Resources\UserResource::getUrl(),
            'links' => [
                ['label' => 'Afiliados', 'href' => \App\Filament\Resources\UserResource::getUrl()],
                ['label' => 'Solicitudes', 'href' => \App\Filament\Resources\SolicitudAfiliacionResource::getUrl()],
                ['label' => 'Carnets', 'href' => \App\Filament\Pages\GestionCarnets::getUrl()],
            ],
        ],
        [
            'label' => 'Institucional y gremial',
            'description' => 'Autoridades, delegados, documentos y escalas.',
            'icon' => 'ti-building-bank',
            'color' => 'slate',
            'href' => \App\Filament\Resources\AutoridadResource::getUrl(),
            'links' => [
                ['label' => 'Autoridades', 'href' => \App\Filament\Resources\AutoridadResource::getUrl()],
                ['label' => 'Delegados', 'href' => \App\Filament\Resources\DelegadoResource::getUrl()],
                ['label' => 'Escalas', 'href' => \App\Filament\Resources\EscalaSalarialResource::getUrl()],
            ],
        ],
        [
            'label' => 'Configuración y seguridad',
            'description' => 'Datos globales, SMTP, backups y recuperación.',
            'icon' => 'ti-shield-lock',
            'color' => 'red',
            'href' => \App\Filament\Pages\Configuracion::getUrl(),
            'links' => [
                ['label' => 'General', 'href' => \App\Filament\Pages\Configuracion::getUrl()],
                ['label' => 'Backups', 'href' => \App\Filament\Pages\Configuracion::getUrl()],
                ['label' => 'Usuarios', 'href' => \App\Filament\Resources\UserResource::getUrl()],
            ],
        ],
    ];
@endphp

<x-filament-widgets::widget>
    <section class="atsa-module-grid" aria-label="Módulos principales del admin">
        @foreach ($modules as $module)
            <article class="atsa-module-card atsa-module-card--{{ $module['color'] }}">
                <a href="{{ $module['href'] }}" class="atsa-module-card__main">
                    <span class="atsa-module-card__icon">
                        <i class="ti {{ $module['icon'] }}"></i>
                    </span>
                    <span class="atsa-module-card__copy">
                        <strong>{{ $module['label'] }}</strong>
                        <small>{{ $module['description'] }}</small>
                    </span>
                    <span class="atsa-module-card__arrow">
                        <i class="ti ti-arrow-up-right"></i>
                    </span>
                </a>
                <div class="atsa-module-card__links">
                    @foreach ($module['links'] as $link)
                        <a href="{{ $link['href'] }}">{{ $link['label'] }}</a>
                    @endforeach
                </div>
            </article>
        @endforeach
    </section>
</x-filament-widgets::widget>
