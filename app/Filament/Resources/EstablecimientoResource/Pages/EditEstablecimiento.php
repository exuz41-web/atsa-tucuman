<?php

namespace App\Filament\Resources\EstablecimientoResource\Pages;

use App\Filament\Resources\EstablecimientoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEstablecimiento extends EditRecord
{
    protected static string $resource = EstablecimientoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
