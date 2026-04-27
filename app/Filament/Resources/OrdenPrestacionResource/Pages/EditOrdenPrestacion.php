<?php

namespace App\Filament\Resources\OrdenPrestacionResource\Pages;

use App\Filament\Resources\OrdenPrestacionResource;
use App\Helpers\LogActividad;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrdenPrestacion extends EditRecord
{
    protected static string $resource = OrdenPrestacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        LogActividad::registrar('edito orden de prestacion', 'OrdenPrestacion', $this->record->id, $this->record->codigo);
    }
}
