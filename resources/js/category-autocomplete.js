/**
 * Category Autocomplete - универсальный компонент
 * Автоматически инициализируется для элементов с data-category-autocomplete
 */
(function($) {
    'use strict';

    /**
     * Инициализация autocomplete для одного элемента
     */
    function initAutocomplete($wrapper) {
        var $input = $wrapper.find('.category-autocomplete-input');
        var $container = $wrapper.find('.category-autocomplete-selected');
        var searchUrl = $wrapper.data('url') || '/category/search';
        var inputName = $wrapper.data('name') || 'product_category[]';
        var selectedCategories = [];

        if (!$input.length || !$container.length) {
            return;
        }

        // Загружаем уже выбранные категории из DOM
        loadExistingCategories($container, selectedCategories);

        // Инициализируем autocomplete
        $input.autocomplete({
            source: function(query, response) {
                searchCategories(query, response, searchUrl);
            },
            select: function(item) {
                selectCategory(item, $input, $container, selectedCategories, inputName);
            }
        });

        // Обработка удаления категории
        $container.on('click', '.remove-category', function(e) {
            e.preventDefault();
            var $category = $(e.currentTarget).closest('.selected-category');
            var id = parseInt($category.data('id'));
            removeCategory(id, $container, selectedCategories);
        });
    }

    /**
     * Загрузка существующих категорий при редактировании
     */
    function loadExistingCategories($container, selectedCategories) {
        $container.find('.selected-category').each(function() {
            var $element = $(this);
            var id = parseInt($element.data('id'));
            var name = $element.data('name');
            var fullPath = $element.data('full-path');

            if (id && name) {
                selectedCategories.push({ id: id, name: name, full_path: fullPath });
            }
        });
    }

    /**
     * AJAX поиск категорий
     */
    function searchCategories(query, callback, searchUrl) {
        if (query.length < 2) {
            callback([]);
            return;
        }

        var url = searchUrl + '?q=' + encodeURIComponent(query);

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                callback(data);
            },
            error: function(xhr, status, error) {
                console.error('[CategoryAutocomplete] Search error:', error);
                callback([]);
            }
        });
    }

    /**
     * Выбор категории из результатов
     */
    function selectCategory(item, $input, $container, selectedCategories, inputName) {
        var id = parseInt(item.value);
        var name = item.name;
        var fullPath = item.full_path;

        // Проверка на дублирование
        var exists = selectedCategories.some(function(cat) {
            return cat.id === id;
        });

        if (exists) {
            $input.val('');
            return;
        }

        // Добавляем в массив
        selectedCategories.push({
            id: id,
            name: name,
            full_path: fullPath
        });

        // Добавляем в DOM
        renderSelectedCategory(id, name, fullPath, $container, inputName);

        // Очищаем input
        $input.val('');
    }

    /**
     * Рендеринг выбранной категории
     */
    function renderSelectedCategory(id, name, fullPath, $container, inputName) {
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
            '<input type="hidden" name="' + inputName + '" value="' + id + '">' +
            '</div>';

        $container.append(html);
    }

    /**
     * Удаление категории
     */
    function removeCategory(id, $container, selectedCategories) {
        // Удаляем из массива
        var index = -1;
        for (var i = 0; i < selectedCategories.length; i++) {
            if (selectedCategories[i].id === id) {
                index = i;
                break;
            }
        }
        if (index !== -1) {
            selectedCategories.splice(index, 1);
        }

        // Удаляем из DOM
        $container.find('.selected-category[data-id="' + id + '"]').remove();

        // Если категорий нет, показываем placeholder
        if (selectedCategories.length === 0 && $container.data('placeholder')) {
            $container.html('<p class="text-muted mb-0 small">' + $container.data('placeholder') + '</p>');
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

    // Автоматическая инициализация всех элементов с data-category-autocomplete
    $(document).ready(function() {
        $('[data-category-autocomplete]').each(function() {
            initAutocomplete($(this));
        });
    });

})(window.jQuery);
