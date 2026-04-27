<?php

namespace App\Filament\Resources\OrdenPrestacionResource\Pages;

use App\Filament\Resources\OrdenPrestacionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrdenPrestacions extends ListRecords
{
    protected static string $resource = OrdenPrestacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
