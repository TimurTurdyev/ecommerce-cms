@props([
    'type' => 'primary',
    'pill' => false,
    'position' => null,
    'notification' => null,
    'class' => '',
    'href' => null,
    'icon' => null,
    'iconSet' => 'heroicon',
    'iconVariant' => 'outline',
    'iconPosition' => 'start',
])

@php
    $classes = 'badge bg-' . $type;
    if ($pill) $classes .= ' rounded-pill';
    if ($position) $classes .= ' position-absolute ' . $position;
    if ($class) $classes .= ' ' . $class;

    $tag = $href ? 'a' : 'span';
    $attributes = $href ? $attributes->merge(['href' => $href]) : $attributes;
@endphp

<{{ $tag }}
    class="{{ $classes }} d-inline-flex align-items-center gap-1"
{{ $attributes }}
>
@if($icon && $iconPosition === 'start')
    @if($iconSet === 'heroicon')
        <x-main-icon
            :name="$icon"
            set="heroicon"
            :variant="$iconVariant"
            size="sm"
        />
    @else
        <i class="bi bi-{{ $icon }}"></i>
    @endif
@endif

{{ $slot }}

@if($icon && $iconPosition === 'end')
    @if($iconSet === 'heroicon')
        <x-main-icon
            :name="$icon"
            set="heroicon"
            :variant="$iconVariant"
            size="sm"
        />
    @else
        <i class="bi bi-{{ $icon }}"></i>
    @endif
@endif

@if($notification && is_numeric($notification))
    @if($notification > 99)
        <span class="position-relative">
                99+
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    {{ $notification }}
                    <span class="visually-hidden">уведомления</span>
                </span>
            </span>
    @else
        {{ $notification }}
    @endif
@endif
</{{ $tag }}>
