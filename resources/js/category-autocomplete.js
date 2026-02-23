import $ from 'jquery';

/**
 * Category Autocomplete - функциональный стиль
 * Использует autocomplete.js плагин
 */
(function($) {
    'use strict';

    // Хранилище выбранных категорий
    var selectedCategories = [];

    /**
     * Инициализация autocomplete для категорий
     */
    function initCategoryAutocomplete() {
        var $input = $('#category-search');
        var $container = $('#selected-categories');

        if (!$input.length) {
            return;
        }

        console.debug('[CategoryAutocomplete] Initializing');

        // Загружаем уже выбранные категории из DOM
        loadExistingCategories($container);

        // Инициализируем autocomplete
        $input.autocomplete({
            source: function(query, response) {
                searchCategories(query, response);
            },
            select: function(item) {
                selectCategory(item, $input, $container);
            }
        });

        // Обработка удаления категории
        $(document).on('click', '.remove-category', function(e) {
            e.preventDefault();
            var $category = $(e.currentTarget).closest('.selected-category');
            var id = parseInt($category.data('id'));
            removeCategory(id, $container);
        });
    }

    /**
     * Загрузка существующих категорий при редактировании
     */
    function loadExistingCategories($container) {
        $container.find('.selected-category').each(function() {
            var $element = $(this);
            var id = parseInt($element.data('id'));
            var name = $element.data('name');
            var fullPath = $element.data('full-path');

            if (id && name) {
                selectedCategories.push({ id: id, name: name, full_path: fullPath });
                console.debug('[CategoryAutocomplete] Loaded existing category', { id: id, name: name });
            }
        });

        console.debug('[CategoryAutocomplete] Total existing categories:', selectedCategories.length);
    }

    /**
     * AJAX поиск категорий
     */
    function searchCategories(query, callback) {
        // Минимум 2 символа
        if (query.length < 2) {
            callback([]);
            return;
        }

        console.debug('[CategoryAutocomplete] Searching', { query: query });

        var url = '/category/search?q=' + encodeURIComponent(query);

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                console.debug('[CategoryAutocomplete] Results received', {
                    query: query,
                    count: data.length,
                    results: data
                });

                // Преобразуем в формат для autocomplete
                var items = data.map(function(category) {
                    return {
                        label: category.full_path,
                        value: category.id,
                        name: category.name,
                        full_path: category.full_path
                    };
                });

                callback(items);
            },
            error: function(xhr, status, error) {
                console.error('[CategoryAutocomplete] Search error', {
                    query: query,
                    error: error
                });
                callback([]);
            }
        });
    }

    /**
     * Выбор категории из результатов
     */
    function selectCategory(item, $input, $container) {
        var id = parseInt(item.value);
        var name = item.name;
        var fullPath = item.full_path;

        console.debug('[CategoryAutocomplete] Selecting category', {
            id: id,
            name: name,
            full_path: fullPath
        });

        // Проверка на дублирование
        var exists = selectedCategories.some(function(cat) {
            return cat.id === id;
        });

        if (exists) {
            console.debug('[CategoryAutocomplete] Category already selected, ignoring', { id: id });
            $input.val('');
            return;
        }

        // Добавляем в массив
        selectedCategories.push({
            id: id,
            name: name,
            full_path: fullPath
        });

        console.debug('[CategoryAutocomplete] Category added', {
            id: id,
            total: selectedCategories.length
        });

        // Добавляем в DOM
        renderSelectedCategory(id, name, fullPath, $container);

        // Очищаем input
        $input.val('');
    }

    /**
     * Рендеринг выбранной категории
     */
    function renderSelectedCategory(id, name, fullPath, $container) {
        // Удаляем placeholder если есть
        $container.find('.text-muted').remove();

        var html = '<div class="selected-category mb-1 d-inline-block" ' +
            'data-id="' + id + '" ' +
            'data-name="' + escapeHtml(name) + '" ' +
            'data-full-path="' + escapeHtml(fullPath) + '">' +
            '<span class="badge bg-primary">' +
            '<i class="fa fa-times-circle remove-category" style="cursor: pointer;"></i> ' +
            escapeHtml(fullPath || name) +
            '</span>' +
            '<input type="hidden" name="product_category[]" value="' + id + '">' +
            '</div>';

        $container.append(html);
    }

    /**
     * Удаление категории
     */
    function removeCategory(id, $container) {
        console.debug('[CategoryAutocomplete] Removing category', { id: id });

        // Удаляем из массива
        selectedCategories = selectedCategories.filter(function(cat) {
            return cat.id !== id;
        });

        console.debug('[CategoryAutocomplete] Category removed', {
            id: id,
            total: selectedCategories.length
        });

        // Удаляем из DOM
        $container.find('.selected-category[data-id="' + id + '"]').remove();

        // Если категорий нет, показываем placeholder
        if (selectedCategories.length === 0) {
            $container.html('<p class="text-muted mb-0 small">Выбранные категории будут отображаться здесь</p>');
        }
    }

    /**
     * Экранирование HTML
     */
    function escapeHtml(text) {
        var map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return String(text).replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    // Инициализация при загрузке DOM
    $(document).ready(function() {
        initCategoryAutocomplete();
    });

})(window.jQuery);
