@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;

    $record = $getRecord();

    if ($record->image) {
        $image = Str::startsWith($record->image, ['http://', 'https://', '/'])
            ?? $record->image
            : Storage::disk('public')->url($record->image);
    } else {
        $image = asset('images/home/hero-atsa-movilizacion.jpeg');
    }

    $categoryLabels = [
        'institucional' => 'Institucional',
        'gremial' => 'Gremial',
        'formacion' => 'Formación',
        'filiales' => 'Filiales',
        'eventos' => 'Eventos',
        'beneficios' => 'Beneficios',
    ];

    $category = $categoryLabels[$record->category] ?? ucfirst((string) $record->category);
    $published = filled($record->published_at);
@endphp

<article class="atsa-admin-post-card">
    <div class="atsa-admin-post-card__image" style="background-image: url('{{ $image }}')">
        <span class="atsa-admin-post-card__badge">{{ $category }}</span>
        @if ($record->destacado)
            <span class="atsa-admin-post-card__star"><i class="ti ti-star-filled"></i></span>
        @endif
    </div>

    <div class="atsa-admin-post-card__body">
        <div class="atsa-admin-post-card__meta">
            <span class="{{ $published ? 'is-published' : 'is-draft' }}"></span>
            {{ $published ? 'Publicado' : 'Borrador' }}
            @if ($record->published_at)
                · {{ $record->published_at->format('d/m/Y') }}
            @endif
        </div>

        <h3>{{ $record->title }}</h3>
        <p>{{ Str::limit(strip_tags($record->excerpt ?: $record->body), 115) }}</p>

        <div class="atsa-admin-post-card__footer">
            <span>{{ count($record->gallery ?? []) }} fotos</span>
            <span>{{ $record->author->name ?? 'ATSA Tucumán' }}</span>
        </div>
    </div>
</article>
