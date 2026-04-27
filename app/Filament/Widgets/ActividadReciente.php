<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ConsultaResource;
use App\Models\Consulta;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class ActividadReciente extends TableWidget
{
    protected static ?int $sort = 3;

    protected static bool $isLazy = false;

    protected int | string | array $columnSpan = 'full';

    protected function getTableHeading(): string
    {
        return 'Actividad reciente';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Consulta::query()->with('afiliado')->latest()->limit(5))
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('afiliado.name')
                    ->label('Afiliado')
                    ->placeholder('Sin afiliado'),
                Tables\Columns\TextColumn::make('asunto')
                    ->label('Asunto')
                    ->limit(45),
                Tables\Columns\TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => ConsultaResource::estados()[$state] ?? ucfirst((string) $state))
                    ->color(fn (?string $state): string => match ($state) {
                        'pendiente' => 'warning',
                        'en_proceso' => 'info',
                        'respondida' => 'success',
                        'cerrada' => 'gray',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i'),
            ]);
    }
}
