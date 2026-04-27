<?php

namespace App\Filament\Resources\EfemerideResource\Pages;

use App\Filament\Resources\EfemerideResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEfemeride extends EditRecord
{
    protected static string $resource = EfemerideResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
