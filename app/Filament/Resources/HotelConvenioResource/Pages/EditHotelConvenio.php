<?php

namespace App\Filament\Resources\HotelConvenioResource\Pages;

use App\Filament\Resources\HotelConvenioResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHotelConvenio extends EditRecord
{
    protected static string $resource = HotelConvenioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
