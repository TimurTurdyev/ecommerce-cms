@props([
    'headers' => [],
    'rows' => [],
    'emptyText' => 'Данные не найдены',
    'emptyIcon' => 'table-cells',
    'emptyIconSet' => 'heroicon',
    'selectable' => false,
    'loading' => false,
    'loadingText' => 'Загрузка...',
])

<tbody>
@if($loading)
    {{-- Состояние загрузки --}}
    <tr class="table-loading">
        <td colspan="{{ count($headers) }}" class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">{{ $loadingText }}</span>
            </div>
            <p class="mt-2 mb-0">{{ $loadingText }}</p>
        </td>
    </tr>
@elseif(count($rows) === 0)
    {{-- Пустое состояние --}}
    <tr>
        <td colspan="{{ count($headers) }}">
            <x-table.empty
                :text="$emptyText"
                :icon="$emptyIcon"
                :iconSet="$emptyIconSet"
            />
        </td>
    </tr>
@else
    {{-- Строки с данными --}}
    @foreach($rows as $index => $row)
        <x-table.row
            :headers="$headers"
            :row="$row"
            :index="$index"
            :selectable="$selectable"
        />
    @endforeach
@endif
</tbody>
