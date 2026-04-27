<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Support\GenericResource;
use App\Models\Efemeride;

class EfemerideResource extends GenericResource
{
    protected static ?string $model = Efemeride::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Gestión web';

    protected static ?string $navigationLabel = 'Efemérides';

    protected static ?string $slug = 'efemerides';

    protected static bool $showInNavigation = false;
}