<?php

namespace App\Filament\Resources\CentSitePageResource\Pages;

use App\Filament\Resources\CentSitePageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCentSitePage extends EditRecord
{
    protected static string $resource = CentSitePageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('preview')
                ->label('Ver página pública')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->color('gray')
                ->url(fn () => match ($this->record->slug) {
                    'cent_home'     => '/cent74/',
                    'cent_carreras' => '/cent74/carreras',
                    'cent_sedes'    => '/cent74/sedes',
                    'cent_faq'      => '/cent74/preguntas-frecuentes',
                    'cent_contacto' => '/cent74/contacto',
                    default         => '/cent74/',
                })
                ->openUrlInNewTab(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Página actualizada correctamente';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
