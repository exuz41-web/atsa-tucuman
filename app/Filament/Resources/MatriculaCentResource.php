<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Support\GenericResource;
use App\Models\MatriculaCent;

class MatriculaCentResource extends GenericResource
{
    protected static ?string $model = MatriculaCent::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationGroup = 'Alumnos';

    protected static ?string $navigationLabel = 'Matrículas';

    protected static ?string $slug = 'matriculas-cent';

    protected static ?string $panelScope = 'cent';
}