<?php

namespace App\Filament\Resources\CentDescargaResource\Pages;

use App\Filament\Resources\CentDescargaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCentDescargas extends ListRecords
{
    protected static string $resource = CentDescargaResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
