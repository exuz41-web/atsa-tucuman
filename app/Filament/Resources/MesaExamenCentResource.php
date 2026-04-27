<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Support\GenericResource;
use App\Models\MesaExamenCent;

class MesaExamenCentResource extends GenericResource
{
    protected static ?string $model = MesaExamenCent::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = 'Académico';

    protected static ?string $navigationLabel = 'Mesas de examen';

    protected static ?string $slug = 'mesas-examen';

    protected static ?string $panelScope = 'cent';
}