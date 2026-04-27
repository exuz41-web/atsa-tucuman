<?php

namespace App\Filament\Resources\CentUserResource\Pages;

use App\Filament\Resources\CentUserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCentUser extends EditRecord
{
    protected static string $resource = CentUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
