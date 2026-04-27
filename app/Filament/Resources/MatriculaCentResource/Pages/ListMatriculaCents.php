<?php

namespace App\Filament\Resources\MatriculaCentResource\Pages;

use App\Filament\Resources\MatriculaCentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMatriculaCents extends ListRecords
{
    protected static string $resource = MatriculaCentResource::class;
    protected function getHeaderActions(): array { return [Actions\CreateAction::make()]; }
}
