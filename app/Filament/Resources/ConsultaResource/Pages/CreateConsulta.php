<?php

namespace App\Filament\Resources\ConsultaResource\Pages;

use App\Filament\Resources\ConsultaResource;
use App\Helpers\LogActividad;
use Filament\Resources\Pages\CreateRecord;

class CreateConsulta extends CreateRecord
{
    protected static string $resource = ConsultaResource::class;

    protected function afterCreate(): void
    {
        LogActividad::registrar('creo consulta', 'Consulta', $this->record->id, $this->record->asunto);
    }
}
