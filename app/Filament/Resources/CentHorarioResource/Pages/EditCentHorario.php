<?php

namespace App\Filament\Resources\CentHorarioResource\Pages;

use App\Filament\Resources\CentHorarioResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCentHorario extends EditRecord
{
    protected static string $resource = CentHorarioResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
