@php
    $query = request()->query();
    unset($query['page']);

    $currentView = request()->query('vista', 'lista');
    $listUrl = request()->url() . '?' . http_build_query(array_merge($query, ['vista' => 'lista']));
    $gridUrl = request()->url() . '?' . http_build_query(array_merge($query, ['vista' => 'grid']));
@endphp

<div class="atsa-admin-view-switcher" aria-label="Cambiar vista de noticias">
    <a href="{{ $listUrl }}" class="atsa-admin-view-switcher__btn {{ $currentView !== 'grid' ? 'is-active' : '' }}">
        <i class="ti ti-list-details"></i>
        <span>Lista</span>
    </a>
    <a href="{{ $gridUrl }}" class="atsa-admin-view-switcher__btn {{ $currentView === 'grid' ? 'is-active' : '' }}">
        <i class="ti ti-layout-grid"></i>
        <span>Grilla</span>
    </a>
</div>
