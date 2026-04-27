<?php

namespace App\Filament\Resources\CentConfiguracionResource\Pages;

use App\Filament\Resources\CentConfiguracionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCentConfiguracion extends EditRecord
{
    protected static string $resource = CentConfiguracionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
