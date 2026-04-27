<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Support\GenericResource;
use App\Models\Tramite;

class TramiteResource extends GenericResource
{
    protected static ?string $model = Tramite::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?string $navigationGroup = 'Atención al afiliado';

    protected static ?string $navigationLabel = 'Trámites';

    protected static ?string $slug = 'tramites';
}