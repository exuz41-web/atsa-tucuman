<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Support\GenericResource;
use App\Models\Establecimiento;

class EstablecimientoResource extends GenericResource
{
    protected static ?string $model = Establecimiento::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationGroup = 'Institución';

    protected static ?string $navigationLabel = 'Establecimientos';

    protected static ?string $slug = 'establecimientos';
}