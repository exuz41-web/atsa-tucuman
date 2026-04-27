<?php

namespace App\Filament\Resources\CentCuotaResource\Pages;

use App\Filament\Resources\CentCuotaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCentCuotas extends ListRecords
{
    protected static string $resource = CentCuotaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
