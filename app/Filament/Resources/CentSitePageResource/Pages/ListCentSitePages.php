<?php

namespace App\Filament\Resources\CentSitePageResource\Pages;

use App\Filament\Resources\CentSitePageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCentSitePages extends ListRecords
{
    protected static string $resource = CentSitePageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('+ Crear página'),
        ];
    }
}
