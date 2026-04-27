<?php

namespace App\Filament\Concerns;

use App\Models\User;

trait HasPerfilInternoAccess
{
    /**
     * Mapa de recursos a perfiles internos que pueden acceder.
     */
    private static function perfilInternoMapa(): array
    {
        $clase = static::class;

        return [
            // Padrón sindical
            'App\Filament\Resources\UserResource' => ['padron', 'responsable_filial', 'gerencia_general'],
            'App\Filament\Resources\DelegadoResource' => ['padron', 'gerencia_general'],
            'App\Filament\Resources\EstablecimientoResource' => ['padron', 'gerencia_general'],
            'App\Filament\Resources\FilialResource' => ['gerencia_general'],
            'App\Filament\Resources\SecretariaResource' => ['secretaria', 'gerencia_general'],

            // Atención al afiliado
            'App\Filament\Resources\PedidoResource' => ['recepcion', 'responsable_filial', 'gerencia_general'],
            'App\Filament\Resources\ConsultaResource' => ['recepcion', 'consulta', 'responsable_filial', 'gerencia_general'],
            'App\Filament\Resources\SolicitudAfiliacionResource' => ['recepcion', 'padron', 'gerencia_general'],
            'App\Filament\Resources\SolicitudBeneficioResource' => ['recepcion', 'gerencia_general'],
            'App\Filament\Resources\TramiteResource' => ['recepcion', 'consulta', 'gerencia_general'],
            'App\Filament\Resources\TurismoConsultaResource' => ['recepcion', 'consulta', 'gerencia_general'],
            'App\Filament\Resources\BeneficioResource' => ['recepcion', 'secretaria', 'gerencia_general'],

            // Institución / Editor del sitio
            'App\Filament\Resources\AutoridadResource' => ['secretaria', 'gerencia_general'],
            'App\Filament\Resources\DocumentoResource' => ['secretaria', 'gerencia_general'],
            'App\Filament\Resources\EfemerideResource' => ['secretaria', 'gerencia_general'],
            'App\Filament\Resources\EscalaSalarialResource' => ['secretaria', 'gerencia_general'],
            'App\Filament\Resources\PostResource' => ['secretaria', 'gerencia_general'],
            'App\Filament\Resources\DescargaResource' => ['secretaria', 'gerencia_general'],
            'App\Filament\Resources\TestimonioResource' => ['secretaria', 'gerencia_general'],
            'App\Filament\Resources\VisualBlockResource' => ['secretaria', 'gerencia_general'],
            'App\Filament\Resources\PageSectionResource' => ['secretaria', 'gerencia_general'],
            'App\Filament\Resources\HotelConvenioResource' => ['secretaria', 'gerencia_general'],

            // Configuración
            'App\Filament\Resources\SiteSettingResource' => ['gerencia_general'],
        ][$clase] ?? [];
    }

    private static function perfilPuedeAcceder(): bool
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return false;
        }

        // Admin ATSA: acceso total
        if ($user->role === 'admin') {
            return true;
        }

        $perfiles = static::perfilInternoMapa();

        return in_array($user->perfil_interno, $perfiles, true);
    }

    public static function canViewAny(): bool
    {
        return static::perfilPuedeAcceder();
    }

    public static function canCreate(): bool
    {
        return static::perfilPuedeAcceder();
    }

    public static function canEdit(): bool
    {
        return static::perfilPuedeAcceder();
    }

    public static function canDelete(): bool
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return false;
        }

        return $user->role === 'admin' || $user->perfil_interno === 'gerencia_general';
    }
}
