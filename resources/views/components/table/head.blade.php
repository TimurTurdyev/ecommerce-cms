@props([
    'headers' => [],
    'sortable' => true,
    'sortColumn' => null,
    'sortDirection' => 'asc',
    'selectable' => false,
    'hasActionsColumn' => false,
])

<thead>
<tr>
    @foreach($headers as $key => $header)
        @php
            $isSortable = $sortable && ($header['sortable'] ?? false);
            $isCurrentSort = $sortColumn === ($header['key'] ?? $key);
            $columnKey = $header['key'] ?? $key;
            $columnType = $header['type'] ?? 'text';
            $columnWidth = $header['width'] ?? null;
            $columnAlign = $header['align'] ?? null;
            $columnClass = $header['class'] ?? '';

            $thClasses = '';
            if ($isSortable) $thClasses .= ' sortable';
            if ($isCurrentSort) $thClasses .= ' sorting sorting-' . $sortDirection;
            if ($columnClass) $thClasses .= ' ' . $columnClass;

            $thStyles = '';
            if ($columnWidth) $thStyles .= 'width: ' . $columnWidth . ';';
            if ($columnAlign) $thStyles .= 'text-align: ' . $columnAlign . ';';
        @endphp

        <th
            @if($isSortable)
                data-sort="{{ $columnKey }}"
            role="button"
            @endif
            class="{{ trim($thClasses) }}"
            style="{{ $thStyles }}"
            {{ $header['attributes'] ?? '' }}
        >
            @if($columnType === 'checkbox' && $selectable)
                <div class="form-check">
                    <input
                        type="checkbox"
                        class="form-check-input table-select-all"
                        id="selectAll-{{ $columnKey }}"
                    >
                </div>
            @else
                {{ $header['label'] ?? '' }}

                @if($isSortable)
                    <div class="sort-indicator ms-1">
                        @if($isCurrentSort)
                            <x-icon
                                :name="$sortDirection === 'asc' ? 'chevron-up' : 'chevron-down'"
                                set="heroicon"
                                size="sm"
                            />
                        @else
                            <x-main-icon name="arrows-up-down" set="heroicon" size="sm"/>
                        @endif
                    </div>
                @endif
            @endif
        </th>
    @endforeach
</tr>
</thead>
