<?php

namespace App\Filament\Resources\CentSedeResource\Pages;

use App\Filament\Resources\CentSedeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCentSedes extends ListRecords
{
    protected static string $resource = CentSedeResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
