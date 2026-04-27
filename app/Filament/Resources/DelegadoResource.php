<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Support\GenericResource;
use App\Models\Delegado;

class DelegadoResource extends GenericResource
{
    protected static ?string $model = Delegado::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationGroup = 'Gremial';

    protected static ?string $navigationLabel = 'Delegados';

    protected static ?string $slug = 'delegados';
}