<?php

namespace App\Filament\Resources\CentPermisoExamenResource\Pages;

use App\Filament\Resources\CentPermisoExamenResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCentPermisoExamens extends ListRecords
{
    protected static string $resource = CentPermisoExamenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
