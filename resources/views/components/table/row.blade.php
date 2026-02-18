@props([
    'headers' => [],
    'row' => [],
    'index' => 0,
    'selectable' => false,
    'striped' => true,
    'clickable' => false,
    'url' => null,
])

@php
    $rowId = $row['id'] ?? $index;
    $rowClasses = '';

    if (is_callable($url)) {
        $url = $url($row);
    }

    // Добавляем классы для чередования строк
    if ($striped && $index % 2 === 0) {
        $rowClasses .= ' table-row-striped';
    }

    // Добавляем класс если строка кликабельна
    if ($clickable || $url) {
        $rowClasses .= ' table-row-clickable';
    }

    // Пользовательские классы строки
    if ($row['_row_class'] ?? false) {
        $rowClasses .= ' ' . $row['_row_class'];
    }
@endphp

<tr
    data-id="{{ $rowId }}"
    class="{{ trim($rowClasses) }}"
    @if($clickable && ($row['click_url'] ?? $url))
        onclick="window.location.href='{{ $row['click_url'] ?? $url }}'"
    style="cursor: pointer;"
    @endif
    {{ $row['_attributes'] ?? '' }}
>
    @foreach($headers as $key => $header)
        @php
            $columnKey = $header['key'] ?? $key;
            $columnType = $header['type'] ?? 'text';
            $columnFormat = $header['format'] ?? null;
            $columnClass = $header['class'] ?? '';
            $columnAlign = $header['align'] ?? null;

            $cellValue = $row[$columnKey] ?? null;
            $actionCallable = $header['actionCallable'] ?? null;

            // Обработка специальных форматов
            if ($columnFormat === 'date' && $cellValue) {
                $cellValue = \Carbon\Carbon::parse($cellValue)->format('d.m.Y');
            } elseif ($columnFormat === 'datetime' && $cellValue) {
                $cellValue = \Carbon\Carbon::parse($cellValue)->format('d.m.Y H:i');
            } elseif ($columnFormat === 'currency' && is_numeric($cellValue)) {
                $cellValue = number_format($cellValue, 2, ',', ' ') . ' ₽';
            } elseif ($columnFormat === 'boolean' && !is_null($cellValue)) {
                $cellValue = (bool)$cellValue;
            }
        @endphp

        <x-table.cell
            :header="$header"
            :value="$cellValue"
            :row="$row"
            :type="$columnType"
            :align="$columnAlign"
            :class="$columnClass"
            :selectable="$selectable && $columnType === 'checkbox'"
            :rowId="$rowId"
        />
    @endforeach
</tr>
