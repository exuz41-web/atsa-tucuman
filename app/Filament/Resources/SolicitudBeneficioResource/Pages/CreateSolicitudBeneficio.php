<?php

namespace App\Filament\Resources\SolicitudBeneficioResource\Pages;

use App\Filament\Resources\SolicitudBeneficioResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSolicitudBeneficio extends CreateRecord
{
    protected static string $resource = SolicitudBeneficioResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['derivado_por'] ??= auth()->id();

        return $data;
    }
}
