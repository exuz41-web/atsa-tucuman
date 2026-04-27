<?php

namespace App\Filament\Resources\PreinscripcionCentResource\Pages;

use App\Filament\Resources\PreinscripcionCentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPreinscripcionCents extends ListRecords
{
    protected static string $resource = PreinscripcionCentResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
