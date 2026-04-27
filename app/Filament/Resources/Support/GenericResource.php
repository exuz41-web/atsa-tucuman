<?php

namespace App\Filament\Resources\Support;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

abstract class GenericResource extends Resource
{
    protected static ?string $navigationIcon = 'heroicon-o-square-3-stack-3d';

    protected static ?string $navigationGroup = null;

    protected static ?string $navigationLabel = null;

    protected static ?string $modelLabel = null;

    protected static ?string $pluralModelLabel = null;

    protected static bool $showInNavigation = true;

    protected static ?string $panelScope = null;

    public static function form(Form $form): Form
    {
        return $form->schema(static::buildSchema())->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort(static::getDefaultSortColumn(), 'desc')
            ->columns(static::buildColumns())
            ->actions(static::buildActions())
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function shouldRegisterNavigation(): bool
    {
        if (! static::$showInNavigation) {
            return false;
        }

        if (static::$panelScope === null) {
            return true;
        }

        return \Filament\Facades\Filament::getCurrentPanel()?->getId() === static::$panelScope;
    }

    public static function canAccess(): bool
    {
        if (static::$panelScope === null) {
            return true;
        }

        return \Filament\Facades\Filament::getCurrentPanel()?->getId() === static::$panelScope;
    }

    public static function getNavigationLabel(): string
    {
        return static::$navigationLabel ?? static::inferPluralLabel();
    }

    public static function getModelLabel(): string
    {
        return static::$modelLabel ?? static::inferSingularLabel();
    }

    public static function getPluralModelLabel(): string
    {
        return static::$pluralModelLabel ?? static::inferPluralLabel();
    }

    public static function getPages(): array
    {
        $pagesNamespace = static::class . '\\Pages';
        $pagesPath = app_path('Filament/Resources/' . class_basename(static::class) . '/Pages');

        if (! is_dir($pagesPath)) {
            return [];
        }

        $pages = [];

        foreach (glob($pagesPath . '/*.php') ?: [] as $file) {
            $class = pathinfo($file, PATHINFO_FILENAME);
            $fqcn = $pagesNamespace . '\\' . $class;

            if (! class_exists($fqcn)) {
                continue;
            }

            $route = static::routeForPage($class);

            if ($route === null) {
                continue;
            }

            $key = static::keyForPage($class);
            $pages[$key] = $fqcn::route($route);
        }

        return $pages;
    }

    protected static function buildSchema(): array
    {
        $schema = [];

        foreach (static::getFillableColumns() as $column) {
            if (in_array($column, ['id', 'created_at', 'updated_at', 'deleted_at'], true)) {
                continue;
            }

            $schema[] = static::makeFormComponent($column);
        }

        return $schema;
    }

    protected static function buildColumns(): array
    {
        $columns = [];

        foreach (static::getFillableColumns() as $column) {
            if (in_array($column, ['created_at', 'updated_at'], true)) {
                continue;
            }

            $columns[] = static::makeTableColumn($column);
        }

        return $columns;
    }

    protected static function buildActions(): array
    {
        return [
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ];
    }

    protected static function makeFormComponent(string $column): Forms\Components\Component
    {
        $casts = static::getModelCasts();
        $label = Str::headline(str_replace('_id', '', $column));

        if (($casts[$column] ?? null) === 'boolean') {
            return Forms\Components\Toggle::make($column)->label($label);
        }

        if (str_contains($column, 'image') || str_contains($column, 'foto') || str_contains($column, 'icon') || str_contains($column, 'logo')) {
            return Forms\Components\FileUpload::make($column)->label($label)->image()->directory(Str::kebab(class_basename(static::getModel())));
        }

        if (str_contains($column, 'archivo') || str_contains($column, 'pdf')) {
            return Forms\Components\FileUpload::make($column)->label($label)->directory(Str::kebab(class_basename(static::getModel())));
        }

        if (($casts[$column] ?? null) === 'date') {
            return Forms\Components\DatePicker::make($column)->label($label);
        }

        if (in_array($casts[$column] ?? null, ['datetime', 'immutable_datetime'], true) || str_ends_with($column, '_at')) {
            return Forms\Components\DateTimePicker::make($column)->label($label);
        }

        if (str_contains($column, 'descripcion') || str_contains($column, 'contenido') || str_contains($column, 'body') || str_contains($column, 'texto') || str_contains($column, 'mensaje')) {
            return Forms\Components\Textarea::make($column)->label($label)->rows(5)->columnSpanFull();
        }

        return Forms\Components\TextInput::make($column)->label($label)->maxLength(255);
    }

    protected static function makeTableColumn(string $column): Tables\Columns\Column
    {
        $casts = static::getModelCasts();
        $label = Str::headline(str_replace('_id', '', $column));

        if (($casts[$column] ?? null) === 'boolean') {
            return Tables\Columns\IconColumn::make($column)->label($label)->boolean();
        }

        if (str_contains($column, 'image') || str_contains($column, 'foto') || str_contains($column, 'logo')) {
            return Tables\Columns\ImageColumn::make($column)->label($label);
        }

        if (($casts[$column] ?? null) === 'date') {
            return Tables\Columns\TextColumn::make($column)->label($label)->date('d/m/Y')->sortable();
        }

        if (in_array($casts[$column] ?? null, ['datetime', 'immutable_datetime'], true) || str_ends_with($column, '_at')) {
            return Tables\Columns\TextColumn::make($column)->label($label)->dateTime('d/m/Y H:i')->sortable();
        }

        $tableColumn = Tables\Columns\TextColumn::make($column)->label($label)->searchable()->sortable();

        if (str_contains($column, 'descripcion') || str_contains($column, 'contenido') || str_contains($column, 'texto') || str_contains($column, 'mensaje')) {
            $tableColumn->limit(50)->wrap();
        }

        return $tableColumn;
    }

    protected static function getFillableColumns(): array
    {
        /** @var Model $model */
        $model = app(static::getModel());

        return $model->getFillable();
    }

    protected static function getModelCasts(): array
    {
        /** @var Model $model */
        $model = app(static::getModel());

        return method_exists($model, 'getCasts') ? $model->getCasts() : [];
    }

    protected static function getDefaultSortColumn(): string
    {
        $fillable = static::getFillableColumns();

        foreach (['orden', 'published_at', 'fecha', 'created_at', 'id'] as $preferred) {
            if (in_array($preferred, $fillable, true) || $preferred === 'id') {
                return $preferred;
            }
        }

        return $fillable[0] ?? 'id';
    }

    protected static function inferSingularLabel(): string
    {
        return Str::headline(Str::snake(class_basename(static::getModel())));
    }

    protected static function inferPluralLabel(): string
    {
        return Str::headline(Str::pluralStudly(class_basename(static::getModel())));
    }

    protected static function routeForPage(string $class): ?string
    {
        return match (true) {
            str_starts_with($class, 'List') => '/',
            str_starts_with($class, 'Create') => '/create',
            str_starts_with($class, 'Edit') => '/{record}/edit',
            str_starts_with($class, 'View') => '/{record}',
            default => null,
        };
    }

    protected static function keyForPage(string $class): string
    {
        return match (true) {
            str_starts_with($class, 'List') => 'index',
            str_starts_with($class, 'Create') => 'create',
            str_starts_with($class, 'Edit') => 'edit',
            str_starts_with($class, 'View') => 'view',
            default => Str::kebab($class),
        };
    }
}
