@props([
    'for' => null,
    'class' => 'form-label',
    'required' => false,
])

<label
    @if($for)
        for="{{ $for }}"
    @endif
    class="{{ $class }}"
    {{ $attributes }}
>
    {{ $slot }}

    @if($required)
        <span class="text-danger">*</span>
    @endif
</label>
