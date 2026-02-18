@props(['class' => ''])

<div
    class="btn-toolbar {{ $class }}"
    role="toolbar"
    {{ $attributes }}
>
    {{ $slot }}
</div>
