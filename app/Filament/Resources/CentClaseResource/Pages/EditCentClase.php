<?php

namespace App\Filament\Resources\CentClaseResource\Pages;

use App\Filament\Resources\CentClaseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCentClase extends EditRecord
{
    protected static string $resource = CentClaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
