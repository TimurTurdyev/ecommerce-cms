@props([
    'text' => 'Данные не найдены',
    'icon' => 'table-cells',
    'iconSet' => 'heroicon',
    'subtext' => null,
    'action' => null,
])

<div class="table-empty text-center py-5">
    <div class="empty-icon mb-3">
        <x-main-icon
            :name="$icon"
            :set="$iconSet"
            size="xl"
            class="text-muted"
        />
    </div>

    <h5 class="text-muted mb-2">{{ $text }}</h5>

    @if($subtext)
        <p class="text-muted mb-3">{{ $subtext }}</p>
    @endif

    @if($action)
        <div class="mt-3">
            {{ $action }}
        </div>
    @endif
</div>
