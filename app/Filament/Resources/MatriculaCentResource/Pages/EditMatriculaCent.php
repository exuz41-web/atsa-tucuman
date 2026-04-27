<?php

namespace App\Filament\Resources\MatriculaCentResource\Pages;

use App\Filament\Resources\MatriculaCentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMatriculaCent extends EditRecord
{
    protected static string $resource = MatriculaCentResource::class;
    protected function getHeaderActions(): array { return [Actions\DeleteAction::make()]; }
}
