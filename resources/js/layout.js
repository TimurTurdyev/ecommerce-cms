import $ from 'jquery';

// Управление сайдбаром
class SidebarManager {
    constructor() {
        this.sidebar = $('#sidebar');
        this.sidebarToggle = $('#sidebarToggle');
        this.mobileMenuToggle = $('#mobileMenuToggle');
        this.mobileMenu = $('.mobile-menu');
        this.mobileMenuClose = $('.mobile-menu-close');
        this.mobileNavToggles = $('.mobile-nav-toggle');

        this.init();
    }

    init() {
        console.log('SidebarManager: init');

        // Проверяем сохраненное состояние из localStorage
        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        const isCollapsible = this.sidebar.data('collapsible') === 'true';

        console.log('SidebarManager: isCollapsible', isCollapsible);
        console.log('SidebarManager: localStorage sidebarCollapsed', isCollapsed);

        if (isCollapsed && isCollapsible && this.sidebar.length) {
            this.sidebar.addClass('sidebar-collapsed');
            // Обновляем иконку
            this.updateToggleIcon(true);
        }

        // Обработчики событий
        if (this.sidebarToggle.length) {
            console.log('SidebarManager: sidebarToggle found');
            this.sidebarToggle.off('click').on('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                console.log('SidebarManager: toggle clicked');
                this.toggleSidebar();
            });
        } else {
            console.log('SidebarManager: sidebarToggle NOT found');
        }

        if (this.mobileMenuToggle.length) {
            this.mobileMenuToggle.off('click').on('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.openMobileMenu();
            });
        }

        if (this.mobileMenuClose.length) {
            this.mobileMenuClose.off('click').on('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.closeMobileMenu();
            });
        }

        if (this.mobileMenu.length) {
            this.mobileMenu.off('click').on('click', (e) => {
                if (e.target === this.mobileMenu[0]) {
                    this.closeMobileMenu();
                }
            });
        }

        if (this.mobileNavToggles.length) {
            this.mobileNavToggles.off('click').on('click', (e) => {
                e.preventDefault();
                this.toggleMobileSubmenu($(e.currentTarget));
            });
        }

        // Закрытие мобильного меню при изменении размера окна
        $(window).on('resize', () => {
            if (window.innerWidth >= 992) {
                this.closeMobileMenu();
            }
        });

        // Инициализация подменю в сайдбаре
        this.initSidebarSubmenus();
    }

    toggleSidebar() {
        console.log('SidebarManager: toggleSidebar called');

        if (!this.sidebar.length) {
            console.log('SidebarManager: sidebar not found');
            return;
        }

        const isCollapsible = this.sidebar.data('collapsible') === 'true';
        console.log('SidebarManager: isCollapsible', isCollapsible);

        if (!isCollapsible) {
            console.log('SidebarManager: sidebar is not collapsible');
            return;
        }

        const isCollapsed = this.sidebar.hasClass('sidebar-collapsed');
        console.log('SidebarManager: isCollapsed before', isCollapsed);

        if (isCollapsed) {
            this.sidebar.removeClass('sidebar-collapsed');
            localStorage.setItem('sidebarCollapsed', 'false');
            console.log('SidebarManager: expanded');
        } else {
            this.sidebar.addClass('sidebar-collapsed');
            localStorage.setItem('sidebarCollapsed', 'true');
            console.log('SidebarManager: collapsed');
        }

        // Обновляем иконку
        this.updateToggleIcon(!isCollapsed);

        // Отправляем событие для других компонентов
        $(document).trigger('sidebar:toggled', [!isCollapsed]);

        // Обновляем cookie для серверного рендеринга
        this.updateSidebarCookie(!isCollapsed);
    }

    updateToggleIcon(isCollapsed) {
        const toggleIcon = this.sidebarToggle.find('.sidebar-toggle-icon');
        if (toggleIcon.length) {
            if (isCollapsed) {
                toggleIcon.css('transform', 'rotate(180deg)');
            } else {
                toggleIcon.css('transform', 'rotate(0deg)');
            }
        }
    }

    updateSidebarCookie(isCollapsed) {
        // Устанавливаем cookie на 30 дней
        const date = new Date();
        date.setTime(date.getTime() + (30 * 24 * 60 * 60 * 1000));
        const expires = "expires=" + date.toUTCString();

        document.cookie = `sidebar_collapsed=${isCollapsed}; ${expires}; path=/`;
    }

    initSidebarSubmenus() {
        // Инициализация Bootstrap Collapse для подменю
        $('.sidebar-nav [data-bs-toggle="collapse"]').each(function() {
            const target = $(this).attr('href');
            if (target && target.startsWith('#')) {
                // Убедимся что collapse инициализирован
                const collapseElement = $(target);
                if (collapseElement.length && !collapseElement.data('bs.collapse')) {
                    new bootstrap.Collapse(collapseElement[0], {
                        toggle: false
                    });
                }
            }
        });

        // Обработка событий collapse
        $('.sidebar-nav .collapse').on('show.bs.collapse', function(e) {
            // Закрываем другие открытые подменю на том же уровне
            const parent = $(this).closest('.nav-item');
            parent.siblings('.nav-item').find('.collapse.show').collapse('hide');

            // Добавляем класс active к родительскому элементу
            parent.addClass('submenu-open');
        }).on('hidden.bs.collapse', function() {
            $(this).closest('.nav-item').removeClass('submenu-open');
        });
    }

    openMobileMenu() {
        console.log('SidebarManager: openMobileMenu');
        if (this.mobileMenu.length) {
            this.mobileMenu.addClass('show');
            $('body').css('overflow', 'hidden');
            // Предотвращаем скролл под меню на мобильных устройствах
            this.disableBodyScroll();
        }
    }

    closeMobileMenu() {
        console.log('SidebarManager: closeMobileMenu');
        if (this.mobileMenu.length) {
            this.mobileMenu.removeClass('show');
            $('body').css('overflow', '');
            this.enableBodyScroll();
        }
    }

    disableBodyScroll() {
        // Сохраняем текущую позицию скролла
        const scrollY = window.scrollY;
        $('body').css({
            'position': 'fixed',
            'top': `-${scrollY}px`,
            'width': '100%'
        });

        // Сохраняем позицию скролла в data-атрибуте
        $('body').data('scrollY', scrollY);
    }

    enableBodyScroll() {
        const scrollY = $('body').data('scrollY') || 0;
        $('body').css({
            'position': '',
            'top': '',
            'width': ''
        });

        // Восстанавливаем позицию скролла
        window.scrollTo(0, scrollY);
    }

    toggleMobileSubmenu($toggle) {
        console.log('SidebarManager: toggleMobileSubmenu');
        const $navItem = $toggle.closest('.mobile-nav-item');
        const $submenu = $navItem.find('.mobile-nav-submenu');

        if ($submenu.length) {
            if ($navItem.hasClass('open')) {
                $submenu.slideUp(300, function() {
                    $navItem.removeClass('open');
                });
            } else {
                // Закрываем другие открытые подменю
                $('.mobile-nav-item.open').not($navItem).each(function() {
                    $(this).find('.mobile-nav-submenu').slideUp(300);
                    $(this).removeClass('open');
                });

                $submenu.slideDown(300, function() {
                    $navItem.addClass('open');
                });
            }
        }
    }
}

// Управление темой
class ThemeManager {
    constructor() {
        this.themeToggle = $('#themeToggle');
        this.themeText = $('.theme-text');

        this.init();
    }

    init() {
        // Проверяем сохраненную тему
        const savedTheme = localStorage.getItem('theme') || 'light';
        this.setTheme(savedTheme);

        if (this.themeToggle.length) {
            this.themeToggle.on('click', () => this.toggleTheme());
        }
    }

    setTheme(theme) {
        $('html').attr('data-bs-theme', theme);
        localStorage.setItem('theme', theme);

        if (this.themeText.length) {
            this.themeText.text(theme === 'dark' ? 'Темная тема' : 'Светлая тема');
        }
    }

    toggleTheme() {
        const currentTheme = $('html').attr('data-bs-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        this.setTheme(newTheme);
    }
}

// Глобальный поиск
class SearchManager {
    constructor() {
        this.searchInput = $('#globalSearch');
        this.searchResults = $('#searchResults');
        this.init();
    }

    init() {
        if (!this.searchInput.length) return;

        // Обработка горячих клавиш
        $(document).on('keydown', (e) => {
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                this.searchInput.focus();
            }

            if (e.key === 'Escape' && document.activeElement === this.searchInput[0]) {
                this.searchInput.blur();
                this.hideResults();
            }
        });

        // Поиск по мере ввода (с дебаунсом)
        let searchTimeout;
        this.searchInput.on('input', (e) => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.performSearch(e.target.value);
            }, 300);
        });

        // Клик вне поля поиска
        $(document).on('click', (e) => {
            if (!$(e.target).closest('.header-search').length) {
                this.hideResults();
            }
        });
    }

    async performSearch(query) {
        if (query.length < 2) {
            this.hideResults();
            return;
        }

        try {
            // Здесь можно заменить на реальный API вызов
            const results = await this.simulateSearch(query);
            this.displayResults(results);
        } catch (error) {
            console.error('Search error:', error);
            this.showError('Ошибка поиска');
        }
    }

    simulateSearch(query) {
        return new Promise((resolve) => {
            setTimeout(() => {
                const mockResults = [
                    { type: 'user', title: 'Иван Петров', subtitle: 'ivan@example.com', url: '/users/1', icon: 'user' },
                    { type: 'project', title: 'Проект Alpha', subtitle: 'Последнее изменение: сегодня', url: '/projects/alpha', icon: 'folder' },
                    { type: 'document', title: 'Отчет за май', subtitle: 'Документ', url: '/documents/report-may', icon: 'document' },
                    { type: 'task', title: 'Завершить дизайн', subtitle: 'Срок: завтра', url: '/tasks/123', icon: 'check-circle' },
                ].filter(item =>
                    item.title.toLowerCase().includes(query.toLowerCase()) ||
                    item.subtitle.toLowerCase().includes(query.toLowerCase())
                );
                resolve(mockResults);
            }, 200);
        });
    }

    displayResults(results) {
        if (!results.length) {
            this.showNoResults();
            return;
        }

        const resultsHtml = results.map(result => `
            <a href="${result.url}" class="search-result-item" data-type="${result.type}">
                <div class="search-result-icon">
                    <i class="bi bi-${result.icon}"></i>
                </div>
                <div class="search-result-content">
                    <div class="search-result-title">${result.title}</div>
                    <div class="search-result-subtitle">${result.subtitle}</div>
                </div>
                <div class="search-result-arrow">
                    <i class="bi bi-arrow-right"></i>
                </div>
            </a>
        `).join('');

        const html = `
            <div class="search-results-dropdown">
                <div class="search-results-header">
                    <h6>Результаты поиска</h6>
                    <span class="badge bg-primary">${results.length}</span>
                </div>
                <div class="search-results-list">
                    ${resultsHtml}
                </div>
                <div class="search-results-footer">
                    <a href="/search?q=${encodeURIComponent(this.searchInput.val())}" class="btn btn-link btn-sm">
                        Показать все результаты
                    </a>
                </div>
            </div>
        `;

        this.searchResults.html(html).addClass('show');
    }

    showNoResults() {
        const html = `
            <div class="search-results-dropdown">
                <div class="search-no-results">
                    <div class="text-center py-4">
                        <i class="bi bi-search text-muted mb-2" style="font-size: 2rem;"></i>
                        <p class="text-muted mb-0">Ничего не найдено</p>
                    </div>
                </div>
        `;
        this.searchResults.html(html).addClass('show');
    }

    showError(message) {
        const html = `
            <div class="search-results-dropdown">
                <div class="search-error">
                    <div class="alert alert-danger mb-0">${message}</div>
                </div>
            </div>
        `;
        this.searchResults.html(html).addClass('show');
    }

    hideResults() {
        this.searchResults.removeClass('show');
    }
}

// Кнопка "Наверх"
class BackToTop {
    constructor() {
        this.button = $('#backToTop');
        this.init();
    }

    init() {
        if (!this.button.length) return;

        // Показываем/скрываем кнопку при прокрутке
        $(window).on('scroll', () => this.toggleVisibility());

        // Обработчик клика
        this.button.on('click', (e) => {
            e.preventDefault();
            this.scrollToTop();
        });

        // Инициализация видимости
        this.toggleVisibility();
    }

    toggleVisibility() {
        if ($(window).scrollTop() > 300) {
            this.button.css({
                'opacity': '1',
                'visibility': 'visible',
                'transform': 'translateY(0)'
            });
        } else {
            this.button.css({
                'opacity': '0',
                'visibility': 'hidden',
                'transform': 'translateY(10px)'
            });
        }
    }

    scrollToTop() {
        $('html, body').animate({ scrollTop: 0 }, 500);
    }
}

// Bootstrap компоненты инициализация
class BootstrapComponents {
    constructor() {
        this.init();
    }

    init() {
        // Tooltips - используем нативный Bootstrap API
        this.initTooltips();

        // Popovers - используем нативный Bootstrap API
        this.initPopovers();

        // Dropdowns - Bootstrap 5 сам инициализирует их при клике по data-bs-toggle="dropdown"
        // Но мы можем предварительно инициализировать если нужно

        // Инициализация всех dropdown элементов
        this.initDropdowns();
    }

    initTooltips() {
        // Используем нативный Bootstrap API через jQuery элементы
        $('[data-bs-toggle="tooltip"]').each(function() {
            new bootstrap.Tooltip(this);
        });
    }

    initPopovers() {
        // Используем нативный Bootstrap API через jQuery элементы
        $('[data-bs-toggle="popover"]').each(function() {
            new bootstrap.Popover(this);
        });
    }

    initDropdowns() {
        // Инициализируем dropdown элементы которые не инициализированы автоматически
        $('.dropdown-toggle').each(function() {
            if (!this._dropdown) {
                this._dropdown = new bootstrap.Dropdown(this);
            }
        });
    }
}

// Уведомления
class NotificationManager {
    constructor() {
        this.notificationBadge = $('.notification-badge');
        this.notificationDropdown = $('.dropdown-notifications');
        this.markAllReadBtn = $('.mark-all-read');

        this.init();
    }

    init() {
        if (this.markAllReadBtn.length) {
            this.markAllReadBtn.on('click', (e) => {
                e.preventDefault();
                this.markAllAsRead();
            });
        }

        // Помечаем уведомление как прочитанное при клике
        $(document).on('click', '.notification-item', (e) => {
            const notificationId = $(e.currentTarget).data('id');
            if (notificationId) {
                this.markAsRead(notificationId);
            }
        });
    }

    async markAllAsRead() {
        try {
            const response = await fetch('/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Content-Type': 'application/json'
                }
            });

            if (response.ok) {
                this.notificationBadge.remove();
                $('.notification-item').removeClass('unread');
                this.showToast('Все уведомления отмечены как прочитанные');
            }
        } catch (error) {
            console.error('Error marking notifications as read:', error);
        }
    }

    async markAsRead(notificationId) {
        try {
            await fetch(`/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        } catch (error) {
            console.error('Error marking notification as read:', error);
        }
    }

    showToast(message, type = 'success') {
        const toastId = 'toast-' + Date.now();
        const toastHtml = `
            <div class="toast ${type}" id="${toastId}" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <strong class="me-auto">Уведомление</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    ${message}
                </div>
            </div>
        `;

        $('.toast-container').append(toastHtml);
        const toastElement = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastElement);
        toast.show();

        // Удаляем toast после скрытия
        $(toastElement).on('hidden.bs.toast', function () {
            $(this).remove();
        });
    }
}

// Dropdown меню пользователя
class UserDropdownManager {
    constructor() {
        this.userToggle = $('.user-toggle');
        this.userDropdown = $('.user-dropdown .dropdown-menu');
        this.init();
    }

    init() {
        if (!this.userToggle.length) return;

        // Закрытие dropdown при клике вне его
        $(document).on('click', (e) => {
            if (!$(e.target).closest('.user-dropdown').length) {
                this.hideDropdown();
            }
        });

        // Обработка клавиши Escape
        $(document).on('keydown', (e) => {
            if (e.key === 'Escape' && this.userDropdown.hasClass('show')) {
                this.hideDropdown();
            }
        });
    }

    hideDropdown() {
        this.userDropdown.removeClass('show');
        this.userToggle.attr('aria-expanded', 'false');
    }
}

// Анимации и эффекты
class Animations {
    constructor() {
        this.init();
    }

    init() {
        // Плавное появление элементов при скролле
        this.animateOnScroll();

        // Эффект параллакса для фонов
        this.parallaxEffect();

        // Анимация загрузки контента
        this.contentLoadAnimation();
    }

    animateOnScroll() {
        const animatedElements = $('.animate-on-scroll');

        if (!animatedElements.length) return;

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    $(entry.target).addClass('animated');
                }
            });
        }, {
            threshold: 0.1
        });

        animatedElements.each((_, element) => {
            observer.observe(element);
        });
    }

    parallaxEffect() {
        $(window).on('scroll', () => {
            const scrolled = $(window).scrollTop();
            $('.parallax-bg').css('transform', `translateY(${scrolled * 0.5}px)`);
        });
    }

    contentLoadAnimation() {
        // Добавляем класс для анимации появления контента
        $('.content-area').addClass('content-loaded');

        // Анимация для карточек
        $('.card').each((index, element) => {
            $(element).css({
                'animation-delay': `${index * 0.1}s`
            }).addClass('card-animate');
        });
    }
}

// Инициализация при загрузке страницы
$(document).ready(function() {
    // Инициализация менеджеров
    const sidebarManager = new SidebarManager();
    const themeManager = new ThemeManager();
    const searchManager = new SearchManager();
    const backToTop = new BackToTop();
    const bootstrapComponents = new BootstrapComponents();
    const notificationManager = new NotificationManager();
    const userDropdownManager = new UserDropdownManager();
    const animations = new Animations();

    // Сохраняем в глобальной области для доступа из консоли
    window.App = {
        sidebar: sidebarManager,
        theme: themeManager,
        search: searchManager,
        bootstrap: bootstrapComponents,
        notifications: notificationManager
    };

    // Инициализация остальных функций
    initForms();
    initTables();
    initModals();

    $('#sidebarToggle').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        console.log('Sidebar toggle clicked');

        const sidebar = $('#sidebar');
        const isCollapsed = sidebar.hasClass('sidebar-collapsed');

        if (isCollapsed) {
            sidebar.removeClass('sidebar-collapsed');
            localStorage.setItem('sidebarCollapsed', 'false');
            console.log('Sidebar expanded');
        } else {
            sidebar.addClass('sidebar-collapsed');
            localStorage.setItem('sidebarCollapsed', 'true');
            console.log('Sidebar collapsed');
        }

        // Анимируем иконку
        const icon = $(this).find('.sidebar-toggle-icon');
        icon.css('transform', isCollapsed ? 'rotate(0deg)' : 'rotate(180deg)');
    });

    // Инициализация из localStorage
    const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    if (isCollapsed) {
        $('#sidebar').addClass('sidebar-collapsed');
        $('#sidebarToggle .sidebar-toggle-icon').css('transform', 'rotate(180deg)');
    }

    console.log('Application initialized with jQuery');
});

// Инициализация форм
function initForms() {
    // Валидация форм
    $('form').on('submit', function(e) {
        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');

        // Показываем индикатор загрузки
        if (submitBtn.length) {
            submitBtn.prop('disabled', true).addClass('loading');
        }

        // Скрываем ошибки при повторной отправке
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').remove();
    });

    // Автосохранение
    let saveTimeout;
    $('.auto-save').on('input', function() {
        clearTimeout(saveTimeout);
        saveTimeout = setTimeout(() => {
            const form = $(this).closest('form');
            if (form.length && form.data('auto-save')) {
                saveForm(form);
            }
        }, 1000);
    });

    async function saveForm(form) {
        const formData = new FormData(form[0]);
        const saveIndicator = form.find('.save-indicator');

        if (saveIndicator.length) {
            saveIndicator.addClass('saving').text('Сохранение...');
        }

        try {
            const response = await fetch(form.attr('action'), {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (saveIndicator.length) {
                if (response.ok) {
                    saveIndicator.addClass('saved').text('Сохранено');
                    setTimeout(() => {
                        saveIndicator.removeClass('saving saved').text('');
                    }, 2000);
                } else {
                    saveIndicator.addClass('error').text('Ошибка');
                }
            }
        } catch (error) {
            console.error('Auto-save error:', error);
            if (saveIndicator.length) {
                saveIndicator.addClass('error').text('Ошибка');
            }
        }
    }
}

// Инициализация таблиц
function initTables() {
    // Сортировка таблиц
    $('.sortable th[data-sort]').on('click', function() {
        const th = $(this);
        const table = th.closest('table');
        const sortBy = th.data('sort');
        const isAsc = th.hasClass('sort-asc');

        // Сбрасываем сортировку для других колонок
        table.find('th').removeClass('sort-asc sort-desc');

        // Устанавливаем новую сортировку
        th.addClass(isAsc ? 'sort-desc' : 'sort-asc');

        // Здесь можно добавить логику сортировки данных
        console.log(`Sort by ${sortBy} ${isAsc ? 'desc' : 'asc'}`);
    });

    // Выбор строк в таблице
    $('.selectable-table tbody tr').on('click', function(e) {
        if (!$(e.target).is('a, button, input')) {
            $(this).toggleClass('selected');
            updateSelectedCount();
        }
    });

    // Кнопка "Выбрать все"
    $('.select-all').on('change', function() {
        const isChecked = $(this).is(':checked');
        $('.selectable-table tbody tr').toggleClass('selected', isChecked);
        updateSelectedCount();
    });

    function updateSelectedCount() {
        const selectedCount = $('.selectable-table tbody tr.selected').length;
        $('.selected-count').text(`Выбрано: ${selectedCount}`);
    }
}

// Инициализация модальных окон
function initModals() {
    // Открытие модального окна с данными
    $(document).on('click', '[data-modal-target]', function(e) {
        e.preventDefault();

        const target = $(this).data('modal-target');
        const modalElement = document.querySelector(target);

        if (modalElement) {
            // Загрузка данных если нужно
            const loadUrl = $(this).data('load-url');
            const modal = $(target);

            if (loadUrl) {
                loadModalContent(modal, loadUrl);
            }

            // Показать модальное окно с использованием Bootstrap API
            const bsModal = new bootstrap.Modal(modalElement);
            bsModal.show();
        }
    });

    async function loadModalContent(modal, url) {
        const modalBody = modal.find('.modal-body');
        const loader = $('<div class="text-center py-5"><div class="spinner-border"></div></div>');

        modalBody.html(loader);

        try {
            const response = await fetch(url);
            const html = await response.text();
            modalBody.html(html);
        } catch (error) {
            modalBody.html('<div class="alert alert-danger">Ошибка загрузки данных</div>');
        }
    }

    // Закрытие модального окна с очисткой формы
    $('.modal').on('hidden.bs.modal', function() {
        const form = $(this).find('form');
        if (form.length && !form.data('persist')) {
            form[0].reset();
            form.find('.is-invalid').removeClass('is-invalid');
            form.find('.invalid-feedback').remove();
        }
    });
}

// Глобальные утилиты
$.fn.extend({
    // Анимация появления
    fadeInUp: function(duration = 400) {
        return this.each(function() {
            $(this).css({
                opacity: 0,
                transform: 'translateY(20px)'
            }).animate({
                opacity: 1,
                transform: 'translateY(0)'
            }, duration);
        });
    },

    // Анимация исчезновения
    fadeOutDown: function(duration = 400, callback) {
        return this.each(function() {
            $(this).animate({
                opacity: 0,
                transform: 'translateY(20px)'
            }, duration, callback);
        });
    },

    // Переключение видимости с анимацией
    toggleSlide: function(duration = 300) {
        return this.each(function() {
            if ($(this).is(':hidden')) {
                $(this).slideDown(duration);
            } else {
                $(this).slideUp(duration);
            }
        });
    }
});

// Глобальные хелперы
window.Helpers = {
    // Форматирование даты
    formatDate: function(date, format = 'DD.MM.YYYY') {
        const d = new Date(date);
        const day = d.getDate().toString().padStart(2, '0');
        const month = (d.getMonth() + 1).toString().padStart(2, '0');
        const year = d.getFullYear();

        return format.replace('DD', day).replace('MM', month).replace('YYYY', year);
    },

    // Форматирование числа
    formatNumber: function(num, decimals = 2) {
        return parseFloat(num).toLocaleString('ru-RU', {
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals
        });
    },

    // Копирование в буфер обмена
    copyToClipboard: function(text) {
        navigator.clipboard.writeText(text).then(() => {
            console.log('Текст скопирован');
        }).catch(err => {
            console.error('Ошибка копирования:', err);
        });
    },

    // Генерация UUID
    generateUUID: function() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            const r = Math.random() * 16 | 0;
            const v = c === 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }
};
