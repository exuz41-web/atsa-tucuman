<?php

namespace App\Filament\Resources\PrestadorResource\Pages;

use App\Filament\Resources\PrestadorResource;
use App\Helpers\LogActividad;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPrestador extends EditRecord
{
    protected static string $resource = PrestadorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        LogActividad::registrar('edito prestador', 'Prestador', $this->record->id, $this->record->nombre);
    }
}
