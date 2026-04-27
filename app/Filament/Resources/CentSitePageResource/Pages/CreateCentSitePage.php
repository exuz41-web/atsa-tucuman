<?php

namespace App\Filament\Resources\CentSitePageResource\Pages;

use App\Filament\Resources\CentSitePageResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCentSitePage extends CreateRecord
{
    protected static string $resource = CentSitePageResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Página creada correctamente';
    }
}
