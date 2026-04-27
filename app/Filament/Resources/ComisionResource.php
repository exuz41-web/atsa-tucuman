<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Support\GenericResource;
use App\Models\Comision;

class ComisionResource extends GenericResource
{
    protected static ?string $model = Comision::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Académico';

    protected static ?string $navigationLabel = 'Comisiones';

    protected static ?string $slug = 'comisiones';

    protected static ?string $panelScope = 'cent';
}