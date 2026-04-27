<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Support\GenericResource;
use App\Models\Nota;

class NotaResource extends GenericResource
{
    protected static ?string $model = Nota::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    protected static ?string $navigationGroup = 'Académico';

    protected static ?string $navigationLabel = 'Notas';

    protected static ?string $slug = 'notas';

    protected static ?string $panelScope = 'cent';
}