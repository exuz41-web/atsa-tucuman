<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Support\GenericResource;
use App\Models\Beneficio;

class BeneficioResource extends GenericResource
{
    protected static ?string $model = Beneficio::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift';

    protected static ?string $navigationGroup = 'Afiliados';

    protected static ?string $navigationLabel = 'Beneficios';

    protected static ?string $slug = 'beneficios';
}