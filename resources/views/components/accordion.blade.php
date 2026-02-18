@props([
    'id',
    'flush' => false,
    'alwaysOpen' => false,
    'class' => '',
])

@php
    $classes = 'accordion';
    if ($flush) $classes .= ' accordion-flush';
    if ($class) $classes .= ' ' . $class;
@endphp

<div
    id="{{ $id }}"
    class="{{ $classes }}"
    {{ $attributes }}
>
    {{ $slot }}
</div>
