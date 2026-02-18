@props([
    'headers' => [],
    'rows' => [],
    'emptyText' => 'Данные не найдены',
    'emptyIcon' => 'table-cells',
    'emptyIconSet' => 'heroicon',
    'striped' => true,
    'hover' => true,
    'bordered' => false,
    'borderless' => false,
    'sm' => false,
    'responsive' => true,
    'selectable' => false,
    'bulkActions' => [],
    'filters' => [],
    'showFilters' => false,
    'pagination' => null,
    'sortable' => true,
    'sortColumn' => null,
    'sortDirection' => 'asc',
    'loading' => false,
    'loadingText' => 'Загрузка...',
    'id' => 'table-' . Str::random(10),
    'class' => '',
    'card' => true,
    'cardTitle' => null,
    'cardActions' => null,
    'footer' => null,
])

@php
    // Классы таблицы
    $tableClasses = 'table';
    if ($striped) $tableClasses .= ' table-striped';
    if ($hover) $tableClasses .= ' table-hover';
    if ($bordered) $tableClasses .= ' table-bordered';
    if ($borderless) $tableClasses .= ' table-borderless';
    if ($sm) $tableClasses .= ' table-sm';
    if ($class) $tableClasses .= ' ' . $class;

    // Определяем, есть ли данные
    $hasRows = count($rows) > 0;

    // Если есть массовые действия, добавляем колонку для чекбоксов
    if ($selectable && !empty($bulkActions)) {
        $headers = array_merge([
            ['type' => 'checkbox', 'width' => '1%']
        ], $headers);
    }

    // Если есть действия, добавляем колонку для кнопок
    $hasActionsColumn = false;
    foreach ($headers as $header) {
        if (($header['type'] ?? null) === 'actions') {
            $hasActionsColumn = true;
            break;
        }
    }
@endphp

<div class="table-wrapper" id="{{ $id }}">
    {{-- Карточка-обертка --}}
    @if($card)
        <div class="card">
            {{-- Заголовок карточки --}}
            @if($cardTitle || $cardActions || $showFilters || (!empty($bulkActions) && $selectable))
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        {{-- Заголовок --}}
                        @if($cardTitle)
                            <h5 class="card-title mb-0">
                                {{ $cardTitle }}
                                @if($hasRows && isset($pagination['total']))
                                    <span class="text-muted fs-6">({{ $pagination['total'] }})</span>
                                @endif
                            </h5>
                        @endif

                        {{-- Действия карточки --}}
                        <div class="card-actions">
                            {{ $cardActions }}

                            {{-- Кнопка фильтров --}}
                            @if($filters && !$showFilters)
                                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#filters-{{ $id }}">
                                    <x-main-icon name="funnel" set="heroicon" size="sm" class="me-1"/>
                                    Фильтры
                                </button>
                            @endif
                        </div>
                    </div>

                    {{-- Массовые действия --}}
                    @if($selectable && !empty($bulkActions))
                        <x-table.bulk-actions
                            :actions="$bulkActions"
                            :total="count($rows)"
                        />
                    @endif

                    {{-- Фильтры --}}
                    @if($filters && $showFilters)
                        <x-table.filters
                            :filters="$filters"
                            :id="$id"
                        />
                    @endif
                </div>
            @endif

            {{-- Тело карточки --}}
            <div class="card-body p-0">
                @endif

                {{-- Таблица --}}
                <div class="{{ $responsive ? 'table-responsive' : '' }}">
                    <table class="{{ $tableClasses }}">
                        {{-- Заголовок --}}
                        <x-table.head
                            :headers="$headers"
                            :sortable="$sortable"
                            :sortColumn="$sortColumn"
                            :sortDirection="$sortDirection"
                            :selectable="$selectable"
                            :hasActionsColumn="$hasActionsColumn"
                        />

                        {{-- Тело таблицы --}}
                        <x-table.body
                            :headers="$headers"
                            :rows="$rows"
                            :emptyText="$emptyText"
                            :emptyIcon="$emptyIcon"
                            :emptyIconSet="$emptyIconSet"
                            :selectable="$selectable"
                            :loading="$loading"
                            :loadingText="$loadingText"
                        />
                    </table>
                </div>

                {{-- Футер таблицы (пагинация) --}}
                @if($pagination || $footer)
                    <x-table.footer
                        :pagination="$pagination"
                        :footer="$footer"
                        :class="$card ? 'px-4' : ''"
                    />
                @endif

                {{-- Закрываем карточку --}}
                @if($card)
            </div>
        </div>
    @endif

    {{-- Скрытые фильтры --}}
    @if($filters && !$showFilters)
        <div class="collapse" id="filters-{{ $id }}">
            <div class="card card-body">
                <x-table.filters
                    :filters="$filters"
                    :id="$id"
                />
            </div>
        </div>
    @endif
</div>
