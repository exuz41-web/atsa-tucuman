<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Support\GenericResource;
use App\Models\PageSection;

class PageSectionResource extends GenericResource
{
    protected static ?string $model = PageSection::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Prensa y web pública';

    protected static ?string $navigationLabel = 'Secciones del sitio';

    protected static ?int $navigationSort = 80;

    protected static ?string $slug = 'secciones-sitio';

    protected static bool $showInNavigation = false;
}