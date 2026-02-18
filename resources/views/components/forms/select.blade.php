@props([
    'wrapperClass' => 'mb-3',
    'options' => [],
    'help' => null,
])

@php
    $name = $attributes->get('name');
    $id = $attributes->get('id', $name);
    $label = $attributes->get('label');
    $selected = old($name, $attributes->get('selected'));
    $error = $errors->first($name);

    $classes = 'form-select';
    if ($error) $classes .= ' is-invalid';
    if ($attributes->has('class')) $classes .= ' ' . $attributes->get('class');
@endphp

<div class="{{ $wrapperClass }}">
    @if($label)
        <x-forms.label :for="$id" :required="$attributes->has('required')">{{ $label }}</x-forms.label>
    @endif

    <select
        id="{{ $id }}"
        name="{{ $name }}{{ $attributes->has('multiple') ? '[]' : '' }}"
        {{ $attributes->except(['id', 'class', 'options', 'selected', 'label', 'name'])->merge(['class' => $classes]) }}
    >
        @if($attributes->has('placeholder'))
            <option value="" disabled {{ !$selected ? 'selected' : '' }}>
                {{ $attributes->get('placeholder') }}
            </option>
        @endif

        @foreach($options as $value => $text)
            <option value="{{ $value }}"
            @if(is_array($selected))
                {{ in_array($value, $selected) ? 'selected' : '' }}
                @else
                {{ $value == $selected ? 'selected' : '' }}
                @endif
            >
                {{ $text }}
            </option>
        @endforeach
    </select>

    @if($error)
        <div class="invalid-feedback d-block">{{ $error }}</div>
    @endif

    @if($help)
        <div class="form-text">{{ $help }}</div>
    @endif
</div>
