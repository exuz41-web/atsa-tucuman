<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Support\GenericResource;
use App\Models\Descarga;

class DescargaResource extends GenericResource
{
    protected static ?string $model = Descarga::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-tray';

    protected static ?string $navigationGroup = 'Prensa y web pública';

    protected static ?string $navigationLabel = 'Descargas';

    protected static ?int $navigationSort = 40;

    protected static ?string $slug = 'descargas';

    protected static bool $showInNavigation = false;
}