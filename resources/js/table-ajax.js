import $ from 'jquery';

(function ($) {
    'use strict';

    function TableAjax(element, options) {
        this.$container = $(element);
        this.options = $.extend({}, $.fn.tableAjax.defaults, options);
        this.init();
    }

    TableAjax.prototype.init = function () {
        this.attachEvents();
    };

    /**
     * Привязка событий (с использованием пространства имён .tableAjax)
     */
    TableAjax.prototype.attachEvents = function () {
        // Удаляем предыдущие обработчики этого экземпляра
        this.$container.off('.tableAjax');

        // Отправка формы фильтров
        this.$container.on('submit.tableAjax', '.filter-form', (e) => {
            e.preventDefault();
            this.fetchTable();
        });

        // Сброс фильтров
        this.$container.on('click.tableAjax', '.reset-filters', () => {
            this.$container.find('.filter-form select').val('');
            this.$container.find('.filter-form input').val('');
            this.fetchTable();
        });

        // Поиск с debounce
        this.$container.on('input.tableAjax', '.search-input',
            this.debounce(() => {
                this.fetchTable();
            }, this.options.debounceDelay)
        );

        // Сортировка
        this.$container.on('click.tableAjax', '.sortable', (e) => {
            const $el = $(e.currentTarget);
            const sort = $el.data('sort');
            let direction = 'asc';

            const currentSort = this.getUrlParam('sort');
            const currentDir = this.getUrlParam('direction');

            if (currentSort === sort && currentDir === 'asc') {
                direction = 'desc';
            }

            this.fetchTable({sort, direction});
        });

        // Пагинация (ссылки могут динамически меняться)
        this.$container.on('click.tableAjax', '.pagination a', (e) => {
            e.preventDefault();
            const url = new URL(e.currentTarget.href);
            const page = url.searchParams.get('page');
            this.fetchTable({page});
        });

        // Обработка истории браузера
        if (this.options.history) {
            $(window).off('popstate.tableAjax').on('popstate.tableAjax', () => {
                this.fetchTable(null, false);
            });
        }
    };

    /**
     * Загрузка содержимого таблицы через AJAX
     * @param {Object} params   Дополнительные параметры запроса
     * @param {boolean} pushState Добавлять состояние в history?
     */
    TableAjax.prototype.fetchTable = function (params = {}, pushState = true) {
        const url = new URL(window.location.href);
        const $form = this.$container.find('.filter-form');
        const $search = this.$container.find('.search-input');

        // Данные из формы
        if ($form.length) {
            const formData = new FormData($form[0]);
            for (let [key, value] of formData.entries()) {
                if (value) {
                    url.searchParams.set(key, value);
                } else {
                    url.searchParams.delete(key);
                }
            }
        }

        // Поисковый запрос
        if ($search.length && $search.val()) {
            url.searchParams.set('search', $search.val());
        } else {
            url.searchParams.delete('search');
        }

        // Переопределяем переданными параметрами
        for (let key in params) {
            if (params[key] !== undefined && params[key] !== null) {
                url.searchParams.set(key, params[key]);
            } else {
                url.searchParams.delete(key);
            }
        }

        /**
         * Обработчики событий не нужно перепривязывать,
         * т.к. они установлены на контейнер через делегирование.
         */
        $.ajax({
            url: url.toString(),
            headers: {'X-Requested-With': 'XMLHttpRequest'}
        })
            .done((html) => {
                this.$container.html(html);

                if (pushState && this.options.history) {
                    history.pushState({}, '', url.toString());
                }
            })
            .fail((xhr, status, error) => {
                console.error('TableAjax error:', error);
            });
    };

    /**
     * Получение значения параметра из текущего URL
     */
    TableAjax.prototype.getUrlParam = function (name) {
        const params = new URLSearchParams(window.location.search);
        return params.get(name);
    };

    /**
     * Debounce-обёртка для функций
     */
    TableAjax.prototype.debounce = function (func, delay) {
        let timeout;
        return (...args) => {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), delay);
        };
    };

    // ---------------------------------------------------------------------
    // jQuery-плагин
    // ---------------------------------------------------------------------
    $.fn.tableAjax = function (options) {
        // Вызов метода, если первый аргумент — строка
        if (typeof options === 'string') {
            const args = Array.prototype.slice.call(arguments, 1);
            return this.each(function () {
                const instance = $(this).data('tableAjax');
                if (instance && typeof instance[options] === 'function') {
                    instance[options].apply(instance, args);
                }
            });
        }

        // Инициализация плагина
        return this.each(function () {
            const $this = $(this);

            // Читаем опции из data-атрибута (если есть)
            // data-table-ajax='{"debounceDelay":500, history: false}'
            let dataOptions = $this.data('table-ajax');
            if (typeof dataOptions === 'string') {
                try {
                    dataOptions = JSON.parse(dataOptions);
                } catch (e) {
                    dataOptions = {};
                }
            }

            // Объединяем опции: переданные при вызове + из data-атрибута
            const opts = $.extend({}, options, dataOptions);

            // Создаём экземпляр и сохраняем в data
            const instance = new TableAjax(this, opts);
            $this.data('tableAjax', instance);
        });
    };

    // Значения по умолчанию
    $.fn.tableAjax.defaults = {
        debounceDelay: 300,
        history: true
    };

    // Автоматическая инициализация для элементов с data-атрибутом
    $(document).ready(function () {
        $('[data-table-ajax]').tableAjax();
        // Example
        // Обновить таблицу с дополнительными параметрами
        // $('#table-container').tableAjax('fetchTable', { sort: 'name', direction: 'desc' });
        // Получить значение параметра из URL
        // const page = $('#table-container').tableAjax('getUrlParam', 'page');
    });
})($);
