<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Support\GenericResource;
use App\Models\Carrera;

class CarreraResource extends GenericResource
{
    protected static ?string $model = Carrera::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Gestión académica';

    protected static ?string $navigationLabel = 'Carreras';

    protected static ?int $navigationSort = 20;

    protected static ?string $slug = 'carreras';

    protected static ?string $panelScope = 'cent';
}