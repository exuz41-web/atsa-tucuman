<?php

namespace App\Filament\Resources\CentUserResource\Pages;

use App\Filament\Resources\CentUserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCentUsers extends ListRecords
{
    protected static string $resource = CentUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
