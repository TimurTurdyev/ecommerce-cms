@props([
    'logo' => config('app.name'),
    'logoIcon' => 'squares-2x2',
    'logoIconSet' => 'heroicon',
    'collapsible' => true,
    'collapsed' => false,
    'darkMode' => false,
])

@php
    // Если нет данных о свернутом состоянии, проверяем localStorage
    if (!$collapsed && request()->cookie('sidebar_collapsed')) {
        $collapsed = request()->cookie('sidebar_collapsed') === 'true';
    }
@endphp

<aside
    id="sidebar"
    class="sidebar {{ $collapsed ? 'sidebar-collapsed' : '' }} {{ $darkMode ? 'sidebar-dark' : 'sidebar-light' }}"
    data-collapsible="{{ $collapsible ? 'true' : 'false' }}"
    data-collapsed="{{ $collapsed ? 'true' : 'false' }}"
>
    {{-- Логотип --}}
    <div class="sidebar-header">
        <a href="{{ route('dashboard') }}" class="sidebar-logo">
            @if($logoIcon)
                <x-main-icon
                    :name="$logoIcon"
                    :set="$logoIconSet"
                    variant="solid"
                    size="lg"
                    class="sidebar-logo-icon"
                />
            @endif
            <span class="sidebar-logo-text">{{ $logo }}</span>
        </a>

        @if($collapsible)
            <button class="sidebar-toggle" id="sidebarToggle" type="button" aria-label="Переключить сайдбар">
                <x-main-icon name="chevron-left" set="heroicon" size="md" class="sidebar-toggle-icon"/>
            </button>
        @endif
    </div>

    {{-- Навигация --}}
    <nav class="sidebar-nav">
        <ul class="nav flex-column">
            {{-- Разделы --}}
            @foreach($navigation ?? [] as $section)
                @if(isset($section['items']) && count($section['items']) > 0)
                    <li class="nav-section">
                        @if(isset($section['label']))
                            <span class="nav-section-label">{{ $section['label'] }}</span>
                        @endif

                        <ul class="nav flex-column">
                            @foreach($section['items'] as $item)
                                @if(isset($item['divider']) && $item['divider'])
                                    <li class="nav-divider"></li>
                                @elseif(isset($item['items']) && count($item['items']) > 0)
                                    {{-- Подменю --}}
                                    @php
                                        $isActive = false;
                                        if (isset($item['active']) && $item['active']) {
                                            $isActive = true;
                                        } elseif (isset($item['items'])) {
                                            foreach ($item['items'] as $subItem) {
                                                if (isset($subItem['active']) && $subItem['active']) {
                                                    $isActive = true;
                                                    break;
                                                }
                                            }
                                        }
                                    @endphp
                                    <li class="nav-item has-submenu">
                                        <a href="#{{ $item['id'] ?? 'submenu-' . Str::random(5) }}"
                                           class="nav-link {{ $isActive ? 'active' : '' }}"
                                           data-bs-toggle="collapse"
                                           aria-expanded="{{ $isActive ? 'true' : 'false' }}"
                                           data-turbo="false"
                                        >
                                            @if(isset($item['icon']))
                                                <x-main-icon :name="$item['icon']" set="heroicon" size="md"
                                                        class="nav-icon"/>
                                            @endif
                                            <span class="nav-text">{{ $item['label'] ?? '' }}</span>
                                            <x-main-icon name="chevron-down" set="heroicon" size="sm" class="nav-chevron"/>
                                        </a>

                                        <div class="collapse {{ $isActive ? 'show' : '' }}"
                                             id="{{ $item['id'] ?? 'submenu-' . Str::random(5) }}">
                                            <ul class="nav flex-column submenu">
                                                @foreach($item['items'] as $subItem)
                                                    <li class="nav-item">
                                                        <a href="{{ $subItem['url'] ?? '#' }}"
                                                           class="nav-link {{ isset($subItem['active']) && $subItem['active'] ? 'active' : '' }}"
                                                           data-turbo="{{ $subItem['turbo'] ?? 'false' }}"
                                                        >
                                                            <span class="nav-text">{{ $subItem['label'] ?? '' }}</span>
                                                            @if(isset($subItem['badge']))
                                                                <x-badge
                                                                    :type="$subItem['badge']['type'] ?? 'primary'"
                                                                    :pill="$subItem['badge']['pill'] ?? true"
                                                                    class="ms-auto"
                                                                >
                                                                    {{ $subItem['badge']['text'] ?? '' }}
                                                                </x-badge>
                                                            @endif
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </li>
                                @else
                                    {{-- Обычная ссылка --}}
                                    <li class="nav-item">
                                        <a href="{{ $item['url'] ?? '#' }}"
                                           class="nav-link {{ isset($item['active']) && $item['active'] ? 'active' : '' }}"
                                           data-turbo="{{ $item['turbo'] ?? 'false' }}"
                                        >
                                            @if(isset($item['icon']))
                                                <x-main-icon :name="$item['icon']" set="heroicon" size="md"
                                                        class="nav-icon"/>
                                            @endif
                                            <span class="nav-text">{{ $item['label'] ?? '' }}</span>

                                            @if(isset($item['badge']))
                                                <x-badge
                                                    :type="$item['badge']['type'] ?? 'primary'"
                                                    :pill="$item['badge']['pill'] ?? true"
                                                    class="ms-auto"
                                                >
                                                    {{ $item['badge']['text'] ?? '' }}
                                                </x-badge>
                                            @endif
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </li>
                @endif
            @endforeach
        </ul>
    </nav>

    {{-- Нижняя часть сайдбара --}}
    <div class="sidebar-footer">
        {{-- Переключатель темы --}}
        <div class="theme-switcher">
            <button class="btn btn-link theme-toggle" id="themeToggle" type="button">
                <x-main-icon name="sun" set="heroicon" size="md" class="theme-icon-light"/>
                <x-main-icon name="moon" set="heroicon" size="md" class="theme-icon-dark"/>
                <span class="theme-text">Светлая тема</span>
            </button>
        </div>

        {{-- Профиль пользователя --}}
        @auth
            <div class="dropdown user-dropdown">
                <button class="btn btn-link user-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="user-avatar">
                        @if(auth()->user()->avatar)
                            <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}">
                        @else
                            <div class="avatar-placeholder">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div class="user-info">
                        <div class="user-name">{{ auth()->user()->name }}</div>
                        <div class="user-email">{{ auth()->user()->email }}</div>
                    </div>
                    <x-main-icon name="chevron-down" set="heroicon" size="sm" class="ms-auto"/>
                </button>

                <div class="dropdown-menu dropdown-menu-end">
                    <a href="#" class="dropdown-item" data-turbo="false">
                        <x-main-icon name="user" set="heroicon" size="sm" class="me-2"/>
                        Профиль
                    </a>
                    <a href="#" class="dropdown-item" data-turbo="false">
                        <x-main-icon name="cog-6-tooth" set="heroicon" size="sm" class="me-2"/>
                        Настройки
                    </a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}" class="dropdown-item-form">
                        @csrf
                        <button type="submit" class="dropdown-item logout-btn">
                            <x-main-icon name="arrow-right-on-rectangle" set="heroicon" size="sm" class="me-2"/>
                            Выйти
                        </button>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</aside>
