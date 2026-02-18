@props([
    'filters' => [],
    'id' => 'table-' . Str::random(10),
])

<form method="GET" class="table-filters" id="filters-form-{{ $id }}">
    <div class="row g-3">
        @foreach($filters as $key => $filter)
            @php
                $type = $filter['type'] ?? 'text';
                $label = $filter['label'] ?? ucfirst(str_replace('_', ' ', $key));
                $placeholder = $filter['placeholder'] ?? $label;
                $value = request($key, $filter['value'] ?? '');
                $options = $filter['options'] ?? [];
                $width = $filter['width'] ?? 'col-md-3';
            @endphp

            <div class="{{ $width }}">
                <label class="form-label small mb-1">{{ $label }}</label>

                @switch($type)
                    @case('select')
                        <select
                            name="{{ $key }}"
                            class="form-select form-select-sm"
                            onchange="this.form.submit()"
                        >
                            <option value="">{{ $placeholder }}</option>
                            @foreach($options as $optionValue => $optionLabel)
                                <option value="{{ $optionValue }}" {{ $value == $optionValue ? 'selected' : '' }}>
                                    {{ $optionLabel }}
                                </option>
                            @endforeach
                        </select>
                        @break

                    @case('date')
                        <input
                            type="date"
                            name="{{ $key }}"
                            class="form-control form-control-sm"
                            value="{{ $value }}"
                            onchange="this.form.submit()"
                        >
                        @break

                    @case('daterange')
                        <div class="input-group input-group-sm">
                            <input
                                type="date"
                                name="{{ $key }}_from"
                                class="form-control"
                                value="{{ request($key . '_from') }}"
                            >
                            <span class="input-group-text">—</span>
                            <input
                                type="date"
                                name="{{ $key }}_to"
                                class="form-control"
                                value="{{ request($key . '_to') }}"
                            >
                        </div>
                        @break

                    @default
                        <input
                            type="text"
                            name="{{ $key }}"
                            class="form-control form-control-sm"
                            placeholder="{{ $placeholder }}"
                            value="{{ $value }}"
                            onkeydown="if(event.key === 'Enter') this.form.submit()"
                        >
                @endswitch
            </div>
        @endforeach

        {{-- Кнопки действий --}}
        <div class="col-md-auto align-self-end">
            <button type="submit" class="btn btn-sm btn-primary">
                <x-main-icon name="magnifying-glass" set="heroicon" size="sm" class="me-1" />
                Применить
            </button>

            @if(request()->hasAny(array_keys($filters)))
                <a href="{{ request()->url() }}" class="btn btn-sm btn-outline-secondary ms-1">
                    <x-main-icon name="x-mark" set="heroicon" size="sm" class="me-1" />
                    Сбросить
                </a>
            @endif
        </div>
    </div>
</form>
