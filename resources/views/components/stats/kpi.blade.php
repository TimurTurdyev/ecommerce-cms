@props([
    'title' => '',
    'value' => 0,
    'target' => null,
    'status' => null, // 'success', 'warning', 'danger', 'info'
    'statusText' => '',
    'format' => 'number',
    'decimals' => 0,
    'prefix' => '',
    'suffix' => '',
    'icon' => null,
    'iconSet' => 'heroicon',
    'iconColor' => null,
    'loading' => false,
    'compact' => false,
    'class' => '',
])

@php
    // Форматируем значение
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

    $formattedValue = $formatValue($value);
    $formattedTarget = $target !== null ? $formatValue($target) : null;

    // Определяем статус
    $statusColor = 'secondary';
    $statusIcon = null;

    if ($status === 'success') {
        $statusColor = 'success';
        $statusIcon = 'check-circle';
    } elseif ($status === 'warning') {
        $statusColor = 'warning';
        $statusIcon = 'exclamation-triangle';
    } elseif ($status === 'danger') {
        $statusColor = 'danger';
        $statusIcon = 'x-circle';
    } elseif ($status === 'info') {
        $statusColor = 'info';
        $statusIcon = 'information-circle';
    }

    // Если target задан, определяем статус автоматически
    if ($target !== null && $target != 0) {
        $percentage = ($value / $target) * 100;

        if ($percentage >= 100) {
            $statusColor = 'success';
            $statusIcon = 'check-circle';
            $statusText = $statusText ?: 'Выполнено';
        } elseif ($percentage >= 80) {
            $statusColor = 'warning';
            $statusIcon = 'exclamation-triangle';
            $statusText = $statusText ?: 'Нормально';
        } else {
            $statusColor = 'danger';
            $statusIcon = 'x-circle';
            $statusText = $statusText ?: 'Отставание';
        }
    }

    $iconColor = $iconColor ?: $statusColor;
    $cardClasses = 'card';
    if ($compact) $cardClasses .= ' card-compact';
    if ($class) $cardClasses .= ' ' . $class;
@endphp

<div class="{{ $cardClasses }} stat-kpi">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
            <div class="flex-grow-1">
                <h6 class="card-subtitle text-muted mb-1">{{ $title }}</h6>

                @if($loading)
                    <div class="placeholder-glow">
                        <h4 class="card-title placeholder col-8"></h4>
                    </div>
                @else
                    <h4 class="card-title mb-0">{{ $formattedValue }}</h4>
                @endif

                @if($formattedTarget && !$compact)
                    <div class="text-muted small mt-1">
                        Цель: {{ $formattedTarget }}
                    </div>
                @endif

                @if($statusText && !$compact)
                    <div class="d-flex align-items-center mt-2">
                        @if($statusIcon)
                            <x-main-icon
                                :name="$statusIcon"
                                set="heroicon"
                                size="sm"
                                class="text-{{ $statusColor }} me-1"
                            />
                        @endif
                        <span class="text-{{ $statusColor }} small">{{ $statusText }}</span>
                    </div>
                @endif
            </div>

            @if($icon)
                <div class="stat-icon">
                    @if($iconSet === 'heroicon')
                        <x-main-icon
                            :name="$icon"
                            set="heroicon"
                            variant="outline"
                            size="lg"
                            class="text-{{ $iconColor }}"
                        />
                    @else
                        <i class="bi bi-{{ $icon }} text-{{ $iconColor }} fs-3"></i>
                    @endif
                </div>
            @endif
        </div>

        @if($formattedTarget && $compact)
            <div class="mt-2">
                <div class="progress" style="height: 4px;">
                    @if($target != 0)
                        @php
                            $percentage = min(100, ($value / $target) * 100);
                        @endphp
                        <div
                            class="progress-bar bg-{{ $statusColor }}"
                            role="progressbar"
                            style="width: {{ $percentage }}%"
                        ></div>
                    @endif
                </div>
                <div class="d-flex justify-content-between mt-1">
                    <span class="text-muted small">Цель: {{ $formattedTarget }}</span>
                    @if($target != 0)
                        <span class="text-{{ $statusColor }} small fw-medium">
                            {{ number_format($percentage, 1) }}%
                        </span>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
