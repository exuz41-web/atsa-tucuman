<?php

namespace App\Filament\Resources\CentReciboResource\Pages;

use App\Filament\Resources\CentReciboResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCentRecibos extends ListRecords
{
    protected static string $resource = CentReciboResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
