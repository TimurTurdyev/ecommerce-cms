<header class="main-header">
    <div class="header-container">
        {{-- Кнопка мобильного меню --}}
        <button class="header-mobile-toggle d-lg-none" id="mobileMenuToggle">
            <x-main-icon name="bars-3" set="heroicon" size="lg" />
        </button>

        {{-- Поиск --}}
        <div class="header-search">
            <div class="search-wrapper">
                <x-main-icon name="magnifying-glass" set="heroicon" size="md" class="search-icon" />
                <input
                    type="search"
                    class="search-input"
                    placeholder="Поиск..."
                    id="globalSearch"
                />
                <div class="search-shortcut">
                    <kbd>Ctrl</kbd> + <kbd>K</kbd>
                </div>
            </div>
        </div>

        {{-- Действия --}}
        <div class="header-actions">
            {{-- Уведомления --}}
            <div class="dropdown">
                <button class="btn btn-link btn-icon" data-bs-toggle="dropdown" aria-expanded="false">
                    <x-main-icon name="bell" set="heroicon" size="lg" />
                    @if($unreadNotificationsCount ?? 0 > 0)
                        <span class="notification-badge">{{ $unreadNotificationsCount }}</span>
                    @endif
                </button>

                <div class="dropdown-menu dropdown-menu-end dropdown-notifications">
                    <div class="dropdown-header">
                        <h6>Уведомления</h6>
                        <a href="#" class="small">Показать все</a>
                    </div>

                    <div class="notification-list">
                        @foreach($recentNotifications ?? [] as $notification)
                            <a href="{{ $notification['url'] ?? '#' }}" class="dropdown-item notification-item">
                                <div class="notification-icon">
                                    <x-main-icon :name="$notification['icon'] ?? 'information-circle'" set="heroicon" />
                                </div>
                                <div class="notification-content">
                                    <div class="notification-title">{{ $notification['title'] }}</div>
                                    <div class="notification-time">{{ $notification['time'] }}</div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <div class="dropdown-footer">
                        <a href="#" class="btn btn-link btn-sm">
                            Отметить все как прочитанные
                        </a>
                    </div>
                </div>
            </div>

            {{-- Быстрые действия --}}
            <div class="dropdown">
                <button class="btn btn-primary btn-sm" data-bs-toggle="dropdown">
                    <x-main-icon name="plus" set="heroicon" size="sm" class="me-1" />
                    Создать
                </button>

                <div class="dropdown-menu dropdown-menu-end">
                    @foreach($quickActions ?? [] as $action)
                        <a href="{{ $action['url'] }}" class="dropdown-item">
                            <x-main-icon :name="$action['icon']" set="heroicon" size="sm" class="me-2" />
                            {{ $action['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</header>
