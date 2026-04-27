<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SitePage extends Model
{
    protected $fillable = ['slug', 'label', 'icon', 'blocks', 'active'];

    protected $casts = [
        'blocks' => 'array',
        'active' => 'boolean',
    ];

    // ──────────────────────────────────────────────────────────────────────────
    // Páginas ATSA disponibles
    // ──────────────────────────────────────────────────────────────────────────
    public const PAGES = [
        'home'       => ['label' => 'Inicio',              'icon' => 'heroicon-o-home'],
        'sindicato'  => ['label' => 'El Sindicato',        'icon' => 'heroicon-o-building-office'],
        'gremial'    => ['label' => 'Gremial',             'icon' => 'heroicon-o-shield-check'],
        'turismo'    => ['label' => 'Turismo y Beneficios','icon' => 'heroicon-o-sun'],
        'afiliados'  => ['label' => 'Afiliados',           'icon' => 'heroicon-o-users'],
        'filiales'   => ['label' => 'Filiales',            'icon' => 'heroicon-o-map-pin'],
        'delegados'  => ['label' => 'Delegados',           'icon' => 'heroicon-o-identification'],
        'documentos' => ['label' => 'Documentos',          'icon' => 'heroicon-o-document-text'],
        'contacto'   => ['label' => 'Contacto',            'icon' => 'heroicon-o-envelope'],
    ];

    // ──────────────────────────────────────────────────────────────────────────
    // Páginas CENT disponibles
    // ──────────────────────────────────────────────────────────────────────────
    public const CENT_PAGES = [
        'cent_home'     => ['label' => 'Inicio CENT',           'icon' => 'heroicon-o-home'],
        'cent_carreras' => ['label' => 'Carreras',              'icon' => 'heroicon-o-academic-cap'],
        'cent_sedes'    => ['label' => 'Sedes',                 'icon' => 'heroicon-o-map-pin'],
        'cent_faq'      => ['label' => 'Preguntas frecuentes',  'icon' => 'heroicon-o-question-mark-circle'],
        'cent_contacto' => ['label' => 'Contacto',              'icon' => 'heroicon-o-envelope'],
    ];

    // ──────────────────────────────────────────────────────────────────────────
    // Tipos de bloques disponibles en el Builder
    // ──────────────────────────────────────────────────────────────────────────
    public const BLOCK_TYPES = [
        'hero'             => '🖼️  Hero / Banner principal',
        'stats_bar'        => '📊  Barra de estadísticas',
        'cards_section'    => '🃏  Sección de cards / pilares',
        'text_image'       => '📝  Texto con imagen',
        'gallery_section'  => '📷  Galería de fotos',
        'cta_section'      => '🎯  Llamada a la acción (CTA)',
        'news_section'     => '📰  Sección de noticias (automático)',
        'timeline_section' => '📅  Línea de tiempo / historia',
        'team_section'     => '👥  Equipo / Autoridades (automático)',
        'accordion_section'=> '❓  Acordeón / preguntas frecuentes',
        'branches_section' => '🗺️  Filiales / Sedes (automático)',
        'downloads_section'=> '📥  Descargas (automático)',
        'contact_info'     => '📞  Información de contacto',
        'embed_section'    => '🗺️  Mapa / Embed',
        'custom_html'      => '💻  HTML personalizado',
    ];

    // ──────────────────────────────────────────────────────────────────────────
    // Queries
    // ──────────────────────────────────────────────────────────────────────────

    public static function forPage(string $slug): ?self
    {
        return static::where('slug', $slug)->where('active', true)->first();
    }

    public static function forPageOrEmpty(string $slug): self
    {
        return static::where('slug', $slug)->first() ?? new static([
            'slug'   => $slug,
            'label'  => static::PAGES[$slug]['label'] ?? static::CENT_PAGES[$slug]['label'] ?? $slug,
            'blocks' => [],
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Helpers de bloques
    // ──────────────────────────────────────────────────────────────────────────

    /** Devuelve el primer bloque del tipo dado o null */
    public function block(string $type): ?array
    {
        foreach ($this->blocks ?? [] as $block) {
            if (($block['type'] ?? null) === $type) {
                return $block['data'] ?? [];
            }
        }
        return null;
    }

    /** Devuelve todos los bloques activos (respetan el toggle active) */
    public function activeBlocks(): array
    {
        return array_values(array_filter(
            $this->blocks ?? [],
            fn ($b) => ($b['data']['visible'] ?? true) !== false
        ));
    }

    /** URL de una imagen guardada en storage/public */
    public static function imageUrl(?string $path, ?string $fallback = null): ?string
    {
        if (!$path) return $fallback;
        if (str_starts_with($path, 'http') || str_starts_with($path, '/images/')) {
            return $path;
        }
        return Storage::disk('public')->url($path);
    }
}
