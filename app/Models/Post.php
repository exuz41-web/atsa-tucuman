<?php

namespace App\Models;

use App\Support\ImageWatermarkSupport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    protected $table = 'posts';

    protected $fillable = [
        'title',
        'slug',
        'body',
        'excerpt',
        'category',
        'image',
        'gallery',
        'video_url',
        'tags',
        'meta_description',
        'fuente',
        'fuente_url',
        'destacado',
        'published_at',
        'author_id',
    ];

    protected function casts(): array
    {
        return [
            'destacado'    => 'boolean',
            'published_at' => 'datetime',
            'gallery'      => 'array',
            'tags'         => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::saved(function (Post $post): void {
            if ($post->wasChanged('image') && filled($post->image)) {
                ImageWatermarkSupport::applyToPublicImage($post->image);
            }
        });
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(PostComment::class);
    }
}
