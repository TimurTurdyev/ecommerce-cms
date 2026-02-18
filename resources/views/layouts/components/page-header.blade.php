@props([
    'title' => null,
    'subtitle' => null,
    'breadcrumbs' => [],
    'actions' => null,
    'backUrl' => null,
])

<div class="page-header mb-4">
    @if(count($breadcrumbs) > 0)
        <nav aria-label="breadcrumb" class="page-breadcrumbs">
            <ol class="breadcrumb">
                @foreach($breadcrumbs as $item)
                    <li class="breadcrumb-item {{ $loop->last ? 'active' : '' }}">
                        @if(!$loop->last && isset($item['url']))
                            <a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
                        @else
                            {{ $item['label'] }}
                        @endif
                    </li>
                @endforeach
            </ol>
        </nav>
    @endif

    <div class="d-flex justify-content-between align-items-start">
        <div>
            @if($backUrl)
                <a href="{{ $backUrl }}" class="btn btn-link btn-back mb-2">
                    <x-main-icon name="arrow-left" set="heroicon" size="sm" class="me-1" />
                    Назад
                </a>
            @endif

            <h1 class="page-title">
                {{ $title ?? '' }}
                @if($subtitle)
                    <small class="text-muted">{{ $subtitle }}</small>
                @endif
            </h1>
        </div>

        {{-- Действия --}}
        @if('actions')
            <div class="page-actions">
                {!! $actions !!}
            </div>
        @endif
    </div>
</div>
