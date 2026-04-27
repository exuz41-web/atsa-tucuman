<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Support\GenericResource;
use App\Models\Filial;

class FilialResource extends GenericResource
{
    protected static ?string $model = Filial::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $navigationGroup = 'Institución';

    protected static ?string $navigationLabel = 'Filiales';

    protected static ?string $slug = 'filials';
}