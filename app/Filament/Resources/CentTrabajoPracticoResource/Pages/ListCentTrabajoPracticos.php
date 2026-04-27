<?php

namespace App\Filament\Resources\CentTrabajoPracticoResource\Pages;

use App\Filament\Resources\CentTrabajoPracticoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCentTrabajoPracticos extends ListRecords
{
    protected static string $resource = CentTrabajoPracticoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
