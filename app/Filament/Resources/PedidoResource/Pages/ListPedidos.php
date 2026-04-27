<?php

namespace App\Filament\Resources\PedidoResource\Pages;

use App\Filament\Resources\PedidoResource;
use App\Models\Pedido;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListPedidos extends ListRecords
{
    protected static string $resource = PedidoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('exportar_csv')
                ->label('Exportar CSV')
                ->icon('heroicon-o-arrow-down-tray')
                ->url(route('panel.reportes.atencion.pedidos'))
                ->openUrlInNewTab(),
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'todos' => Tab::make('Todos'),

            'pendientes' => Tab::make('Pendientes')
                ->badge(Pedido::where('estado', 'pendiente')->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $q) => $q->where('estado', 'pendiente')),

            'en_revision' => Tab::make('En revisión')
                ->badge(Pedido::where('estado', 'en_revision')->count())
                ->badgeColor('info')
                ->modifyQueryUsing(fn (Builder $q) => $q->where('estado', 'en_revision')),

            'aprobados' => Tab::make('Aprobados')
                ->modifyQueryUsing(fn (Builder $q) => $q->where('estado', 'aprobado')),

            'entregados' => Tab::make('Entregados')
                ->modifyQueryUsing(fn (Builder $q) => $q->where('estado', 'entregado')),

            'rechazados' => Tab::make('Rechazados')
                ->modifyQueryUsing(fn (Builder $q) => $q->where('estado', 'rechazado')),
        ];
    }
}
