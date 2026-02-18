@props([
    'title' => '',
    'value' => 0,
    'icon' => null,
    'iconSet' => 'heroicon',
    'iconVariant' => 'outline',
    'iconColor' => 'primary',
    'change' => null, // процент изменения
    'changeLabel' => null, // текст изменения
    'trend' => null, // 'up', 'down', 'neutral'
    'description' => '',
    'color' => 'primary',
    'loading' => false,
    'href' => null,
    'valueFormat' => 'number', // number, currency, percent, short
    'decimals' => 0,
    'prefix' => '',
    'suffix' => '',
    'class' => '',
])

@php
    // Определяем цвет
    $colorMap = [
        'primary' => 'bg-primary',
        'secondary' => 'bg-secondary',
        'success' => 'bg-success',
        'danger' => 'bg-danger',
        'warning' => 'bg-warning',
        'info' => 'bg-info',
        'light' => 'bg-light',
        'dark' => 'bg-dark',
    ];

    $bgColor = $colorMap[$color] ?? 'bg-primary';
    $textColor = in_array($color, ['light']) ? 'text-dark' : 'text-white';

    // Форматируем значение
    $formattedValue = $value;

    switch($valueFormat) {
        case 'currency':
            $formattedValue = number_format($value, $decimals, ',', ' ') . ' ₽';
            break;
        case 'percent':
            $formattedValue = number_format($value, $decimals, ',', ' ') . '%';
            break;
        case 'short':
            if ($value >= 1000000) {
                $formattedValue = number_format($value / 1000000, 1, ',', ' ') . 'M';
            } elseif ($value >= 1000) {
                $formattedValue = number_format($value / 1000, 1, ',', ' ') . 'K';
            } else {
                $formattedValue = number_format($value, $decimals, ',', ' ');
            }
            break;
        default:
            $formattedValue = number_format($value, $decimals, ',', ' ');
    }

    $formattedValue = $prefix . $formattedValue . $suffix;

    // Определяем тренд
    $trendIcon = null;
    $trendColor = 'text-muted';

    if ($trend === 'up') {
        $trendIcon = 'arrow-trending-up';
        $trendColor = 'text-success';
    } elseif ($trend === 'down') {
        $trendIcon = 'arrow-trending-down';
        $trendColor = 'text-danger';
    } elseif ($trend === 'neutral') {
        $trendIcon = 'minus';
        $trendColor = 'text-warning';
    }

    // Если change передан, определяем тренд автоматически
    if ($change !== null) {
        if ($change > 0) {
            $trend = 'up';
            $trendIcon = 'arrow-trending-up';
            $trendColor = 'text-success';
        } elseif ($change < 0) {
            $trend = 'down';
            $trendIcon = 'arrow-trending-down';
            $trendColor = 'text-danger';
        } else {
            $trend = 'neutral';
            $trendIcon = 'minus';
            $trendColor = 'text-warning';
        }
    }

    $cardClasses = 'card';
    if ($class) $cardClasses .= ' ' . $class;

    $tag = $href ? 'a' : 'div';
    $attributes = $href ? $attributes->merge(['href' => $href]) : $attributes;
@endphp

<{{ $tag }}
    class="{{ $cardClasses }} stat-card {{ $href ? 'card-link' : '' }}"
{{ $attributes }}
>
<div class="card-body">
    <div class="d-flex justify-content-between align-items-start mb-3">
        <div class="flex-grow-1">
            <h6 class="card-subtitle text-muted mb-1">{{ $title }}</h6>

            @if($loading)
                <div class="placeholder-glow">
                    <h4 class="card-title placeholder col-6"></h4>
                </div>
            @else
                <h4 class="card-title mb-0">{{ $formattedValue }}</h4>
            @endif
        </div>

        @if($icon)
            <div class="stat-icon">
                @if($iconSet === 'heroicon')
                    <x-main-icon
                        :name="$icon"
                        set="heroicon"
                        :variant="$iconVariant"
                        size="lg"
                        class="text-{{ $iconColor }}"
                    />
                @else
                    <i class="bi bi-{{ $icon }} text-{{ $iconColor }} fs-3"></i>
                @endif
            </div>
        @endif
    </div>

    @if($description || $change !== null)
        <div class="d-flex justify-content-between align-items-center">
            @if($description)
                <p class="card-text text-muted small mb-0">{{ $description }}</p>
            @endif

            @if($change !== null || $trendIcon)
                <div class="stat-change {{ $trendColor }} d-flex align-items-center">
                    @if($trendIcon)
                        <x-main-icon :name="$trendIcon" set="heroicon" size="sm" class="me-1"/>
                    @endif

                    @if($change !== null)
                        <span class="small fw-medium">
                                {{ $change > 0 ? '+' : '' }}{{ number_format($change, 1) }}%
                            </span>
                    @elseif($changeLabel)
                        <span class="small fw-medium">{{ $changeLabel }}</span>
                    @endif
                </div>
            @endif
        </div>
    @endif
</div>
</{{ $tag }}>
