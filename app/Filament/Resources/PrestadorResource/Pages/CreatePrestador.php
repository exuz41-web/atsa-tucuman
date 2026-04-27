<?php

namespace App\Filament\Resources\PrestadorResource\Pages;

use App\Filament\Resources\PrestadorResource;
use App\Helpers\LogActividad;
use Filament\Resources\Pages\CreateRecord;

class CreatePrestador extends CreateRecord
{
    protected static string $resource = PrestadorResource::class;

    protected function afterCreate(): void
    {
        LogActividad::registrar('creo prestador', 'Prestador', $this->record->id, $this->record->nombre);
    }
}
