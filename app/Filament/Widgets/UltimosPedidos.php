<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\PedidoResource;
use App\Models\Pedido;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class UltimosPedidos extends TableWidget
{
    protected static ?int $sort = 2;

    protected static bool $isLazy = false;

    protected int | string | array $columnSpan = 'full';

    protected function getTableHeading(): string
    {
        return 'Últimos pedidos';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Pedido::query()->with('afiliado')->latest()->limit(5))
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('afiliado.name')
                    ->label('Afiliado')
                    ->placeholder('Sin afiliado'),
                Tables\Columns\TextColumn::make('tipo')
                    ->label('Tipo')
                    ->formatStateUsing(fn (?string $state): string => PedidoResource::tipos()[$state] ?? ucfirst((string) $state)),
                Tables\Columns\TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => PedidoResource::estados()[$state] ?? ucfirst((string) $state))
                    ->color(fn (?string $state): string => PedidoResource::estadoColor($state)),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i'),
            ]);
    }
}
