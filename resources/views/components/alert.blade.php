@props([
    'type' => 'primary',
    'dismissible' => false,
    'icon' => null,
    'iconSet' => 'bootstrap',
    'iconVariant' => 'outline',
    'title' => null,
    'class' => '',
])

@php
    $alertClasses = 'alert alert-' . $type;
    if ($dismissible) $alertClasses .= ' alert-dismissible fade show';
    if ($class) $alertClasses .= ' ' . $class;

    // Иконки по типам (значения по умолчанию)
    $defaultIcons = [
        'primary' => ['bootstrap' => 'info-circle', 'heroicon' => 'information-circle'],
        'secondary' => ['bootstrap' => 'circle', 'heroicon' => 'circle-stack'],
        'success' => ['bootstrap' => 'check-circle', 'heroicon' => 'check-circle'],
        'danger' => ['bootstrap' => 'exclamation-triangle', 'heroicon' => 'exclamation-triangle'],
        'warning' => ['bootstrap' => 'exclamation-triangle', 'heroicon' => 'exclamation-triangle'],
        'info' => ['bootstrap' => 'info-circle', 'heroicon' => 'information-circle'],
        'light' => ['bootstrap' => 'info-circle', 'heroicon' => 'light-bulb'],
        'dark' => ['bootstrap' => 'info-circle', 'heroicon' => 'moon'],
    ];

    // Определяем иконку
    $iconName = $icon;
    if (!$iconName && isset($defaultIcons[$type][$iconSet])) {
        $iconName = $defaultIcons[$type][$iconSet];
    }
@endphp

<div
    role="alert"
    class="{{ $alertClasses }}"
    {{ $attributes->except(['type', 'dismissible', 'icon', 'iconSet', 'iconVariant', 'title', 'class']) }}
>
    @if($dismissible)
        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Close"
        ></button>
    @endif

    @if($iconName)
        <div class="d-flex align-items-center">
            @if($iconSet === 'heroicon')
                <x-main-icon
                    :name="$iconName"
                    set="heroicon"
                    :variant="$iconVariant"
                    size="lg"
                    class="me-3 flex-shrink-0"
                />
            @else
                <i class="bi bi-{{ $iconName }} me-3 flex-shrink-0" style="font-size: 1.25rem;"></i>
            @endif

            <div class="flex-grow-1">
                @if($title)
                    <h5 class="alert-heading mb-1">{{ $title }}</h5>
                @endif
                {{ $slot }}
            </div>
        </div>
    @else
        @if($title)
            <h5 class="alert-heading">{{ $title }}</h5>
        @endif
        {{ $slot }}
    @endif
</div>
