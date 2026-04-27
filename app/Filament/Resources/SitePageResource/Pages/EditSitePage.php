<?php

namespace App\Filament\Resources\SitePageResource\Pages;

use App\Filament\Resources\SitePageResource;
use App\Models\SitePage;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSitePage extends EditRecord
{
    protected static string $resource = SitePageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('preview')
                ->label('Ver página pública')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->color('gray')
                ->url(fn () => match ($this->record->slug) {
                    'home'       => '/',
                    'sindicato'  => '/el-sindicato',
                    'gremial'    => '/gremial',
                    'turismo'    => '/turismo',
                    'afiliados'  => '/afiliados',
                    'filiales'   => '/filiales',
                    'delegados'  => '/delegados',
                    'documentos' => '/documentos',
                    'contacto'   => '/contacto',
                    default      => '/',
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
