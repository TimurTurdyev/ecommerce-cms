@props([
    'title' => '',
    'value' => 0,
    'total' => 100,
    'label' => '',
    'description' => '',
    'color' => 'primary',
    'striped' => false,
    'animated' => false,
    'showValue' => true,
    'showPercentage' => true,
    'height' => '6px',
    'class' => '',
])

@php
    $percentage = $total > 0 ? ($value / $total) * 100 : 0;
    $percentage = min(100, max(0, $percentage));

    $colorClass = 'bg-' . $color;
    $progressClasses = 'progress-bar ' . $colorClass;
    if ($striped) $progressClasses .= ' progress-bar-striped';
    if ($animated) $progressClasses .= ' progress-bar-animated';

    $cardClasses = 'card';
    if ($class) $cardClasses .= ' ' . $class;
@endphp

<div class="{{ $cardClasses }} stat-progress">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
            @if($title)
                <h6 class="card-subtitle mb-0">{{ $title }}</h6>
            @endif

            @if($showValue || $showPercentage)
                <div class="stat-values">
                    @if($showValue)
                        <span class="fw-medium">{{ number_format($value) }}</span>
                    @endif

                    @if($showValue && $showPercentage)
                        <span class="text-muted mx-1">/</span>
                    @endif

                    @if($showPercentage)
                        <span class="text-muted">{{ number_format($percentage, 1) }}%</span>
                    @endif
                </div>
            @endif
        </div>

        <div class="progress mb-2" style="height: {{ $height }};">
            <div
                class="{{ $progressClasses }}"
                role="progressbar"
                style="width: {{ $percentage }}%"
                aria-valuenow="{{ $value }}"
                aria-valuemin="0"
                aria-valuemax="{{ $total }}"
            ></div>
        </div>

        @if($label || $description)
            <div class="d-flex justify-content-between align-items-center">
                @if($label)
                    <span class="text-muted small">{{ $label }}</span>
                @endif

                @if($description)
                    <span class="text-muted small">{{ $description }}</span>
                @endif
            </div>
        @endif
    </div>
</div>
