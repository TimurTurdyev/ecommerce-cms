@props([
    'actions' => [],
    'total' => 0,
])

@php
    $hasActions = count($actions) > 0;
@endphp

@if($hasActions)
    <div class="table-bulk-actions d-none">
        <div class="d-flex align-items-center p-2 gap-2">
            <div class="me-3">
                <span class="selected-count">0</span> из <span class="total-count">{{ $total }}</span> выбрано
            </div>
            @foreach($actions as $action)
                @if($action['type'] === 'button')
                    <x-button
                        variant="{{ $action['variant'] ?? 'secondary' }}"
                        data-action="{{ $action['action'] }}"
                        data-url="{{ $action['url'] ?? '#' }}"
                        data-confirm="{{ $action['confirm'] ?? false }}"
                        data-confirm-text="{{ $action['confirm_text'] ?? 'Вы уверены?' }}"
                        :icon="$action['icon']"
                    >
                        {{ $action['label'] }}
                    </x-button>
                @elseif($action['type'] === 'dropdown')
                    <div class="dropdown">
                        <x-button
                            class="dropdown-toggle"
                            variant="{{ $action['variant'] ?? 'secondary' }}"
                            data-bs-toggle="dropdown"
                        >
                            {{ $action['label'] }}
                        </x-button>
                        <div class="dropdown-menu">
                            @foreach($action['items'] as $item)
                                <a
                                    class="dropdown-item"
                                    href="{{ $item['url'] ?? '#' }}"
                                    data-action="{{ $item['action'] }}"
                                    data-confirm="{{ $item['confirm'] ?? false }}"
                                >
                                    @if($item['icon'] ?? false)
                                        <x-main-icon :name="$item['icon']" set="heroicon" size="sm" class="me-2"/>
                                    @endif
                                    {{ $item['label'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
            <x-button
                class="text-decoration-none"
                variant="link"
                size="sm"
                icon="x-mark"
            >
                Отменить выбор
            </x-button>
        </div>
    </div>
@endif
