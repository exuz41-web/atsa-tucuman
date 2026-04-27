<?php

namespace App\Filament\Resources\DelegadoResource\Pages;

use App\Filament\Resources\DelegadoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDelegado extends EditRecord
{
    protected static string $resource = DelegadoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
