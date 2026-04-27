<?php

namespace App\Filament\Resources\CentPermisoExamenResource\Pages;

use App\Filament\Resources\CentPermisoExamenResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCentPermisoExamen extends EditRecord
{
    protected static string $resource = CentPermisoExamenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
