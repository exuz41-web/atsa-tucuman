<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissionAccess;
use App\Filament\Resources\CentLegajoDocumentoResource\Pages;
use App\Models\CentLegajoDocumento;
use App\Models\User;
use App\Services\Cent\CentNotificar;
use App\Services\Cent\FilamentSedeScope;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CentLegajoDocumentoResource extends Resource
{
    use HasResourcePermissionAccess;

    protected static ?string $model = CentLegajoDocumento::class;
    protected static ?string $navigationIcon = 'heroicon-o-folder-open';
    protected static ?string $navigationGroup = 'Administración';
    protected static ?string $navigationLabel = 'Legajos';
    protected static ?string $modelLabel = 'documento de legajo';
    protected static ?string $pluralModelLabel = 'legajos digitales';
    protected static ?string $slug = 'legajos';
    protected static ?string $panelScope = 'cent';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('user_id')
                ->label('Alumno')
                ->options(fn () => User::where('cent_role', 'alumno')->orderBy('name')->pluck('name', 'id'))
                ->searchable()
                ->required(),
            Forms\Components\Select::make('tipo')->options(CentLegajoDocumento::tipos())->required()->native(false),
            Forms\Components\FileUpload::make('archivo')->disk('local')->directory('cent/legajos')->helperText('Archivo privado. La descarga se realiza desde acciones.'),
            Forms\Components\Select::make('estado')->options([
                'pendiente' => 'Pendiente',
                'aprobado' => 'Aprobado',
                'rechazado' => 'Rechazado',
            ])->default('pendiente')->required()->native(false),
            Forms\Components\Textarea::make('observaciones')->rows(4)->columnSpanFull(),
            Forms\Components\Hidden::make('validado_por')->default(fn () => auth()->id()),
            Forms\Components\DateTimePicker::make('validado_at')->label('Validado el')->native(false),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('alumno.name')->label('Alumno')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('alumno.dni')->label('DNI')->searchable(),
                Tables\Columns\TextColumn::make('tipo')->formatStateUsing(fn ($state) => CentLegajoDocumento::tipos()[$state] ?? $state)->badge(),
                Tables\Columns\TextColumn::make('estado')->badge()->color(fn ($state) => match ($state) {
                    'aprobado' => 'success',
                    'rechazado' => 'danger',
                    default => 'warning',
                }),
                Tables\Columns\TextColumn::make('validado_at')->label('Validado')->dateTime('d/m/Y H:i')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label('Subido')->dateTime('d/m/Y H:i')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipo')->options(CentLegajoDocumento::tipos()),
                Tables\Filters\SelectFilter::make('estado')->options([
                    'pendiente' => 'Pendiente',
                    'aprobado' => 'Aprobado',
                    'rechazado' => 'Rechazado',
                ]),
            ])
            ->actions([
                Tables\Actions\Action::make('archivo')
                    ->label('Archivo')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->visible(fn (CentLegajoDocumento $record) => filled($record->archivo))
                    ->url(fn (CentLegajoDocumento $record) => route('cent.archivos.legajo', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('aprobar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->estado !== 'aprobado')
                    ->action(function (CentLegajoDocumento $record): void {
                        $record->update(['estado' => 'aprobado', 'validado_por' => auth()->id(), 'validado_at' => now()]);
                        CentNotificar::usuario($record->user_id, 'Documento aprobado', 'Tu documento de legajo fue aprobado.', 'legajo', route('cent.alumno.legajo'));
                    }),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return FilamentSedeScope::sedeId()
            ? parent::getEloquentQuery()->whereHas('alumno.matriculasCent', fn ($q) => $q->where('cent_sede_id', FilamentSedeScope::sedeId()))
            : parent::getEloquentQuery();
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
            'index' => Pages\ListCentLegajoDocumentos::route('/'),
            'create' => Pages\CreateCentLegajoDocumento::route('/create'),
            'edit' => Pages\EditCentLegajoDocumento::route('/{record}/edit'),
        ];
    }
}
