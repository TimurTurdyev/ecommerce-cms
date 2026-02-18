@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => null,
    'outline' => false,
    'active' => false,
    'disabled' => false,
    'block' => false,
    'loading' => false,
    'loadingText' => 'Загрузка...',
    'icon' => null,
    'iconSet' => 'heroicon', // 'bootstrap', 'heroicon'
    'iconVariant' => 'outline', // для heroicon
    'iconPosition' => 'start',
    'badge' => null,
    'class' => '',
    'href' => null,
    'target' => null,
    'iconOnly' => false,
])

@php
    $classes = 'btn';

    // Вариант кнопки
    if ($outline) {
        $classes .= ' btn-outline-' . $variant;
    } else {
        $classes .= ' btn-' . $variant;
    }

    // Размер
    if ($size && in_array($size, ['sm', 'lg'])) {
        $classes .= ' btn-' . $size;
    }

    // Состояния
    if ($active) $classes .= ' active';
    if ($block) $classes .= ' d-block w-100';
    if ($iconOnly) $classes .= ' btn-icon';
    if ($class) $classes .= ' ' . $class;

    // Тег в зависимости от наличия ссылки
    $tag = $href ? 'a' : 'button';

    // Размеры иконок
    $iconSize = match($size) {
        'sm' => 'sm',
        'lg' => 'lg',
        default => 'md',
    };

    $attributes = $attributes->merge([
        'class' => $classes,
        'type' => $tag === 'button' ? $type : null,
        'href' => $href,
        'target' => $target,
        'disabled' => $disabled && $tag === 'button' ? true : null,
        'aria-disabled' => $disabled ? 'true' : null,
        'aria-label' => $iconOnly && $slot ? (string) $slot : null,
    ]);
@endphp

<{{ $tag }} {{ $attributes }}>
@if($loading)
    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
    {{ $loadingText }}
@else
    @if($icon && $iconPosition === 'start')
        @if($iconSet === 'heroicon')
            <x-main-icon
                :name="$icon"
                set="heroicon"
                :variant="$iconVariant"
                :size="$iconSize"
                :class="$slot ? 'me-2' : ''"
            />
        @else
            <i class="bi bi-{{ $icon }} me-2"></i>
        @endif
    @endif

    @unless($iconOnly)
        {{ $slot }}
    @endunless

    @if($icon && $iconPosition === 'end')
        @if($iconSet === 'heroicon')
            <x-main-icon
                :name="$icon"
                set="heroicon"
                :variant="$iconVariant"
                :size="$iconSize"
                :class="$slot ? 'ms-2' : ''"
            />
        @else
            <i class="bi bi-{{ $icon }} ms-2"></i>
        @endif
    @endif

    @if($badge)
        <span class="badge bg-light text-dark ms-2">{{ $badge }}</span>
    @endif

    @if($iconOnly && !$icon)
        {{ $slot }}
    @endif
@endif
</{{ $tag }}>
