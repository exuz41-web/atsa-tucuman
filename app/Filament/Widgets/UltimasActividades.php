<?php

namespace App\Filament\Widgets;

use App\Models\ActivityLog;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class UltimasActividades extends TableWidget
{
    protected static ?int $sort = 6;

    protected int | string | array $columnSpan = 2;

    protected function getTableHeading(): string
    {
        return 'Log de actividad';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => ActivityLog::query()->with('user')->latest()->limit(10))
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('accion')->label('Accion')->badge()->color('info'),
                Tables\Columns\TextColumn::make('modelo')->label('Modulo'),
                Tables\Columns\TextColumn::make('user.name')->label('Usuario')->placeholder('Sistema'),
                Tables\Columns\TextColumn::make('created_at')->label('Fecha')->dateTime('d/m/Y H:i'),
            ]);
    }
}
