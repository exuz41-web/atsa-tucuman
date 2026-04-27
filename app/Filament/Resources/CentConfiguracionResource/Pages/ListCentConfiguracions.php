<?php

namespace App\Filament\Resources\CentConfiguracionResource\Pages;

use App\Filament\Resources\CentConfiguracionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCentConfiguracions extends ListRecords
{
    protected static string $resource = CentConfiguracionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
