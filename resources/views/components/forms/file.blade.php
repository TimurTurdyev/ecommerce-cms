@props([
    'help' => null,
    'wrapperClass' => 'mb-3',
])

@php
    $name = $attributes->get('name');
    $id = $attributes->get('id', $name);
    $label = $attributes->get('label');
    $error = $errors->first($name);

    $classes = 'form-control';
    if ($error) $classes .= ' is-invalid';
    if ($attributes->has('class')) $classes .= ' ' . $attributes->get('class');
@endphp

<div class="{{ $wrapperClass }}">
    @if($label)
        <label for="{{ $id }}" class="form-label">
            {{ $label }}
            @if($attributes->has('required'))
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    <input
        type="file"
        id="{{ $id }}"
        name="{{ $name }}{{ $attributes->has('multiple') ? '[]' : '' }}"
        {{ $attributes->except(['id', 'name', 'class', 'label'])->merge(['class' => $classes]) }}
    >

    @if($error)
        <div class="invalid-feedback d-block">{{ $error }}</div>
    @endif

    @if($help)
        <div class="form-text">{{ $help }}</div>
    @endif
</div>
