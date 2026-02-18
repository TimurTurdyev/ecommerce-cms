@props([
    'help' => null,
    'wrapperClass' => 'form-check mb-3',
])

@php
    $name = $attributes->get('name');
    $id = $attributes->get('id', $name);
    $label = $attributes->get('label');
    $checked = old($name, $attributes->has('checked'));
    $error = $errors->first($name);

    $classes = 'form-check-input';
    if ($error) $classes .= ' is-invalid';
    if ($attributes->has('class')) $classes .= ' ' . $attributes->get('class');

    if ($attributes->has('switch')) $wrapperClass .= ' form-switch';
    if ($attributes->has('inline')) $wrapperClass .= ' form-check-inline';
@endphp

<div class="{{ $wrapperClass }}">
    <input
        type="checkbox"
        id="{{ $id }}"
        name="{{ $name }}"
        {{ $checked ? 'checked' : '' }}
        {{ $attributes->except(['id', 'name', 'class', 'checked', 'label', 'switch', 'inline'])->merge(['class' => $classes]) }}
    >

    @if($label)
        <x-forms.label :for="$id" class="form-check-label">{{ $label }}</x-forms.label>
    @endif

    @if($error)
        <div class="invalid-feedback d-block">{{ $error }}</div>
    @endif

    @if($help)
        <div class="form-text">{{ $help }}</div>
    @endif
</div>
