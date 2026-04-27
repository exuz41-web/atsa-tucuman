<?php

namespace App\Filament\Resources\CentTrabajoPracticoResource\Pages;

use App\Filament\Resources\CentTrabajoPracticoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCentTrabajoPractico extends EditRecord
{
    protected static string $resource = CentTrabajoPracticoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
