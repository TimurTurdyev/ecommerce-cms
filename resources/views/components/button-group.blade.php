@props([
    'size' => null,
    'vertical' => false,
    'role' => 'group',
    'ariaLabel' => null,
    'class' => '',
])

@php
    $classes = $vertical ? 'btn-group-vertical' : 'btn-group';
    if ($size && in_array($size, ['sm', 'lg'])) {
        $classes .= ' btn-group-' . $size;
    }
    if ($class) $classes .= ' ' . $class;
@endphp

<div
    class="{{ $classes }}"
    role="{{ $role }}"
    @if($ariaLabel) aria-label="{{ $ariaLabel }}" @endif
    {{ $attributes->except(['size', 'vertical', 'role', 'ariaLabel', 'class']) }}
>
    {{ $slot }}
</div>
