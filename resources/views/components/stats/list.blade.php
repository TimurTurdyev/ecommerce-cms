@props([
    'items' => [],
    'divided' => true,
    'bordered' => false,
    'compact' => false,
    'class' => '',
])

@php
    $listClasses = 'stats-list';
    if ($divided) $listClasses .= ' list-divided';
    if ($bordered) $listClasses .= ' list-bordered';
    if ($compact) $listClasses .= ' list-compact';
    if ($class) $listClasses .= ' ' . $class;
@endphp

<div class="{{ $listClasses }}">
    @foreach($items as $item)
        <div class="stats-list-item">
            <div class="d-flex justify-content-between align-items-center">
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center">
                        @if($item['icon'] ?? false)
                            <div class="me-3">
                                @if(($item['iconSet'] ?? 'heroicon') === 'heroicon')
                                    <x-main-icon
                                        :name="$item['icon']"
                                        set="heroicon"
                                        variant="outline"
                                        size="md"
                                        class="text-{{ $item['iconColor'] ?? 'primary' }}"
                                    />
                                @else
                                    <i class="bi bi-{{ $item['icon'] }} text-{{ $item['iconColor'] ?? 'primary' }}"></i>
                                @endif
                            </div>
                        @endif

                        <div>
                            <div class="fw-medium">{{ $item['label'] ?? '' }}</div>
                            @if($item['description'] ?? false)
                                <div class="text-muted small">{{ $item['description'] }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <div class="fw-medium">{{ $item['value'] ?? '' }}</div>
                    @if($item['change'] ?? false)
                        @php
                            $changeColor = $item['change'] >= 0 ? 'text-success' : 'text-danger';
                            $changeIcon = $item['change'] >= 0 ? 'arrow-trending-up' : 'arrow-trending-down';
                        @endphp
                        <div class="small {{ $changeColor }}">
                            <x-main-icon :name="$changeIcon" set="heroicon" size="xs" class="me-1" />
                            {{ $item['change'] > 0 ? '+' : '' }}{{ $item['change'] }}%
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>
