<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Support\GenericResource;
use App\Models\PostComment;

class PostCommentResource extends GenericResource
{
    protected static ?string $model = PostComment::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';

    protected static ?string $navigationGroup = 'Gestión web';

    protected static ?string $navigationLabel = 'Comentarios';

    protected static ?string $slug = 'comentarios';

    protected static bool $showInNavigation = false;
}