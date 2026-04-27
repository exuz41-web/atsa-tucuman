<?php

namespace App\Filament\Resources\EscalaSalarialResource\Pages;

use App\Filament\Resources\EscalaSalarialResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEscalaSalarial extends EditRecord
{
    protected static string $resource = EscalaSalarialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
