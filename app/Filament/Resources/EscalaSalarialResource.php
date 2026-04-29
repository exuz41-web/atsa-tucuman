<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissionAccess;
use App\Filament\Resources\EscalaSalarialResource\Pages;
use App\Models\EscalaSalarial;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EscalaSalarialResource extends Resource
{
    use HasResourcePermissionAccess;

    protected static ?string $model = EscalaSalarial::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Institucional y gremial';

    protected static ?string $navigationLabel = 'Escalas salariales';

    protected static ?string $modelLabel = 'escala salarial';

    protected static ?string $pluralModelLabel = 'escalas salariales';

    protected static ?string $slug = 'escalas-salariales';

    protected static ?int $navigationSort = 30;

    protected static ?string $panelScope = 'admin';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Información')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('titulo')
                        ->label('Título')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('descripcion')
                        ->label('Descripción')
                        ->rows(3)
                        ->columnSpanFull(),

                    Forms\Components\DatePicker::make('vigente_desde')
                        ->label('Vigente desde')
                        ->displayFormat('d/m/Y'),

                    Forms\Components\DatePicker::make('vigente_hasta')
                        ->label('Vigente hasta')
                        ->displayFormat('d/m/Y')
                        ->after('vigente_desde'),

                    Forms\Components\Toggle::make('activo')
                        ->label('Activa / Publicada')
                        ->helperText('Solo las escalas activas se muestran en el portal.')
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Archivo')
                ->schema([
                    Forms\Components\FileUpload::make('archivo')
                        ->label('Archivo PDF')
                        ->acceptedFileTypes(['application/pdf'])
                        ->directory('escalas-salariales')
                        ->disk('public')
                        ->downloadable()
                        ->helperText('Subí el PDF de la escala salarial.')
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('vigente_desde', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('titulo')
                    ->label('Título')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('vigente_desde')
                    ->label('Vigente desde')
                    ->date('d/m/Y')
                    ->placeholder('—')
                    ->sortable(),

                Tables\Columns\TextColumn::make('vigente_hasta')
                    ->label('Vigente hasta')
                    ->date('d/m/Y')
                    ->placeholder('Sin vencimiento'),

                Tables\Columns\IconColumn::make('archivo')
                    ->label('PDF')
                    ->boolean()
                    ->trueIcon('heroicon-o-document')
                    ->falseIcon('heroicon-o-x-mark')
                    ->trueColor('success')
                    ->falseColor('gray'),

                Tables\Columns\IconColumn::make('activo')
                    ->label('Activa')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Cargada')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('activo')
                    ->label('Activa'),
            ])
            ->actions([
                Tables\Actions\Action::make('activar')
                    ->label('Activar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (EscalaSalarial $r) => ! $r->activo)
                    ->action(fn (EscalaSalarial $r) => $r->update(['activo' => true])),

                Tables\Actions\Action::make('desactivar')
                    ->label('Desactivar')
                    ->icon('heroicon-o-x-circle')
                    ->color('gray')
                    ->visible(fn (EscalaSalarial $r) => $r->activo)
                    ->action(fn (EscalaSalarial $r) => $r->update(['activo' => false])),

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
            'index'  => Pages\ListEscalaSalarials::route('/'),
            'create' => Pages\CreateEscalaSalarial::route('/create'),
            'edit'   => Pages\EditEscalaSalarial::route('/{record}/edit'),
        ];
    }
}
