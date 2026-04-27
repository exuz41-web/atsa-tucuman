<?php

namespace App\Filament\Resources\CentActivityLogResource\Pages;

use App\Filament\Resources\CentActivityLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCentActivityLogs extends ListRecords
{
    protected static string $resource = CentActivityLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
