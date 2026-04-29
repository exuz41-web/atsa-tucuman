<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class AdminModuleShortcuts extends Widget
{
    protected static string $view = 'filament.widgets.admin-module-shortcuts';

    protected static ?int $sort = 0;

    protected int | string | array $columnSpan = 'full';
}
