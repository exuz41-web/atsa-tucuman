<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Dashboard;
use App\Filament\Widgets\ActividadMensual;
use App\Filament\Widgets\PedidosPorEstado;
use App\Filament\Widgets\ProximasEfemerides;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\UltimasActividades;
use App\Filament\Widgets\UltimosMovimientos;
use App\Models\SiteSetting;
use Filament\Tables\View\TablesRenderHook;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::hex('#1e3a5f'),
                'danger' => Color::hex('#c0392b'),
                'success' => Color::hex('#1D9E75'),
                'warning' => Color::hex('#f59e0b'),
                'info' => Color::hex('#378ADD'),
            ])
            ->brandName(SiteSetting::siteName())
            ->brandLogo(SiteSetting::logoUrl())
            ->brandLogoHeight('2.5rem')
            ->favicon(SiteSetting::faviconUrl())
            ->font('Plus Jakarta Sans')
            ->darkMode(false)
            ->databaseNotifications()
            ->sidebarCollapsibleOnDesktop()
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->resources([
                \App\Filament\Resources\AutoridadResource::class,
                \App\Filament\Resources\BeneficioResource::class,
                \App\Filament\Resources\ConsultaResource::class,
                \App\Filament\Resources\DelegadoResource::class,
                \App\Filament\Resources\DescargaResource::class,
                \App\Filament\Resources\DocumentoResource::class,
                \App\Filament\Resources\EfemerideResource::class,
                \App\Filament\Resources\EscalaSalarialResource::class,
                \App\Filament\Resources\EstablecimientoResource::class,
                \App\Filament\Resources\FilialResource::class,
                \App\Filament\Resources\HotelConvenioResource::class,
                \App\Filament\Resources\OrdenPrestacionResource::class,
                \App\Filament\Resources\PageSectionResource::class,
                \App\Filament\Resources\PedidoResource::class,
                \App\Filament\Resources\PostCommentResource::class,
                \App\Filament\Resources\PostResource::class,
                \App\Filament\Resources\PrestadorResource::class,
                \App\Filament\Resources\SecretariaResource::class,
                \App\Filament\Resources\SitePageResource::class,
                \App\Filament\Resources\SiteSettingResource::class,
                \App\Filament\Resources\SolicitudAfiliacionResource::class,
                \App\Filament\Resources\SolicitudBeneficioResource::class,
                \App\Filament\Resources\TestimonioResource::class,
                \App\Filament\Resources\TramiteResource::class,
                \App\Filament\Resources\TurismoConsultaResource::class,
                \App\Filament\Resources\UserResource::class,
                \App\Filament\Resources\VisualBlockResource::class,
            ])
            ->discoverPages(
                in: app_path('Filament/Pages'),
                for: 'App\\Filament\\Pages'
            )
            ->pages([
                Dashboard::class,
            ])
            ->widgets([
                StatsOverview::class,
                PedidosPorEstado::class,
                ActividadMensual::class,
                UltimosMovimientos::class,
                ProximasEfemerides::class,
                UltimasActividades::class,
            ])
            ->renderHook(
                PanelsRenderHook::TOPBAR_END,
                fn (): string => Blade::render(<<<'BLADE'
                    <a href="{{ url('/') }}" target="_blank" class="fi-btn fi-btn-size-sm fi-btn-color-primary inline-flex items-center gap-2 rounded-lg bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500">
                        <span>Ver sitio público</span>
                    </a>
                BLADE)
            )
            ->renderHook(
                PanelsRenderHook::PAGE_HEADER_WIDGETS_BEFORE,
                function (): string {
                    $siteEditorResources = [
                        'posts',
                        'efemerides',
                        'descargas',
                        'testimonios',
                        'bloques-visuales',
                        'site-pages',
                        'secciones-sitio',
                    ];

                    if (! in_array(request()->segment(2), $siteEditorResources, true)) {
                        return '';
                    }

                    return Blade::render(<<<'BLADE'
                        <div class="atsa-editor-resource-layout">
                            @include('filament.partials.editor-sitio-sidebar')
                            <div class="atsa-editor-resource-main">
                    BLADE);
                }
            )
            ->renderHook(
                PanelsRenderHook::PAGE_FOOTER_WIDGETS_BEFORE,
                function (): string {
                    $siteEditorResources = [
                        'posts',
                        'efemerides',
                        'descargas',
                        'testimonios',
                        'bloques-visuales',
                        'site-pages',
                        'secciones-sitio',
                    ];

                    if (! in_array(request()->segment(2), $siteEditorResources, true)) {
                        return '';
                    }

                    return '</div></div>';
                }
            )
            ->renderHook(
                TablesRenderHook::TOOLBAR_SEARCH_BEFORE,
                fn (): string => Blade::render('@include("filament.tables.posts-view-switcher")'),
                \App\Filament\Resources\PostResource\Pages\ListPosts::class
            )
            ->navigationItems([
                NavigationItem::make('Sitio')
                    ->icon('heroicon-o-globe-alt')
                    ->url('/admin/editar-inicio')
                    ->sort(1),
            ])
            ->navigationGroups([
                NavigationGroup::make('Padrón sindical'),
                NavigationGroup::make('Institución'),
                NavigationGroup::make('Gremial'),
                NavigationGroup::make('Atención al afiliado'),
                NavigationGroup::make('Afiliados'),
                NavigationGroup::make('Gestión web'),
                NavigationGroup::make('Configuración'),
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
