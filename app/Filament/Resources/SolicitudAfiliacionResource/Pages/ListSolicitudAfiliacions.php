<?php

namespace App\Filament\Resources\SolicitudAfiliacionResource\Pages;

use App\Filament\Resources\SolicitudAfiliacionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSolicitudAfiliacions extends ListRecords
{
    protected static string $resource = SolicitudAfiliacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
