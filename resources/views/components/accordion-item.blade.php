@props([
    'id' => 'accordion-item-' . Str::random(10),
    'header' => '',
    'show' => false,
    'parent' => null,
    'class' => '',
])

@php
    $classes = 'accordion-item';
    if ($class) $classes .= ' ' . $class;

    $collapseClasses = 'accordion-collapse collapse';
    if ($show) $collapseClasses .= ' show';
@endphp

<div id="{{ $id }}" class="{{ $classes }}">
    <h2 class="accordion-header">
        <button
            class="accordion-button {{ $show ? '' : 'collapsed' }}"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#{{ $id }}-collapse"
            @if($parent) data-bs-parent="#{{ $parent }}" @endif
            aria-expanded="{{ $show ? 'true' : 'false' }}"
            aria-controls="{{ $id }}-collapse"
        >
            {{ $header }}
        </button>
    </h2>

    <div
        id="{{ $id }}-collapse"
        class="{{ $collapseClasses }}"
        @if($parent) data-bs-parent="#{{ $parent }}" @endif
        aria-labelledby="{{ $id }}-heading"
    >
        <div class="accordion-body">
            {{ $slot }}
        </div>
    </div>
</div>
