<?php

namespace App\Filament\Resources\CentClaseResource\Pages;

use App\Filament\Resources\CentClaseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCentClases extends ListRecords
{
    protected static string $resource = CentClaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
