<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Support\GenericResource;
use App\Models\AvisoCent;

class AvisoCentResource extends GenericResource
{
    protected static ?string $model = AvisoCent::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $navigationGroup = 'Comunicación CENT';

    protected static ?string $navigationLabel = 'Avisos';

    protected static ?int $navigationSort = 40;

    protected static ?string $slug = 'avisos-cent';

    protected static ?string $panelScope = 'cent';
}