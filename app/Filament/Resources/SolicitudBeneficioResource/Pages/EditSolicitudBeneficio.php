<?php

namespace App\Filament\Resources\SolicitudBeneficioResource\Pages;

use App\Filament\Resources\SolicitudBeneficioResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSolicitudBeneficio extends EditRecord
{
    protected static string $resource = SolicitudBeneficioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
