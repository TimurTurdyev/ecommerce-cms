@props([
    'items' => [],
    'divider' => '/',
    'current' => null,
    'homeIcon' => 'house-door',
    'homeIconSet' => 'heroicon',
    'homeIconVariant' => 'outline',
    'homeText' => 'Главная',
    'homeUrl' => '/',
    'class' => '',
    'showIcons' => false,
])

@php
    $classes = 'breadcrumb';
    if ($class) $classes .= ' ' . $class;
@endphp

<nav aria-label="breadcrumb" {{ $attributes->except(['items', 'divider', 'current', 'homeIcon', 'homeIconSet', 'homeIconVariant', 'homeText', 'homeUrl', 'class', 'showIcons']) }}>
    <ol class="{{ $classes }}">
        {{-- Home item --}}
        <li class="breadcrumb-item">
            <a href="{{ $homeUrl }}" class="d-inline-flex align-items-center gap-1">
                @if($homeIcon && $showIcons)
                    @if($homeIconSet === 'heroicon')
                        <x-main-icon
                            :name="$homeIcon"
                            set="heroicon"
                            :variant="$homeIconVariant"
                            size="sm"
                        />
                    @else
                        <i class="bi bi-{{ $homeIcon }}"></i>
                    @endif
                @endif
                {{ $homeText }}
            </a>
        </li>

        {{-- Middle items --}}
        @foreach($items as $item)
            @if(isset($item['url']) && $item['url'])
                <li class="breadcrumb-item">
                    <a href="{{ $item['url'] }}" class="d-inline-flex align-items-center gap-1">
                        @if(isset($item['icon']) && $showIcons)
                            @if(($item['iconSet'] ?? 'bootstrap') === 'heroicon')
                                <x-main-icon
                                    :name="$item['icon']"
                                    set="heroicon"
                                    :variant="$item['iconVariant'] ?? 'outline'"
                                    size="sm"
                                />
                            @else
                                <i class="bi bi-{{ $item['icon'] }}"></i>
                            @endif
                        @endif
                        {{ $item['label'] }}
                    </a>
                </li>
            @else
                <li class="breadcrumb-item d-inline-flex align-items-center gap-1">
                    @if(isset($item['icon']) && $showIcons)
                        @if(($item['iconSet'] ?? 'bootstrap') === 'heroicon')
                            <x-main-icon
                                :name="$item['icon']"
                                set="heroicon"
                                :variant="$item['iconVariant'] ?? 'outline'"
                                size="sm"
                            />
                        @else
                            <i class="bi bi-{{ $item['icon'] }}"></i>
                        @endif
                    @endif
                    {{ $item['label'] }}
                </li>
            @endif
        @endforeach

        {{-- Current page --}}
        @if($current)
            <li class="breadcrumb-item active" aria-current="page">
                {{ $current }}
            </li>
        @endif
    </ol>
</nav>
