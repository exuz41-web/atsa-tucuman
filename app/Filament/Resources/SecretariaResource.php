<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Support\GenericResource;
use App\Models\Secretaria;

class SecretariaResource extends GenericResource
{
    protected static ?string $model = Secretaria::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationGroup = 'Institución';

    protected static ?string $navigationLabel = 'Secretarías';

    protected static ?string $slug = 'secretarias';
}