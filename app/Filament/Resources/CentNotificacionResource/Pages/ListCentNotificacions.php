<?php

namespace App\Filament\Resources\CentNotificacionResource\Pages;

use App\Filament\Resources\CentNotificacionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCentNotificacions extends ListRecords
{
    protected static string $resource = CentNotificacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
