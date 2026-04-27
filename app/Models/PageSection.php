<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PageSection extends Model
{
    protected $fillable = [
        'page',
        'key',
        'label',
        'title',
        'subtitle',
        'body',
        'image_path',
        'button_text',
        'button_url',
        'secondary_button_text',
        'secondary_button_url',
        'orden',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'orden' => 'integer',
    ];

    public static function get(string $page, string $key): ?self
    {
        return static::query()
            ->where('page', $page)
            ->where('key', $key)
            ->where('active', true)
            ->first();
    }

    public function imageUrl(?string $fallback = null): ?string
    {
        if ($this->image_path) {
            if (str_starts_with($this->image_path, 'images/')) {
                return asset($this->image_path);
            }

            return Storage::disk('public')->url($this->image_path);
        }

        return $fallback;
    }
}
