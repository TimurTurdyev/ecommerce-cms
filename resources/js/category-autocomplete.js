import $ from 'jquery';

/**
 * Autocomplete для выбора категорий в форме товара
 */
class CategoryAutocomplete {
    constructor() {
        this.searchInput = $('#category-search');
        this.dropdown = $('#category-dropdown');
        this.selectedContainer = $('#selected-categories');
        this.selectedCategories = [];
        this.searchTimeout = null;

        if (this.searchInput.length) {
            this.init();
        }
    }

    init() {
        console.debug('[CategoryAutocomplete] Initializing');

        // Загружаем уже выбранные категории из DOM
        this.loadExistingCategories();

        // Привязываем события
        this.bindEvents();
    }

    /**
     * Загрузка уже выбранных категорий при редактировании товара
     */
    loadExistingCategories() {
        this.selectedContainer.find('.selected-category').each((index, element) => {
            const $element = $(element);
            const id = parseInt($element.data('id'));
            const name = $element.data('name');
            const fullPath = $element.data('full-path');

            if (id && name) {
                this.selectedCategories.push({ id, name, full_path: fullPath });
                console.debug('[CategoryAutocomplete] Loaded existing category', { id, name, full_path: fullPath });
            }
        });

        console.debug('[CategoryAutocomplete] Total existing categories', this.selectedCategories.length);
    }

    /**
     * Привязка обработчиков событий
     */
    bindEvents() {
        // Поиск при вводе (с debounce 300ms)
        this.searchInput.on('input', (e) => {
            const query = e.target.value.trim();
            this.debounce(() => this.search(query), 300);
        });

        // Клик на результат поиска
        $(document).on('click', '.category-result', (e) => {
            e.preventDefault();
            const $result = $(e.currentTarget);
            this.selectCategory(
                parseInt($result.data('id')),
                $result.data('name'),
                $result.data('full-path')
            );
        });

        // Удаление категории
        $(document).on('click', '.remove-category', (e) => {
            e.preventDefault();
            const $category = $(e.currentTarget).closest('.selected-category');
            this.removeCategory(parseInt($category.data('id')));
        });

        // Закрытие dropdown при клике вне его
        $(document).on('click', (e) => {
            if (!$(e.target).closest('#category-search, #category-dropdown').length) {
                this.hideResults();
            }
        });

        // Закрытие dropdown по Escape
        this.searchInput.on('keydown', (e) => {
            if (e.key === 'Escape') {
                this.hideResults();
            }
        });
    }

    /**
     * Debounce функция
     */
    debounce(callback, delay) {
        clearTimeout(this.searchTimeout);
        this.searchTimeout = setTimeout(callback, delay);
    }

    /**
     * AJAX поиск категорий
     */
    async search(query) {
        console.debug('[CategoryAutocomplete] Search triggered', { query });

        // Минимум 2 символа
        if (query.length < 2) {
            this.hideResults();
            return;
        }

        try {
            const url = `/category/search?q=${encodeURIComponent(query)}`;
            console.debug('[CategoryAutocomplete] AJAX request', { url });

            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }

            const results = await response.json();
            console.debug('[CategoryAutocomplete] Search results received', {
                query,
                results_count: results.length,
                results: results
            });

            this.showResults(results);

        } catch (error) {
            console.error('[CategoryAutocomplete] Search error', { query, error: error.message });
            this.showError('Ошибка поиска категорий');
        }
    }

    /**
     * Отображение результатов поиска
     */
    showResults(results) {
        if (!results.length) {
            this.showNoResults();
            return;
        }

        const resultsHtml = results.map(category => `
            <div class="category-result dropdown-item"
                 data-id="${category.id}"
                 data-name="${category.name}"
                 data-full-path="${category.full_path}">
                ${this.escapeHtml(category.full_path)}
            </div>
        `).join('');

        this.dropdown.html(resultsHtml).show();
    }

    /**
     * Показать сообщение "Ничего не найдено"
     */
    showNoResults() {
        this.dropdown.html(`
            <div class="dropdown-item text-muted">
                Ничего не найдено
            </div>
        `).show();
    }

    /**
     * Показать ошибку
     */
    showError(message) {
        this.dropdown.html(`
            <div class="dropdown-item text-danger">
                ${this.escapeHtml(message)}
            </div>
        `).show();
    }

    /**
     * Скрыть результаты поиска
     */
    hideResults() {
        this.dropdown.hide().empty();
    }

    /**
     * Выбор категории из результатов поиска
     */
    selectCategory(id, name, fullPath) {
        console.debug('[CategoryAutocomplete] Selecting category', { id, name, full_path: fullPath });

        // Проверка на дублирование
        if (this.selectedCategories.some(cat => cat.id === id)) {
            console.debug('[CategoryAutocomplete] Category already selected, ignoring', { id });
            this.hideResults();
            this.searchInput.val('').focus();
            return;
        }

        // Добавляем в массив
        this.selectedCategories.push({ id, name, full_path: fullPath });
        console.debug('[CategoryAutocomplete] Category added', {
            id,
            total_selected: this.selectedCategories.length
        });

        // Добавляем в DOM
        this.renderSelectedCategory(id, name, fullPath);

        // Очищаем поле поиска и скрываем результаты
        this.hideResults();
        this.searchInput.val('').focus();
    }

    /**
     * Рендеринг выбранной категории в блоке
     */
    renderSelectedCategory(id, name, fullPath) {
        const categoryHtml = `
            <div class="selected-category" data-id="${id}" data-name="${name}" data-full-path="${fullPath}">
                <i class="fa fa-times-circle remove-category"></i>
                ${this.escapeHtml(fullPath || name)}
                <input type="hidden" name="product_category[]" value="${id}">
            </div>
        `;

        this.selectedContainer.append(categoryHtml);
    }

    /**
     * Удаление категории из выбранных
     */
    removeCategory(id) {
        console.debug('[CategoryAutocomplete] Removing category', { id });

        // Удаляем из массива
        this.selectedCategories = this.selectedCategories.filter(cat => cat.id !== id);
        console.debug('[CategoryAutocomplete] Category removed', {
            id,
            total_selected: this.selectedCategories.length
        });

        // Удаляем из DOM
        this.selectedContainer.find(`.selected-category[data-id="${id}"]`).remove();
    }

    /**
     * Экранирование HTML для предотвращения XSS
     */
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// Инициализация при загрузке DOM
$(document).ready(function() {
    new CategoryAutocomplete();
});
