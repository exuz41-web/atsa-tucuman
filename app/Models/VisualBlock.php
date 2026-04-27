<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class VisualBlock extends Model
{
    protected $fillable = [
        'page',
        'section',
        'title',
        'subtitle',
        'description',
        'image_path',
        'link_url',
        'link_text',
        'size',
        'position',
        'orden',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'orden' => 'integer',
    ];

    public static function activeFor(string $page, string $section)
    {
        return static::query()
            ->where('page', $page)
            ->where('section', $section)
            ->where('active', true)
            ->orderBy('orden')
            ->get();
    }

    public function imageUrl(): string
    {
        if (str_starts_with($this->image_path, 'images/')) {
            return asset($this->image_path);
        }

        return Storage::disk('public')->url($this->image_path);
    }
}
