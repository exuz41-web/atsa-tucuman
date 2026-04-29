<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Support\GenericResource;
use App\Models\SitePage;

class SitePageResource extends GenericResource
{
    protected static ?string $model = SitePage::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    protected static ?string $navigationGroup = 'Prensa y web pública';

    protected static ?string $navigationLabel = 'Páginas del sitio';

    protected static ?int $navigationSort = 70;

    protected static ?string $slug = 'site-pages';

    protected static bool $showInNavigation = false;
}