@props([
    'prepend' => null,
    'append' => null,
    'help' => null,
    'wrapperClass' => 'mb-3',
])
@php
    $name = $attributes->get('name');
    $id = $attributes->get('id', $name);
    $label = $attributes->get('label');
    $value = old($name, $attributes->get('value'));
    $error = $errors->first($name);

    $inputClasses = 'form-control';
    if ($error) $inputClasses .= ' is-invalid';
    if ($attributes->has('class')) $inputClasses .= ' ' . $attributes->get('class');

    $hasPrepend = !empty($prepend);
    $hasAppend = !empty($append);

    $sizeClass = '';
    if ($attributes->has('size')) {
        $size = $attributes->get('size');
        if (in_array($size, ['sm', 'lg'])) {
            $sizeClass = 'input-group-' . $size;
        }
    }

    $wrapperClassInput = 'input-group ' . $sizeClass;
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

    <div class="{{ $wrapperClassInput }}">
        @if($hasPrepend)
            {!! $prepend !!}
        @endif

        <input
            id="{{ $id }}"
            name="{{ $name }}"
            value="{{ $value }}"
            {{ $attributes->except(['id', 'name', 'class', 'value', 'label', 'size'])->merge(['class' => $inputClasses]) }}
        >

        @if($hasAppend)
            {!! $append !!}
        @endif
    </div>

    @if($error)
        <div class="invalid-feedback d-block">{{ $error }}</div>
    @endif

    @if($help)
        <div class="form-text">{{ $help }}</div>
    @endif
</div>
