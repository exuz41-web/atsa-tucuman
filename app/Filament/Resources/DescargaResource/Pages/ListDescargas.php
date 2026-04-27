<?php

namespace App\Filament\Resources\DescargaResource\Pages;

use App\Filament\Resources\DescargaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDescargas extends ListRecords
{
    protected static string $resource = DescargaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
