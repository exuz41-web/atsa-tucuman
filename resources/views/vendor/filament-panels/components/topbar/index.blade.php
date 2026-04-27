@props([
    'navigation',
])

@php
    use Illuminate\Support\Str;

    $user = auth()->user();
    $panelId = filament()->getId();
    $panelPath = $panelId === 'cent' ? '/cent-admin' : '/admin';
    $logoutRoute = 'filament.' . $panelId . '.auth.logout';
    $name = $user?->name ?? 'Administrador';
    $email = $user?->email ?? 'admin@atsa.com';
    $roleSource = $panelId === 'cent' ? ($user?->cent_role ?: $user?->role) : $user?->role;
    $role = $roleSource ? Str::headline($roleSource) : 'Administrador';
    $unreadNotifications = $user?->unreadNotifications()->latest()->take(5)->get() ?? collect();
    $unreadNotificationsCount = $user?->unreadNotifications()->count() ?? 0;
@endphp

{{-- Modernize topbar adapted to Filament --}}
<header class="topbar">
    <div class="with-vertical">
        <!-- ---------------------------------- -->
        <!-- Start Vertical Layout Header -->
        <!-- ---------------------------------- -->
        <nav class="navbar navbar-expand-lg p-0">
            <ul class="navbar-nav">
                <li class="nav-item nav-icon-hover-bg rounded-circle ms-n2">
                    <a class="nav-link sidebartoggler" id="headerCollapse" href="javascript:void(0)">
                        <i class="ti ti-menu-2"></i>
                    </a>
                </li>
                <li class="nav-item nav-icon-hover-bg rounded-circle d-none d-lg-flex">
                    <button type="button" class="nav-link modernize-search-trigger">
                        <i class="ti ti-search"></i>
                    </button>
                </li>
            </ul>

            <div class="navbar-collapse modernize-header-actions-wrap" id="navbarNav">
                <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-center modernize-header-actions">
                    <li class="nav-item nav-icon-hover-bg rounded-circle">
                        <button type="button" class="nav-link modernize-theme-toggle" aria-label="Cambiar modo oscuro o claro">
                            <i class="ti ti-moon modernize-theme-icon"></i>
                        </button>
                    </li>

                    <li class="nav-item dropdown modernize-notifications-nav">
                        <a
                            class="nav-link position-relative modernize-notifications-toggle"
                            href="javascript:void(0)"
                            aria-label="Notificaciones"
                            aria-expanded="false"
                        >
                            <i class="ti ti-bell-ringing"></i>
                            @if ($unreadNotificationsCount > 0)
                                <span class="modernize-notification-dot"></span>
                                <span class="modernize-notification-count">{{ min($unreadNotificationsCount, 9) }}</span>
                            @endif
                        </a>

                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up modernize-notifications-dropdown">
                            <div class="modernize-notifications-card">
                                <div class="modernize-notifications-head">
                                    <div>
                                        <h5>Notificaciones</h5>
                                        <p>{{ $unreadNotificationsCount }} sin leer</p>
                                    </div>
                                    <a href="{{ url($panelId === 'cent' ? '/cent-admin/preinscripciones-cent' : '/admin/consultas') }}">Ver consultas</a>
                                </div>

                                <div class="modernize-notifications-list">
                                    @forelse ($unreadNotifications as $notification)
                                        @php
                                            $notificationTitle = $notification->data['title'] ?? 'Nueva notificación';
                                            $notificationBody = $notification->data['body'] ?? 'Hay una actualización pendiente de revisión.';
                                        @endphp
                                        <a href="{{ data_get($notification->data, 'actions.0.url', url($panelPath)) }}" class="modernize-notification-item">
                                            <span class="modernize-notification-icon">
                                                <i class="ti ti-bell-ringing"></i>
                                            </span>
                                            <span>
                                                <strong>{{ $notificationTitle }}</strong>
                                                <small>{{ Str::limit($notificationBody, 72) }}</small>
                                                <em>{{ $notification->created_at?->diffForHumans() }}</em>
                                            </span>
                                        </a>
                                    @empty
                                        <div class="modernize-notification-empty">
                                            <i class="ti ti-circle-check"></i>
                                            <strong>Todo al día</strong>
                                            <span>No hay notificaciones pendientes.</span>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="nav-item dropdown modernize-profile-nav">
                        <a
                            class="nav-link position-relative ms-6 modernize-profile-toggle"
                            href="javascript:void(0)"
                            aria-label="Menu de usuario"
                            aria-expanded="false"
                        >
                            <img src="{{ asset('images/admin-avatar.jpg') }}" class="rounded-circle atsa-topbar-avatar" alt="{{ $name }}" />
                        </a>

                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up user-dd modernize-profile-dropdown">
                            <div class="modernize-profile-card">
                                <h5 class="modernize-profile-title">Perfil de usuario</h5>

                                <div class="modernize-profile-user">
                                    <img src="{{ asset('images/admin-avatar.jpg') }}" class="rounded-circle modernize-profile-photo" alt="{{ $name }}" />
                                    <div class="modernize-profile-user-info">
                                        <h6>{{ $name }}</h6>
                                        <p>{{ $role }}</p>
                                        <a href="mailto:{{ $email }}">
                                            <i class="ti ti-mail"></i>
                                            <span>{{ $email }}</span>
                                        </a>
                                    </div>
                                </div>

                                <div class="modernize-profile-divider"></div>

                                <a href="{{ url($panelId === 'cent' ? '/cent-admin/usuarios' : '/admin/users') }}" class="modernize-profile-action">
                                    <span class="modernize-profile-action-icon">
                                        <img src="{{ asset('images/icon-account.svg') }}" alt="" />
                                    </span>
                                        <span>
                                        <strong>Mi perfil</strong>
                                        <small>Configuración de cuenta</small>
                                        </span>
                                </a>

                                <a href="{{ url($panelId === 'cent' ? '/cent-admin/preinscripciones-cent' : '/admin/consultas') }}" class="modernize-profile-action">
                                    <span class="modernize-profile-action-icon">
                                        <img src="{{ asset('images/icon-inbox.svg') }}" alt="" />
                                    </span>
                                        <span>
                                        <strong>{{ $panelId === 'cent' ? 'Preinscripciones' : 'Mi bandeja' }}</strong>
                                        <small>{{ $panelId === 'cent' ? 'Solicitudes de ingreso' : 'Mensajes y consultas' }}</small>
                                        </span>
                                </a>

                                <a href="{{ url($panelId === 'cent' ? '/cent-admin/matriculas-cent' : '/admin/pedidos') }}" class="modernize-profile-action">
                                    <span class="modernize-profile-action-icon">
                                        <img src="{{ asset('images/icon-tasks.svg') }}" alt="" />
                                    </span>
                                        <span>
                                        <strong>{{ $panelId === 'cent' ? 'Matriculas' : 'Mis tareas' }}</strong>
                                        <small>{{ $panelId === 'cent' ? 'Alumnos y cursado' : 'Pedidos y gestiones diarias' }}</small>
                                        </span>
                                </a>

                                <div class="modernize-profile-upgrade">
                                    <div>
                                        <strong>{!! $panelId === 'cent' ? 'Gestion<br />CENT' : 'Gestion<br />ATSA' !!}</strong>
                                        <a href="{{ url($panelPath) }}">Ver panel</a>
                                    </div>
                                    <img src="{{ asset('images/modernize-unlimited-bg.png') }}" alt="" />
                                </div>

                                <form method="POST" action="{{ route($logoutRoute) }}">
                                    @csrf
                                    <button type="submit" class="modernize-profile-logout">Salir</button>
                                </form>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- ---------------------------------- -->
        <!-- End Vertical Layout Header -->
        <!-- ---------------------------------- -->
    </div>

    <script data-navigate-once>
        document.addEventListener("DOMContentLoaded", function () {
            "use strict";

            if (!document.body.hasAttribute("data-sidebartype")) {
                document.body.setAttribute("data-sidebartype", "full");
            }

            const setSidebarType = () => {
                const stored = localStorage.getItem("atsa-sidebar-type-modernize");
                if (stored) {
                    document.body.setAttribute("data-sidebartype", stored);
                    return;
                }
                const width = window.innerWidth > 0 ? window.innerWidth : screen.width;
                if (width < 992) {
                    document.body.setAttribute("data-sidebartype", "mini-sidebar");
                } else {
                    document.body.setAttribute("data-sidebartype", "full");
                }
            };

            window.addEventListener("resize", setSidebarType);
            setSidebarType();

            function handleSidebar() {
                document.querySelectorAll(".sidebartoggler").forEach((element) => {
                    element.addEventListener("click", function () {
                        document.querySelectorAll(".sidebartoggler").forEach((el) => {
                            el.checked = true;
                        });

                        const mainWrapper = document.getElementById("main-wrapper");
                        if (mainWrapper) {
                            mainWrapper.classList.toggle("show-sidebar");
                        }

                        document.querySelectorAll(".sidebarmenu").forEach((el) => {
                            el.classList.toggle("close");
                        });

                        const dataTheme = document.body.getAttribute("data-sidebartype");
                        if (dataTheme === "full") {
                            document.body.setAttribute("data-sidebartype", "mini-sidebar");
                            localStorage.setItem("atsa-sidebar-type-modernize", "mini-sidebar");
                        } else {
                            document.body.setAttribute("data-sidebartype", "full");
                            localStorage.setItem("atsa-sidebar-type-modernize", "full");
                        }
                    });
                });
            }

            function handleProfileDropdown() {
                const profileToggle = document.querySelector(".modernize-profile-toggle");
                const profileDropdown = document.querySelector(".modernize-profile-dropdown");

                if (!profileToggle || !profileDropdown) {
                    return;
                }

                profileToggle.addEventListener("click", function (event) {
                    event.preventDefault();
                    event.stopPropagation();

                    const isOpen = profileDropdown.classList.toggle("is-open");
                    profileToggle.setAttribute("aria-expanded", isOpen ? "true" : "false");
                });

                document.addEventListener("click", function (event) {
                    if (!profileDropdown.contains(event.target) && !profileToggle.contains(event.target)) {
                        profileDropdown.classList.remove("is-open");
                        profileToggle.setAttribute("aria-expanded", "false");
                    }
                });

                document.addEventListener("keydown", function (event) {
                    if (event.key === "Escape") {
                        profileDropdown.classList.remove("is-open");
                        profileToggle.setAttribute("aria-expanded", "false");
                    }
                });
            }

            function handleNotificationsDropdown() {
                const notificationsToggle = document.querySelector(".modernize-notifications-toggle");
                const notificationsDropdown = document.querySelector(".modernize-notifications-dropdown");

                if (!notificationsToggle || !notificationsDropdown) {
                    return;
                }

                notificationsToggle.addEventListener("click", function (event) {
                    event.preventDefault();
                    event.stopPropagation();

                    const isOpen = notificationsDropdown.classList.toggle("is-open");
                    notificationsToggle.setAttribute("aria-expanded", isOpen ? "true" : "false");
                });

                document.addEventListener("click", function (event) {
                    if (!notificationsDropdown.contains(event.target) && !notificationsToggle.contains(event.target)) {
                        notificationsDropdown.classList.remove("is-open");
                        notificationsToggle.setAttribute("aria-expanded", "false");
                    }
                });

                document.addEventListener("keydown", function (event) {
                    if (event.key === "Escape") {
                        notificationsDropdown.classList.remove("is-open");
                        notificationsToggle.setAttribute("aria-expanded", "false");
                    }
                });
            }

            function handleThemeToggle() {
                const themeToggle = document.querySelector(".modernize-theme-toggle");
                const themeIcon = document.querySelector(".modernize-theme-icon");

                if (!themeToggle || !themeIcon) {
                    return;
                }

                const applyTheme = (theme) => {
                    document.body.setAttribute("data-modernize-theme", theme);
                    themeIcon.classList.toggle("ti-moon", theme !== "dark");
                    themeIcon.classList.toggle("ti-sun", theme === "dark");
                    localStorage.setItem("atsa-modernize-theme", theme);
                };

                applyTheme(localStorage.getItem("atsa-modernize-theme") || "light");

                themeToggle.addEventListener("click", function () {
                    const currentTheme = document.body.getAttribute("data-modernize-theme") || "light";
                    applyTheme(currentTheme === "dark" ? "light" : "dark");
                });
            }

            const storedSidebarType = localStorage.getItem("atsa-sidebar-type-modernize");
            if (storedSidebarType) {
                document.body.setAttribute("data-sidebartype", storedSidebarType);
            }

            handleSidebar();
            handleNotificationsDropdown();
            handleProfileDropdown();
            handleThemeToggle();
        });
    </script>
</header>
