<?php

namespace App\Filament\Resources\CentEntregaTrabajoResource\Pages;

use App\Filament\Resources\CentEntregaTrabajoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCentEntregaTrabajo extends EditRecord
{
    protected static string $resource = CentEntregaTrabajoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
