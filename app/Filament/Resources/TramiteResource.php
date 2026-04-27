<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissionAccess;
use App\Filament\Resources\TramiteResource\Pages;
use App\Models\Tramite;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TramiteResource extends Resource
{
    use HasResourcePermissionAccess;

    protected static ?string $model = Tramite::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?string $navigationGroup = 'Atención al afiliado';

    protected static ?string $navigationLabel = 'Trámites';

    protected static ?string $modelLabel = 'trámite';

    protected static ?string $pluralModelLabel = 'trámites';

    protected static ?string $slug = 'tramites';

    protected static ?int $navigationSort = 5;

    protected static ?string $panelScope = 'admin';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Datos del trámite')
                ->columns(2)
                ->schema([
                    Forms\Components\Select::make('alumno_id')
                        ->label('Alumno / Usuario')
                        ->relationship('alumno', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false)
                        ->columnSpanFull(),

                    Forms\Components\Select::make('type')
                        ->label('Tipo')
                        ->options([
                            'inscripcion'      => 'Inscripción',
                            'equivalencia'     => 'Equivalencia',
                            'titulo'           => 'Título',
                            'beca'             => 'Beca',
                            'certificado'      => 'Certificado',
                            'otro'             => 'Otro',
                        ])
                        ->required()
                        ->native(false),

                    Forms\Components\Select::make('status')
                        ->label('Estado')
                        ->options([
                            'pendiente'  => 'Pendiente',
                            'en_proceso' => 'En proceso',
                            'resuelto'   => 'Resuelto',
                            'cancelado'  => 'Cancelado',
                        ])
                        ->required()
                        ->native(false),

                    Forms\Components\Textarea::make('notes')
                        ->label('Notas / Observaciones')
                        ->rows(4)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('alumno.name')
                    ->label('Alumno')
                    ->searchable()
                    ->placeholder('—'),

                Tables\Columns\BadgeColumn::make('type')
                    ->label('Tipo')
                    ->formatStateUsing(fn (?string $s): string => match ($s) {
                        'inscripcion'  => 'Inscripción',
                        'equivalencia' => 'Equivalencia',
                        'titulo'       => 'Título',
                        'beca'         => 'Beca',
                        'certificado'  => 'Certificado',
                        default        => ucfirst((string) $s),
                    })
                    ->color('info'),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Estado')
                    ->formatStateUsing(fn (?string $s): string => match ($s) {
                        'pendiente'  => 'Pendiente',
                        'en_proceso' => 'En proceso',
                        'resuelto'   => 'Resuelto',
                        'cancelado'  => 'Cancelado',
                        default      => ucfirst((string) $s),
                    })
                    ->colors([
                        'warning' => 'pendiente',
                        'info'    => 'en_proceso',
                        'success' => 'resuelto',
                        'gray'    => 'cancelado',
                    ]),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipo')
                    ->options([
                        'inscripcion'  => 'Inscripción',
                        'equivalencia' => 'Equivalencia',
                        'titulo'       => 'Título',
                        'beca'         => 'Beca',
                        'certificado'  => 'Certificado',
                        'otro'         => 'Otro',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'pendiente'  => 'Pendiente',
                        'en_proceso' => 'En proceso',
                        'resuelto'   => 'Resuelto',
                        'cancelado'  => 'Cancelado',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('resolver')
                    ->label('Resolver')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Tramite $r) => ! in_array($r->status, ['resuelto', 'cancelado']))
                    ->action(fn (Tramite $r) => $r->update(['status' => 'resuelto'])),

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
            'index'  => Pages\ListTramites::route('/'),
            'create' => Pages\CreateTramite::route('/create'),
            'edit'   => Pages\EditTramite::route('/{record}/edit'),
        ];
    }
}
