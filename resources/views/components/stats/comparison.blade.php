@props([
    'title' => '',
    'current' => 0,
    'previous' => 0,
    'currentLabel' => 'Текущий период',
    'previousLabel' => 'Прошлый период',
    'format' => 'number',
    'decimals' => 0,
    'prefix' => '',
    'suffix' => '',
    'showChange' => true,
    'showPercentage' => true,
    'icon' => null,
    'iconSet' => 'heroicon',
    'class' => '',
])

@php
    // Форматируем значения
    $formatValue = function($value) use ($format, $decimals, $prefix, $suffix) {
        switch($format) {
            case 'currency':
                return $prefix . number_format($value, $decimals, ',', ' ') . ' ₽' . $suffix;
            case 'percent':
                return $prefix . number_format($value, $decimals, ',', ' ') . '%' . $suffix;
            case 'short':
                if ($value >= 1000000) {
                    return $prefix . number_format($value / 1000000, 1, ',', ' ') . 'M' . $suffix;
                } elseif ($value >= 1000) {
                    return $prefix . number_format($value / 1000, 1, ',', ' ') . 'K' . $suffix;
                } else {
                    return $prefix . number_format($value, $decimals, ',', ' ') . $suffix;
                }
            default:
                return $prefix . number_format($value, $decimals, ',', ' ') . $suffix;
        }
    };

    $currentFormatted = $formatValue($current);
    $previousFormatted = $formatValue($previous);

    // Рассчитываем изменение
    $change = 0;
    $changePercentage = 0;

    if ($previous != 0) {
        $change = $current - $previous;
        $changePercentage = ($change / abs($previous)) * 100;
    } elseif ($current != 0) {
        $change = $current;
        $changePercentage = 100;
    }

    $isPositive = $change >= 0;
    $changeColor = $isPositive ? 'text-success' : 'text-danger';
    $changeIcon = $isPositive ? 'arrow-trending-up' : 'arrow-trending-down';

    $cardClasses = 'card';
    if ($class) $cardClasses .= ' ' . $class;
@endphp

<div class="{{ $cardClasses }} stat-comparison">
    <div class="card-body">
        @if($title)
            <h6 class="card-subtitle text-muted mb-3">{{ $title }}</h6>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <div class="d-flex align-items-center mb-1">
                    @if($icon)
                        <div class="me-2">
                            @if($iconSet === 'heroicon')
                                <x-main-icon
                                    :name="$icon"
                                    set="heroicon"
                                    variant="outline"
                                    size="md"
                                    class="text-primary"
                                />
                            @else
                                <i class="bi bi-{{ $icon }} text-primary"></i>
                            @endif
                        </div>
                    @endif
                    <h4 class="card-title mb-0">{{ $currentFormatted }}</h4>
                </div>
                <div class="text-muted small">{{ $currentLabel }}</div>
            </div>

            @if($showChange && $previous != 0)
                <div class="text-end">
                    <div class="stat-change {{ $changeColor }} d-flex align-items-center justify-content-end mb-1">
                        <x-main-icon :name="$changeIcon" set="heroicon" size="sm" class="me-1" />
                        <span class="fw-medium">
                            {{ $isPositive ? '+' : '' }}{{ number_format($changePercentage, 1) }}%
                        </span>
                    </div>
                    <div class="text-muted small">{{ $previousFormatted }}</div>
                    <div class="text-muted small">{{ $previousLabel }}</div>
                </div>
            @endif
        </div>

        @if($showPercentage && $previous != 0)
            <div class="progress" style="height: 4px;">
                <div
                    class="progress-bar {{ $isPositive ? 'bg-success' : 'bg-danger' }}"
                    role="progressbar"
                    style="width: {{ min(100, abs($changePercentage)) }}%"
                ></div>
            </div>
        @endif
    </div>
</div>
