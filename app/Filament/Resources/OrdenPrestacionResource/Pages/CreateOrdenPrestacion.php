<?php

namespace App\Filament\Resources\OrdenPrestacionResource\Pages;

use App\Filament\Resources\OrdenPrestacionResource;
use App\Helpers\LogActividad;
use Filament\Resources\Pages\CreateRecord;

class CreateOrdenPrestacion extends CreateRecord
{
    protected static string $resource = OrdenPrestacionResource::class;

    protected function afterCreate(): void
    {
        LogActividad::registrar('creo orden de prestacion', 'OrdenPrestacion', $this->record->id, $this->record->codigo);
    }
}
