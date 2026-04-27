<?php

namespace App\Filament\Resources\SolicitudAfiliacionResource\Pages;

use App\Filament\Resources\SolicitudAfiliacionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSolicitudAfiliacion extends EditRecord
{
    protected static string $resource = SolicitudAfiliacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
