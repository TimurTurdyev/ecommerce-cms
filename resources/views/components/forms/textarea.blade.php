@props([
    'wrapperClass' => 'mb-3',
    'help' => null,
])

@php
    $name = $attributes->get('name', '');
    $id = $attributes->get('id', $name);
    $label = $attributes->get('label');
    $value = old($name, $attributes->get('value', ''));
    $error = $errors->first($name);

    $classes = 'form-control';
    if ($error) $classes .= ' is-invalid';
    if ($attributes->has('class')) $classes .= ' ' . $attributes->get('class');
@endphp

<div class="{{ $wrapperClass }}">
    @if($label)
        <x-forms.label :for="$id" :required="$attributes->has('required')">{{ $label }}</x-forms.label>
    @endif

    <textarea
        id="{{ $id }}"
        name="{{ $name }}"
        {{ $attributes->except(['id', 'name', 'class', 'value', 'label'])->merge(['class' => $classes]) }}
    >{{ $value }}</textarea>

    @if($error)
        <div class="invalid-feedback d-block">{{ $error }}</div>
    @endif

    @if($help)
        <div class="form-text">{{ $help }}</div>
    @endif
</div>
