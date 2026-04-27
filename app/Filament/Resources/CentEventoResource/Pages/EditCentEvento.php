<?php

namespace App\Filament\Resources\CentEventoResource\Pages;

use App\Filament\Resources\CentEventoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCentEvento extends EditRecord
{
    protected static string $resource = CentEventoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
