<?php

namespace App\Filament\Resources\CentHorarioResource\Pages;

use App\Filament\Resources\CentHorarioResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCentHorarios extends ListRecords
{
    protected static string $resource = CentHorarioResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
