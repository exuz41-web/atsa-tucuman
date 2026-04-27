<?php

namespace App\Filament\Resources\CentMaterialResource\Pages;

use App\Filament\Resources\CentMaterialResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCentMaterials extends ListRecords
{
    protected static string $resource = CentMaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
