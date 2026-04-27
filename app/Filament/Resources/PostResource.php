<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Support\GenericResource;
use App\Models\Post;

class PostResource extends GenericResource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationGroup = 'Gestión web';

    protected static ?string $navigationLabel = 'Noticias';

    protected static ?string $slug = 'posts';

    protected static bool $showInNavigation = false;
}