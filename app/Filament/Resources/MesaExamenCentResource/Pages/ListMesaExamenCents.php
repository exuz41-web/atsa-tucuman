<?php

namespace App\Filament\Resources\MesaExamenCentResource\Pages;

use App\Filament\Resources\MesaExamenCentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMesaExamenCents extends ListRecords
{
    protected static string $resource = MesaExamenCentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
