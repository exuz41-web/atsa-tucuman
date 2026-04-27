<?php

namespace App\Filament\Resources\CentEquivalenciaResource\Pages;

use App\Filament\Resources\CentEquivalenciaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCentEquivalencias extends ListRecords
{
    protected static string $resource = CentEquivalenciaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
