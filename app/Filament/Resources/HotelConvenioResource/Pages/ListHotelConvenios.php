<?php

namespace App\Filament\Resources\HotelConvenioResource\Pages;

use App\Filament\Resources\HotelConvenioResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHotelConvenios extends ListRecords
{
    protected static string $resource = HotelConvenioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
