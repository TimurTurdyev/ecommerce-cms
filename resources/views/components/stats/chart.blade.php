@props([
    'type' => 'line', // line, bar, area, pie
    'data' => [],
    'labels' => [],
    'colors' => ['#3b82f6', '#10b981', '#ef4444', '#f59e0b', '#8b5cf6'],
    'height' => 200,
    'showLegend' => false,
    'legendPosition' => 'bottom',
    'title' => '',
    'class' => '',
])

@php
    // Нормализуем данные
    $chartData = $data;
    if (empty($chartData)) {
        // Демо данные
        $chartData = [
            'datasets' => [
                [
                    'label' => 'Показатель 1',
                    'data' => [65, 59, 80, 81, 56, 55, 40],
                    'color' => $colors[0],
                ],
                [
                    'label' => 'Показатель 2',
                    'data' => [28, 48, 40, 19, 86, 27, 90],
                    'color' => $colors[1],
                ],
            ],
            'labels' => $labels ?: ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'],
        ];
    }

    // Определяем максимальное значение для масштабирования
    $allValues = [];
    foreach ($chartData['datasets'] ?? [] as $dataset) {
        $allValues = array_merge($allValues, $dataset['data'] ?? []);
    }
    $maxValue = max($allValues) ?: 100;

    $cardClasses = 'card';
    if ($class) $cardClasses .= ' ' . $class;
@endphp

<div class="{{ $cardClasses }} stat-chart">
    @if($title)
        <div class="card-header">
            <h6 class="card-title mb-0">{{ $title }}</h6>
        </div>
    @endif

    <div class="card-body">
        <div class="chart-container" style="height: {{ $height }}px;">
            <svg
                width="100%"
                height="100%"
                viewBox="0 0 100 100"
                preserveAspectRatio="none"
            >
                @switch($type)
                    @case('line')
                        @foreach($chartData['datasets'] ?? [] as $index => $dataset)
                            @php
                                $points = '';
                                $dataPoints = $dataset['data'] ?? [];
                                $color = $dataset['color'] ?? $colors[$index % count($colors)];

                                for ($i = 0; $i < count($dataPoints); $i++) {
                                    $x = ($i / (count($dataPoints) - 1)) * 100;
                                    $y = 100 - (($dataPoints[$i] / $maxValue) * 100);
                                    $points .= "{$x},{$y} ";
                                }
                            @endphp

                            <polyline
                                points="{{ trim($points) }}"
                                fill="none"
                                stroke="{{ $color }}"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        @endforeach
                        @break

                    @case('bar')
                        @foreach($chartData['datasets'] ?? [] as $index => $dataset)
                            @php
                                $dataPoints = $dataset['data'] ?? [];
                                $color = $dataset['color'] ?? $colors[$index % count($colors)];
                                $barWidth = 100 / (count($dataPoints) * 2);
                                $groupOffset = ($index * $barWidth) / count($chartData['datasets']);
                            @endphp

                            @for($i = 0; $i < count($dataPoints); $i++)
                                @php
                                    $x = ($i / count($dataPoints)) * 100 + $groupOffset;
                                    $height = ($dataPoints[$i] / $maxValue) * 100;
                                    $y = 100 - $height;
                                @endphp

                                <rect
                                    x="{{ $x }}"
                                    y="{{ $y }}"
                                    width="{{ $barWidth * 0.8 }}"
                                    height="{{ $height }}"
                                    fill="{{ $color }}"
                                    rx="2"
                                />
                            @endfor
                        @endforeach
                        @break

                    @case('area')
                        @foreach($chartData['datasets'] ?? [] as $index => $dataset)
                            @php
                                $points = '';
                                $dataPoints = $dataset['data'] ?? [];
                                $color = $dataset['color'] ?? $colors[$index % count($colors)];

                                $points .= "0,100 ";

                                for ($i = 0; $i < count($dataPoints); $i++) {
                                    $x = ($i / (count($dataPoints) - 1)) * 100;
                                    $y = 100 - (($dataPoints[$i] / $maxValue) * 100);
                                    $points .= "{$x},{$y} ";
                                }

                                $points .= "100,100";
                            @endphp

                            <polygon
                                points="{{ trim($points) }}"
                                fill="{{ $color }}"
                                opacity="0.2"
                            />

                            <polyline
                                points="{{ trim(str_replace('0,100 ', '', str_replace(' 100,100', '', $points))) }}"
                                fill="none"
                                stroke="{{ $color }}"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        @endforeach
                        @break

                    @case('pie')
                        @php
                            $dataset = $chartData['datasets'][0] ?? [];
                            $dataPoints = $dataset['data'] ?? [25, 25, 25, 25];
                            $total = array_sum($dataPoints);
                            $centerX = 50;
                            $centerY = 50;
                            $radius = 40;
                            $startAngle = 0;
                        @endphp

                        @foreach($dataPoints as $index => $value)
                            @php
                                $percentage = $value / $total;
                                $endAngle = $startAngle + ($percentage * 360);
                                $color = $colors[$index % count($colors)];

                                // Конвертируем углы в радианы
                                $startRad = deg2rad($startAngle - 90);
                                $endRad = deg2rad($endAngle - 90);

                                // Вычисляем точки дуги
                                $x1 = $centerX + ($radius * cos($startRad));
                                $y1 = $centerY + ($radius * sin($startRad));
                                $x2 = $centerX + ($radius * cos($endRad));
                                $y2 = $centerY + ($radius * sin($endRad));

                                $largeArcFlag = ($endAngle - $startAngle) > 180 ? 1 : 0;

                                $d = "M $centerX,$centerY L $x1,$y1 A $radius,$radius 0 $largeArcFlag 1 $x2,$y2 Z";
                            @endphp

                            <path
                                d="{{ $d }}"
                                fill="{{ $color }}"
                                stroke="white"
                                stroke-width="2"
                            />

                            @php
                                $startAngle = $endAngle;
                            @endphp
                        @endforeach
                        @break
                @endswitch

                {{-- Оси --}}
                <line x1="0" y1="100" x2="100" y2="100" stroke="currentColor" stroke-width="0.5" opacity="0.2" />
                <line x1="0" y1="0" x2="0" y2="100" stroke="currentColor" stroke-width="0.5" opacity="0.2" />
            </svg>
        </div>

        @if($showLegend && !empty($chartData['datasets']))
            <div class="chart-legend mt-3 d-flex flex-wrap justify-content-{{ $legendPosition === 'bottom' ? 'center' : 'start' }}">
                @foreach($chartData['datasets'] as $index => $dataset)
                    @php
                        $color = $dataset['color'] ?? $colors[$index % count($colors)];
                    @endphp
                    <div class="legend-item d-flex align-items-center me-3 mb-1">
                        <div
                            class="legend-color me-2"
                            style="width: 12px; height: 12px; background-color: {{ $color }}; border-radius: 2px;"
                        ></div>
                        <span class="small text-muted">{{ $dataset['label'] ?? '' }}</span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
