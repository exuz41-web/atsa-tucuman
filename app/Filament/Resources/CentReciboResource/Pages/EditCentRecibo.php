<?php

namespace App\Filament\Resources\CentReciboResource\Pages;

use App\Filament\Resources\CentReciboResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCentRecibo extends EditRecord
{
    protected static string $resource = CentReciboResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
