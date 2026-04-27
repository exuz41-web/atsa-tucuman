<?php

namespace App\Filament\Resources\CentEventoResource\Pages;

use App\Filament\Resources\CentEventoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCentEventos extends ListRecords
{
    protected static string $resource = CentEventoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
