<?php

namespace App\Filament\Resources\SolicitudBeneficioResource\Pages;

use App\Filament\Resources\SolicitudBeneficioResource;
use App\Models\SolicitudBeneficio;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListSolicitudBeneficios extends ListRecords
{
    protected static string $resource = SolicitudBeneficioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('exportar_csv')
                ->label('Exportar CSV')
                ->icon('heroicon-o-arrow-down-tray')
                ->url(route('panel.reportes.atencion.solicitudes-beneficios'))
                ->openUrlInNewTab(),
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'todos' => Tab::make('Todos'),

            'pendientes' => Tab::make('Pendientes')
                ->badge(SolicitudBeneficio::where('estado', 'pendiente')->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $q) => $q->where('estado', 'pendiente')),

            'en_revision' => Tab::make('En revisión')
                ->badge(SolicitudBeneficio::where('estado', 'en_revision')->count())
                ->badgeColor('info')
                ->modifyQueryUsing(fn (Builder $q) => $q->where('estado', 'en_revision')),

            'aprobadas' => Tab::make('Aprobadas')
                ->modifyQueryUsing(fn (Builder $q) => $q->where('estado', 'aprobada')),

            'entregadas' => Tab::make('Entregadas')
                ->modifyQueryUsing(fn (Builder $q) => $q->where('estado', 'entregada')),

            'rechazadas' => Tab::make('Rechazadas')
                ->modifyQueryUsing(fn (Builder $q) => $q->where('estado', 'rechazada')),
        ];
    }
}
