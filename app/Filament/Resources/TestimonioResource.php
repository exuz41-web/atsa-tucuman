<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Support\GenericResource;
use App\Models\Testimonio;

class TestimonioResource extends GenericResource
{
    protected static ?string $model = Testimonio::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-oval-left-ellipsis';

    protected static ?string $navigationGroup = 'Gestión web';

    protected static ?string $navigationLabel = 'Testimonios';

    protected static ?string $slug = 'testimonios';

    protected static bool $showInNavigation = false;
}