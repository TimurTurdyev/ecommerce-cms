<div class="mobile-menu">
    <div class="mobile-menu-content">
        <div class="mobile-menu-header">
            <a href="{{ route('dashboard') }}" class="mobile-menu-logo">
                @if(config('app.logo_icon'))
                    <x-main-icon
                        :name="config('app.logo_icon')"
                        :set="config('app.logo_icon_set', 'heroicon')"
                        variant="solid"
                        size="lg"
                    />
                @endif
                <span>{{ config('app.name', 'MyApp') }}</span>
            </a>

            <button class="mobile-menu-close" aria-label="Закрыть меню">
                <x-main-icon name="x-mark" set="heroicon" size="lg"/>
            </button>
        </div>

        <div class="mobile-menu-body">
            {{-- Профиль пользователя --}}
            @auth
                <div class="mobile-user-profile">
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
                </div>

                <hr class="mobile-divider">
            @endauth

            {{-- Навигация --}}
            <nav class="mobile-nav">
                <ul class="mobile-nav-list">
                    @foreach($mobileNavigation ?? [] as $section)
                        @if(isset($section['items']) && count($section['items']) > 0)
                            <li class="mobile-nav-section">
                                @if(isset($section['label']))
                                    <div class="mobile-nav-section-label">{{ $section['label'] }}</div>
                                @endif

                                <ul class="mobile-nav-submenu">
                                    @foreach($section['items'] as $item)
                                        @if(isset($item['divider']) && $item['divider'])
                                            <li class="mobile-nav-divider"></li>
                                        @elseif(isset($item['items']) && count($item['items']) > 0)
                                            {{-- Подменю --}}
                                            <li class="mobile-nav-item has-submenu">
                                                <div class="mobile-nav-link mobile-nav-toggle">
                                                    @if(isset($item['icon']))
                                                        <x-main-icon :name="$item['icon']" set="heroicon" size="md"
                                                                class="mobile-nav-icon"/>
                                                    @endif
                                                    <span class="mobile-nav-text">{{ $item['label'] ?? '' }}</span>
                                                    <x-main-icon name="chevron-down" set="heroicon" size="sm"
                                                            class="mobile-nav-chevron"/>
                                                </div>

                                                <ul class="mobile-nav-submenu">
                                                    @foreach($item['items'] as $subItem)
                                                        <li class="mobile-nav-item">
                                                            <a href="{{ $subItem['url'] ?? '#' }}"
                                                               class="mobile-nav-link"
                                                            >
                                                                <span
                                                                    class="mobile-nav-text">{{ $subItem['label'] ?? '' }}</span>
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @else
                                            {{-- Обычная ссылка --}}
                                            <li class="mobile-nav-item">
                                                <a href="{{ $item['url'] ?? '#' }}"
                                                   class="mobile-nav-link {{ isset($item['active']) && $item['active'] ? 'active' : '' }}"
                                                >
                                                    @if(isset($item['icon']))
                                                        <x-main-icon :name="$item['icon']" set="heroicon" size="md"
                                                                class="mobile-nav-icon"/>
                                                    @endif
                                                    <span class="mobile-nav-text">{{ $item['label'] ?? '' }}</span>

                                                    @if(isset($item['badge']))
                                                        <x-badge
                                                            :type="$item['badge']['type'] ?? 'primary'"
                                                            :pill="$item['badge']['pill'] ?? true"
                                                            class="mobile-nav-badge"
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

            <hr class="mobile-divider">

            {{-- Нижние элементы --}}
            <div class="mobile-menu-footer">
                {{-- Переключатель темы --}}
                <button class="mobile-theme-toggle" id="mobileThemeToggle">
                    <x-main-icon name="sun" set="heroicon" size="md" class="mobile-theme-icon-light"/>
                    <x-main-icon name="moon" set="heroicon" size="md" class="mobile-theme-icon-dark"/>
                    <span class="mobile-theme-text">Светлая тема</span>
                </button>

                @auth
                    {{-- Выход --}}
                    <form method="POST" action="{{ route('logout') }}" class="mobile-logout-form">
                        @csrf
                        <button type="submit" class="mobile-logout-btn">
                            <x-main-icon name="arrow-right-on-rectangle" set="heroicon" size="md"
                                    class="mobile-logout-icon"/>
                            <span class="mobile-logout-text">Выйти</span>
                        </button>
                    </form>
                @endauth
            </div>
        </div>
    </div>
</div>
