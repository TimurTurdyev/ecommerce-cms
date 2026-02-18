@props([
    'name' => null,
    'set' => 'heroicon', // 'heroicon', 'bootstrap', 'custom'
    'variant' => 'outline', // для heroicon: 'outline', 'solid', 'mini'
    'size' => null, // sm, md, lg, xl
    'class' => '',
    'spin' => false,
    'pulse' => false,
    'flip' => null, // 'horizontal', 'vertical', 'both'
])

@php
    $classes = '';

    // Размеры
    $sizeClasses = [
        'sm' => 'h-4 w-4',
        'md' => 'h-5 w-5',
        'lg' => 'h-6 w-6',
        'xl' => 'h-8 w-8',
        '2xl' => 'h-10 w-10',
    ];

    if ($size && isset($sizeClasses[$size])) {
        $classes .= ' ' . $sizeClasses[$size];
    }

    // Анимации
    if ($spin) $classes .= ' animate-spin';
    if ($pulse) $classes .= ' animate-pulse';

    // Отражение
    if ($flip) {
        $flipClasses = [
            'horizontal' => 'scale-x-[-1]',
            'vertical' => 'scale-y-[-1]',
            'both' => 'scale-[-1]',
        ];
        if (isset($flipClasses[$flip])) {
            $classes .= ' ' . $flipClasses[$flip];
        }
    }

    $classes .= ' ' . $class;
    $classes = trim($classes);
@endphp

@if($set === 'heroicon')
    @switch($variant)
        @case('solid')
            @svg("heroicon-s-$name", $classes, $attributes->getAttributes())
            @break
        @case('mini')
            @svg("heroicon-m-$name", $classes, $attributes->getAttributes())
            @break
        @default
            @svg("heroicon-o-$name", $classes, $attributes->getAttributes())
    @endswitch
@elseif($set === 'bootstrap')
    <i class="bi bi-{{ $name }} {{ $classes }}" {{ $attributes }}></i>
@elseif($set === 'custom')
    {!! $slot !!}
@endif
