@props([
    'pagination' => null,
    'footer' => null,
    'class' => ''
])

@if($pagination || $footer)
    <div class="table-footer {{ $class }}">
        @if(is_string($pagination))
            {!! $pagination !!}
        @elseif($pagination)
            <div class="d-flex justify-content-between align-items-center py-3 px-4 border-top">
                {{-- Информация о странице --}}
                <div class="text-muted small">
                    @if(is_array($pagination))
                        Показано с {{ $pagination['from'] ?? 1 }} по {{ $pagination['to'] ?? 0 }}
                        из {{ $pagination['total'] ?? 0 }} записей
                    @elseif($pagination instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        Показано с {{ $pagination->firstItem() }} по {{ $pagination->lastItem() }}
                        из {{ $pagination->total() }} записей
                    @endif
                </div>

                {{-- Пагинация --}}
                @if(is_array($pagination) && isset($pagination['links']))
                    <nav aria-label="Навигация по страницам">
                        <ul class="pagination pagination-sm mb-0">
                            @foreach($pagination['links'] as $link)
                                <li class="page-item {{ $link['active'] ? 'active' : '' }} {{ $link['url'] ? '' : 'disabled' }}">
                                    <a
                                        class="page-link"
                                        href="{{ $link['url'] ?: '#' }}"
                                        {!! $link['label'] === '&laquo; Previous' ? 'aria-label="Предыдущая"' : '' !!}
                                        {!! $link['label'] === 'Next &raquo;' ? 'aria-label="Следующая"' : '' !!}
                                    >
                                        @if($link['label'] === '&laquo; Previous')
                                            <x-main-icon name="chevron-left" set="heroicon" size="sm"/>
                                        @elseif($link['label'] === 'Next &raquo;')
                                            <x-main-icon name="chevron-right" set="heroicon" size="sm"/>
                                        @else
                                            {{ $link['label'] }}
                                        @endif
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </nav>
                @elseif($pagination instanceof \Illuminate\Pagination\AbstractPaginator)
                    {{ $pagination->links() }}
                @endif
            </div>
        @endif

        {{-- Кастомный футер --}}
        @if($footer)
            <div class="border-top">
                {{ $footer }}
            </div>
        @endif
    </div>
@endif
