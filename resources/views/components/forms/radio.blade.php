@props([
    'help' => null,
    'wrapperClass' => 'mb-3'
])
@php
    $name = $attributes->get('name');
    $value = $attributes->get('value');
    $id = $attributes->get('id', $name . '_' . $value);
    $label = $attributes->get('label');
    $checked = old($name, $attributes->get('checked')) == $value;
    $error = $errors->first($name);

    $classes = 'form-check-input';
    if ($error) $classes .= ' is-invalid';
    if ($attributes->has('class')) $classes .= ' ' . $attributes->get('class');

    $wrapperClass .= 'form-check';
    if ($attributes->has('inline')) $wrapperClass .= ' form-check-inline';
@endphp

<div class="{{ $wrapperClass }} mb-3">
    <input
        type="radio"
        id="{{ $id }}"
        name="{{ $name }}"
        value="{{ $value }}"
        {{ $checked ? 'checked' : '' }}
        {{ $attributes->except(['id', 'name', 'class', 'checked', 'label', 'inline', 'value'])->merge(['class' => $classes]) }}
    >

    @if($label)
        <label for="{{ $id }}" class="form-check-label">
            {{ $label }}
        </label>
    @endif

    @if($error)
        <div class="invalid-feedback d-block">{{ $error }}</div>
    @endif

    @if($help)
        <div class="form-text">{{ $help }}</div>
    @endif
</div>
