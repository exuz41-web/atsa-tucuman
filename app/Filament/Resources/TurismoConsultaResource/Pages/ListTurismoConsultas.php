<?php

namespace App\Filament\Resources\TurismoConsultaResource\Pages;

use App\Filament\Resources\TurismoConsultaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTurismoConsultas extends ListRecords
{
    protected static string $resource = TurismoConsultaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
