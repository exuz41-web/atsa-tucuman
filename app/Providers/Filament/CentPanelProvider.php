<?php

namespace App\Providers\Filament;

use App\Filament\Resources\AvisoCentResource;
use App\Filament\Resources\CarreraResource;
use App\Filament\Resources\CentDescargaResource;
use App\Filament\Resources\CentHorarioResource;
use App\Filament\Resources\CentActivityLogResource;
use App\Filament\Resources\CentClaseResource;
use App\Filament\Resources\CentConfiguracionResource;
use App\Filament\Resources\CentCuotaResource;
use App\Filament\Resources\CentEntregaTrabajoResource;
use App\Filament\Resources\CentEquivalenciaResource;
use App\Filament\Resources\CentEventoResource;
use App\Filament\Resources\CentLegajoDocumentoResource;
use App\Filament\Resources\CentMaterialResource;
use App\Filament\Resources\CentNotificacionResource;
use App\Filament\Resources\CentPermisoExamenResource;
use App\Filament\Resources\CentReciboResource;
use App\Filament\Resources\CentSedeResource;
use App\Filament\Resources\CentSitePageResource;
use App\Filament\Resources\CentTrabajoPracticoResource;
use App\Filament\Resources\CentUserResource;
use App\Filament\Resources\ComisionResource;
use App\Filament\Resources\InscripcionResource;
use App\Filament\Resources\MateriaResource;
use App\Filament\Resources\MatriculaCentResource;
use App\Filament\Resources\MesaExamenCentResource;
use App\Filament\Resources\NotaResource;
use App\Filament\Resources\PreinscripcionCentResource;
use App\Filament\Widgets\CentStatsOverview;
use App\Filament\Widgets\CentUltimasPreinscripciones;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class CentPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $panel = $panel
            ->id('cent')
            ->path('cent-admin')
            ->login()
            ->colors([
                'primary' => Color::hex('#1e3a5f'),
                'info' => Color::hex('#49beff'),
                'success' => Color::hex('#1D9E75'),
                'warning' => Color::hex('#f59e0b'),
                'danger' => Color::hex('#c0392b'),
            ])
            ->brandName('CENT Nro 74')
            ->font('Plus Jakarta Sans')
            ->darkMode(false)
            ->sidebarCollapsibleOnDesktop()
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->resources([
                CentSitePageResource::class,
                CentSedeResource::class,
                CentUserResource::class,
                CarreraResource::class,
                MateriaResource::class,
                PreinscripcionCentResource::class,
                MatriculaCentResource::class,
                ComisionResource::class,
                InscripcionResource::class,
                NotaResource::class,
                AvisoCentResource::class,
                CentClaseResource::class,
                CentMaterialResource::class,
                CentTrabajoPracticoResource::class,
                CentEntregaTrabajoResource::class,
                MesaExamenCentResource::class,
                CentEventoResource::class,
                CentDescargaResource::class,
                CentHorarioResource::class,
                CentLegajoDocumentoResource::class,
                CentCuotaResource::class,
                CentReciboResource::class,
                CentNotificacionResource::class,
                CentPermisoExamenResource::class,
                CentEquivalenciaResource::class,
                CentActivityLogResource::class,
                CentConfiguracionResource::class,
            ])
            ->navigationGroups([
                NavigationGroup::make('Web CENT'),
                NavigationGroup::make('Gestión académica'),
                NavigationGroup::make('Ingresantes CENT'),
                NavigationGroup::make('Alumnos y legajos'),
                NavigationGroup::make('Aula virtual'),
                NavigationGroup::make('Administración CENT'),
                NavigationGroup::make('Comunicación CENT'),
                NavigationGroup::make('Auditoría y configuración'),
            ])
            ->widgets([
                CentStatsOverview::class,
                CentUltimasPreinscripciones::class,
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

        if (filled(env('CENT_DOMAIN'))) {
            $panel->domain(env('CENT_DOMAIN'));
        }

        return $panel;
    }
}
