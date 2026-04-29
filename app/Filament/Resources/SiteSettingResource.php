<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Support\GenericResource;
use App\Models\SiteSetting;

class SiteSettingResource extends GenericResource
{
    protected static ?string $model = SiteSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Configuración y seguridad';

    protected static ?string $navigationLabel = 'Configuración del sitio';

    protected static ?int $navigationSort = 90;

    protected static ?string $slug = 'configuracion-sitio';

    protected static bool $showInNavigation = false;
}