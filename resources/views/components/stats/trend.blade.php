@props([
    'title' => '',
    'value' => 0,
    'data' => [], // массив значений для графика
    'period' => 'за 30 дней',
    'change' => 0,
    'format' => 'number',
    'color' => 'primary',
    'height' => 40,
    'showChart' => true,
    'class' => '',
])

@php
    // Форматируем значение
    $formatValue = function($value) use ($format) {
        switch($format) {
            case 'currency':
                return number_format($value, 0, ',', ' ') . ' ₽';
            case 'percent':
                return number_format($value, 1, ',', ' ') . '%';
            case 'short':
                if ($value >= 1000000) {
                    return number_format($value / 1000000, 1, ',', ' ') . 'M';
                } elseif ($value >= 1000) {
                    return number_format($value / 1000, 1, ',', ' ') . 'K';
                } else {
                    return number_format($value, 0, ',', ' ');
                }
            default:
                return number_format($value, 0, ',', ' ');
        }
    };

    $formattedValue = $formatValue($value);

    // Нормализуем данные для графика
    $chartData = $data;
    if (empty($chartData)) {
        // Генерируем демо-данные если нет
        $chartData = array_fill(0, 10, rand(30, 70));
    }

    $maxValue = max($chartData) ?: 100;
    $minValue = min($chartData) ?: 0;

    // Цвета графика
    $colorMap = [
        'primary' => '#3b82f6',
        'success' => '#10b981',
        'danger' => '#ef4444',
        'warning' => '#f59e0b',
        'info' => '#06b6d4',
    ];

    $chartColor = $colorMap[$color] ?? '#3b82f6';
    $changeColor = $change >= 0 ? 'text-success' : 'text-danger';
    $changeIcon = $change >= 0 ? 'arrow-trending-up' : 'arrow-trending-down';

    $cardClasses = 'card';
    if ($class) $cardClasses .= ' ' . $class;
@endphp

<div class="{{ $cardClasses }} stat-trend">
    <div class="card-body">
        <h6 class="card-subtitle text-muted mb-2">{{ $title }}</h6>

        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <h4 class="card-title mb-0">{{ $formattedValue }}</h4>
                <div class="text-muted small">{{ $period }}</div>
            </div>

            @if($change !== 0)
                <div class="stat-change {{ $changeColor }} d-flex align-items-center">
                    <x-main-icon :name="$changeIcon" set="heroicon" size="sm" class="me-1" />
                    <span class="fw-medium">
                        {{ $change > 0 ? '+' : '' }}{{ number_format($change, 1) }}%
                    </span>
                </div>
            @endif
        </div>

        @if($showChart)
            <div class="trend-chart" style="height: {{ $height }}px;">
                <svg
                    width="100%"
                    height="100%"
                    viewBox="0 0 100 {{ $height }}"
                    preserveAspectRatio="none"
                >
                    {{-- Фон --}}
                    <rect width="100" height="{{ $height }}" fill="rgba(0,0,0,0.02)" rx="2" />

                    {{-- Линия графика --}}
                    <polyline
                        points="
                            @for($i = 0; $i < count($chartData); $i++)
                                {{ $i * (100 / (count($chartData) - 1)) }},
                                {{ $height - (($chartData[$i] - $minValue) / ($maxValue - $minValue ?: 1) * $height) }}
                                @if($i < count($chartData) - 1) @endif
                            @endfor
                        "
                        fill="none"
                        stroke="{{ $chartColor }}"
                        stroke-width="1.5"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />

                    {{-- Заливка под графиком --}}
                    <polygon
                        points="
                            0,{{ $height }}
                            @for($i = 0; $i < count($chartData); $i++)
                                {{ $i * (100 / (count($chartData) - 1)) }},
                                {{ $height - (($chartData[$i] - $minValue) / ($maxValue - $minValue ?: 1) * $height) }}
                            @endfor
                            100,{{ $height }}
                        "
                        fill="url(#gradient-{{ $color }})"
                        opacity="0.2"
                    />

                    {{-- Градиент --}}
                    <defs>
                        <linearGradient id="gradient-{{ $color }}" x1="0%" y1="0%" x2="0%" y2="100%">
                            <stop offset="0%" stop-color="{{ $chartColor }}" stop-opacity="0.4" />
                            <stop offset="100%" stop-color="{{ $chartColor }}" stop-opacity="0" />
                        </linearGradient>
                    </defs>
                </svg>
            </div>
        @endif
    </div>
</div>
