<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissionAccess;
use App\Filament\Resources\EstablecimientoResource\Pages;
use App\Models\Establecimiento;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class EstablecimientoResource extends Resource
{
    use HasResourcePermissionAccess;

    protected static ?string $model = Establecimiento::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationGroup = 'Institución';

    protected static ?string $navigationLabel = 'Establecimientos';

    protected static ?string $modelLabel = 'establecimiento';

    protected static ?string $pluralModelLabel = 'establecimientos';

    protected static ?string $slug = 'establecimientos';

    protected static ?int $navigationSort = 2;

    protected static ?string $panelScope = 'admin';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Datos del establecimiento')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('nombre')
                        ->label('Nombre')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug((string) $state)))
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->maxLength(255)
                        ->unique(Establecimiento::class, 'slug', ignoreRecord: true)
                        ->columnSpanFull(),

                    Forms\Components\Select::make('tipo')
                        ->label('Tipo')
                        ->options([
                            'hospital'    => 'Hospital',
                            'clinica'     => 'Clínica',
                            'sanatorio'   => 'Sanatorio',
                            'farmacia'    => 'Farmacia',
                            'laboratorio' => 'Laboratorio',
                            'otro'        => 'Otro',
                        ])
                        ->native(false),

                    Forms\Components\TextInput::make('sector')
                        ->label('Sector')
                        ->maxLength(255),

                    Forms\Components\Select::make('filial_id')
                        ->label('Filial')
                        ->relationship('filial', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false)
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('direccion')
                        ->label('Dirección')
                        ->maxLength(255),

                    Forms\Components\TextInput::make('localidad')
                        ->label('Localidad')
                        ->maxLength(255),

                    Forms\Components\TextInput::make('telefono')
                        ->label('Teléfono')
                        ->tel()
                        ->maxLength(80),

                    Forms\Components\TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('responsable')
                        ->label('Responsable')
                        ->maxLength(255),

                    Forms\Components\Toggle::make('activo')
                        ->label('Activo')
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('nombre')
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('tipo')
                    ->label('Tipo')
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'hospital'    => 'Hospital',
                        'clinica'     => 'Clínica',
                        'sanatorio'   => 'Sanatorio',
                        'farmacia'    => 'Farmacia',
                        'laboratorio' => 'Laboratorio',
                        default       => ucfirst((string) $state),
                    })
                    ->color('info'),

                Tables\Columns\TextColumn::make('filial.name')
                    ->label('Filial')
                    ->placeholder('—')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('localidad')
                    ->label('Localidad')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('responsable')
                    ->label('Responsable')
                    ->placeholder('—')
                    ->toggleable(),

                Tables\Columns\IconColumn::make('activo')
                    ->label('Activo')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('filial_id')
                    ->label('Filial')
                    ->relationship('filial', 'name'),
                Tables\Filters\SelectFilter::make('tipo')
                    ->label('Tipo')
                    ->options([
                        'hospital'    => 'Hospital',
                        'clinica'     => 'Clínica',
                        'sanatorio'   => 'Sanatorio',
                        'farmacia'    => 'Farmacia',
                        'laboratorio' => 'Laboratorio',
                        'otro'        => 'Otro',
                    ]),
                Tables\Filters\TernaryFilter::make('activo')->label('Activo'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Editar'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListEstablecimientos::route('/'),
            'create' => Pages\CreateEstablecimiento::route('/create'),
            'edit'   => Pages\EditEstablecimiento::route('/{record}/edit'),
        ];
    }
}
