<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Support\GenericResource;
use App\Models\Materia;

class MateriaResource extends GenericResource
{
    protected static ?string $model = Materia::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationGroup = 'Gestión académica';

    protected static ?string $navigationLabel = 'Materias';

    protected static ?int $navigationSort = 30;

    protected static ?string $slug = 'materias';

    protected static ?string $panelScope = 'cent';
}