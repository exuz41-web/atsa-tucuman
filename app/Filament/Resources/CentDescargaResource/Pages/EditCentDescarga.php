<?php

namespace App\Filament\Resources\CentDescargaResource\Pages;

use App\Filament\Resources\CentDescargaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCentDescarga extends EditRecord
{
    protected static string $resource = CentDescargaResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
