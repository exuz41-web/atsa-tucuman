<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Support\GenericResource;
use App\Models\EscalaSalarial;

class EscalaSalarialResource extends GenericResource
{
    protected static ?string $model = EscalaSalarial::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Gremial';

    protected static ?string $navigationLabel = 'Escalas salariales';

    protected static ?string $slug = 'escalas-salariales';
}