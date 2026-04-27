<?php

namespace App\Filament\Resources\VisualBlockResource\Pages;

use App\Filament\Resources\VisualBlockResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVisualBlocks extends ListRecords
{
    protected static string $resource = VisualBlockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
