<?php

namespace App\Filament\Resources\CentLegajoDocumentoResource\Pages;

use App\Filament\Resources\CentLegajoDocumentoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCentLegajoDocumento extends EditRecord
{
    protected static string $resource = CentLegajoDocumentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
