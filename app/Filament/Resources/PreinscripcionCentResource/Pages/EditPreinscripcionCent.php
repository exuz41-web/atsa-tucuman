<?php

namespace App\Filament\Resources\PreinscripcionCentResource\Pages;

use App\Filament\Resources\PreinscripcionCentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPreinscripcionCent extends EditRecord
{
    protected static string $resource = PreinscripcionCentResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
