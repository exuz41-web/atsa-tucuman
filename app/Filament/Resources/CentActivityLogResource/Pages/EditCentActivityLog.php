<?php

namespace App\Filament\Resources\CentActivityLogResource\Pages;

use App\Filament\Resources\CentActivityLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCentActivityLog extends EditRecord
{
    protected static string $resource = CentActivityLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
