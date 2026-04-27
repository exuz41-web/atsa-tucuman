@props([
    'navigation',
])

@php
    use Illuminate\Support\Str;

    $user = auth()->user();
    $panelId = filament()->getId();
    $logoutRoute = 'filament.' . $panelId . '.auth.logout';
    $siteLogo = \App\Models\SiteSetting::logoUrl();
    $name = $user?->name ?? 'Administrador';
    $roleSource = $panelId === 'cent' ? ($user?->cent_role ?: $user?->role) : $user?->role;
    $role = $roleSource ? Str::headline($roleSource) : 'Administrador';
    $initials = Str::of($name)
        ->explode(' ')
        ->filter()
        ->take(2)
        ->map(fn (string $part): string => Str::upper(Str::substr($part, 0, 1)))
        ->join('');

    $tablerIcons = [
        'heroicon-o-newspaper' => 'ti-news',
        'heroicon-o-arrow-down-tray' => 'ti-download',
        'heroicon-o-map-pin' => 'ti-map-pin',
        'heroicon-o-academic-cap' => 'ti-school',
        'heroicon-o-users' => 'ti-users',
        'heroicon-o-user-group' => 'ti-users-group',
        'heroicon-o-user-plus' => 'ti-user-plus',
        'heroicon-o-clipboard-document-list' => 'ti-clipboard-list',
        'heroicon-o-chat-bubble-left-right' => 'ti-message',
        'heroicon-o-chat-bubble-left-ellipsis' => 'ti-message-circle',
        'heroicon-o-book-open' => 'ti-books',
        'heroicon-o-document-text' => 'ti-file-text',
        'heroicon-o-document-arrow-down' => 'ti-file-download',
        'heroicon-o-building-office' => 'ti-building-hospital',
        'heroicon-o-building-office-2' => 'ti-building-community',
        'heroicon-o-gift' => 'ti-gift',
        'heroicon-o-banknotes' => 'ti-cash-banknote',
        'heroicon-o-calendar-days' => 'ti-calendar',
        'heroicon-o-paint-brush' => 'ti-brush',
        'heroicon-o-photo' => 'ti-photo',
        'heroicon-o-inbox-stack' => 'ti-inbox',
        'heroicon-o-sun' => 'ti-sun',
        'heroicon-o-phone' => 'ti-phone',
        'heroicon-o-scale' => 'ti-scale',
        'heroicon-o-home' => 'ti-home',
        'heroicon-o-identification' => 'ti-id',
        'heroicon-o-cog-6-tooth' => 'ti-settings',
    ];

    $iconFor = fn (?string $icon): string => $tablerIcons[$icon] ?? 'ti-circle';

    $groupIcons = [
        'Editor del sitio - ATSA' => 'ti-layout-dashboard',
        'Editor del sitio — ATSA' => 'ti-layout-dashboard',
        'Gestión web' => 'ti-world-www',
        'Gestion web' => 'ti-world-www',
        'Padrón sindical' => 'ti-address-book',
        'Padron sindical' => 'ti-address-book',
        'Institución' => 'ti-building-bank',
        'Institucion' => 'ti-building-bank',
        'Gremial' => 'ti-scale',
        'Atención al afiliado' => 'ti-heart-handshake',
        'Atencion al afiliado' => 'ti-heart-handshake',
        'Afiliados' => 'ti-users',
        'Configuración' => 'ti-settings',
        'Configuracion' => 'ti-settings',
    ];

    $groupIconFor = fn (?string $label): string => $groupIcons[$label] ?? 'ti-folder';
@endphp

{{-- Modernize vertical sidebar adapted to Filament navigation --}}
<aside {{ $attributes->class(['left-sidebar with-vertical']) }}>
    <div class="modernize-sidebar-shell">
        <!-- ---------------------------------- -->
        <!-- Start Vertical Layout Sidebar -->
        <!-- ---------------------------------- -->
        <div class="brand-logo d-flex align-items-center justify-content-between sidebar-header">
            <a href="{{ filament()->getHomeUrl() ?? url('/admin') }}" class="text-nowrap logo-img">
                <img src="{{ $siteLogo }}" class="dark-logo atsa-sidebar-logo" alt="{{ filament()->getBrandName() }}" />
                <span class="mini-logo">{{ $panelId === 'cent' ? 'C' : 'A' }}</span>
            </a>
            <a href="javascript:void(0)" class="sidebartoggler ms-auto text-decoration-none fs-5 d-block d-xl-none">
                <i class="ti ti-x"></i>
            </a>
        </div>

        <nav class="sidebar-nav scroll-sidebar" data-simplebar>
            <ul id="sidebarnav">
                @foreach ($navigation as $group)
                    @php
                        $groupItems = $group->getItems();
                        $groupHasLabel = filled($group->getLabel());
                        $groupIsActive = collect($groupItems)->contains(fn ($item): bool => $item->isActive());
                        $groupShouldOpen = $groupIsActive || ($loop->first && ! collect($navigation)->contains(fn ($navGroup): bool => collect($navGroup->getItems())->contains(fn ($item): bool => $item->isActive())));
                    @endphp

                    @if ($groupHasLabel)
                        <li class="sidebar-module">
                            <details class="sidebar-module-details" @if ($groupShouldOpen) open @endif>
                                <summary class="sidebar-module-summary" title="{{ $group->getLabel() }}">
                                    <span class="module-summary-icon">
                                        <i class="ti {{ $groupIconFor($group->getLabel()) }}"></i>
                                    </span>
                                    <span class="hide-menu module-summary-label">{{ $group->getLabel() }}</span>
                                    <span class="hide-menu module-summary-caret">
                                        <i class="ti ti-chevron-down"></i>
                                    </span>
                                </summary>

                                <ul class="sidebar-module-list">
                    @endif

                    @foreach ($groupItems as $item)
                        @php
                            $itemIsActive = $item->isActive();
                            $itemIcon = $itemIsActive ? ($item->getActiveIcon() ?? $item->getIcon()) : $item->getIcon();
                            $childItems = $item->getChildItems();
                            $hasChildren = filled($childItems);
                        @endphp

                        <li class="sidebar-item {{ $itemIsActive ? 'selected' : '' }}">
                            <a
                                class="sidebar-link {{ $itemIsActive ? 'active' : '' }}"
                                href="{{ $item->getUrl() }}"
                                aria-expanded="false"
                                title="{{ $item->getLabel() }}"
                                @if ($item->shouldOpenUrlInNewTab()) target="_blank" rel="noopener noreferrer" @endif
                            >
                                <span>
                                    <i class="ti {{ $iconFor($itemIcon) }}"></i>
                                </span>
                                <span class="sidebar-label-wrap">
                                    <span class="hide-menu">{{ $item->getLabel() }}</span>
                                    @if ($hasChildren)
                                        <span class="sidebar-caret hide-menu" aria-hidden="true">
                                            <i class="ti ti-chevron-down"></i>
                                        </span>
                                    @endif
                                </span>
                            </a>

                            @if ($hasChildren)
                                <ul class="sidebar-submenu">
                                    @foreach ($childItems as $childItem)
                                        <li class="sidebar-subitem">
                                            <a
                                                class="sidebar-sublink {{ $childItem->isActive() ? 'active' : '' }}"
                                                href="{{ $childItem->getUrl() }}"
                                                @if ($childItem->shouldOpenUrlInNewTab()) target="_blank" rel="noopener noreferrer" @endif
                                            >
                                                <span>{{ $childItem->getLabel() }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach

                    @if ($groupHasLabel)
                                </ul>
                            </details>
                        </li>
                    @endif
                @endforeach
            </ul>
        </nav>

        <div class="fixed-profile p-3 mx-4 mb-2 bg-secondary-subtle rounded mt-3">
            <div class="hstack gap-3">
                <div class="john-img">
                    <div class="rounded-circle atsa-user-avatar" aria-hidden="true">
                        {{ $initials ?: 'A' }}
                    </div>
                </div>
                <div class="john-title">
                    <h6 class="mb-0 fs-4 fw-semibold">{{ $name }}</h6>
                    <span class="fs-2">{{ $role }}</span>
                </div>
                <form method="POST" action="{{ route($logoutRoute) }}" class="ms-auto">
                    @csrf
                    <button class="border-0 bg-transparent text-primary" tabindex="0" type="submit" aria-label="logout" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="logout">
                        <i class="ti ti-power fs-6"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- ---------------------------------- -->
        <!-- End Vertical Layout Sidebar -->
        <!-- ---------------------------------- -->
    </div>
</aside>
