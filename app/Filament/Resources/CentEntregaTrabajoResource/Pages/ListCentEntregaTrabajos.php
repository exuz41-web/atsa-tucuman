<?php

namespace App\Filament\Resources\CentEntregaTrabajoResource\Pages;

use App\Filament\Resources\CentEntregaTrabajoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCentEntregaTrabajos extends ListRecords
{
    protected static string $resource = CentEntregaTrabajoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
