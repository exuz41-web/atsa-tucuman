<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CentActivityLogResource\Pages;
use App\Models\CentActivityLog;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CentActivityLogResource extends Resource
{
    protected static ?string $model = CentActivityLog::class;
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationGroup = 'Auditoría';
    protected static ?string $navigationLabel = 'Actividad';
    protected static ?string $modelLabel = 'actividad';
    protected static ?string $pluralModelLabel = 'auditoría';
    protected static ?string $slug = 'auditoria';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('user_id')->relationship('user', 'name')->disabled(),
            Forms\Components\TextInput::make('accion')->disabled(),
            Forms\Components\TextInput::make('modelo')->disabled(),
            Forms\Components\TextInput::make('modelo_id')->disabled(),
            Forms\Components\Textarea::make('descripcion')->disabled()->columnSpanFull(),
            Forms\Components\TextInput::make('ip')->disabled(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->label('Fecha')->dateTime('d/m/Y H:i')->sortable(),
                Tables\Columns\TextColumn::make('user.name')->label('Usuario')->placeholder('Sistema')->searchable(),
                Tables\Columns\TextColumn::make('accion')->label('Acción')->searchable(),
                Tables\Columns\TextColumn::make('modelo')->label('Módulo')->badge()->placeholder('-'),
                Tables\Columns\TextColumn::make('descripcion')->limit(60)->searchable(),
                Tables\Columns\TextColumn::make('ip')->label('IP')->toggleable(),
            ])
            ->filters([])
            ->actions([Tables\Actions\ViewAction::make()])
            ->bulkActions([]);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Filament::getCurrentPanel()?->getId() === 'cent';
    }

    public static function canAccess(): bool
    {
        return Filament::getCurrentPanel()?->getId() === 'cent';
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCentActivityLogs::route('/'),
            'create' => Pages\CreateCentActivityLog::route('/create'),
            'edit' => Pages\EditCentActivityLog::route('/{record}/edit'),
        ];
    }
}
