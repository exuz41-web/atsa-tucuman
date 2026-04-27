<?php

namespace App\Filament\Resources\AvisoCentResource\Pages;

use App\Filament\Resources\AvisoCentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAvisoCents extends ListRecords
{
    protected static string $resource = AvisoCentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
