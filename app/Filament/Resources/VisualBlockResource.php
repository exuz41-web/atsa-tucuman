<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Support\GenericResource;
use App\Models\VisualBlock;

class VisualBlockResource extends GenericResource
{
    protected static ?string $model = VisualBlock::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Prensa y web pública';

    protected static ?string $navigationLabel = 'Bloques visuales';

    protected static ?int $navigationSort = 60;

    protected static ?string $slug = 'bloques-visuales';

    protected static bool $showInNavigation = false;
}