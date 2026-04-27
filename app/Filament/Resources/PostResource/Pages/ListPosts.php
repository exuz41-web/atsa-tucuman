<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use App\Models\Post;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListPosts extends ListRecords
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'todos' => Tab::make('Todos'),

            'publicadas' => Tab::make('Publicadas')
                ->modifyQueryUsing(fn (Builder $q) => $q->whereNotNull('published_at')),

            'borradores' => Tab::make('Borradores')
                ->badge(Post::whereNull('published_at')->count())
                ->badgeColor('gray')
                ->modifyQueryUsing(fn (Builder $q) => $q->whereNull('published_at')),

            'destacadas' => Tab::make('Destacadas')
                ->modifyQueryUsing(fn (Builder $q) => $q->where('destacado', true)),

            'gremial' => Tab::make('Gremial')
                ->modifyQueryUsing(fn (Builder $q) => $q->where('category', 'gremial')),
        ];
    }
}
