<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Support\GenericResource;
use App\Models\PreinscripcionCent;

class PreinscripcionCentResource extends GenericResource
{
    protected static ?string $model = PreinscripcionCent::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-plus';

    protected static ?string $navigationGroup = 'Ingresantes';

    protected static ?string $navigationLabel = 'Preinscripciones';

    protected static ?string $slug = 'preinscripciones-cent';

    protected static ?string $panelScope = 'cent';
}
