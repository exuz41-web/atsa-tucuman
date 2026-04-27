<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Filament\Resources\ConsultaResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ConsultasRelationManager extends RelationManager
{
    protected static string $relationship = 'consultas';

    protected static ?string $title = 'Historial de consultas';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('tipo')
                    ->label('Tipo')
                    ->options(ConsultaResource::tipos())
                    ->required(),
                Forms\Components\TextInput::make('asunto')
                    ->label('Asunto')
                    ->required(),
                Forms\Components\Select::make('estado')
                    ->label('Estado')
                    ->options(ConsultaResource::estados())
                    ->required(),
                Forms\Components\Textarea::make('mensaje')
                    ->label('Mensaje')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('respuesta')
                    ->label('Respuesta')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('asunto')
            ->columns([
                Tables\Columns\TextColumn::make('tipo')
                    ->label('Tipo')
                    ->formatStateUsing(fn (?string $state): string => ConsultaResource::tipos()[$state] ?? ucfirst((string) $state)),
                Tables\Columns\TextColumn::make('asunto')
                    ->label('Asunto')
                    ->limit(45),
                Tables\Columns\TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => ConsultaResource::estados()[$state] ?? ucfirst((string) $state)),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->headerActions([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
    }
}
